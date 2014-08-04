<? 
namespace Rocks\Geo\Models;

use Scotch\Models\BaseModel as BaseModel;
use Rocks\Geo\Collections\StateCollection as StateCollection;

class Country extends BaseModel
{
	public $countryID;
	public $countryName;
	public $currencyID;
	public $distanceID;
	public $areaID;
	public $googleMapsDomain;
	public $acceptCC;
	
	public $states;
	
	function __construct($properties = array())
	{
		$this->states = new StateCollection();
		parent::__construct($properties);
	}
	
	protected function getTypeMap()
	{
		return array(
			"acceptCC" => "bit",
		);
	}
	
}
?>