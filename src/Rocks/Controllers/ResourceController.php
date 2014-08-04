<?php
namespace Rocks\Controllers;

use Scotch\System as System;
use Scotch\Controllers\Controller as Controller;
use Scotch\Exceptions\FileNotFoundException as FileNotFoundException;
use Scotch\Utilities\Utilities as Utilities;

abstract class ResourceController extends Controller
{
	const ResourcePath = "_Resources";
	const DefaultlibraryRoot = ".";
	const CacheTime = 0x1E13380;		//  60*60*24*365 - seconds, minutes, hours, days
	
	protected $contentType;
	protected $path;
	protected $library;
	protected $resources;
	protected $libraryRoot;
	
	function __construct()
	{
		parent::__construct();
		
		$this->resources = $this->request("r");
		$this->library = $this->request("l");
		$this->cleanseProperties();
	}
	
	public function index()
	{
		if ( isset($this->libraryRoot) )
		{
			$this->path = $this->libraryRoot . self::ResourcePath . "\\" . $this->path . "\\";
			$resourceArray = explode(";", $this->resources);
			
			header("Content-Type: " . $this->contentType);
			
			foreach($resourceArray as $resource)
			{
				if( file_exists($this->path.$resource) )
				{
					$this->util->readfile($this->path.$resource);
					echo "\n";
				}
			}
			
			$this->setCache();
		}
		else
		{
			throw new FileNotFoundException("404 - Resource Not Found");
		}
		
		die();
	}
	
	protected function cleanseProperties()
	{
		$this->libraryRoot = $this->util->getValue(System::$application->configuration->resourceLibraries, $this->library);
		if ( !isset($this->libraryRoot) )
		{
			$this->libraryRoot = $this->util->getValue(System::$application->configuration->resourceLibraries, self::DefaultlibraryRoot);
			$this->resources = isset($this->library) ? $this->library . "\\" . $this->resources : $this->resources;
			$this->library = null;
		}
		$this->resources = str_replace("/","\\",$this->resources);
	}
	
	protected function setCache()
	{
		if(System::$application->configuration->staticCacheEnabled == true)
		{
			$expire = self::CacheTime;
			header("Pragma: public");
			header("Cache-Control: maxage=".$expire);
			header("Expires: " . gmdate("D, d M Y H:i:s", time() + $expire) . " GMT");
		}
	}
}
?>