<?php

namespace Framework\Core\Aliases;

use Framework\Core\Alias;

class Response extends Alias
{
	protected static function serviceName()
    {
		return 'Framework\\Routing\\Response';
	}
}