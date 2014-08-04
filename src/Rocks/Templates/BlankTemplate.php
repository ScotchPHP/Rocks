<?php
namespace Kohva\Core\Templates;

use Kohva\Scotch\System as System;
use Kohva\Core\Templates\CoreTemplate as CoreTemplate;
use Kohva\Scotch\Utilities\WebUtilities as WebUtilities;
use Kohva\Core\Models\HtmlResource as HtmlResource;

class BlankTemplate extends CoreTemplate
{
	protected function templateStyleSheets()
	{
		return new HtmlResource(array(
			"resourceType" => "css",
			"resources" => array(
				"tieon.css",
			),
			"domain" => System::$application->configuration->staticDomain,
			"version" => System::$application->configuration->cssVersion,
		));
	}
	
	protected function templateScripts()
	{
		return new HtmlResource(array(
			"resourceType" => "js",
			"resources" => array(
				"jquery.js",
				"jquery.ui.js",
				"tieon.js",
			),
			"domain" => System::$application->configuration->staticDomain,
			"version" => System::$application->configuration->jsVersion,
		));
	}
	
	function body()
	{
		$h = WebUtilities::getInstance();
		
		$user = $this->controller->user;
		$currentAccount = $this->controller->account;
		$data = $this->view->data;
		
		$message = $h->getString($h->getValue($data, "message"));
		$errorMessage = $h->getString($h->getValue($data, "errorMessage"));
		$warnMessage = $h->getString($h->getValue($data, "warnMessage"));
		$infoMessage = $h->getString($h->getValue($data, "infoMessage"));
?>	
		<div id="ajaxLoading" class="hide"><?=$h->getString("loading...")?></div>		
		
		<? if ($h->hasValue($errorMessage)) { ?> 
			<div class="msg-box msg-error"> 
				<b>Error:</b> <?=$errorMessage?>
				<button type="button" class="close">&times;</button> 
			</div> 
		<? } ?>
		<? if ($h->hasValue($warnMessage)) { ?> 
			<div class="msg-box msg-warning"> 
				<b>Warning:</b> <?=$warnMessage?>
				<button type="button" class="close">&times;</button> 
			</div> 
		<? } ?>
		<? if ($h->hasValue($infoMessage)) { ?> 
			<div class="msg-box msg-info"> 
				<b>Info:</b> <?=$infoMessage?>
				<button type="button" class="close">&times;</button> 
			</div> 
		<? } ?>
		<? if ($h->hasValue($message)) {?>
			<div class="msg-box msg-success"> 
				<?=$message?>
				<button type="button" class="close">&times;</button> 
			</div> 
		<? } ?>
		
		<?$this->view()?>
<?
	}
}
?>