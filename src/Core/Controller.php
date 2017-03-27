<?php

namespace Hardel\Core;

use Hardel\Core\Config;

class Controller{

	protected $config;

	protected $conn;

	public function __construct(Config $conf){
		$this->config = $conf;
	}

	public function getConfig()
	{
		return $this->config;
	}
}