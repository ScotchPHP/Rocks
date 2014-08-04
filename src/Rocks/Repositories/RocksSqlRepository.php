<?php
namespace Rocks\Repositories;

use Scotch\System as System;
use Scotch\Repositories\SqlRepository as SqlRepository;
use	Scotch\Data\SqlParameter as SqlParameter;
use	Scotch\Data\SqlParameterDirections as SqlParameterDirections;
use	Scotch\Data\SqlParameterTypes as SqlParameterTypes;
use Scotch\Utilities\Utilities as Utilities;

/**
* Abstract base repository for Kohva Sql Repositories based on the Kohva Scotch framework.
*
*/
abstract class RocksSqlRepository extends SqlRepository
{
	// Constants for Associative Array Stored Procedure Output
	const ERROR_INDEX = "errors";
	const OUTPUT_INDEX = "outputs";
	const DATA_INDEX = "data";
	
	// Contants for RegEx Patterns
	const ERROR_PARAMETER_FORMAT_REGEX =  "/^@[a-zA-Z0-9]+_Error$/";
	
	// Constants for parameter names
	const ERROR_BIT_PARAMETER_NAME = "@error";
	
	protected abstract function getSqlSessionName();
	
	
	protected function getSqlSession()
	{
		return System::$application->configuration->getSqlSession($this->getSqlSessionName());
	}
	
	
	// Stored Procedure Methods
	
	/**
	* Call standard stored procedure.  Standard Stored Procedures have two error parameters 
	* that are always included.  @errorJson and @error.  This function appends the two error
	* parameters to the end of the parameters list automatically.
	*
	* @param string $procedureName The name of the stored procedure to execute
	* @param array<Kohva/Scotch/Data/SqlParameter> $parameters An array of SqlParameter(s) to pass to the stored procedure
	* @returns array returns an associative array 
	*		"data" - record set returned from the stored procedure.
	*       "errors" - associative array of errors <Error Name> => <Error Value>
	*       "output" - associative array of output parameters <Parameter Name> => <Parameter Value>
	*/
	protected function callStandardStoredProcedure($procedureName, $parameters = array())
	{
		$returnData = array();
		$errorData = array();
		$outputData = array();
		
		// Append Error Bit Parameter
		$errorParameter = $this->createErrorBitSqlParameter();
		array_push($parameters, $errorParameter);
		
		$rs = $this->sqlSession->executeStoredProcedure($procedureName, $parameters);
		
		$outputParameters = array();
		$this->setOutputParameters($outputParameters, $parameters);
		
		foreach($outputParameters as $key => $value)
		{
			if(preg_match(self::ERROR_PARAMETER_FORMAT_REGEX, $key))
			{
				$errorData[$key] = $value;
			}
			else
			{
				$outputData[$key] = $value;	
			}
		}
		
		// Delete ouptut parameter lists
		unset($outputParameters);
		
		// Create output data
		$returnData[self::DATA_INDEX] = $rs;
		$returnData[self::ERROR_INDEX] = $errorData;
		$returnData[self::OUTPUT_INDEX] = $outputData;
	
		return $returnData;
	}
	
	/**
	* Call a stored procedure that could return paged information.  Paged stored procedures have 3-4 parameters that are always included:
	*   @search (Optional) - include a search string that searches the data
	*	@page - the current page the data should retrieve
	* 	@pageSize - the amount of data that should be on each page
	*   @maxRows - maximum number of rows in the data coming out (used for creating paging interfaces)
	*
	* @param string $procedureName The name of the stored procedure to execute
	* @param array $parameterValue the parameter values array that specify the values that need to be passed to parameters
	* @param array $prePageParameters an array of all the parameters that need to be before the paging parameters
	* @param array $postPageParameters an array of all the parameters that need to be after the paging parameters
	* @param boolean $includeSearch boolean that if true adds the search parameter and doesn't add the parameter otherwise (defaults to true)
	* @param array <Kohva/Scotch/Data/SqlParameter> $parameters An array of SqlParameter(s) to pass to the stored procedure
	* @returns array returns an associative array 
	*		"data" - record set returned from the stored procedure.
	*       "errors" - associative array of errors <Error Name> => <Error Value>
	*       "output" - associative array of output parameters <Parameter Name> => <Parameter Value>
	*/
	public function callPagedStoredProcedure($procedureName, $parameterValues, $prePageParameters = array(), $postPageParameters = array(), $includeSearch = true)
	{
		$parameters = $prePageParameters;
		
		//create paging parameters
		if($includeSearch == true)
		{
			array_push($parameters, $this->createStringSqlParameter("@search", 100, $this->getParameterValue($parameterValues, "search")));
		}
		
		array_push($parameters, $this->createIntegerSqlParameter("@page", $this->getParameterValue($parameterValues, "page"), SqlParameterDirections::InOut));
		array_push($parameters, $this->createIntegerSqlParameter("@pageSize", $this->getParameterValue($parameterValues, "pageSize"), SqlParameterDirections::InOut));
		array_push($parameters, $this->createIntegerSqlParameter("@maxRows", $this->getParameterValue($parameterValues, "maxRows"), SqlParameterDirections::InOut));
		
		$parameters = array_merge($parameters, $postPageParameters);
		
		return $this->callStandardStoredProcedure($procedureName, $parameters);
	}
	
	// End Stored Procedure Methods
	
	// SQL Parameter Methods
	
	/**
	* Create a NVarChar Sql Parameter
	* 
	* @param string $name The name of the parameter.
	* @param int $size The size of the parameter.
	* @param mixed $value The value of the parameter
	* @param int $direction The direction of the parameter constants for these values are found in Kohva\Scotch\Data\SqlParameterDirections 
	* @returns Kohva\Scotch\Data\SqlParameter
	*/
	protected function createStringSqlParameter($name, $size, $value, $direction = SqlParameterDirections::In)
	{
		return new SqlParameter(array("name"=>$name,"direction"=>$direction,"type"=>SqlParameterTypes::SqlNVarChar,"size"=>$size,"value"=>$value));
	}
	
	/**
	* Create a NVarChar(Max) Sql Parameter
	* 
	* @param string $name The name of the parameter.
	* @param mixed $value The value of the parameter
	* @param int $direction The direction of the parameter constants for these values are found in Kohva\Scotch\Data\SqlParameterDirections 
	* @returns Kohva\Scotch\Data\SqlParameter
	*/
	protected function createStringMaxSqlParameter($name, $value, $direction = SqlParameterDirections::In)
	{
		return new SqlParameter(array("name"=>$name,"direction"=>$direction,"type"=>SqlParameterTypes::SqlNVarCharMax,"value"=>$value));
	}
	
	/**
	* Create an Integer Sql Parameter
	* 
	* @param string $name The name of the parameter.
	* @param int $size The size of the parameter.
	* @param mixed $value The value of the parameter
	* @param int $direction The direction of the parameter constants for these values are found in Kohva\Scotch\Data\SqlParameterDirections 
	* @returns Kohva\Scotch\Data\SqlParameter
	*/
	protected function createIntegerSqlParameter($name, $value, $direction = SqlParameterDirections::In)
	{
		return new SqlParameter(array("name"=>$name,"direction"=>$direction,"type"=>SqlParameterTypes::SqlInt,"value"=>$value));
	}
	
	/**
	* Create a Decimal Sql Parameter
	* 
	* @param string $name The name of the parameter.
	* @param int $size The size of the parameter.
	* @param mixed $value The value of the parameter
	* @param int $direction The direction of the parameter constants for these values are found in Kohva\Scotch\Data\SqlParameterDirections 
	* @returns Kohva\Scotch\Data\SqlParameter
	*/
	protected function createDecimalSqlParameter($name, $precision, $scale, $value, $direction = SqlParameterDirections::In)
	{
		return new SqlParameter(array("name"=>$name,"direction"=>$direction,"type"=>SqlParameterTypes::SqlDecimal,"precision"=>$precision,"scale"=>$scale,"value"=>$value));
	}
	
	/**
	* Create a DateTime Sql Parameter
	* 
	* @param string $name The name of the parameter.
	* @param int $size The size of the parameter.
	* @param mixed $value The value of the parameter
	* @param int $direction The direction of the parameter constants for these values are found in Kohva\Scotch\Data\SqlParameterDirections 
	* @returns Kohva\Scotch\Data\SqlParameter
	*/
	protected function createDateTimeSqlParameter($name, $value, $direction = SqlParameterDirections::In)
	{
		return new SqlParameter(array("name"=>$name,"direction"=>$direction,"type"=>SqlParameterTypes::SqlDateTime,"value"=>$value));
	}
	
	/**
	* Create a GUID Sql Parameter
	* 
	* @param string $name The name of the parameter.
	* @param int $size The size of the parameter.
	* @param mixed $value The value of the parameter
	* @param int $direction The direction of the parameter constants for these values are found in Kohva\Scotch\Data\SqlParameterDirections 
	* @returns Kohva\Scotch\Data\SqlParameter
	*/
	protected function createGUIDSqlParameter($name, $value, $direction = SqlParameterDirections::In)
	{
		return new SqlParameter(array("name"=>$name,"direction"=>$direction,"type"=>SqlParameterTypes::SqlUniqueIdentifier,"value"=>$value));
	}
	
	/**
	* Create a GUID Sql Parameter
	* 
	* @param string $name The name of the parameter.
	* @param int $size The size of the parameter.
	* @param mixed $value The value of the parameter
	* @param int $direction The direction of the parameter constants for these values are found in Kohva\Scotch\Data\SqlParameterDirections 
	* @returns Kohva\Scotch\Data\SqlParameter
	*/
	protected function createBitSqlParameter($name, $value, $direction = SqlParameterDirections::In)
	{
		return new SqlParameter(array("name"=>$name,"direction"=>$direction,"type"=>SqlParameterTypes::SqlBit,"value"=>$value));
	}
	
	/**
	* Create a XML Sql Parameter
	*
	* @param string $name The name of the parameter.
	* @param mixed $value The value of the parameter
	* @param int $direction The direction of the parameter constants for these values are found in Kohva\Scotch\Data\SqlParameterDirections 
	* @returns Kohva\Scotch\Data\SqlParameter
	*/
	protected function createXmlSqlParameter($name, $value, $direction = SqlParameterDirections::In)
	{
		return new SqlParameter(array("name"=>$name,"direction"=>$direction,"type"=>SqlParameterTypes::SqlXml,"value"=>$value));
	}
	
	/**
	* Create an Error String Sql output parameter
	* 
	* @param string $name The name of the parameter.
	* @param mixed $value The value of the parameter
	* @returns Kohva\Scotch\Data\SqlParameter
	*/
	protected function createErrorStringSqlParameter($name, $value = "")
	{
		return new SqlParameter(array("name"=>$name,"direction"=>SqlParameterDirections::Out,"type"=>SqlParameterTypes::SqlNVarChar,"size"=>100,"value"=>$value));
	}
	
	/**
	* Create an Error Bit Sql output parameter
	* 
	* @param mixed $value The value of the parameter
	* @returns Kohva\Scotch\Data\SqlParameter
	*/
	protected function createErrorBitSqlParameter($value = 0)
	{
		return new SqlParameter(array("name"=>self::ERROR_BIT_PARAMETER_NAME,"direction"=>SqlParameterDirections::Out,"value"=>$value));
	}
	
	// End SQL Parameter Methods
	
	// Cache Methods
	
	protected function retrieveFromCache($key, &$value)
	{
		$wasFound = false;
		
		$value = null;
		$cacheProvider = null;		
		if($this->hasCacheProvider($cacheProvider))
		{
			if($cacheProvider->exists($key))
			{
				$wasFound = true;
				$value = $cacheProvider->get($key);
			}
		}
		
		return $wasFound;
	}
	
	protected function putInCache($key, $value)
	{
		$cacheProvider = null;		
		if($this->hasCacheProvider($cacheProvider))
		{
			$wasFound = true;
			$value = $cacheProvider->set($key, $value);
		}		
	}
	
	protected function removeFromCache($key)
	{
		$cacheProvider = null;		
		if($this->hasCacheProvider($cacheProvider))
		{
			$value = $cacheProvider->delete($key);
		}
	}
	
	protected function clearCache()
	{
		$cacheProvider = null;		
		if($this->hasCacheProvider($cacheProvider))
		{
			$cacheProvider->clear();
		}
	}
	
	private function hasCacheProvider(&$cacheProvider)
	{
		$hasCacheProvider = false;
		
		$cacheProvider = System::$application->cache;
		if(isset($cacheProvider))
		{
			$hasCacheProvider = true;
		}
		
		return $hasCacheProvider;
	}
	
	// End Cache Methods
	
}
?>