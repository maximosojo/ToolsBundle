MaxtoanToolsBundle
========================

## Paginator

Uso y configuración del paginador:

1. Descarga de componentes via composer
2. Configurar en el config.yml
3. Extender los repositorios del repositorio base

### Paso 1: Descargar componentes requeridos

```js
{
    "require": {
        "pagerfanta/pagerfanta": "v1.0.5"
    }
}
```

### Paso 2: Configurar en el config.yml

Puede configurar el tipo de retorno que desea del paginador:
1. default
2. standard
3. dataTables

``` yml
maxtoan_tools:
    paginator:
        format_array: standard #default, standard ó dataTables

```

### Paso 3: Extender los repositorios del repositorio base

Los repositorios que desea usar para paginar se deben extender del base:

``` php
<?php
// AppBundle/Repository/MyRepository.php

class MyRepository extends Maxtoan\ToolsBundle\Repository\EntityRepository
{

}

```