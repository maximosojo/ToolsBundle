MaxtoanToolsBundle
========================

## Mailer
### Generador de correos desde plantillas en Base de Datos

Configuración del componente:

### Configurar en el config.yml

El componente debe ser habilitado y a su vez puede registrar el prefijo deseado para sus tablas:

``` yml
maxtoan_tools:
    mailer:
        enable: true
        mailer_component_class: App\Entity\M\Master\Email\Component
        mailer_template_class: App\Entity\M\Master\Email\Template

```

### Entidad de plantilla

``` php
<?php

namespace App\Entity\M\Master\Email;

use Doctrine\ORM\Mapping as ORM;
use Maxtoan\ToolsBundle\Model\Mailer\ModelTemplate;
use Maxtoan\ToolsBundle\Interfaces\Mailer\TemplateInterface;

/**
 * Plantilla de correo electronico
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\Entity()
 * @ORM\Table(name="email_template")
 */
class Template extends ModelTemplate implements TemplateInterface
{
    /**
     * @var Component
     * @ORM\ManyToOne(targetEntity="App\Entity\M\Master\Email\Component",inversedBy="bases")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $base;
    /**
     * @var Component
     * @ORM\ManyToOne(targetEntity="App\Entity\M\Master\Email\Component",inversedBy="headers")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $header;
    /**
     * @var Component
     * @ORM\ManyToOne(targetEntity="App\Entity\M\Master\Email\Component",cascade={"persist"},inversedBy="bodys")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $body;
    /**
     * @var Component
     * @ORM\ManyToOne(targetEntity="App\Entity\M\Master\Email\Component",inversedBy="footers")
     * @ORM\JoinColumn(nullable=false)
     */
    protected $footer;
    
}

```

### Entidad de componente

``` php
<?php

namespace App\Entity\M\Master\Email;

use Doctrine\ORM\Mapping as ORM;
use Maxtoan\ToolsBundle\Model\Mailer\ModelComponent;

/**
 * Componente de correo
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 * @ORM\Entity()
 * @ORM\Table(name="email_component")
 */
class Component extends ModelComponent
{
    /**
     * @var EmailTemplate
     * @ORM\OneToMany(targetEntity="App\Entity\M\Master\Email\Template",mappedBy="base")
     */
    protected $bases;
    
    /**
     * @var EmailTemplate
     * @ORM\OneToMany(targetEntity="App\Entity\M\Master\Email\Template",mappedBy="header")
     */
    protected $headers;
    
    /**
     * @var EmailTemplate
     * @ORM\OneToMany(targetEntity="App\Entity\M\Master\Email\Template",mappedBy="body")
     */
    protected $bodys;
    
    /**
     * @var EmailTemplate
     * @ORM\OneToMany(targetEntity="App\Entity\M\Master\Email\Template",mappedBy="footer")
     */
    protected $footers;
    
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->bases = new \Doctrine\Common\Collections\ArrayCollection();
        $this->headers = new \Doctrine\Common\Collections\ArrayCollection();
        $this->bodys = new \Doctrine\Common\Collections\ArrayCollection();
        $this->footers = new \Doctrine\Common\Collections\ArrayCollection();
    }
}


```