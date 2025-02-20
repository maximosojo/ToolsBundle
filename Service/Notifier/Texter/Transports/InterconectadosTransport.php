<?php

namespace Maximosojo\ToolsBundle\Service\Notifier\Texter\Transports;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Maximosojo\ToolsBundle\Model\Notifier\Texter\ModelMessage;
use Maximosojo\ToolsBundle\Service\Notifier\Texter\BaseTransport;

/**
 * Interconectados (https://www.interconectados.net)
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class InterconectadosTransport extends BaseTransport
{
    private $timeout;

    private $priority;

    private $enabled;

    private $user;

    private $password;

    public function setOptions(array $options = array())
    {
        // Resuelve opciones
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'timeout' => 10.00,
            'priority' => 50,
            'enabled' => false,
            'user' => null,
            'password' => null,
            "extraData" => []
        ]);

        $options = $resolver->resolve($options);
        // Options
        $this->timeout = $options["timeout"];
        $this->priority = $options["priority"];
        $this->enabled = $options["enabled"];
        $this->user = $options["user"];
        $this->password = $options["password"];
    }

    public function send(ModelMessage $message)
    {
        $messageSend = false;
        $recipient = $message->getRecipient();
        $contents = $this->parseContent($message->getContent());
        $phoneNro = '0' . substr($recipient, 2, strlen($recipient));
        foreach ($contents as $content) {
            $this->logPre($phoneNro,$content);
            $callTo = sprintf('https://www.interconectados.net/api2/?phonenumber=%s&Text=%s&user=%s&password=%s', $phoneNro, urlencode($content), $this->user, $this->password);
            $client = new \GuzzleHttp\Client();
            $messageSend = false;
            try {
                $response = $client->request('GET', $callTo, [
                    'timeout' => $this->timeout,
                    'connect_timeout' => $this->timeout,
                ]);
                $body = (string) $response->getBody();
                if($body === '200 / 1'){
                    $messageSend = true;
                } else if($body == '401') {
                    $this->logError("Interconectados: Error de autentificación");
                }
                $this->logPost($messageSend);
                $this->logResponse($body);
            } catch (\GuzzleHttp\Exception\RequestException $e) {
                if ($e->hasResponse()) {
                    if ($e->getResponse()->getStatusCode() === 452) {
                    }
                    $error = (string) $e->getResponse()->getBody();
                    $message->setErrorMessage($error);
                    $this->logError($error);
                }
            }
        }

        return $messageSend;
    }

    public function getPriority()
    {
        return $this->priority;
    }

    public function isEnabled()
    {
        return $this->enabled;
    }

    public static function getName()
    {
        return "interconectados";
    }
}