<?
namespace Rocks\Product\Collections;

use Scotch\Collections\Collection as Collection;

class PlanCollection extends Collection
{
	protected function idProperty()
	{
		return "planID";
	}
	protected function valueProperty()
	{
		return "planName";
	}
}
?>