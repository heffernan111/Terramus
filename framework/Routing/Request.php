<?php

namespace Framework\Routing;


class Request {

	public function build()
	{
		$array = [
			'uri'		=> $this->uri(),
			'method'	=> $this->method()
		];
		if ($this->method() == 'GET') {
			$array['parameters'] = $this->parameters($this->uri());
		}
		return $array;
	}

	public function uri()
	{
		return $_SERVER['REQUEST_URI'];
	}

	public function method()
	{
		return $_SERVER['REQUEST_METHOD'];
	}

	protected function parameters($uri)
	{
		$segments = explode('/', $uri);
        return array_values(array_filter($segments, function ($v) {
            return $v !== '';
        }));
	}
}