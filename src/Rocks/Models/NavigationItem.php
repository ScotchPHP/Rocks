<?php
namespace Rocks\Models;

use Scotch\Models\BaseModel as BaseModel;

class NavigationItem extends BaseModel
{
	public $id;
	public $name;
	public $label;
	public $url;
	public $icon;
	public $tooltip;
	public $breadcrumb;
}
?>