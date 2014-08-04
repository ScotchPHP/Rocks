<?php
namespace Rocks\Models;

use Scotch\Models\BaseModel as BaseModel;

class HtmlResource extends BaseModel
{
	public $resources = array();
	public $resourceType;
	public $domain;
	public $version;
	
	private static $resourceTypeMethodMap = array(
		"css" => "renderStyleSheet",
		"stylesheet" => "renderStyleSheet",
		"js" => "renderScript",
		"javascript" => "renderScript",
		"script" => "renderScript",
	);
	
	public function render()
	{
		if(isset($this->resourceType)){
			$method = self::$resourceTypeMethodMap[$this->resourceType];
			echo $this->$method();
		}
	}
	
	protected function renderStyleSheet()
	{
		$output = null;
		$resources = implode(";", $this->resources);
		if ( isset($resources) )
		{
			$output = "<link rel=\"stylesheet\" href=\"http://".$this->domain."/css/?r=".$resources."&v=".$this->version."\"/>";
		}
		return $output;
	}
	
	protected function renderScript()
	{
		$output = null;
		$resources = implode(";", $this->resources);
		if ( isset($resources) )
		{
			$output = "<script type=\"text/javascript\" src=\"http://".$this->domain."/js/?r=".$resources."&v=".$this->version."\"></script>";
		}
		return $output;
	}
}
?>