<?php
namespace App;
/**
 * Application registry class
 * @author ad
 *
 */
class Registry
{
	private static $instance = null;
	
	public static function getInstance()
	{
		if (!isset(self::$instance)) {
			self::$instance = new Registry();
			return self::$instance;
		} else {
			return self::$instance;
		}
	}
	public function get($name)
	{
		if (!isset($this->variables[$name])) {
			return null;
		}
		return $this->variables[$name];
	}
	
	public function set($name, $data)
	{
		return $this->variables[$name] = $data;
	}
}
