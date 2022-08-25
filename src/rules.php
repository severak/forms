<?php
namespace severak\forms;

/**
 * Built-in validation rules.
 *
 * @package severak\forms
 */
class rules
{
	static function required($value, $others)
	{
		return !empty($value);
	}
}