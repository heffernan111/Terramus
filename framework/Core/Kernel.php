<?php

namespace Framework\Core;

use Dotenv\Dotenv;
use Framework\Core\Container;
use Framework\Helpers\Helpers;


class Kernel {
	// paths
	protected $basePath;
	protected $configPath;
	protected $controllerPath;
	protected $middlewarePath;
	protected $routePath;
	// container
	public $container;
	// App Instance
	protected static $kernelInstance;
	//
	public $routes = [];

	//
	public function __construct()
	{
		// paths
		$this->setPaths();
		$this->loadENV();
		// boot
		$this->boot();
	}
	// Set Paths
	protected function setPaths()
	{
		$this->basePath 		= PHP_SAPI != 'cli' ? str_replace('public', '', $_SERVER['DOCUMENT_ROOT']) : str_replace('public', '', $_SERVER['PWD']);
		$this->configPath 		= $this->basePath . 'app' . DIRECTORY_SEPARATOR . 'Config' . DIRECTORY_SEPARATOR;
		$this->controllerPath 	= $this->basePath . 'app' . DIRECTORY_SEPARATOR . 'Controllers' . DIRECTORY_SEPARATOR;
		$this->middlewarePath 	= $this->basePath . 'framework' . DIRECTORY_SEPARATOR . 'Middlewares' . DIRECTORY_SEPARATOR;
		$this->routePath 		= $this->basePath . 'app' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR;
	}
	// Get paths
	public function getBasePath()
	{
		return $this->basePath;
	}

	public function getRoutePath()
	{
		return $this->routePath;
	}

	public function getConfigPath()
	{
		return $this->configPath;
	}

	public function getControllerPath()
	{
		return $this->controllerPath;
	}

	public function getMiddlewaresPath()
	{
		return $this->middlewarePath;
	}
	// Load Env File
	protected function loadENV()
    {
    	if (file_exists($this->basePath . '.env')) {
    		$dotenv = new Dotenv($this->basePath);
            $dotenv->load();
    	}
    }
    // Load Services and Aliases
    protected function boot()
	{
		$this->container = new Container();
		$this->loadServices();

		self::$kernelInstance = $this;

		$this->loadAliases();
		$this->makeRoutes();
	}

	public function loadServices()
	{
		$services = $this->initialConfig('services.services');
		foreach ($services as $name => $service) {
			$this->container->make($name, $service);
		}
	}

	public static function getKernel()
	{
		return self::$kernelInstance;
	}

	protected function loadAliases()
	{
		$aliases = $this->initialConfig('services.aliases');
		foreach ($aliases as $key => $value) {
			$this->container->makeAliases($key, $value);
		}
	}

	public function makeRoutes()
	{
		$route_service = $this->get('Routes');
		include_once $this->basePath . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Routes' . DIRECTORY_SEPARATOR . 'Routes.php';
        $this->routes = \Routes::getRoutes();
	}

	public function get($service)
	{
		return $this->container->box[$service];
	}

	// Used before \App aliases is available
	protected function initialConfig($key)
    {
        $explode = explode('.', $key);
        $array = include $this->configPath . DIRECTORY_SEPARATOR . $explode[0] . '.php';
        return $array[$explode[1]];
    }
	// Make Static Service
	public function makeService($service)
	{
		$explode = explode('\\', $service);
		return $this->container->make($explode[count($explode) - 1], $service, []);
	}
	//
    public function resolveMethodDependancies($class, $method)
    {
    	$this->container->resolveMethodDependancies($class, $method);
    }
}
