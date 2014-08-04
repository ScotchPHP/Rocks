<?php
namespace Rocks\Models;

use Rocks\Models\TrackModel as TrackModel;
use Scotch\Utilities\HtmlUtilities as HtmlUtilities;
use Scotch\DataTypes as DataTypes;

class Address extends TrackModel
{
	public $countryID;
	public $address;
	public $address2;
	public $cityID;
	public $cityName;
	public $stateID;
	public $stateName;
	public $postalCode;
	public $county;
	public $latitude;
	public $longitude;
	
	protected function getTypeMap()
	{
		return array_merge(array(
			"cityID" => DataTypes::Int,
			"latitude" => DataTypes::Float,
			"longitude" => DataTypes::Float,
		),parent::getTypeMap());
	}
	
	public function fullAddress()
	{
		$util = HtmlUtilities::getInstance();
		
		$fullAddress = $util->coalesce($this->address,"");
		$fullAddress .= $util->coalesce(" ".$this->address2,"");
		$fullAddress .= $util->coalesce(" ".$this->cityName,"");
		$fullAddress .= $util->coalesce(", ".$this->stateName,"");
		$fullAddress .= $util->coalesce(" ".$this->postalCode,"");
		$fullAddress .= $util->coalesce(" ".$this->county,"");
		
		return $fullAddress;
	}
	
	public function renderAddress()
	{
		$util = HtmlUtilities::getInstance();
		
		return $util->renderAddress(array(
			"address" => $this->address,
			"address2" => $this->address2,
			"city" => $this->cityName,
			"state" => $this->stateName,
			"postalCode" => $this->postalCode,
			"country" => $this->countryID,
		));
	}
}
?>