<?php

namespace Maximosojo\ToolsBundle\Service\Notifier\Texter\Transports;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Maximosojo\ToolsBundle\Model\Notifier\Texter\ModelMessage;
use Maximosojo\ToolsBundle\Service\Notifier\Texter\BaseTransport;
use Twilio\Rest\Client;
use Twilio\Exceptions\HttpException;
use Twilio\Exceptions\RestException;

/**
 * Twilio (https://www.twilio.com/docs)
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class TwilioTransport extends BaseTransport
{
    private $timeout;

    private $priority;

    private $enabled;

    private $sid;

    private $token;

    private $number;

    public function setOptions(array $options = array())
    {
        // Resuelve opciones
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'timeout' => 10.00,
            'priority' => 50,
            'enabled' => false,
            'sid' => null,
            'token' => null,
            'number' => null,
            "extraData" => []
        ]);

        $options = $resolver->resolve($options);
        // Options
        $this->timeout = $options["timeout"];
        $this->priority = $options["priority"];
        $this->enabled = $options["enabled"];
        $this->sid = $options["sid"];
        $this->token = $options["token"];
        $this->number = $options["number"];
    }

    public function send(ModelMessage $message)
    {
        $messageSend = false;
        $recipient = $message->getRecipient();
        $contents = $this->parseContent($message->getContent());
        foreach ($contents as $content) {
            $this->logPre($recipient,$content);
            // Use the client to do fun stuff like send text messages!
            $client = new Client($this->sid, $this->token);
            $messageSend = false;
            try {
                // Use the client to do fun stuff like send text messages!
                $response = $client->messages->create(
                    // the number you'd like to send the message to
                    $recipient,
                    [
                        // A Twilio phone number you purchased at twilio.com/console
                        'from' => $this->number,
                        // the body of the text message you'd like to send
                        'body' => $content
                    ]
                );

                // if($response->getStatusCode() == 200){
                    $messageSend = true;
                // }
                
                $this->logPost($messageSend);
                // $this->logResponse($body);
            } catch (HttpException $e) {
                // $error = (string) $e->getMessage();
                // $message->setErrorMessage($error);
                // $this->logError($error);
                throw $e;
            } catch (RestException $e) {
                // $error = (string) $e->getMessage();
                // $message->setErrorMessage($error);
                // $this->logError($error);
                throw $e;
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
        return "twilio";
    }
}