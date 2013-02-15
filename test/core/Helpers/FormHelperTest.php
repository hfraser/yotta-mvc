<?php
/**
 * Form Helper Unit test.
 *
 * @version	   Release: 1.0
 * @author	   Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2013 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
use core\Helpers\FormHelper;
/**
 * Form Helper Unit test.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
*/
class FormHelperTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * Test constructor.
	 *
	 * @return void
	 */
	public function testConstructor()
	{
		$myHelper = new FormHelper();
		$this->assertInstanceOf('stdClass', $myHelper->getErrors());
	}
	
	/**
	 * Test setErrors().
	 *
	 * @return void
	 */
	public function testSetErrors()
	{
		$myHelper = new FormHelper();
		$myHelper->setErrors($this->getErrorData());
		$this->assertEquals('NO_DATA', $myHelper->getErrors()->field->error);
	}
	
	/**
	 * Test getError($error).
	 *
	 * @return void
	 */
	public function testGetError()
	{
		$myHelper = new FormHelper();
		$myHelper->setErrors($this->getErrorData());
		$this->assertEquals('ERROR_MESSAGE', $myHelper->getError('field'));
	}
	
	/**
	 * Test getErrorType($error).
	 *
	 * @return void
	 */
	public function testGetErrorType()
	{
		$myHelper = new FormHelper();
		$myHelper->setErrors($this->getErrorData());
		$this->assertEquals('NO_DATA', $myHelper->getErrorType('field'));
	}
	
	/**
	 * Simple function to generate standard error.
	 *
	 * @return stdClass
	 */
	public function getErrorData()
	{
		$myErrors = new stdClass();
		$myErrors->field = new stdClass();
		$myErrors->field->error = 'NO_DATA';
		$myErrors->field->message = 'ERROR_MESSAGE';
		
		return $myErrors;
	}
}