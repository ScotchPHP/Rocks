<?php
namespace Rocks;

use Scotch\Application as Application;
use Rocks\RockSessionHandler as CoreSessionHandler;

abstract class RocksApplication extends Application
{
	function __construct($configuration)
	{
		
		if(isset($configuration["libraryRoot"]))
		{
			$this->libraryRoot = $configuration["libraryRoot"];		
		}
		
		if(isset($configuration["sessionName"]))
		{
			session_name($configuration["sessionName"]);
		}
		
		parent::__construct($configuration);
		
		$sessionHandler = new RocksSessionHandler();
		
		// Setup Session Hanlder
		session_set_save_handler(array ($sessionHandler,"open"),array ($sessionHandler,"close"),array ($sessionHandler,"read"),array ($sessionHandler,"write"),array ($sessionHandler,"destroy"),array ($sessionHandler,"gc"));
	}
}
?>