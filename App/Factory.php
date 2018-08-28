<?php

namespace App;

class Factory
{
	/**
	 * Gets instance of a class
	 * @param string $class
	 * @return object
	 */
	protected function getClass($class)
	{
		$instances = Registry::getInstance()->get('instances');
		if (!isset($instances) || (isset($instances) && !is_array($instances))) {
			$instances = [];
		}
		if (!isset($instances[$class])) { 
			$instances[$class] = new $class(); // creates new instansce of a class
		}
		return $instances[$class];
	}

	/**
	 * Gets instance of a controller class
	 * @param string $name
	 * @return object
	 */
	public function getController($name)
	{
		return $this->getClass('App\\Controllers\\' . ucfirst($name) . 'Controller');
	}
	
	/**
	 * Gets instance of a view class 
	 * @param string $name
	 * @return object
	 */

	public function getView($name)
	{
		return $this->getClass('App\\Views\\' . ucfirst($name) . 'View');
	}
	
	/**
	 * Gets instance of a model class
	 * @param string $name
	 * @return object
	 */
	
	public function getModel($name)
	{
		return $this->getClass('App\\Models\\' . ucfirst($name) . 'Model');
	}
}