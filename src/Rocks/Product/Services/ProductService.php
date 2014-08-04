<?php
namespace Rocks\Product\Services;

use Rocks\RocksService as RocksService;

class ProductService extends RocksService
{
/* CACHE KEYS */
	const PRODUCT_CACHE_KEY = "ProductService::getProducts";
	const PLAN_CACHE_KEY = "ProductService::getPlans";
	
/* CLASS CONSTANTS */
	const PRODUCT_COLLECTION_CLASS = "Rocks\Product\Collections\ProductCollection";
	const PRODUCT_MODEL_CLASS = "Rocks\Product\Models\Product";
	const PRODUCT_ID_PARAMETER = "productID";
	
	const PLAN_COLLECTION_CLASS = "Rocks\Product\Collections\PlanCollection";
	const PLAN_MODEL_CLASS = "Rocks\Product\Models\Plan";
	const PLAN_ID_PARAMETER = "planID";

/* PRIVATE PROPERTIES */
	private $sqlSession;
	private $productRepository;
	
/* CONSTRUCTOR */
	function __construct($sqlSession)
	{
		parent::__construct();

		$this->sqlSession = $sqlSession;
		$this->productRepository = $this->getService("IProductRepository", $this->sqlSession);
	}

/* PRODUCTS */
	public function getProducts($parameters = array())
	{
		return $this->callCachedReadRepository(self::PRODUCT_COLLECTION_CLASS,self::PRODUCT_MODEL_CLASS,self::PRODUCT_ID_PARAMETER,self::PRODUCT_CACHE_KEY,function($repository,$parameters){
			return  $repository->getProducts($parameters);
		},$this->productRepository,$parameters);
	}

/* PLANS */
	function getPlans($parameters = array())
	{
		$productID = $this->util->getValue($parameters, "productID");
		unset($parameters["productID"]);
		
		$data = $this->callCachedReadRepository(self::PLAN_COLLECTION_CLASS,self::PLAN_MODEL_CLASS,self::PLAN_ID_PARAMETER,self::PLAN_CACHE_KEY,function($repository,$parameters){
			return  $repository->getPlans($parameters);
		},$this->productRepository,$parameters);
		
		if($this->util->hasValue($productID))
		{
			$data->filter("productID", $productID);
		}
		
		return $data;
	}
}
?>