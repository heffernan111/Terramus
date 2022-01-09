<?php

namespace Framework\Core\Aliases;

use Framework\Core\Alias;

class Request extends Alias
{
	protected static function serviceName()
    {
		return 'Framework\\Routing\\Request';
	}
}