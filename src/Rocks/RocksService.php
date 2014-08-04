<?php
namespace Rocks;

use Scotch\Services\CacheableService as CacheableService;
use Scotch\Models\BaseModel as BaseModel;
use Scotch\Exceptions\InvalidArgumentException as InvalidArgumentException;
use Scotch\Collections\PagedCollection as PagedCollection;

use Rocks\Models\ServiceResult as ServiceResult;
use Rocks\Models\Typeahead as Typeahead;

class RocksService extends CacheableService
{
	const COLLECTION_CLASS_NAME = "collectionClassName";
	const MODEL_CLASS_NAME = "modelClassName";
	const GET_FUNCTION = "getFunction";
	
	function __construct($router = null)
	{
		parent::__construct();
		$this->router = $router;
	}
	
	protected function callReadRepository($collectionClassName,$modelClassName,$idParameter,$getFunction,$repository,$parameters)
	{
		$data = null;
		if(isset($getFunction))
		{
			if(is_callable($getFunction))
			{
				$idParameterValue = $this->util->getValue($parameters, $idParameter);
				$output = $getFunction($repository,$parameters);
				
				if(isset($idParameterValue))
				{
					if(($row = $output["data"]->getNextRow()))
					{
						$data = new $modelClassName($row);
					}
				}
				else
				{
					$data = new $collectionClassName();
					while(($row = $output["data"]->getNextRow()))
					{
						$data->addItem(new $modelClassName($row));
					}
					if($data instanceof PagedCollection)
					{
						$data->page = $this->util->getValue($output["outputs"],"@page");
						$data->pageSize = $this->util->getValue($output["outputs"],"@pageSize");
						$data->maxRows = $this->util->getValue($output["outputs"],"@maxRows");
					}
				}
			}
			else
			{
				throw new InvalidArgumentException("The repository get function must be a valid function");
			}
		}
		return $data;
	}
	
	protected function callCachedReadRepository($collectionClassName,$modelClassName,$idParameter,$cacheKey,$getFunction,$repository,$parameters)
	{
		$data = null;
		if(isset($getFunction) && $this->util->hasValue($cacheKey))
		{
			$idParameterValue = $this->util->getValue($parameters, $idParameter);
			unset($parameters[$idParameter]);
			
			$data = $this->retrieveCachedData($cacheKey, function($repository, $parameters, $modelParameters){
				$collectionClassName = $modelParameters[RocksService::COLLECTION_CLASS_NAME];
				$modelClassName = $modelParameters[RocksService::MODEL_CLASS_NAME];
				$getFunction = $modelParameters[RocksService::GET_FUNCTION];
				
				$data = new $collectionClassName();
				$output = $getFunction($repository,$parameters);
				
				while(($row = $output["data"]->getNextRow()))
				{
					$data->addItem(new $modelClassName($row));
				}
				return $data;
			}, $repository, $parameters, array(
				self::COLLECTION_CLASS_NAME => $collectionClassName,
				self::MODEL_CLASS_NAME => $modelClassName,
				self::GET_FUNCTION => $getFunction,
			));
			if ( $this->util->hasValue($idParameterValue) )
			{
				$data = $data->getItemById($idParameterValue);
			}
		}
		
		return $data;
	}
	
	protected function callReadRepositoryForTypeahead($valueParameter,$textParameter,$getFunction,$repository,$parameters)
	{
		$data = array();
		if(isset($getFunction))
		{
			if(is_callable($getFunction))
			{
				$output = $getFunction($repository,$parameters);
				$valueParameterIsFunction = ( is_callable($valueParameter) ) ? true : false;
				$textParameterIsFunction = ( is_callable($textParameter) ) ? true : false;
				
				while(($row = $output["data"]->getNextRow()))
				{
					$value = ( $valueParameterIsFunction ) ? $valueParameter($row) : $row[$valueParameter];
					$text = ( $textParameterIsFunction ) ? $textParameter($row) : $row[$textParameter];
					
					array_push($data, new Typeahead(array(
						"value" => $value,
						"text" => $text,
					)));
				}
			}
			else
			{
				throw new InvalidArgumentException("The repository get function must be a valid function");
			}
		}
		return $data;
	}
	
	protected function callModifyRepository($model, $repositoryFunction = null, $repository)
	{
		$output = array();
		if(isset($repositoryFunction))
		{
			if(is_callable($repositoryFunction))
			{
				$output = $repositoryFunction($repository,$model);
				$output["model"] = $model;
			}
			else
			{
				throw new InvalidArgumentException("The repository function must be a valid function");
			}
		}
		
		return new ServiceResult($output);
	}
}
?>