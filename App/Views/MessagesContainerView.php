<?php
namespace App\Views;

class MessagesContainerView extends View
{
	public function render()
	{
		$this->html = $this->getTemplate('messages_container');
		return $this->getHtml();
	}
}