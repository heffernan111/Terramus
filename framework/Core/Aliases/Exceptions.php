<?php

namespace Framework\Core\Aliases;

use Framework\Core\Alias;

class Exceptions extends Alias
{
	protected static function serviceName()
    {
		return 'Framework\\Exceptions\\Exceptions';
	}
}