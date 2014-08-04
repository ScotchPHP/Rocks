<?php
namespace Rocks\Geo\Collections;

use Scotch\Collections\Collection as Collection;

class CountryCollection extends Collection
{
	protected function idProperty()
	{
		return "countryID";
	}
	protected function valueProperty()
	{
		return "countryName";
	}
}
?>