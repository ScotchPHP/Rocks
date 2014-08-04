<?
namespace Rocks\Product\Collections;

use Scotch\Collections\Collection as Collection;

class ProductCollection extends Collection
{
	protected function idProperty()
	{
		return "productID";
	}
	protected function valueProperty()
	{
		return "productName";
	}
}
?>