<?php
foreach (glob(__dir__."/../cli/*.php") as $filename) 
{
	require_once $filename;
}

class PrunCli extends CliFactory implements CliInterface
{
	function __construct() 
	{
		parent::__construct();
		$this->validArgument();
	}

	public function run()
	{
		list($ClassName,$Method) = explode(':', $this->argv[1]);
		call_user_func_array([new $ClassName,$Method], 
			$this->extractArguments());
	}
}