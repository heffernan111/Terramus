<?php

namespace Framework\Core\Aliases;

use Framework\Core\Alias;

class Kernel extends Alias
{
	protected static function serviceName()
    {
		return 'Framework\\Core\\Kernel';
	}
}