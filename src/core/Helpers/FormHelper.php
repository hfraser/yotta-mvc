<?php
/**
 * Base Form template helper.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2013 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Core
 * @package    Core
 * @subpackage Helpers
 * @filesource
 */
namespace core\Helpers;
use core\App;
use core\Url;

/**
 * Base Form template helper.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   Helpers
 * @package    Core
 * @subpackage Helpers
 */
class FormHelper
{
	protected $_errors;
	
	/**
	 * Class Constructor.
	 */
	public function __construct()
	{
		$this->_errors = new \stdClass();
	}
	
	/**
	 * Set the error data.
	 *
	 * @param \stdClass $aErrorData The form data error.
	 *
	 * @return void
	 */
	public function setErrors(\stdClass $aErrorData)
	{
		$this->_errors = $aErrorData;
	}
	
	/**
	 * Get the error message for a specific field.
	 *
	 * @param string $aField The feild that we want to see the error for.
	 *
	 * @return string|boolean
	 */
	public function getError($aField)
	{
		return isset($this->_errors->$aField->message)? $this->_errors->$aField->message : false;
	}
	
	
	/**
	 * Get All Errors.
	 *
	 * @return stdClass
	 */
	public function getErrors()
	{
		return $this->_errors;
	}
	
	/**
	 * Get a specific error type for a specific form field.
	 *
	 * @param string $aField Form field that we require the error type from.
	 *
	 * @return boolean|string This function returns either the type string
	 *                        or false if there is no error on the specific field.
	 */
	public function getErrorType($aField)
	{
		return isset($this->_errors->$aField->error)? $this->_errors->$aField->error : false;
	}
}