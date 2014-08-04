<?php
namespace Rocks\Models;

use Scotch\Models\BaseModel as BaseModel;
use Scotch\Utilities\Utilities as Utilities;

class Pagination extends BaseModel
{
	public $page;							//current page
	public $pageSize;						//# of records per page
	public $maxRows;						//total # of records in un-paged results
	public $includeQueryString = true;		
	public $includeFormVariables = false;		
	
	private static $propertyMap = array(
		"pg" => "page",
		"ps" => "pageSize",
	);
	
	public function setData($properties = array())
	{
		if(count($properties)>0)
		{
			foreach($properties as $key => $value)
			{
				$key = ltrim($key, "@");
				if( property_exists($this, $key) )
				{
					$this->$key = $value;
				}
				else
				{
					$key = Utilities::getInstance()->getValue($propertyMap, $key);
					if ( property_exists($this, $key) )
					{
						$this->$key = $value;
					}
				}
			}
		}
	}
	
	protected function getTypeMap()
	{
		return array(
			"page" => DataTypes::Int,
			"pageSize" => DataTypes::Int,
			"maxRows" => DataTypes::Int,
			"includeQueryString" => DataTypes::Bit,
			"includeFormVariables" => DataTypes::Bit
		);
	}
}
?>