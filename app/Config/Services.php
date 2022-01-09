<?php

return [
	'services'	=> [
		'Exceptions'	=> Framework\Exceptions\Exceptions::class,
		'Helpers'		=> Framework\Helpers\Helpers::class,
		'Response'		=> Framework\Routing\Response::class,
		'Request'		=> Framework\Routing\Request::class,
		'Router'		=> Framework\Routing\Router::class,
		'Routes'		=> Framework\Routing\Routes::class,		
	],
	'aliases'	=> [
		'Kernel'		=> Framework\Core\Aliases\Kernel::class,
		'Exceptions'	=> Framework\Core\Aliases\Exceptions::class,
		'Helpers'		=> Framework\Core\Aliases\Helpers::class,
		'Request'		=> Framework\Core\Aliases\Request::class,
		'Response'		=> Framework\Core\Aliases\Response::class,
		'Router'		=> Framework\Core\Aliases\Router::class,
		'Routes'		=> Framework\Core\Aliases\Routes::class,
		
	],
	'middlewares'	=> [
		'Test'			=> Framework\Middlewares\Test::class,
		'Auth'			=> Framework\Middlewares\Auth::class,
	],
];