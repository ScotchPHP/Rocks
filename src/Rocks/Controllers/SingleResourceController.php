<?php
namespace Rocks\Controllers;

use Scotch\System as System;
use Scotch\Exceptions\FileNotFoundException as FileNotFoundException;
use Rocks\Controllers\ResourceController as ResourceController;

abstract class SingleResourceController extends ResourceController
{
	protected $contentTypeMap = array();
	
	public function index()
	{
		$this->path = $this->libraryRoot . self::ResourcePath . "\\" . $this->path . "\\";
		
		$resourceExtension = substr(strrchr($this->resources,'.'),1);
		if( isset($this->contentTypeMap[$resourceExtension]) )
		{
			$contentType = $this->contentTypeMap[$resourceExtension];
			
			$this->setCache();
			
			if( file_exists ( $this->path.$this->resources ) )
			{
				header("Content-Type: " . $contentType);
				$this->util->readfile($this->path.$this->resources);
			}
			else
			{
				throw new FileNotFoundException("404 - Resource Not Found");
			}
		}
		else
		{
			throw new FileNotFoundException("404 - Resource Not Found");
		}
		
		die();
	}
	
}
?>