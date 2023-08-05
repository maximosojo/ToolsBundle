<?php

/*
 * This file is part of the Maximosojo Tools package.
 * 
 * (c) https://maximosojo.github.io/tools-bundle
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Maximosojo\ToolsBundle\Service\Notifier;

use Maximosojo\ToolsBundle\Service\Notifier\Texter\TransportInterface;
use Maximosojo\ToolsBundle\Model\Notifier\Texter\ModelMessage;
use Maximosojo\ToolsBundle\Service\BaseService;
use Maxtoan\Common\Util\DateUtil;
use Maxtoan\Common\Util\StringUtil;
use Maxtoan\Common\Util\UserUtil;
use DateTime;
use Maximosojo\ToolsBundle\Model\Notifier\Texter\ModelMessageInterface;

/**
 * Servicio para enviar mensajes de texto
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class TexterManager extends BaseService implements TexterManagerInterface
{
    /**
     * @var Message
     */
    private $class;
    
    private $isInit = false;

    private $transports;

    private $options;

    private $forbiddenChars = array("'","\\","\"",'"');

    private $logger;
            
    function __construct(array $options = [])
    {
        $this->transports = [];
        
        $resolver = new \Symfony\Component\OptionsResolver\OptionsResolver();
        $resolver->setDefaults([
            "class" => false
        ]);
        $resolver->setRequired("env","class");
        $options = $resolver->resolve($options);
        $this->options = $options;

        $this->class = $options["class"];
        $this->environment = $options["env"];

        $this->init();
    }
    
    /**
     * Inicia el proceso de envío
     */
    public function init()
    {
        if($this->isInit === true){
            return;
        }

        $this->isInit = true;
        usort($this->transports, function ($a,$b){
            return $a->getPriority() < $b->getPriority();
        });
    }
    
    /**
     * Añade un transport
     *
     * @param   TransportInterface  $transport
     */
    public function addTransport(TransportInterface $transport)
    {
        if(isset($this->transports[$transport->getName()])){
            throw new \InvalidArgumentException(sprintf("The transport '%s' is already added.",$transport->getName()));
        }

        if($transport->isEnabled() === true){
            $this->transports[$transport->getName()] = $transport;
        }
    }
    
    /**
     * 
     * @param type $name
     * @return \Maximosojo\ToolsBundle\Service\Sms\TransportInterface
     * @throws \InvalidArgumentException
     */
    public function getTransport($name = null)
    {
        $transport = null;
        if($name !== null){
            foreach ($this->transports as $t) {
                if($t->getName() === $name){
                    $transport = $t;
                    break;
                }
            }
            if($transport === null){
                throw new \InvalidArgumentException(sprintf("The transport '%s' is not register.",$name));
            }
        }else{
            $transport = reset($this->transports);
        }

        return $transport;
    }
    
    /**
     * Valida este el transport
     *
     * @param   $name
     *
     * @return  Boolean
     */
    private function hasTransport($name)
    {
        return $this->getTransport($name) !== null;
    }

    public function onSmsQueue($phoneNro = null, $messageText = null, array $options = array())
    {
        if(empty($phoneNro) || empty($messageText)){
            //Se ignora mensaje sin numero o contenido
            return;
        }

        $resolver = new \Symfony\Component\OptionsResolver\OptionsResolver();
        $resolver->setDefaults([
            "priority" => 50,
            "sentBy" => null,
            "shippingDate" => null,
            "category" => "default",
            "extraData" => []
        ]);
        $resolver->setAllowedTypes("priority", "integer");
        $resolver->setAllowedTypes("shippingDate", ["null", \DateTime::class]);
        $options = $resolver->resolve($options);
        
        $phoneNro = StringUtil::clean($phoneNro,"/[^0-9]/");
        if($options["sentBy"] !== null){
            $phoneNro = UserUtil::formatPhoneNumber($options["sentBy"], $phoneNro);
        }

        if($phoneNro === null){
            $this->addError("El número de teléfono es invalido.");
            return false;
        }

        // Se registra en la base de datos
        $message = $this->newMessage($phoneNro, $messageText, [
            "extraData" => $options["extraData"],
            "priority" => $options["priority"],
            "sentBy" => $options["sentBy"]
        ]);
        $message->setPriority(101);

        return $message;
    }

    /**
     * Envia un mensaje de texto
     * @param integer $phoneNro Numero de teléfono del destinatario en formato internacional (584129876543)
     * @param string $message Contenido del mensaje a enviar
     * @param integer $priority Prioridad del mensaje
     * @return boolean
     */
    public function onSendSmsQueue(ModelMessageInterface $message, array $options = array()): bool
    {
        $messageSend = false;

        // Se intenta con todos los transportes
        for($i=0;$i < count($this->transports) ; $i++){
            $messageSend = $this->handleSend($message);
            if($messageSend === true){
                break;
            }
            
        }

        return $messageSend;
    }

    private function newMessage($recipient, $content, array $options = array())
    {
        $resolver = new \Symfony\Component\OptionsResolver\OptionsResolver();
        $resolver->setDefaults([
            "priority" => 50,
            "sentBy" => null,
            "extraData" => []
        ]);
        $resolver->setAllowedTypes("priority", "integer");
        $options = $resolver->resolve($options);
        $priority = $options["priority"];
        $sentBy = $options["sentBy"];
        $extraData = $options["extraData"];

        $shippingDate = new \DateTime();

        $content = StringUtil::clearSpecialChars($content,"",$this->forbiddenChars);
        $content = $this->parseContent($content);
        $sms = new $this->class;
        $sms
                ->setRecipient($recipient)
                ->setContent($content)
                ->setPriority($priority)
                ->setShippingDate($shippingDate)
                ->setExtras($extraData)
        ;
        if ($sentBy != null) {
            $sms->setSentBy($sentBy);
        }
        
        $sms->setEnvironment($this->environment);
        $sms->setStatus(ModelMessage::STATUS_READY);
        $sms->setRetries(0);
        $this->doPersist($sms);

        return $sms;
    }
    
    /**
     * Construye el contenido antes de enviarlo
     * @param string $content
     * @return string
     */
    public function parseContent($content)
    {
        $content = $this->container->getParameter("app_name").": ".$content;
        return $content;
    }

    /**
     * Envíar mensaje
     *
     * @param   ModelMessage             $message
     * @param   TransportInterface  $transport
     *
     * @return  $result
     */
    protected function handleSend(ModelMessage $message, TransportInterface $transport = null)
    {   
        $phone = $message->getRecipient();
        $str = substr($phone, 2, 3);
        
        if($transport === null){
            $transport = $this->getTransport();
        }

        if($this->logger !== null){
            $transport->setLogger($this->logger);
        }

        $lastTransport = $transport;

        if(!is_null($message->getTransport())) {
            reset($this->transports);
            foreach ($this->transports as $key => $value) {
                if($value->getName() === $message->getTransport()) {
                    $lastTransport = next($this->transports);
                    if($lastTransport === false){
                        reset($this->transports);
                        $lastTransport =  current($this->transports);
                    }
                    break;
                }
                next($this->transports);
            }
        }

        $message->setTransport($lastTransport->getName());
        $content = StringUtil::clearSpecialChars($message->getContent(),"",$this->forbiddenChars);
        $message->setContent($content);
        $result = $lastTransport->send($message);
        $rs = array("transport"=>$lastTransport->getName());
        if ($result === true) {
            $message->setStatus(ModelMessage::STATUS_COMPLETED);
            $message->setSentAt(new \DateTime());
            $message->setErrorMessage('');
        } else {
            $message->setErrorMessage($result);
            $message->setStatus(ModelMessage::STATUS_FAILED);
            $message->setRetries($message->getRetries() + 1);
            if(!empty($message->getErrorMessage()) && $this->logger !== null){
                $this->logger->critical($message->getErrorMessage());
                $rs["error"] = $message->getErrorMessage();
            }
        }
        $rs["response"] = $lastTransport->getResponse();

        $message->addTransportHistory($rs);
        
        $this->doPersist($message,false);
        $this->doFlush();
        
        return $result;
    }
}
