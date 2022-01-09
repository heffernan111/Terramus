<?php

namespace Framework\Core\Aliases;

use Framework\Core\Alias;

class Routes extends Alias
{
	protected static function serviceName()
    {
		return 'Framework\\Routing\\Routes';
	}
}