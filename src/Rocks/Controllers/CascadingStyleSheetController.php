<?php
namespace Rocks\Controllers;

use Scotch\System as System;
use Rocks\Controllers\ResourceController as ResourceController;

class CascadingStyleSheetController extends ResourceController
{
	function __construct($router = null)
	{
		parent::__construct($router);
		
		$this->contentType = "text/css";
		$this->path = "css";
	}
}
?>