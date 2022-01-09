<?php

namespace Framework\Core\Aliases;

use Framework\Core\Alias;

class Router extends Alias
{
	protected static function serviceName()
    {
		return 'Framework\\Routing\\Router';
	}
}