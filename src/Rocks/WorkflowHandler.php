<?php
namespace Rocks;

use Scotch\Models\BaseModel as BaseModel;
use Scotch\Utilities\Utilities as Utilities;

use Rocks\Repositories\WorkflowRepository as WorkflowRepository;
use Rocks\Models\Workflow as Workflow;

class WorkflowHandler extends BaseModel
{
	private $workflowRepository;
	
	private static $singletonInstance;
	
	function __construct($sqlSession = null, $properties = array())
	{
		$this->workflowRepository = new WorkflowRepository($sqlSession);
		parent::__construct($properties);
	}
	
	public static function getInstance($sqlSession)
	{
		if(!self::$singletonInstance)
		{
			self::$singletonInstance = new WorkflowHandler($sqlSession);
		}
		
		return self::$singletonInstance;
	}
	
	public function get($workflowModel)
	{
		$workflow = null;
		$workflowID = (isset($_SESSION["workflow"][$workflowModel])) ? $_SESSION["workflow"][$workflowModel] : null;
		
		if(isset($workflowID))
		{
			$output = $this->workflowRepository->getWorkflows(array(
				"workflowID" => $workflowID,
			));
			if ( $result = $output["data"]->getNextRow() )
			{
				$workflow = unserialize($result["workflowData"]);
			}
			else
			{
				unset($_SESSION["workflow"][$workflowModel]);
			}
		}
		if(!isset($workflow))
		{
			$workflow = new $workflowModel;
		}
		
		return $workflow;
	}
	
	public function save($workflow)
	{
		if($workflow instanceof Workflow)
		{
			$util = Utilities::getInstance();
			$setWorkflowID = false;
			$workflowModel = get_class($workflow);
			$workflowID = (isset($_SESSION["workflow"][$workflowModel])) ? $_SESSION["workflow"][$workflowModel] : null;
			
			if ( !$util->hasValue($workflowID) )
			{
				$setWorkflowID = true;
				$workflowID = Utilities::getInstance()->uid();
			}
			
			$result = $this->workflowRepository->updateWorkflow(array(
				"workflowID" => $workflowID,
				"workflowTypeID" => $workflow->getWorkflowTypeID(),
				"workflowData" => serialize($workflow)
			));
			
			if($setWorkflowID)
			{
				$_SESSION["workflow"][$workflowModel] = $result["outputs"]["@workflowID"];
			}
		}
	}
	
	public function delete($workflow)
	{
		if($workflow instanceof Workflow)
		{
			$workflowModel = get_class($workflow);
			$workflowID = (isset($_SESSION["workflow"][$workflowModel])) ? $_SESSION["workflow"][$workflowModel] : null;
			
			if(isset($workflowID))
			{
				$result = $this->workflowRepository->deleteWorkflow(array(
					"workflowID" => $workflowID
				));
				
				unset($_SESSION["workflow"][$workflowModel]);
			}
		}
	}
	
}
?>