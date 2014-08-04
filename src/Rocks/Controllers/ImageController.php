<?php
namespace Rocks\Controllers;

use Scotch\System as System;
use Rocks\Controllers\SingleResourceController as SingleResourceController;

class ImageController extends SingleResourceController
{
	function __construct($router = null)
	{
		parent::__construct($router);
		
		$this->path = "images";
		$this->contentTypeMap = array(
			"jpg" => "image/jpeg",
			"jpeg" => "image/jpeg",
			"gif" => "image/gif",
			"png" => "image/png",
			"tif" => "image/tiff",
			"tiff" => "image/tiff",
			"bmp" => "image/bmp",
			"ico" => "image/x-icon"
		);
	}
}
?>