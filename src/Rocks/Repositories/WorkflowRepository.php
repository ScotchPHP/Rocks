<?
namespace Rocks\Repositories;

use Rocks\Repositories\CoreSqlRepository as RocksSqlRepository;
use Scotch\Data\SqlParameterDirections as SqlParameterDirections;

Class WorkflowRepository extends RocksSqlRepository
{
	const SQL_SESSION_NAME = "workflow";
	
	function __construct()
	{
		parent::__construct();
	}
	
	protected function getSqlSessionName()
	{
		return self::SQL_SESSION_NAME;
	}

/* WORKFLOWS */
	public function getWorkflows($parameters = array())
	{
		return $this->callStandardStoredProcedure("wf.getWorkflows", array(
			$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
			$this->createStringSqlParameter("@workflowID", 50, $this->getParameterValue($parameters, "workflowID")),
		));
	}
	
	public function updateWorkflow($parameters = array())
	{
		return $this->callStandardStoredProcedure("wf.updateWorkflow", array(
			$this->createStringSqlParameter("@workflowID", 50, $this->getParameterValue($parameters, "workflowID"), SqlParameterDirections::InOut),
			$this->createIntegerSqlParameter("@workflowTypeID", $this->getParameterValue($parameters, "workflowTypeID")),
			$this->createStringMaxSqlParameter("@workflowData", $this->getParameterValue($parameters, "workflowData")),
			$this->createErrorStringSqlParameter("@workflowID_Error"),
			$this->createErrorStringSqlParameter("@workflowTypeID_Error"),
			$this->createErrorStringSqlParameter("@workflowData_Error"),
		));
	}

	public function deleteWorkflow($parameters = array())
	{
		return $this->callStandardStoredProcedure("wf.deleteWorkflow", array(
			$this->createStringSqlParameter("@workflowID", 50, $this->getParameterValue($parameters, "workflowID")),
		));
	}

}
?>