<?php
namespace Code;

class Paging
{
	protected $number_of_pages;

	protected $items_per_page;

	protected $items_total;

	protected $first_page_no = 1;

	protected $page_handler = 'page'; 

	protected $prev_page_link_title = '';

	protected $next_page_link_title = '';
	
	protected $current_page_no;

	public function __construct($items_per_page, $items_total)
	{
		$this->items_per_page = $items_per_page;
		$this->items_total = $items_total;
		$this->current_page_no = $this->first_page_no;
	}
	
	/** 
	 * Sets current page no.
	 * @param integer $page_no
	 */
	public function setCurrentPage($page_no)
	{
		$this->current_page_no = (int)$page_no;
	}
	
	/**
	 * Gets first page no.
	 * @return integer
	 */
	public function getFirstPageNo()
	{
		return $this->first_page_no;
	}
	/**
	 * Gets page handler (name of GET variable which holds current page no.)
	 * @return string
	 */
	public function getPageHandler()
	{
		return $this->page_handler;
	}

	/**
	 * Gets total pages count
	 * @return integer
	 */
	public function getPagesTotal()
	{
		return ceil($this->items_total / $this->items_per_page);
	}
	
	/**
	 * Gets items per page
	 * @return integer
	 */
	public function getItemsPerPage()
	{
		return $this->items_per_page;
	}
	
	/**
	 * Gets items offset for current page
	 * @return integer
	 */
	public function getOffset()
	{
		return ($this->current_page_no - 1) * $this->items_per_page;
	}
	
	public function getNextPageNo()
	{
		return $this->current_page_no < $this->getPagesTotal() ? $this->current_page_no + 1 : $this->current_page_no;
	}
	
	public function getPrevPageNo()
	{
		return $this->current_page_no > $this->first_page_no ? $this->current_page_no -1 : $this->first_page_no;
	}
}