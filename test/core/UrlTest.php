<?php
/**
 * Url Test.
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
 * Test Url request parser.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class UrlTest extends PHPUnit_Framework_TestCase
{
	
	/**
	 * URL Object
	 *
	 * @var Url
	 */
	public $request;
	
	/**
	 * Test SetUp.
	 *
	 * @see    PHPUnit_Framework_TestCase::setUp()
	 * @return void
	 */
	public function setUp()
	{
		// set GLOBAL values;
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/fr/test/request?va1=1&val2=2';
		$_SERVER['HTTPS'] = 'on';
		$_GET['val1'] = '1';
		$_POST['val2'] = '2';
		$this->request = Url::getInstance();
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
		$this->request = null;
	}
	
	/**
	 * Base test to make sure that the request is well set.
	 *
	 * @return void
	 */
	public function testGetValues()
	{
		$this->assertEquals('https', $this->request->protocol);
		$this->assertEquals('fr/test/request', $this->request->uri);
		$this->assertEquals('localhost', $this->request->host);
		$this->assertArrayHasKey('val1', $this->request->filters);
		$this->assertArrayHasKey('val2', $this->request->post);
	}
	
	/**
	 * Test setCurrentAction.
	 *
	 * @return void
	 */
	public function testSetCurrentAction()
	{
		$this->request->setCurrentAction('test');
		$this->assertEquals('test', $this->request->currentAction);
	}
	
	/**
	 * Test setCurrentActionType.
	 *
	 * @return void
	 */
	public function testSetCurrentActionType()
	{
		$this->request->setCurrentActionType('page');
		$this->assertEquals('page', $this->request->currentActionType);
	}
	
	/**
	 * Test shiftPath.
	 *
	 * @return void
	 */
	public function testShiftPath()
	{
		$expectedCount = count($this->request->path) - 1;
		$this->request->shiftPath();
		$this->assertCount($expectedCount, $this->request->path);
	}
	
	/**
	 * Test getUrl.
	 *
	 * @return void
	 */
	public function testGetUrl()
	{
		$url = $this->request->getUrl('404', 'fr', 'test');
		$this->assertEquals('/fr/404/test', $url);
		$url = $this->request->getUrl('noroute');
		$this->assertEquals('/fr/', $url);
	}
	
	/**
	 * Test getCurrentLang.
	 *
	 * @return void
	 */
	public function testGetCurrentLang()
	{
		$this->assertEquals('fr', $this->request->getCurrentLang());
	}
	
	/**
	 * Test getDefaultLang.
	 *
	 * @return void
	 */
	public function testGetDefaultLang()
	{
		$this->assertEquals('en', $this->request->getDefaultLang());
	}
	
	/**
	 * Test getAllLang.
	 *
	 * @return void
	 */
	public function testGetAllLang()
	{
		$this->assertCount(2, $this->request->getAllLang());
		$this->assertContainsOnly('string', $this->request->getAllLang());
	}
	
	/**
	 * Test getRootUrl.
	 *
	 * @return void
	 */
	public function testGetRootUrl()
	{
		$this->assertEquals('https://localhost/fr/', $this->request->getRootUrl('fr'));
	}
	
	/**
	 * Test get bad request values.
	 *
	 * @expectedException PHPUnit_Framework_Error
	 * @return void
	 */
	public function testGetBadValue()
	{
		$this->request->no_data_here;
	}
}