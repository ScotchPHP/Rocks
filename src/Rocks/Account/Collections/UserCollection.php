<?
namespace Rocks\Account\Collections;

use Scotch\Collections\PagedCollection as PagedCollection;

class UserCollection extends PagedCollection
{
	protected function idProperty()
	{
		return "userID";
	}
	protected function valueProperty()
	{
		return "email";
	}
}
?>