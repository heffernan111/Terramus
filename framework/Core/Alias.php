<?php

namespace Framework\Core;

use Framework\Core\Kernel;

class Alias
{
    public static function __callStatic($method, $args = [])
    {
    	$Kernel = Kernel::getKernel();
        if (static::serviceName() == 'Framework\Core\Kernel') {
            $instance = $Kernel;
        } else {
            $instance = $Kernel->makeService(static::serviceName());            
        }
        return call_user_func_array([$instance, $method], $args);
    }

    protected static function serviceName()
    {

    }
}