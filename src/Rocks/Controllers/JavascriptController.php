<?php
namespace Rocks\Controllers;

use Scotch\System as System;
use Rocks\Controllers\ResourceController as ResourceController;

class JavascriptController extends ResourceController
{
	function __construct($router = null)
	{
		parent::__construct($router);
		
		$this->contentType = "text/javascript";
		$this->path = "js";
	}
}
?>