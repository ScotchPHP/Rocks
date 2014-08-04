<?php
namespace Rocks\Geo\Services;

use Rocks\RocksService as RocksService;
use Rocks\Geo\Models\Country as Country;
use Rocks\Geo\Models\State as State;
use Rocks\Geo\Models\City as City;
use Rocks\Geo\Collections\CountryCollection as CountryCollection;
use Rocks\Geo\Collections\StateCollection as StateCollection;
use Rocks\Geo\Collections\CityCollection as CityCollection;

class GeoService extends RocksService
{
/* CACHE KEYS */
	const COUNTRY_CACHE_KEY = "GeoService::getCountries";
	const COUNTRY_BY_ACCEPT_CC_CACHE_KEY_FORMAT = "GeoService::getCountries[%s]";
	const STATE_BY_COUNTRY_CACHE_KEY_FORMAT = "GeoService::getStates[%s]";

/* CLASS CONSTANTS */
	const COUNTRY_COLLECTION_CLASS = "Rocks\Geo\Collections\CountryCollection";
	const COUNTRY_MODEL_CLASS = "Rocks\Geo\Models\Country";
	const COUNTRY_ID_PARAMETER = "countryID"; 
	
	const STATE_COLLECTION_CLASS = "Rocks\Geo\Collections\StateCollection";
	const STATE_MODEL_CLASS = "Rocks\Geo\Models\State";
	const STATE_ID_PARAMETER = "stateID";
	
	const CITY_COLLECTION_CLASS = "Rocks\Geo\Collections\CityCollection";
	const CITY_MODEL_CLASS = "Rocks\Geo\Models\City";
	const CITY_ID_PARAMETER = "cityID";

/* PRIVATE PROPERTIES */
	private $sqlSession;
	private $geoRepository;

/* CONSTRUCTOR */
	function __construct()
	{
		parent::__construct();
		
		$this->geoRepository = $this->getService("IGeoRepository");
	}

/* COUNTRIES */
	public function getCountries($parameters = array())
	{
		$cacheKey = self::COUNTRY_CACHE_KEY;
		$acceptCC = $this->util->getValue($parameters, "acceptCC");
		if ($this->util->hasValue($acceptCC))
		{
			$cacheKey = sprintf(self::COUNTRY_BY_ACCEPT_CC_CACHE_KEY_FORMAT, $acceptCC);
		}
		
		return $this->callCachedReadRepository(self::COUNTRY_COLLECTION_CLASS,self::COUNTRY_MODEL_CLASS,self::COUNTRY_ID_PARAMETER,$cacheKey,function($repository,$parameters){
			return  $repository->getCountries($parameters);
		},$this->geoRepository,$parameters);
	}
	
/* STATES */
	public function getStates($parameters = array())
	{
		$data = null;
		$countryID = $this->util->getValue($parameters, "countryID");
		if( $this->util->hasValue($countryID) )
		{
			$cacheKey = sprintf(self::STATE_BY_COUNTRY_CACHE_KEY_FORMAT, $countryID);
			$data = $this->callCachedReadRepository(self::STATE_COLLECTION_CLASS,self::STATE_MODEL_CLASS,self::STATE_ID_PARAMETER,$cacheKey,function($repository,$parameters){
				return  $repository->getStates($parameters);
			},$this->geoRepository,$parameters);
		}
		else
		{
			$data = $this->callReadRepository(self::STATE_COLLECTION_CLASS,self::STATE_MODEL_CLASS,self::STATE_ID_PARAMETER,function($repository,$parameters){
				return  $repository->getStates($parameters);
			},$this->geoRepository,$parameters);
		}
		return $data;
	}
	
/* CITIES */
	public function getCities($parameters = array())
	{
		return $this->callReadRepository(self::CITY_COLLECTION_CLASS,self::CITY_MODEL_CLASS,self::CITY_ID_PARAMETER,function($repository,$parameters){
			return  $repository->getCities($parameters);
		},$this->geoRepository,$parameters);
	}
	
	function getCitiesTypeahead($parameters = array(), $valueParameter = "cityID", $textParameter = "cityName")
	{
		$data = null;
		if ( $this->util->hasValue($this->util->getValue($parameters, "search")) )
		{
			$parameters["action"] = 3;
			return $this->callReadRepositoryForTypeahead($valueParameter,$textParameter,function($source,$parameters){
				return $source->getCities($parameters);
			},$this->geoRepository,$parameters);
		}
		return $data;
	}
}
?>