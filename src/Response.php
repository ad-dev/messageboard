<?php
namespace Code;
/**
 * Class for formatting response
 * @author ad
 *
 */
class Response
{
	protected $data;
	
	public function add($key, $value)
	{
		$this->data[$key] = $value;
	}
	
	public function getJSON()
	{
		return json_encode($this->data);
	}
}