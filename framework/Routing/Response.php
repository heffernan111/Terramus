<?php

namespace Framework\Routing;

class Response {

	protected $code;
	protected $content;

	public function __construct($code = 200, $content = '')
	{
		$this->code 	= $code;
		$this->content 	= $content;
	}

	public function view($blade, $data = [])
	{
		if ($this->getView($blade)) {
			// code...
		} else {
			\Exceptions::routeError(404, array('name' => '', 'message' => ''));
		}
	}

	public function json($data = [])
	{
		return (json_encode($data));
	}

	public function abort($code, $name = '', $message = '')
	{
		\Exceptions::routeError($code, array('name' => $name, 'message' => $message));
	}

	public static function getView($key, $array = false)
    {
        if ($array !== false) {
            if (array_key_exists($key, $array)) {
                return $array[$key];
            } else {
                foreach (explode('.', $key) as $segment) {
                    if (is_array($array) && array_key_exists($segment, $array)) {
                        $array = $array[$segment];
                    } else {
                        return null;
                    }
                }
                return $array;
            }
        } else {
            $split = explode('.', $key);
            if (!file_exists(\Kernel::getRoutePath() . DIRECTORY_SEPARATOR . $split[0] . '.php')) {
                return null;
            }
            $array = include \Kernel::getRoutePath() . DIRECTORY_SEPARATOR . $split[0] . '.php';
            unset($split[0]);
            if (empty($split)) {
                return $array;
            } else {
                $split = array_values($split);
                $key = implode('.', $split);
                return static::get($key, $array);
            }
        }
    }
}
