<?php
namespace Rocks\Controllers;

use Rocks\Controllers\RocksController as RocksController;

/*
*  Controller that forces the current request to be over SSL
*/
class SslController extends RocksController
{
	function __construct($router = null)
	{
		parent::__construct($router);
		
		$this->ensureHttps();
	}
}
?>