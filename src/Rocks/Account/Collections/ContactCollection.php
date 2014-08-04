<?
namespace Rocks\Account\Collections;

use Scotch\Collections\PagedCollection as PagedCollection;

class ContactCollection extends PagedCollection
{
	protected function idProperty()
	{
		return "contactID";
	}
	protected function valueProperty()
	{
		return "email";
	}
}
?>