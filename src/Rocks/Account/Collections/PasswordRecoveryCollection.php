<?
namespace Rocks\Account\Collections;

use Scotch\Collections\Collection as Collection;

class PasswordRecoveryCollection extends Collection
{
	protected function idProperty()
	{
		return "userID";
	}
}
?>