<?php
namespace Rocks\Models;

use Scotch\Models\BaseModel as BaseModel;
use Rocks\Repositories\WorkflowRepository as WorkflowRepository;

abstract class Workflow extends BaseModel
{
	function __construct($properties = array())
	{
		parent::__construct($properties);
	}
	
	abstract function getWorkflowTypeID();
}
?>