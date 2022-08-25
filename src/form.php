<?php
namespace severak\forms;
use severak\forms\rules;

/**
 * Main form logic.
 *
 * Note that this is very similar how would you define form in pure HTML but it automatically adds server-side validation.
 *
 * @package severak\forms
 */
class form
{
	/** @var bool Is form valid? */
	public $isValid = true;
	/** @var array Error messages (one for field). */
	public $errors = [];
	/** @var array Form values. */
	public $values = [];
	/** @var array Definition of fields. */
	public $fields = [];
	/** @var array Attributes of form itself. */
	public $attr=[];

	protected $_rules=[];

	public $messages = [
		'required' => 'Field is required.'
	];

	/**
	 * Defines <form>.
	 *
	 * @param array $attr Attributes of form.
	 */
	public function __construct($attr=[])
	{
		if (empty($attr['id'])) $attr['id'] = 'form';

		$this->attr = $attr;
	}

	/**
	 * Adds new form field. Adds some implicit validation rules and sensible defaults to attributes.
	 *
	 * @param string $name Form field name (must be unique).
	 * @param array  $attr Attributes of field (label is label text).
	 *
	 * @throws usageException When developer used this library in bad way.
	 */
	public function field($name, $attr=[])
	{
		if (isset($this->fields[$name])) {
			throw new usageException('Field "'.$name.'" already defined.');
		}
		$attr['name'] = $name;

		// sensible defaults:
		if (empty($attr['type'])) $attr['type'] = 'text';
		if (empty($attr['label'])) $attr['label'] = ucfirst($name);

		if ($attr['type']=='submit') $attr['value'] = $attr['label'];

		if ($attr['type']=='checkbox' && empty($attr['value'])) $attr['value'] = 1;
		if ($attr['type']=='select' && empty($attr['options'])) $attr['options'] = [];

		// automatic element ID:
		if (empty($attr['id'])) $attr['id'] = $this->attr['id'] . '_' . $name;
		// ---
		$this->fields[$name] = $attr;
		
		if ($attr['type']=='file') $this->attr['enctype'] = 'multipart/form-data'; // enable upload

		// implicit rule's
		if (!empty($attr['required'])) $this->rule($name, 'severak\forms\rules::required', $this->messages['required']);
		// todo: implement numeric, email etc...
	}

	/**
	 * Adds validation rule for field.
	 *
	 * @param string   $name     Field name.
	 * @param callback $callback Validation callback with signature fun($fieldValue, $allFieldValues). Must return TRUE when value is valid.
	 * @param string   $message  Error message.
	 */
	public function rule($name, $callback, $message)
	{
		$this->_rules[$name][] = ['check'=>$callback, 'message'=>$message];
	}

	/**
	 * Fills form with data.
	 *
	 * @param array $data Form data.
	 * @return array Updated form data.
	 */
	public function fill($data)
	{
		// prefill checkboxes:
		foreach ($this->fields as $key=>$val) {
			if ($val['type']=='checkbox') {
				$this->values[$key] = 0;
			}
		}
		// fill data:
		foreach ($data as $key=>$val) {
			if (!empty($this->fields[$key])) {
				$this->values[$key] = $val;
			}
		}

		return $this->values;
	}

	/**
	 * Manually adds error message to field.
	 *
	 * @param string $name    Field name.
	 * @param string $message Error message.
	 */
	public function error($name, $message)
	{
		$this->errors[$name] = $message;
		$this->isValid = false;
	}

	/**
	 * Validates submitted form data.
	 *
	 * @return bool Is form valid?
	 */
	public function validate()
	{
		foreach ($this->_rules as $name => $rules) {
			$fieldValue = isset($this->values[$name]) ? $this->values[$name] : '';

			foreach ($rules as $rule) {
				$passed = call_user_func_array($rule['check'], [$fieldValue, $this->values]);
				if (empty($passed)) {
					$this->error($name, $rule['message']);
					break;
				}
			}
		}
		return $this->isValid;
	}

	/**
	 * Automagically turns your form into HTML code.
	 *
	 * @return string HTML code of form.
	 */
	function __toString()
	{
		return (string) new html($this);
	}

}