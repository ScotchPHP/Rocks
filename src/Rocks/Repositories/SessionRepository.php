<?php
namespace Rocks\Repositories;

use Rocks\Repositories\RocksSqlRepository as RocksSqlRepository;
use	Scotch\Data\SqlParameterDirections as SqlParameterDirections;
use Exception;

/**
* Repository to session database. Provides means for sessions to
* be created, updated, and retrieved.
*/
class SessionRepository extends RocksSqlRepository
{	
	const SQL_SESSION_NAME = "session";
	
	private $first = true;
	function __construct()
	{
		parent::__construct();
		
	}
	
	protected function getSqlSessionName()
	{
		return self::SQL_SESSION_NAME;
	}
	
	public function deleteSession($parameters = array())
	{
		$rs = $this->sqlSession->executeStoredProcedure("dbo.deleteSession", array(
			$this->createStringSqlParameter("@sessionID", 64, $this->getParameterValue($parameters, "sessionID"))
		));
		
		return $rs;
	}
	
	public function deleteExpiredSessions($parameters = array())
	{
		$rs = $this->sqlSession->executeStoredProcedure("dbo.deleteSession_Expired", array(
			$this->createIntegerSqlParameter("@maxLifetime", $this->getParameterValue($parameters, "maxLifetime"))
		));
		
		return $rs;
	}
	
	public function getSession($parameters = array())
	{
		$rs = $this->sqlSession->executeStoredProcedure("dbo.getSession", array(
			$this->createStringSqlParameter("@sessionID", 64, $this->getParameterValue($parameters, "sessionID"))
		));
		
		return $rs;
	}
	
	public function updateSession($parameters = array())
	{
		$rs = $this->sqlSession->executeStoredProcedure("dbo.updateSession", array(
			$this->createStringSqlParameter("@sessionID", 64, $this->getParameterValue($parameters, "sessionID")),
			$this->createStringMaxSqlParameter("@sessionData", $this->getParameterValue($parameters, "sessionData"))
		));
		
		return $rs;
	}
	
}
?>