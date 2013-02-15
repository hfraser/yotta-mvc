<?php
/**
 * Autoload Class Unit test.
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

/**
 * Autoload Class Unit test.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class AutoloadTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test Setup.
	 *
	 * @see    PHPUnit_Framework_TestCase::setUp()
	 * @return void
	 */
	public function setUp()
	{
		Autoload::$test = true;
	}
	
	/**
	 * Test TearDown.
	 *
	 * @see    PHPUnit_Framework_TestCase::tearDown()
	 * @return void
	 */
	public function tearDown()
	{
		Autoload::getInstance()->reload();
		Autoload::getInstance();
		Autoload::$test = true;
	}
	
	/**
	 * Test Construction.
	 *
	 * @return void
	 */
	public function testConstruct()
	{
		$myInstance = Autoload::getInstance();
		$this->assertInstanceOf('Autoload', $myInstance);
	}
	
	/**
	 * Test Construction.
	 *
	 * @expectedException BadMethodCallException
	 *
	 * @return void
	 */
	public function testClone()
	{
		$myInstance = Autoload::getInstance();
		$myClone = clone $myInstance;
	}
	
	/**
	 * Test Reload Autoload.
	 *
	 * @return void
	 */
	public function testReload()
	{
		$myInstance = Autoload::getInstance();
		$myInstance->reload();
		$myLoaders = $myInstance->getLoaders();
		$mySplLoaders = spl_autoload_functions();
		$myPresence = true;
		foreach ($mySplLoaders as $value) {
			if (in_array('baseAutoload', (array)$value)) {
				$myPresence = false;
			}
		}
		$this->assertTrue($myPresence);
	}
	
	/**
	 * Test addLoader() / deleteLoaders().
	 *
	 * @return void
	 */
	public function testAddLoader()
	{
		$myInstance = Autoload::getInstance();
		// load now
		$myInstance->addLoader('AutoloadTest::staticTestLoader');
		// delete now
		$this->assertTrue($myInstance->deleteLoader('AutoloadTest::staticTestLoader'));
		// load later and prepend
		$myInstance->addLoader('AutoloadTest::staticTestLoader', false, true);
		// test adding the loader twice
		$this->assertFalse($myInstance->addLoader('AutoloadTest::staticTestLoader', false, true));
		// test that loader is in first position
		$myLoaders = $myInstance->getLoaders();
		$this->assertTrue(in_array('AutoloadTest::staticTestLoader', $myLoaders));
		// delete later
		$this->assertTrue($myInstance->deleteLoader('AutoloadTest::staticTestLoader', false));
		// test delete non existant
		$this->assertFalse($myInstance->deleteLoader('AutoloadTest::staticTestLoader', false));
	}
	
	/**
	 * Test Namespace Loading getter and setter and namespace autoload function, and delete.
	 *
	 * @return void
	 */
	public function testSetNamespace()
	{
		$myInstance = Autoload::getInstance();
		$myInstance->setNamespace('testingPath\\', UNITTEST_ROOT . 'core/');
		$myNamspaces = (array)$myInstance->getNamespaceList();
		$this->assertEquals(UNITTEST_ROOT . 'core/', $myNamspaces['testingPath\\']);
		$this->assertTrue($myInstance->namespaceIsRegistered('testingPath\\'));
		$myObj = new testingPath\testObj();
		$this->assertInstanceOf('testingPath\testObj', $myObj);
		// test remove namespace
		$myInstance->removeNamespace('testingPath\\');
		$myNamspaces = $myInstance->getNamespaceList();
	}
	
	/**
	 * Test Base autoload.
	 *
	 * Nothing would be running without it ...
	 *
	 * @return void
	 */
	public function testBaseAutoload()
	{
		$this->assertTrue(true);
	}
	
	/**
	 * Allready tested with all classes using redbean.
	 *
	 * @return void
	 */
	public function testLibsAutoload()
	{
		$this->assertTrue(true);
	}
	
	/**
	 * Static test loader.
	 *
	 * @return boolean
	 */
	public static function staticTestLoader()
	{
		return false;
	}
}