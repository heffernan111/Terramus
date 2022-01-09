<?php

namespace Framework\Routing;

use Framework\Routing\Request;
use Framework\Routing\Response;

class Routes {

	public $routes = [];

	public $processing;

	protected function add($method, $uri, $action)
	{
		if ($uri == '/') {
			$segments = [''];
		} else {
			$uri = substr($uri, 1, strlen($uri));
			$segments = explode('/', $uri);
		}
		foreach (preg_grep('/{/', $segments) as $value) {
			$key = array_search($value, $segments);
			unset($segments[$key]);
		}
		$route = "";
		foreach ($segments as $seg){
		    	$route .= '/' . $seg;
			}
		
		$this->routes[strtoupper($method)][$route] = [
			'id'		=> md5($uri . $method),
			'uri'		=> $route,
			'method'	=> strtoupper($method),
			'action'	=> ucfirst($action)
		];
		if ($method == 'get') {
			$this->routes[strtoupper($method)][$route]['parameters'] = $this->parameters($uri);
		}
		$this->processing = $this->routes[strtoupper($method)][$route];
	}

	protected function parameters($uri)
	{
		if ($uri == '/') {
			$segments = ['/'];
		} else {
			$uri = substr($uri, 0, strlen($uri));
			$segments = explode('/', $uri);
		}
		$array = [];
        return array_values(array_filter($segments, function ($v) {
            return $v !== '';
        }));
	}

	public function middleware($middlewares = [])
	{
		if (!is_array($middlewares)) {
			$explode = explode('|', $middlewares);
			$middlewares = $explode;
		}
		foreach ($middlewares as $key => $value) {
			$this->processing['middlewares'][$key] = $value;
			$this->routes[$this->processing['method']][$this->processing['uri']]['middlewares'][$key] = $value;
		}
	}

	public function get($uri, $action)
	{
		$this->add('get', $uri, $action);
		return $this;

	}

	public function post($uri, $action)
	{
		$this->add('post', $uri, $action);
	}

	public function put($uri, $action)
	{
		$this->add('put', $uri, $action);
	}

	public function patch($uri, $action)
	{
		$this->add('patch', $uri, $action);
	}

	public function delete($uri, $action)
	{
		$this->add('delete', $uri, $action);
	}

	public function getRoutes()
	{
		return $this->routes;
	}
}