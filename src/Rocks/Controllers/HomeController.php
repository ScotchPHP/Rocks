<?php
namespace Kohva\Core\Controllers;

use Kohva\Core\Controllers\CoreController as CoreController;

class HomeController extends CoreController
{
	function __construct()
	{
		parent::__construct();
		
		$this->template = "Kohva\Core\Templates\BasicTemplate";
	}
	
	function index()
	{
		$data = array();
	
		return $this->createView(array(
			"data" => $data,
			"metaTitle" => "Kohva",
			"file" => "{LIBRARY_ROOT}/Kohva/Core/Views/Home/index.php"
		));
	}
}
?>