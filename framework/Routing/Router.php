<?php

namespace Framework\Routing;

use Framework\Routing\Request;
use Framework\Routing\Response;
use Framework\Routing\Routes;

class Router {

	protected $request;
	protected $response;
	protected $routes;
	protected $coffee;

	public function __construct(Request $request, Response $response, Routes $routes)
	{
		$this->request 	= $request;
		$this->response = $response;
		$this->routes 	= $routes;
		$this->coffee   = $this->coffee();
	}

	public function getRequest()
	{
		return $this->request->build();
	}

	public function handle()
	{
		$request = $this->getRequest();
		if ($request['method'] == 'GET') {
			$request['uri'] = substr($request['uri'], 1, strlen($request['uri']));
			if ($request['uri'] == 'teapot' || $request['uri'] == 'Teapot') {
				$this->sendError(418, 'I\'m a teapot', 'No coffee available' . "<br/>\n" . $this->coffee);
			}
			if ($request['uri'] == '') {
				$segments = ['/'];
			} else {
				$segments = explode('/', $request['uri']);
			}
			foreach ($this->routes->routes[$request['method']] as $key => $value) {
				if (count($segments) == count($value['parameters'])) {
					$matched = [];
					foreach ($value['parameters'] as $k => $v) {
						if (!$this->isPlaceholder($v)) {
							if (in_array($v, $segments)) {
								array_push($matched, 1);
							} else {
								array_push($matched, 0);
							}
						} else {
							array_push($matched, 1);
						}
					}
					if (!in_array(0, $matched)) {
						$matches[$key] = $value;
					}
				}
			}
			if (!isset($matches)) {
				$this->sendError(404, 'Route not found', 'This pages does not exist');
			}
			switch (count($matches)) {
				case 0:
					return null;
				case 1:
					reset($matches);
					$first_key = key($matches);
					$this->processMatched($matches[$first_key], $segments);
			}
		}
	}

	public function processMatched($route, $parameters)
	{
		$parameters = array_diff($parameters, $route['parameters']);
		$controller = explode('@', $route['action']);
		if (isset($route['middlewares']) && !is_null($route['middlewares'])) {
			$this->processMiddlewares($route['middlewares']);
		}
		$this->findController($controller);
		$this->findMethod($controller, $parameters);
	}

	protected function isPlaceholder($segment)
	{
		preg_match('/\{(.*?)\}/', $segment, $matches);
		return ($matches) ? 1 : 0;
	}

	public function sendError($code, $name, $message)
	{
		\Exceptions::routeError($code, array('name' => $name, 'message' => $message));
	}

	public function findController($controller)
	{
		if (!file_exists(\Kernel::getControllerPath() . $controller[0] . '.php')) {
			$this->sendError('400', \Kernel::getControllerPath() . $controller[0] . '.php', $controller[0] . ' controller not found');
		} else {
			\Kernel::makeService('App\\Controllers\\' . $controller[0]);
		}
	}

	public function findMethod($controller, $parameters)
	{
		if (!method_exists('App\\Controllers\\' . $controller[0], $controller[1])) {
			$this->sendError('400', 'App\\Controllers\\' . $controller[0] . '.php', $controller[1] .' method not found');
		} else {
			call_user_func_array(['App\\Controllers\\' . $controller[0], $controller[1]], $parameters);
		}
	}

	public function processMiddlewares($middlewares)
	{
		foreach ($middlewares as $key => $value) {
			if (!file_exists(\Kernel::getMiddlewaresPath() . $value . '.php')) {
				$this->sendError('401', \Kernel::getMiddlewaresPath() . $value . '.php', $value . ' middleware not found');
			} else {
				\Kernel::makeService('Framework\\Middlewares\\' . $value);
			}
		}
	}

	public function coffee()
	{
		return '<br/>
		<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 289.88 289.88" style="enable-background:new 0 0 289.88 289.88;" xml:space="preserve">
			<g>
				<path style="fill:#CCD0D2;" d="M234.63,271.768c0,10.003-8.109,18.112-18.121,18.112H35.297c-10.003,0-18.121-8.118-18.121-18.112   H234.63z"/>
				<path style="fill:#CCD0D2;" d="M272.703,168.096c0-27.707-27.571-32.047-45.702-32.292v-18.067H25.457v54.364   c0,52.071,31.214,94.819,82.678,99.277c2.945,0.263,15.086,0.39,18.094,0.39c3.09,0,15.294-0.136,18.311-0.408   c33.905-3.017,58.894-22.697,72.023-50.64C252.334,210.228,272.703,197.924,272.703,168.096z M222.453,204.928   c2.936-10.293,4.548-21.32,4.548-32.836v-18.629c13.527-0.263,29.71,0.562,29.71,14.207   C256.72,183.373,245.285,195.777,222.453,204.928z"/>
				<g>
					<g>
						<polygon style="fill:#868686;" points="107.093,163.041 98.721,163.041 98.721,117.729 89.66,117.729 89.66,163.041      80.174,163.041 71.539,181.162 71.539,217.404 116.842,217.404 116.842,181.162    "/>
					</g>
				</g>
				<g>
					<g>
						<path style="fill:#CCD0D2;" d="M173.534,38.231c-12.585-16.545-2.872-25.143,0.308-27.961c2.727-2.41,2.655-6.234-0.163-8.562     s-7.312-2.256-10.012,0.136c-13.392,11.851-14.098,27.1-2.002,42.993c6.877,9.024,5.708,18.864-3.117,26.294     c-2.791,2.356-2.818,6.179-0.063,8.562c1.368,1.205,3.207,1.803,5.038,1.803c1.803,0,3.588-0.58,4.983-1.749     C182.069,68.33,184.045,52.021,173.534,38.231z M83.245,10.27c2.718-2.41,2.646-6.234-0.163-8.562     c-2.827-2.329-7.312-2.265-10.03,0.127c-13.383,11.851-14.089,27.1-1.993,42.993c6.877,9.024,5.708,18.864-3.117,26.294     c-2.782,2.356-2.809,6.179-0.054,8.571c1.377,1.205,3.198,1.803,5.029,1.803c1.803,0,3.588-0.58,4.983-1.749     c13.563-11.417,15.539-27.726,5.028-41.516C70.343,21.686,80.047,13.079,83.245,10.27z M128.548,10.27     c2.718-2.41,2.646-6.234-0.163-8.562c-2.818-2.329-7.303-2.256-10.021,0.136c-13.383,11.851-14.089,27.1-1.993,42.993     c6.877,9.024,5.708,18.864-3.117,26.294c-2.791,2.356-2.818,6.179-0.063,8.562c1.377,1.205,3.207,1.803,5.038,1.803     c1.803,0,3.588-0.58,4.983-1.749c13.555-11.416,15.539-27.725,5.029-41.516C115.646,21.686,125.35,13.079,128.548,10.27z"/>
					</g>
				</g>
				<g>
					<path style="fill:#C2C5C7;" d="M153.601,266.83c-3.008,0.272-15.222,0.408-18.311,0.408c-3.008,0-15.14-0.127-18.094-0.39    c-51.473-4.458-82.678-47.206-82.678-99.277v-49.842h-9.061v54.364c0,52.071,31.214,94.819,82.678,99.277    c2.945,0.263,15.086,0.39,18.094,0.39c3.09,0,15.294-0.136,18.311-0.408c13.845-1.232,26.113-5.364,36.786-11.579    C172.891,263.405,163.685,265.933,153.601,266.83z"/>
				</g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
			<g>
			</g>
		</svg>';
	}

}
