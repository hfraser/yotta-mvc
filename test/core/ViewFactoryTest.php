<?php
/**
 * View Factory Unit testing.
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
use core\ViewFactory;

/**
 * View Factory Unit testing.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class ViewFactoryTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test base setUp.
	 *
	 * @see    PHPUnit_Framework_TestCase::setUp()
	 * @return void
	 */
	public function setUp()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
	}
	
	/**
	 * Test getView() with base data.
	 *
	 * @return void
	 */
	public function testGetView()
	{
		$myView = ViewFactory::getView('PHPtemplates');
		$this->assertInstanceOf('core\Renderers\PHPtemplates', $myView);
		$this->assertEquals('en', $myView->getData('lang'));
	}
	
	/**
	 * Test getView() with no base data.
	 *
	 * @return void
	 */
	public function testGetViewNoBaseData()
	{
		$myView = ViewFactory::getView('PHPtemplates', false);
		$this->assertInstanceOf('core\Renderers\PHPtemplates', $myView);
		$this->assertNull($myView->getData('lang'));
	}
}