<?php

namespace Framework\Core;

class Container {

	public $box = [];

	public function exists($key)
    {
        return (isset($this->box[$key])) ? 1 : 0;
    }

    public function makeAliases($name, $class)
	{
        class_alias($class, $name);
	}

    public function resolveMethodDependancies($class, $method)
    {
    	$reflector = new \ReflectionClass($class);
        $parameters = $reflector->getMethod($method)->getParameters();
        $dependencies = [];
        foreach($parameters as $parameter) {
            $dependency = $parameter->getType() && !$parameter->getType()->isBuiltin() ? new \ReflectionClass($parameter->getType()->getName()) : null;
            if ($dependency) {
                if ($this->exists($dependency->name)) {
                    $dependencies[] = $this->box[$dependency->name];
                } else {
                	$explode = explode('\\', $dependency->name);
                    $dependency_instance = $this->make($explode[count($explode) - 1], $dependency->name, []);
                    $this->box[$name] = $dependency_instance;
                    $dependencies[] = $dependency_instance;
                }
            }
        }
        return $dependencies;
    }

	public function make($name, $class, $params = [])
	{
		if ($this->exists($name)) {
            return $this->box[$name];
        } else {
            $reflector = new \ReflectionClass($class);
            if ($reflector->isInstantiable()) {
                $constructor = $reflector->getConstructor();
                if (!$constructor) {
                    $instance = $reflector->newInstanceWithoutConstructor();
                    $this->box[$name] = $instance;
                    return $instance;
                } else {
                    $parameters = $constructor->getParameters();
                    $dependencies = [];
                    foreach($parameters as $parameter) {
                        $dependency = $parameter->getType() && !$parameter->getType()->isBuiltin() ? new \ReflectionClass($parameter->getType()->getName()) : null;
                        if ($dependency) {
                            if ($this->exists($dependency->name)) {
                                $dependencies[] = $this->box[$dependency->name];
                            } else {
                            	$explode = explode('\\', $dependency->name);
                                $dependency_instance = $this->make($explode[count($explode) - 1], $dependency->name, []);
                                $this->box[$name] = $dependency_instance;
                                $dependencies[] = $dependency_instance;
                            }
                        }
                    }
                    $instance = $reflector->newInstanceArgs(array_merge($dependencies, $params));
                    $this->box[$name] = $instance;
                    return $instance;
                }
            } else {
                throw new \Exception($class . ' is not instantiable');
            }
        }
	}
}
