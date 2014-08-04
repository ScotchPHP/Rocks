<?php
namespace Rocks\Controllers;

use Scotch\System as System;
use Rocks\Controllers\SingleResourceController as SingleResourceController;

class FontController extends SingleResourceController
{
	function __construct($router = null)
	{
		parent::__construct($router);
		
		$this->path = "fonts";
		$this->contentTypeMap = array(
			"eot" => "application/vnd.ms-fontobject",
			"otf" => "application/font-sfnt",
			"svg" => "image/svg+xml",
			"ttf" => "application/font-sfnt",
			"woff" => "application/font-woff",
		);
	}
}
?>