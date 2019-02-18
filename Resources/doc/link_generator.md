AtechnologiesToolsBundle
========================

## Generador de enlaces

Uso y configuración:

1. Configurar en el config.yml
2. Configurar el servicio en services.yml
3. Uso del componente en Twig

### Paso 1: Configurar en el config.yml

Habilitar el componente para su uso y puede ser registrado el color de enlaces:

``` yml
atechnologies_tools:
    link_generator:
        enable: true
        color: "#000"

```

### Paso 2: Registrar en services.yml

Registrar en servicio de registro de entidades a usar:

``` yml
AppBundle\Service\LinkGenerator\MyLinkGeneratorItem:
        public: true
        tags:
            - { name: link_generator.item } 

```
Estructura de servicio:

``` php
<?php
// AppBundle\Service\LinkGenerator\MyLinkGeneratorItem.php;

use Atechnologies\ToolsBundle\Model\LinkGenerator\LinkGeneratorItem;

class MyLinkGeneratorItem extends LinkGeneratorItem
{
    public static function getConfigObjects() {
    	return [
            ['class' => 'AppBundle\Entity\User','icon' => 'fa fa-user','route' => 'app_user_show','labelMethod' => 'getUsername']           
        ];
    }
}

```

### Paso 3: Uso del componente en Twig

Para usar el componente solo debe ser llamada la función generadora de enlace y pasar la entidad registrada:

``` php

{{link_generator(user)}}

```