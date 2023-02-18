<?php

namespace Maximosojo\ToolsBundle\Service\Notifier\Texter;

/**
 * Base de transporte sms
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
abstract class BaseTransport implements TransportInterface
{
    const TAG = "BaseTransport";

    protected $response;
    
    use \Psr\Log\LoggerAwareTrait;
    
    protected function logPre($recipient,$content)
    {
        if($this->logger){
            $this->logger->debug(sprintf("%s: Enviando mensaje a destinatario = %s - contenido = %s",$this::TAG,$recipient,$content));
        }
    }

    protected function logResponse($result)
    {
        $this->response = $result;
        if($this->logger){
            $this->logger->notice(sprintf("%s: response = %s",$this::TAG,var_export($result,true)));
        }
    }

    protected function logPost($messageSend)
    {
        if($this->logger){
            $this->logger->info(sprintf("%s: Enviado? %s",$this::TAG,$messageSend));
        }
    }

    protected function logError($error)
    {
        if($this->logger){
                $this->logger->critical($this::TAG.": ".$error);
            }
    }

    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Se da formato a mensajes de texto separando si exceden límite de caracteres
     * Formato: Aplica (n/n) según sea el caso
     * @author Máximo Sojo <maxsojo13@gmail.com>
     * @param  string $content
     * @return string
     */
    protected function parseContent($content)
    {
        $message = [];
        $limit = 155;
        if (strlen($content) > $limit) {
            $chunks = explode("||||",wordwrap($content,$limit,"||||",false));
            $total = count($chunks);

            foreach($chunks as $page => $chunk)
            {
                $content = sprintf("(%d/%d) %s",$page+1,$total,$chunk);
                $message[] = $content;
            }
        } else {
            $message[] = $content;
        }

        return $message;
    }
}