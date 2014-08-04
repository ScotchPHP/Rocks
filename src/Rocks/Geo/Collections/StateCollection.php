<?php
namespace Rocks\Geo\Collections;

use Scotch\Collections\Collection as Collection;

class StateCollection extends Collection
{
	protected function idProperty()
	{
		return "stateID";
	}
	protected function valueProperty()
	{
		return "stateName";
	}
}
?>