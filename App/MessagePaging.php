<?php
namespace App;

use Code\Paging;

class MessagePaging extends Paging
{
	const ITEMS_PER_PAGE = 5; // number of messages per page
	
	protected $prev_page_link_title = 'atgal';
	
	protected $next_page_link_title = 'toliau';
	
	public function getPaginationString()
	{
		$str = '';
		if ($this->current_page_no > $this->first_page_no) {
			$str .= '<a href="?' . $this->page_handler
				. '=' . $this->getPrevPageNo() . '"'
					. ' data-page="' . $this->getPrevPageNo() . '"'
					. '>' . $this->prev_page_link_title 
			. '</a>';
		}
		for($i = 1; $i <= $this->getPagesTotal(); $i++) {
			$str .= ' <a href="?'. $this->page_handler . '=' . $i . '"'
				. ' data-page="' . $i. '"' 
					. ($this->current_page_no == $i ? 'class="active"' : '') . '>' . $i . '</a>';
		}
		if ($this->current_page_no < $this->getPagesTotal()) {
			$str .= ' <a href="?' . $this->page_handler 
				. '=' . $this->getNextPageNo() . '"'
				. ' data-page="' . $this->getNextPageNo() . '"'
				. '>' . $this->next_page_link_title . '</a>';
		}
		
		return '<p id="pages">' .$str . '</p>';
	}

}