<?php

namespace Maxtoan\ToolsBundle\Component\JMS\Serializer\Metadata\Driver;

use JMS\Serializer\Metadata\Driver\YamlDriver as JMSYamlDriver;

/**
 * Driver para el JMS con la extension yaml
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class YamlDriver extends JMSYamlDriver
{
    protected function getExtension()
    {
        return "yaml";
    }
}
