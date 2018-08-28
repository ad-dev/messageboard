<?php
namespace App\Views;

class NoMessagesView extends View
{
	public function render()
	{
		$this->html = $this->getTemplate('no_messages');
		return $this->getHtml();
	}
}