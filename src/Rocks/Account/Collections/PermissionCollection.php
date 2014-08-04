<?
namespace Rocks\Account\Collections;

use Scotch\Collections\PagedCollection as PagedCollection;

class PermissionCollection extends PagedCollection
{
	protected function idProperty()
	{
		return "permissionID";
	}
}
?>