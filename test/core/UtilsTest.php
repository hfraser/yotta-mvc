<?php
/**
 * Utils Unit testing.
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
use core\Utils;

/**
 * Utils Unit testing.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class UtilsTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * Base test to make sure that the request is well set.
	 *
	 * @return void
	 */
	public function testArrayToObj()
	{
		$myObject = Utils::arrayToObj(array(
				'a' => 'aa',
				'b' => array(
						'c' => 'cc'
						)
				)
			);
		$this->assertInstanceOf('stdClass', $myObject);
		$this->assertEquals('aa', $myObject->a);
		$this->assertInstanceOf('stdClass', $myObject->b);
		$this->assertEquals('cc', $myObject->b->c);
	}
}