d<?php
namespace Rocks\Geo\Collections;

use Scotch\Collections\Collection as Collection;

class CityCollection extends Collection
{
	protected function idProperty()
	{
		return "cityID";
	}
	protected function valueProperty()
	{
		return "cityName";
	}
}
?>