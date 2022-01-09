<?php

namespace Framework\Helpers;

class Helpers
{
	public static function get($key, $array = false)
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
            if (!file_exists(\App::getConfigPath() . DIRECTORY_SEPARATOR . $split[0] . '.php')) {
                return null;
            }
            $array = include \App::getConfigPath() . DIRECTORY_SEPARATOR . $split[0] . '.php';
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
