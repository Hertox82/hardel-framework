<?php

namespace Hardel\Core;

use \Closure;
use \ReflectionClass;

class Container {

	protected $instances = [];

	protected $bindings  = [];

	public function instances($alias, $concrete){
		$alias = $this->normalize($alias);

		if(isset($this->bindings[$alias]))
			unset($this->bindings[$alias]);

		$this->instances[$alias] = $concrete;
	}

	public function singleton($abstract,$concrete,$primitive = ''){
		$this->bind($abstract,$concrete,$primitive,true);
	}

	public function bind($abstract,$concrete = null, $primitive = '',$shared = false){
		
		$abstract = $this->normalize($abstract);
		$concrete = $this->normalize($concrete);
        
		if(is_null($concrete))
			$concrete = $abstract;

		if(! $concrete instanceof Closure){
			$concrete = $this->getClosure($abstract,$concrete);
		}

		$this->bindings[$abstract] = compact('concrete', 'shared','primitive');
	}

    protected function getClosure($abstract, $concrete)
    {
        return function ($c, $parameters = []) use ($abstract, $concrete) {
            $method = ($abstract == $concrete) ? 'build' : 'make';

            return $c->$method($concrete, $parameters);
        };
    }
    public function make($abstract,$parameters=[]){

    	$abstract = $this->normalize($abstract);

    	if(isset($this->instances[$abstract]))
    		return $this->instances[$abstract];

    	$concrete = $this->getConcrete($abstract);

    	if($concrete === $abstract || $concrete instanceof Closure){
			$object = $this->build($concrete,$parameters);
    	}else{
    		$object = $this->make($concrete,$parameters);
    	}

    	if(isset($this->bindings[$abstract])){
    		if($this->bindings[$abstract]['shared'] === true){
    			$this->instances[$abstract] = $object;
    		}
    	}

    	return $object;
    }

    public function build($concrete,$parameters=[]){

    	if ($concrete instanceof Closure) {
            return $concrete($this, $parameters);
        }

        $reflector = new ReflectionClass($concrete);

        $constructor = $reflector->getConstructor();

        if (is_null($constructor)) {
            return new $concrete;
        }

        $dependencies = $constructor->getParameters();

        $instances = $this->instanceDependencies($dependencies);

        return $reflector->newInstanceArgs($instances);

    }

    protected function instanceDependencies(array $dependecies)
    {
        $result = [];
        foreach ($dependecies as $parameter) {
            $concrete = $parameter->getClass()->name;
            if(is_null($concrete)){
                // non so che fare
            }
            $result[] = $this->make($this->getAliasFromConcrete($concrete));
        }

        return $result;
    }

    protected function getAliasFromConcrete($concrete){
        $concrete = $this->normalize($concrete);

        if(in_array($concrete, $this->instances))
        {
            $key = array_search($concrete, $this->instances);
            print_array($key,true);
        }

        foreach ($this->bindings as $key => $value) {
            $vC = $value['concrete'];
            if($vC instanceof Closure)
            {
               if($value['primitive'] === $concrete)
               {
                return $key;
               }
            }
            else
            {
                if($vC === $concrete){
                    return $key;
                }
            }
        }

        return $concrete;
    }

     protected function getConcrete($abstract)
    {
        if (! isset($this->bindings[$abstract])) {
            return $abstract;
        }

        return $this->bindings[$abstract]['concrete'];
    }

	public function normalize($valore){
		return is_string($valore) ? ltrim($valore,'\\') : $valore;
	}

}