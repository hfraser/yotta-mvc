<?php
/**
 * Page Parser Class Unit testing.
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
use core\PageParser;
/**
 * Page Parser Class Unit tests.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class PageParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test default constructor.
	 *
	 * @return void
	 */
	public function testConstructor()
	{
		$myPage = new PageParser('index');
		$this->expectOutputString('TEST_INDEX_TEMPLATE');
	}
	
	/**
	 * Test exception when call with no valid route.
	 *
	 * @return void
	 */
	public function testNoRouteException()
	{
		$this->setExpectedException('BadMethodCallException');
		$myPage = new PageParser('no-route');
	}
	
	/**
	 * Test static getContent call.
	 *
	 * @return void
	 */
	public function testGetContent()
	{
		PageParser::getContent('index');
		$this->expectOutputString('TEST_INDEX_TEMPLATE');
	}
}