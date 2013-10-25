<?php

class Test extends CliFactory
{
	protected $info = [
					"make" =>	[
						"info" => "generate testing string",
						'--name' => '--name="Wiki"',
						'--greeting' => '--greeting="Hello" (optional)',
						'-print' => '-print=2 set to print "n" of time',
					],
				];

	function __construct() 
	{
		parent::__construct();
	}

	function make($name, $greeting ='Hello')
	{
		$settings = $this->extractSettings();
		$print = '';
		if(count($settings) > 0)
			$print = $settings['print'];

		$this->printing("{$greeting} {$name}",$print);
	}

	protected function printing($line,$print = 1)
	{
		for ($i=0; $i < $print; $i++) { 
			$this->outline($line);
		}
	}
}