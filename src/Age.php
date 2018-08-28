<?php
namespace Code;
/**
 * Simple class used to calculate age when date is given
 * @author ad
 *
 */
class Age
{
	public static function get($date)
	{
		$datetime1 = new \DateTime();
		$datetime2 = new \DateTime($date);
		$interval = $datetime1->diff($datetime2);
		return $interval->format('%y m');
	}	
}