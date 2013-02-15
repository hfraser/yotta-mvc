<?php
/**
 * App Class Unit testing.
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
use core\App;
use core\Patterns\ASingleton;
use core\Url;

/**
 * App Class Unit tests.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class AppTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test App Object.
	 *
	 * @var App
	 */
	public $app;
	
	/**
	 * Test SetUp.
	 *
	 * @see    PHPUnit_Framework_TestCase::setUp()
	 * @return void
	 */
	public function setUp()
	{
		$this->app = App::getInstance();
	}
	
	/**
	 * Tears down the test.
	 *
	 * This method is called after the test is executed.
	 *
	 * @return void
	 */
	public function tearDown()
	{
		$this->app = null;
		ASingleton::$test = true;
	}
	
	/**
	 * Test Construction.
	 *
	 * Constructor loads the config.
	 *
	 * @return void
	 */
	public function testConstructor()
	{
		$this->assertEquals('en', App::$config->lang->default);
		$this->assertEquals(true, App::$config->minify);
	}
	
	/**
	 * Test Clone.
	 *
	 * @expectedException BadMethodCallException
	 *
	 * @return void
	 */
	public function testClone()
	{
		$myClone = clone(App::getInstance());
	}
	
	/**
	 * Test Run App.
	 *
	 * @outputBuffering enabled
	 * @return void
	 */
	public function testRunPageIndex()
	{
		// set GLOBAL values;
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/index';
		Url::getInstance()->path;
		ASingleton::$test = false;
		$this->app->run();
		$this->expectOutputString('TEST_INDEX_TEMPLATE');
	}
	
	/**
	 * Test Run App.
	 *
	 * @outputBuffering enabled
	 * @return void
	 */
	public function testRunService()
	{
		// set GLOBAL values;
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = 'assets/';
		Url::getInstance()->path;
		ASingleton::$test = false;
		App::$config->DEBUG = true;
		$this->app->run();
		$this->expectOutputString('TEST_404_PAGE');
	}
	
	/**
	 * Test Run App.
	 *
	 * @outputBuffering enabled
	 * @return void
	 */
	public function testThrow404()
	{
		// set GLOBAL values;
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		ASingleton::$test = false;
		App::$config->DEBUG = true;
		$this->app->throw404();
		$this->expectOutputString('TEST_404_PAGE');
		App::$config->DEBUG = false;
	}
	
	/**
	 * Test mkCacheDir.
	 *
	 * @return void
	 */
	public function testCache()
	{
		App::mkCacheDir('test');
		$this->assertFileExists(CM_CACHE . 'test');
		App::writeCacheFile('test/test.txt', 'test');
		$this->assertFileExists(CM_CACHE . 'test/test.txt');
		$this->assertEquals('test', file_get_contents(CM_CACHE . 'test/test.txt'));
		// cleanup
		unlink(CM_CACHE . 'test/test.txt');
		//rmdir(CM_CACHE . 'js');
		rmdir(CM_CACHE . 'test');
		rmdir(CM_CACHE);
	}
}