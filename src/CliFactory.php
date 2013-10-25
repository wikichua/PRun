<?php

abstract class CliFactory
{
	protected $argv,$argc;
	protected $spaces2 = " ";
	protected $spaces4 = "\t";
	protected $spaces6 = "\t\t";
	protected $spaces8 = "\t\t\t";
	protected $spaces10 = "\t\t\t\t";

	function __construct() 
	{
		$this->argv = $_SERVER['argv'];
		$this->argc = $_SERVER['argc'];
	}

	protected function validArgument()
	{
		if ($this->argc < 2){
			$strs = [];
			$strs[] = "Method Name" . $this->spaces6 . "Descriptions";
			$strs[] = str_repeat('=', 80);
			$this->outline($strs);
			foreach (glob(\Config::get('app.cli_dir')."*.php") as $filename) 
			{
				$ClassName = preg_replace('/(.+).php$/i', '$1', basename($filename));
				(new $ClassName)->info();
			}
			exit;
		}
	}

	protected function info()
	{
		$strs = [];
		
		foreach ($this->info as $method => $info) {
			$strs[] = get_called_class().":".$method . $this->spaces6 .$info['info'];
			$strs[] = str_repeat('-', 80);
		}
		$this->outline($strs);
	}

	protected function help()
	{
		$strs = [];
		foreach ($this->info as $method => $info) {
			$strs[] = "Method Name" . $this->spaces2 . get_called_class().":".$method;
			$strs[] = "Description" . $this->spaces2 .$info['info'];
			$strs[] = str_repeat('-', 80);
			unset($info['info']);
			foreach ($info as $key => $value) {
				$strs[] = $this->spaces2 . $key . $this->spaces8 .$value;
			}
			$strs[] = str_repeat('-', 80);
		}
		$this->outline($strs);
	}

	protected function extractArguments()
	{
		$args = [];

		foreach ($this->argv as $key => $value) {
			if($key > 1 AND preg_match('/\-{2}/', $value))
			{
				list($var,$val) = explode('=', $value);
				$var = str_replace('--', '', $var);
				$val = preg_replace('/^[\"\'](.+)[\"\']$/i', '$1', $val);
				$args[$var] = $val;
			}
		}
		return $args;
	}

	protected function extractSettings()
	{
		$settings = [];

		foreach ($this->argv as $key => $value) {
			if($key > 1 AND !preg_match('/\-{2}/', $value))
			{
				list($var,$val) = explode('=', $value);
				$var = str_replace('-', '', $var);
				$settings[$var] = $val;
			}
		}
		return $settings;
	}

	protected function outline($lines)
	{
		if(is_array($lines))
		{
			$lns = [];
			foreach ($lines as $ln) {
				$lns[] = $this->spaces2 . $ln;
			}
			$line = implode("\n",$lns);
		}
		else
			$line = $this->spaces2 . $lines;
		fwrite(STDOUT, $line."\n");
	}
}