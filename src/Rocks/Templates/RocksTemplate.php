<?php
namespace Rocks\Templates;

use Scotch\System as System;
use Scotch\Templates\Template as Template;
use Scotch\Utilities\HtmlUtilities as HtmlUtilities;

abstract class RocksTemplate extends Template 
{	
	const APP_ROOT = "{APP_ROOT}";
	const APP_ROOT_REGEX = "#\{APP_ROOT\}#";
	private $scripts;
	protected $staticDomain;
	protected $isResponsive = false;
	protected $favicon = null;
	
	abstract protected function body();
	abstract protected function templateStyleSheets();
	abstract protected function templateScripts();
	
	function __construct($view) {
		parent::__construct($view);
		$this->staticDomain = System::$application->configuration->staticDomain;
	}
	
	function view()
	{
		$data = $this->view->data;
		$staticDomain = $this->staticDomain;
		$user = $this->controller->user;
		$h = HtmlUtilities::getInstance();
		
		$appRoot = System::$application->configuration->appRoot;
		$file = str_replace(self::APP_ROOT,$appRoot,$this->view->file);
		//$file = preg_replace(self::APP_ROOT_REGEX, $appRoot, $this->view->file);
		include $file;
	}
	
	function head()
	{
		$title = ( isset($this->view->metaTitle) ) ? $this->view->metaTitle : "Untitled Page";
		echo "\t<title>".$title."</title>\n";
		if ( isset($this->view->metaDescription) )
		{
			echo "\t".$this->view->metaDescription."\n";
		}
		if ( isset($this->view->metaKeywords) )
		{
			echo "\t".$this->view->metaKeywords."\n";
		}
		if ( $this->isResponsive ) 
		{
			echo "\t<meta name=\"viewport\" content=\"width=device-width, minimumscale=1.0, maximum-scale=1.0\" />\n";

		}
		
		if(isset($this->favicon))
		{
			echo "\t<link rel=\"shortcut icon\" href=\"//".$this->staticDomain . $this->favicon . "\" type=\"image/x-icon\" />\n";
			echo "\t<link rel=\"icon\" href=\"//" . $this->staticDomain . $this->favicon ."\" type=\"image/x-icon\">\n";
		}
		
		if ( isset($this->view->canonical) )
		{
			echo "\t<link rel=\"canonical\" href=\"".$this->view->canonical."\" />\n";
		}
		if ( isset($this->view->relPrev) )
		{
			echo "\t<link rel=\"prev\" href=\"".$this->view->relPrev."\" />\n";
		}
		if ( isset($this->view->relNext) )
		{
			echo "\t<link rel=\"next\" href=\"".$this->view->relNext."\" />\n";
		}
		$this->css();
	}
	
	function css()
	{
		echo $this->templateStyleSheets();
		
		if ( isset($this->view->css) )
		{
			$this->view->css->render();
		}
	}
	
	function scripts()
	{
		echo $this->templateScripts();
		
		if ( isset($this->view->scripts) )
		{
			$this->view->scripts->render();
		}
	}
	
	private function mergeResources($templateResources = array(), $viewResources = array(), $delimiter = ";")
	{
		$mergedResources = array();
		if ( is_array($templateResources) )
		{
			$mergedResources = $templateResources;
		}
		if ( is_array($viewResources) )
		{
			$mergedResources = array_merge($mergedResources,$viewResources);
		}
		return implode($delimiter,$mergedResources);
	}
	
	public function render()
	{
?>
<!DOCTYPE html>
<html>
<head>
<?$this->head();?>
<?$this->scripts()?>
</head>
<body>
	<?$this->body()?>
	
</body>
</html>
<?php
	}
}
?>