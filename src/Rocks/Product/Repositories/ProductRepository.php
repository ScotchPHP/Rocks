<?
namespace Rocks\Product\Repositories;

use	Scotch\Data\SqlParameterDirections as SqlParameterDirections;
use Rocks\Repositories\RocksSqlRepository as RocksSqlRepository;

Class ProductRepository extends CoreSqlRepository
{
	function __construct($session)
	{
		parent::__construct($session);
	}
	
/* PRODUCTS */
	public function getProducts($parameters = array())
	{
		return $this->callStandardStoredProcedure("pdct.getProducts", array(
			$this->createIntegerSqlParameter("@productID", $this->getParameterValue($parameters, "productID")),
		));
	}
	
	public function updateProduct($parameters = array())
	{
		return $this->callStandardStoredProcedure("pdct.updateProduct", array(
			$this->createIntegerSqlParameter("@productID", $this->getParameterValue($parameters, "productID"), SqlParameterDirection::InOut),
			$this->createStringSqlParameter("@productName", 50, $this->getParameterValue($parameters, "productName")),
			$this->createIntegerSqlParameter("@productTypeID", $this->getParameterValue($parameters, "productTypeID")),
			$this->createErrorStringSqlParameter("@productName_Error"),
			$this->createErrorStringSqlParameter("@productTypeID_Error"),
		));
	}

/* PLANS */
	public function getPlans($parameters = array())
	{
		return $this->callStandardStoredProcedure("pdct.getPlans", array(
			$this->createIntegerSqlParameter("@planID", $this->getParameterValue($parameters, "planID")),
			$this->createIntegerSqlParameter("@productID", $this->getParameterValue($parameters, "productID")),
		));
	}
	
	public function updatePlan($parameters = array())
	{
		return $this->callStandardStoredProcedure("pdct.updatePlan", array(
			$this->createIntegerSqlParameter("@planID", $this->getParameterValue($parameters, "planID"), SqlParameterDirection::InOut),
			$this->createIntegerSqlParameter("@productID", $this->getParameterValue($parameters, "productID")),
			$this->createStringSqlParameter("@planName", 50, $this->getParameterValue($parameters, "planName")),
			$this->createDecimalSqlParameter("@price", 9, 2, $this->getParaecmeterValue($parameters, "price")),
			$this->createErrorStringSqlParameter("@productID_Error"),
			$this->createErrorStringSqlParameter("@planName_Error"),
			$this->createErrorStringSqlParameter("@price_Error"),
		));
	}

/* PRODUCT TYPES */
	public function getProductTypes($parameters = array())
	{
		return $this->callStandardStoredProcedure("pdct.getProductTypes", array(
			$this->createIntegerSqlParameter("@productTypeID", $this->getParameterValue($parameters, "productTypeID")),
		));
	}
	
}
?>