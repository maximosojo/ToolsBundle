<?php

namespace Maximosojo\ToolsBundle\Service\Util;

/**
 * EnvironmentUtil
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class EnvironmentUtil 
{
    const ENV_PROD = "prod";
    const ENV_DEV = "dev";
    const ENV_TEST = "test";
    const ENV_SANDBOX = "sandbox";

    public static function isEnvironment($environment)
    {
        return $_ENV['APP_ENV'] === $environment;
    }
    
    public static function isEnvironmentProd()
    {
        return self::isEnvironment(self::ENV_PROD);
    }

    public static function isEnvironmentTest()
    {
        return self::isEnvironment(self::ENV_TEST);
    }

    public static function isEnvironmentDev()
    {
        return self::isEnvironment(self::ENV_DEV);
    }

    public static function isEnvironmentSandbox()
    {
        return self::isEnvironment(self::ENV_SANDBOX);
    }
}
