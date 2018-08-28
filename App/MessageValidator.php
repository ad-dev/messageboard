<?php
namespace App;

use Code\Validator;
/**
 * Validator for user messages
 * @author ad
 *
 */
class MessageValidator extends Validator
{
	// rules to validate user fields
	protected $rules = [
		'fullname' => '[A-Za-z]+ {1,3}[A-Za-z]+',
		'birthdate' => '\d{4}-\d{2}-\d{2}',
		'email' => '^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,}$',
		'message' => '^.+$',
	];
	
	protected $custom_rules = [
		'birthdate' => 'date' // rule to check if date is valid
	];
}