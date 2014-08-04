<?
namespace Rocks\Account\Collections;

use Scotch\Collections\PagedCollection as PagedCollection;

class AccountCollection extends PagedCollection
{
	protected function idProperty()
	{
		return "accountID";
	}
	protected function valueProperty()
	{
		return "accountName";
	}
}
?>