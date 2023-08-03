<?php

namespace Maximosojo\ToolsBundle\Service\Notifier;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig\Environment;
use Maximosojo\ToolsBundle\Service\Notifier\Mailer\Adapter\EmailAdapterInterface;
use Maximosojo\ToolsBundle\Model\Notifier\Mailer\ModelQueueInterface;

/**
 * Servicio para enviar correo con una plantilla twig
 *
 * @author Máximo Sojo <maxojo13@gmail.com>
 */
class MailerManager
{
	/**
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @var EmailAdapterInterface
     */
    protected $adapter;

    public function __construct(MailerInterface $mailer,Environment $twig, EmailAdapterInterface $adapter, array $options = [])
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->twig->addExtension(new \Twig\Extension\StringLoaderExtension());

        $this->adapter = $adapter;

        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "debug" => false,
            "extra_params" => null,
            "skeleton_email" => "skeleton_email.html.twig"
        ]);
        $resolver->setRequired([
            "debug_mail", 
            "env", 
            "from_email", 
            "from_name",
            "skeleton_email"]);
        $resolver->setDefined(["extra_params"]);
        $resolver->setAllowedTypes("debug", "boolean");
        $resolver->setAllowedTypes("debug_mail", "string");

        $this->options = $resolver->resolve($options);
        
        $this->templateSource = <<<EOF
        {% extends template_from_string(baseString) %}
        
        {% block header %}{% include template_from_string(headerString) %}{% endblock %}

        {% block content_html %}{% include(template_from_string(bodyString)) with _context %}{% endblock %}
                
        {% block footer %}{% include(template_from_string(footerString)) with _context %}{% endblock %}
EOF;
    }

    /**
     * Genera un email y lo guarda en base de datos para enviar luego
     *
     * @param   string $templateName
     * @param   string | array $toEmail
     * @param   array $context
     * @param   array  $attachs
     * @param   array  $extras
     *
     * @return  []
     */
    public function onEmailQueue(string $templateName, $toEmail, array $context, array $attachs = [], array $extras = []): ModelQueueInterface
    {
        $email = $this->render($templateName,$toEmail,$context,$attachs);
        
        if ($email) {
            $email->setAttachs($attachs);
            $email->setExtras($extras);
            $email->setEnvironment($this->options["env"]);
            $this->adapter->persist($email);
            $this->adapter->flush();
        }

        return $email === null ? false : $email;
    }

    /**
     * Envia un email guardado en base de datos
     *
     * @param   ModelQueueInterface  $emailQueue
     * @param   array                $attachs
     *
     * @return  bool
     */
    public function onSendEmailQueue(ModelQueueInterface $emailQueue = null, array $attachs = []): bool
    {
        $success = false;

        try {
            // Message
            $fromEmail = null;
            foreach ($emailQueue->getFromEmail() as $address => $name) {
                $fromEmail = new Address($address,$name);
            }
            
            // Prepare and send message
            foreach ($emailQueue->getToEmail() as $to) {
                $message = (new Email())
                    ->from($fromEmail)
                    ->to($to)
                    ->subject($emailQueue->getSubject())
                    ->html($emailQueue->getBody())
                    ;
                
                $this->send($message);
            }

            $success = true;

            // Mark success
            $emailQueue->onSendSuccessAt();
        } catch (\Exception $exc) {
            // Mark error
            $emailQueue->onSendErrorAt();
            // throw $exc;
        }

        return $success;
    }

    /**
     * Envia un email sin guardar en base de datos
     *
     * @param   string $templateName
     * @param   string $toEmail
     * @param   array  $context
     * @param   array  $attachs
     * @param   array  $extras
     *
     * @return  bool
     */
    public function onSendEmail(string $templateName, $toEmail, array $context, array $attachs = [], array $extras = []): bool
    {
        $email = $this->render($templateName,$toEmail,$context,$attachs);
        return $this->onSendEmailQueue($email,$attachs);
    }

    private function render(string $templateName, $toEmail, array $context,array $attachs = []): ModelQueueInterface
    {
        $context = $this->buildDocumentContext($templateName, $context, $toEmail, $attachs);
    	$template = $this->twig->createTemplate($this->templateSource);
        $message = $this->buildEmail($template, $toEmail, $context);
        return $message;
    }

    private function send($message)
    {
        try {
            $r = $this->mailer->send($message);
        } catch (TransportExceptionInterface $e) {
            // some error prevented the email sending; display an
            // error message or try to resend the message
        }
    }

	/**
     * Construye un correo a partir de la plantilla
     * @param type $templateName
     * @param type $context
     * @param type $toEmail
     * @return ModelQueueInterface
     */
    private function buildEmail($templateName, $toEmail, array $context)
    {
        $context['toEmail'] = $toEmail;   
        $context['appName'] = $this->options["from_name"];
        
        if(!is_array($toEmail)){
            $toEmail = [$toEmail];
        }

        if($templateName instanceof \Twig_Template){
            $template = $templateName;            
        }elseif((class_exists("Twig\TemplateWrapper") && $templateName instanceof \Twig\TemplateWrapper) ||
                 (class_exists("Twig_TemplateWrapper") && $templateName instanceof \Twig_TemplateWrapper)){
            $template = $templateName;
        }else{
            $template = $this->twig->loadTemplate($templateName);               
        }
        
        $tplSubjet = $this->twig->createTemplate("{% block subject %}{% include (template_from_string(subjectString)) %}{% endblock subject %}");
        $subject = $tplSubjet->renderBlock('subject', $context);
        $htmlBody = $template->render($context);

        $fromEmail = array($this->options["from_email"] => $this->options["from_name"]);

        $email = $this->adapter->createEmailQueue();
        $email
                ->setStatus(ModelQueueInterface::STATUS_READY)
                ->setSubject($subject)
                ->setFromEmail($fromEmail)
                ->setToEmail($toEmail)
                ->onCreatedAt()
        ;
        $email->setBody($htmlBody);
        
        return $email;
    }

    private function buildDocumentContext($id,array $context)
    {
        $idExp = explode("/",$id);
        if(count($idExp) > 0){
            $id = $idExp[count($idExp) - 1];
        }
        
        $document = $this->adapter->find($id);
        if($document === null){
            throw new \RuntimeException(sprintf("Document '%s' not found.",$id));
        }
        
        $headerString = $baseString = $footerString = "";
        $header = $document->getHeader();
        $base = $document->getBase();
        $footer = $document->getFooter();
        $subject = $document->getSubject();
        $bodyDocument = $document->getBody();
        
        if($header){
            $headerString = $header->getBody();
        }

        if($base){
            $baseString = "{% block body_html %}".$base->getBody()."{% endblock body_html %}";
        }else {
            $baseString = "{% block body_html %}{% endblock body_html %}";
        }

        if($footer){
            $footerString = $footer->getBody();
        }

        $body = $bodyDocument->getBody();
        $headerString = html_entity_decode($headerString);
        $baseString = html_entity_decode($baseString);
        $footerString = html_entity_decode($footerString);
        $bodyString = html_entity_decode($body);
        $subject = strip_tags(html_entity_decode($subject));
        $context = array_merge($context,[
            "headerString" => $headerString,
            "baseString" => $baseString,
            "footerString" => $footerString,
            "bodyString" => $bodyString,
            "subjectString" => $subject,
        ]);

        return $context;
    }
}