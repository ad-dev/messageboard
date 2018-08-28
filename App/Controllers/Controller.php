<?php

namespace App\Controllers;

/**
 * Base Controller class
 * @author ad
 */

class Controller
{
	protected $_post = [];
	protected $_get = [];

	public function __construct()
	{
		// sanitizes user input
		if (!empty($_GET) && is_array($_GET)) {
			foreach($_GET as $key => $value) {
				$this->_get[$key] =
					htmlspecialchars(strip_tags(urldecode($value)), ENT_QUOTES);
			}
		}
		
		if (!empty($_POST) && is_array($_POST)) {
			foreach($_POST as $key => $value) {
			 $this->_post[$key] =
			 	htmlspecialchars(strip_tags(urldecode($value)), ENT_QUOTES);
			}
		}
	}

	/**
	 * Get HTTP POST data
	 * @return array
	 */
	public function getHttpPostData()
	{
		return $this->_post;
	}

	/**
	 * Get HTTP GET data
	 * @return array
	 */
	public function getHttpGetData()
	{
		return $this->_get;
	}

}