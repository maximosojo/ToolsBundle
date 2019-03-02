MaxtoanToolsBundle
========================

## Instalación

La instalación es rápida y sencilla, a sólo 2 pasos:

1. Descarga MaxtoanToolsBundle via composer
2. Habilitar el paquete

### Paso 1: Descargar usando composer

```js
{
    "require": {
        "maxtoan/tools-bundle": "dev-master"
    }
}
```

Puede realizarlo directamente a travez del siguiente comando:

``` bash
$ composer require maxtoan/tools-bundle
```

Composer instalará el paquete en el directorio `vendor / maxtoan` de su proyecto.

### Paso 2: Habilitar bundle

Habilitar bundle en el kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Maxtoan\ToolsBundle\maxtoanToolsBundle(),
    );
}
```