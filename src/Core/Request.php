<?php

namespace Hardel\Core;

/**
* 
*/
class Request 
{  
	protected $getParams = []; // lista di parametri

	protected $postParams = []; // lista di data

	protected $fileParams = []; // lista di file

	protected $serverParams = []; // lista di parametri server

	protected $cookiesParams = []; // lista di parametri cookies

	protected $attributes = [];

	public function __construct(array $get, array $post, array $server, array $file, 
		array $cookies, $content = null){

		$this->init($get,$post,$server,$file,$cookies,$content);
	}

	protected function init(array $get, array $post, array $server, array $file, 
		array $cookies, $content = null){

		if(function_exists('get_magic_quotes_gpc') && (get_magic_quotes_gpc() === 0 || get_magic_quotes_gpc() === false))
			{
			    $post      = array_map( 'addslashes', $post ); 
			    $get       = array_map( 'addslashes', $get ); 
			    $cookies    = @array_map( 'addslashes', $cookies );
			}

		$this->getParams 		= $get;
		$this->postParams 		= $post;
		$this->fileParams 		= $file;
		$this->serverParams 	= $server;
		$this->cookiesParams	= $cookies;
		$this->setAttributes();

	}

	public static function createFromGlobal(){
		 return new Request($_GET,$_POST,$_SERVER,$_FILES,$_COOKIE);
	}

	protected function getQueryString()
	{
		return $this->serverParams['QUERY_STRING'];
	}

	public function getServerAddress()
	{
		return $this->serverParams['SERVER_ADDR'];
	}

	public function getMethod()
	{
		return $this->serverParams['REQUEST_METHOD'];
	}

	public function getIpAddress()
	{
		return $this->serverParams['REMOTE_ADDR'];
	}

	public function getURI()
	{
		return $this->serverParams['REQUEST_URI'];
	}

	public function getAttribute($key)
	{
		return isset($this->attributes[$key]) ? $this->attributes[$key]  : null;
	}

	public function getAttributes(){
		return $this->attributes;
	}

	public function getCookie($key)
	{
		return isset($this->cookiesParams[$key]) ? $this->cookiesParams[$key] : null;
	}

	public function getFile($key)
	{
		return isset($this->fileParams[$key]) ? $this->fileParams[$key] : null;
	}

	public function getInput()
	{
		return $this->postParams;
	}

	protected function setAttributes()
	{
		$qS = $this->getQueryString();

		$aQS = explode('&', $qS);

		foreach ($aQS as $query) {
			list($key,$value) = explode('=', $query);
			$this->attributes[$key] = $value;
		}
	}
}

