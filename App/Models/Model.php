<?php
namespace App\Models;

use App\Registry;

/**
 * Base Model class
 * @author ad
 *
 */
class Model
{
	protected $db = null;
	
	public function __construct()
	{
		$this->db = Registry::getInstance()->get('db');
	}
}