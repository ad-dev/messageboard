<?php
namespace App\Views;

class MessageView extends View
{
	public function render()
	{
		$this->html = $this->getTemplate('message');
		return $this->getHtml();
	}
}