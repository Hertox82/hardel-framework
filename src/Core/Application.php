<?php

namespace Hardel\Core;

use Illuminate\Container;

class Application extends Container {

	protected static $instance;

	public function __construct(){

		$this->setInstace($this);
	}

	protected function setInstace(Container $app)
	{
		static::instance = $app;
	}

	public static function getInstance()
	{
		return static::instance;
	}
}
