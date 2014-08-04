<?
namespace Rocks\Geo\Repositories;

use Rocks\Repositories\RocksSqlRepository as RocksSqlRepository;
use	Scotch\Data\SqlParameterDirections as SqlParameterDirections;

class GeoRepository extends RocksSqlRepository
{
	
	const SQL_SESSION_NAME = "geo";
	
	protected function getSqlSessionName()
	{
		return self::SQL_SESSION_NAME;
	}
	
/* COUNTRIES */
	public function getCountries($parameters = array())
	{
		return $this->callStandardStoredProcedure("geo.getCountries", array(
			$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
			$this->createStringSqlParameter("@countryID", 2, $this->getParameterValue($parameters, "countryID")),
			$this->createBitSqlParameter("@acceptCC",  $this->getParameterValue($parameters, "acceptCC")),
		));
	}
	
/* STATES */
	public function getStates($parameters = array())
	{	
		return $this->callStandardStoredProcedure("geo.getStates", array(
			$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
			$this->createStringSqlParameter("@stateID", 3,$this->getParameterValue($parameters, "stateID")),
			$this->createStringSqlParameter("@countryID", 2, $this->getParameterValue($parameters, "countryID")),
		));
	}

/* CITIES */
	public function getCities($parameters = array())
	{
		return $this->callPagedStoredProcedure("geo.getCities", $parameters, array(
			$this->createIntegerSqlParameter("@action", $this->getParameterValue($parameters, "action")),
			$this->createIntegerSqlParameter("@cityID", $this->getParameterValue($parameters, "cityID")),
			$this->createStringSqlParameter("@countryID", 2, $this->getParameterValue($parameters, "countryID")),
			$this->createStringSqlParameter("@stateID", 3, $this->getParameterValue($parameters, "stateID")),
		), array(
		));
	}
	
	public function updateCity($parameters = array())
	{
		return $this->callStandardStoredProcedure("geo.updateCity", array(
			$this->createIntegerSqlParameter("@cityID", $this->getParameterValue($parameters, "cityID"), SqlParameterDirections::InOut),
			$this->createStringSqlParameter("@cityName", 100, $this->getParameterValue($parameters, "cityName")),
			$this->createStringSqlParameter("@countryID", 2, $this->getParameterValue($parameters, "countryID")),
			$this->createStringSqlParameter("@stateID", 3, $this->getParameterValue($parameters, "stateID")),
			$this->createDecimalSqlParameter("@latitude", 16, 8, $this->getParameterValue($parameters, "latitude")),
			$this->createDecimalSqlParameter("@longitude", 16, 8, $this->getParameterValue($parameters, "longitude")),
			$this->createErrorStringSqlParameter("@cityName_Error"),
			$this->createErrorStringSqlParameter("@countryID_Error"),
			$this->createErrorStringSqlParameter("@stateID_Error"),
			$this->createErrorStringSqlParameter("@latitude_Error"),
			$this->createErrorStringSqlParameter("@longitude_Error"),
		));
	}

}
?>