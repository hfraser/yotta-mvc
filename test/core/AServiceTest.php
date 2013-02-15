<?php
/**
 * Abstract class AService Unit testing.
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
use core\Url;
use core\Patterns\ASingleton;
include(UNITTEST_ROOT . 'services/AServiceStub.php');

/**
 * Abstract class AService Unit testing.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class AServiceTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Servive Stub.
	 *
	 * @var AServiceStub
	 */
	public $serviceStub;
	
	/**
	 * Test SetUp.
	 *
	 * @see    PHPUnit_Framework_TestCase::setUp()
	 * @return void
	 */
	public function setUp()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$this->serviceStub = new AServiceStub();
	}
	
	/**
	 * Test tear down.
	 *
	 * @see    PHPUnit_Framework_TestCase::tearDown()
	 * @return void
	 */
	public function tearDown()
	{
		$this->serviceStub = null;
		ASingleton::$test = true;
	}
	
	/**
	 * Base test to make sure that the constructor is properly called.
	 *
	 * @outputBuffering enabled
	 * @return void
	 */
	public function testConstruct()
	{
		// set GLOBAL values;
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$this->serviceStub = new AServiceStub('construct');
		
		$this->expectOutputString('CONSTRUCT_ACTION_CALLED');
	}
	
	/**
	 * Test custom call().
	 *
	 * @outputBuffering enabled
	 * @return void
	 */
	public function testCallCustom()
	{
		$myResponse = $this->serviceStub->call('custom');
		$this->expectOutputString('CUSTOM_ACTION_CALLED');
	}
	
	/**
	 * Test custom call with parameters.
	 *
	 * @outputBuffering enabled
	 * @return void
	 */
	public function testCallWithParams()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = 'params/param1/param2';
		Url::getInstance();
		ASingleton::$test = false;
		$myResponse = $this->serviceStub->call('params');
		$this->expectOutputString('param1-param2');
	}
}