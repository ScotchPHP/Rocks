<?php
namespace Rocks\Templates;

use Scotch\System as System;
use Scotch\Templates\Template as Template;
use Scotch\Utilities\HtmlUtilities as HtmlUtilities;

class EmptyTemplate extends Template 
{	
	const APP_ROOT = "{APP_ROOT}";
	const APP_ROOT_REGEX = "/{APP_ROOT}/";
	
	function __construct($view) {
		parent::__construct($view);
	}
	
	function view()
	{
		$data = $this->view->data;
		$h = HtmlUtilities::getInstance();
		
		$appRoot = System::$application->configuration->appRoot;
		$file = str_replace(self::APP_ROOT,$appRoot,$this->view->file);
		//$file = preg_replace(self::APP_ROOT_REGEX, $appRoot, $this->view->file);
		include $file;
	}
	
	public function render()
	{
		$this->view();
	}
}
?>