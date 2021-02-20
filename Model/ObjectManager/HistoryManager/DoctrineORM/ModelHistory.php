<?php

namespace Maxtoan\ToolsBundle\Model\ObjectManager\HistoryManager\DoctrineORM;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Maxtoan\ToolsBundle\Model\ObjectManager\HistoryManager\HistoryInterface;
use App\Entity\M\User;

/**
 * Modelo de historial
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 * @ORM\MappedSuperclass()
 */
abstract class ModelHistory implements HistoryInterface
{
    use \Maxtoan\ToolsBundle\Traits\ObjectManager\TraitBaseORM;
    
    /**
     * Nombre del evento
     * @var string 
     * @ORM\Column(name="event_name",type="string")
     */
    protected $eventName;
    
    /**
     * Tipo de historial
     * @var string self::TYPE_*
     * @ORM\Column(name="type",type="string",length=20)
     */
    protected $type = self::TYPE_DEFAULT;

    /**
     * Extra info
     * @var array
     * @ORM\Column(name="extra",type="json_array") 
     */
    protected $extra;
    
    /**
     * Navegador usado
     * @var string
     * @ORM\Column(name="browser",type="string",length=80) 
     */
    protected $browser;
    
    /**
     * Navegador usado
     * @var string
     * @ORM\Column(name="browser_version",type="string",length=20) 
     */
    protected $browserVersion;
    
    /**
     * Sistema operativo
     * @var string
     * @ORM\Column(name="os",type="string",length=40) 
     */
    protected $os;
    
    /**
     * Si se ejecuto el request desde un teléfono o pc
     * @var string
     * @ORM\Column(name="mobile",type="boolean") 
     */
    protected $mobile;

    /**
     * Usuario
     * @var \App\Entity\M\User
     * @ORM\Column(name="user_id", type="string", length=36)
     */
    protected $user;

    public function getEventName()
    {
        return $this->eventName;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setEventName($eventName)
    {
        $this->eventName = $eventName;
        return $this;
    }

    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    public function getExtraData($key,$default = null)
    {
        if(isset($this->extra[$key])){
            $default = $this->extra[$key];
        }
        return $default;
    }
    
    public function getExtra()
    {
        return $this->extra;
    }

    public function setExtra(array $extra)
    {
        $this->extra = $extra;
        return $this;
    }

    public function getBrowser()
    {
        return $this->browser;
    }

    public function getOs()
    {
        return $this->os;
    }

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setBrowser($browser)
    {
        $this->browser = $browser;
        return $this;
    }

    public function setOs($os)
    {
        $this->os = $os;
        return $this;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }
    
    public function getBrowserVersion()
    {
        return $this->browserVersion;
    }

    public function setBrowserVersion($browserVersion)
    {
        $this->browserVersion = $browserVersion;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        if($user instanceof User){
            $this->user = $user->getId();
        }
        return $this;
    }
}
