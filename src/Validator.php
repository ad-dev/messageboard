<?php
namespace Code;
/**
 * Validator class for validating user input
 * @author ad
 *
 */
class Validator
{

	protected $rules; // regexp rules

	protected $user_input; // user input comes here

	protected $failed_inputs; // this holds failed user input after validation

	protected $custom_rules; // additional rules (only 'date' is implemented)

	public function addUserInput(array $user_input)
	{
		$this->user_input = $user_input;
	}

	public function addField($name, $regexp_rule)
	{
		$this->rules[$name] = $regexp_rule;
	}

	public function validate()
	{
		$failed_inputs = [];
		foreach ($this->user_input as $key => $value) {
			if (! empty($this->rules[$key])) {
				if (! preg_match('/' . $this->rules[$key] . '/ui', $value)) {
					$this->failed_inputs[$key] = $value;
				}

				if (!isset($this->failed_inputs[$key]) && ! empty($this->custom_rules[$key])) {
					switch ($this->custom_rules[$key]) {
						case 'date': 
							// custom rule for date validation:
							// checks if date is valid and not in the future
							$format = 'Y-m-d';
							$d = \DateTime::createFromFormat($format, $value);
							$now = new \DateTime();
							if ( !($d && $d->format($format) === $value) || $d->getTimestamp() > $now->getTimestamp()) {
								$this->failed_inputs[$key] = $value;
							}
							break;
						default:
							break;
					}
				}
			}
		}

		return empty($this->failed_inputs) ? true : false;
	}

	public function getFailedInputs()
	{
		return $this->failed_inputs;
	}
}