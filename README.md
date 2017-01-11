AtechnologiesToolsBundle
========================

## Requisitos Previos

Esta versión del paquete requiere Symfony 2.1+.

## Instalación

La instalación es rápida y sencilla, a sólo 2 pasos:

1. Descarga AtechnologiesToolsBundle via composer
2. Habilitar el paquete

### Paso 1: Descargar usando composer

```js
{
    "require": {
        "atechnologies/tools-bundle": "dev-master"
    }
}
```

Puede realizarlo directamente a travez del siguiente comando:

``` bash
$ composer require atechnologies/tools-bundle
```

Composer instalará el paquete en el directorio `vendor / atechnologies` de su proyecto.

### Paso 2: Habilitar bundle

Habilitar bundle en el kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Atechnologies\ToolsBundle\AtechnologiestoolsBundle(),
    );
}