<?php

namespace Hardel\Core;


class Application extends Container {

	protected static $instance;

	public function __construct(){

		static::setInstace($this);
		$this->bootstrap();
	}

	protected static function setInstace(Container $app)
	{
		static::$instance = $app;
	}

	public static function getInstance()
	{
		return static::$instance;
	}

	public function bootstrap()
	{
		//TODO
	}
}
