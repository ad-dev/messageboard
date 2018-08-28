<?php
namespace App\Views;

class IndexView extends View
{
	public function render()
	{
		$this->html = $this->getTemplate('form');
		return $this->getHtml();
	}
}