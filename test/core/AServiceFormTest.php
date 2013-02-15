<?php
/**
 * Form service Unit test.
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
use core\Url;
use core\Patterns\ASingleton;
include(UNITTEST_ROOT . 'services/AServiceFormStub.php');

/**
 * Form service Unit test.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class AServiceFormTest extends PHPUnit_Framework_TestCase
{
	/**
	 * AServiceForm stub.
	 *
	 * @var AServiceFormStub
	 */
	public $formStub;
	
	/**
	 * Test SetUp.
	 *
	 * @see    PHPUnit_Framework_TestCase::setUp()
	 * @return void
	 */
	public function setUp()
	{
		// init ORM
		\R::setup(
			'sqlite:' . DATA_DIR . App::$config->DB->host,
			App::$config->DB->user,
			App::$config->DB->password);
	}
	
	/**
	 * Test tear down.
	 *
	 * @see    PHPUnit_Framework_TestCase::tearDown()
	 * @return void
	 */
	public function tearDown()
	{
		R::nuke();
		R::close();
		$this->formStub = null;
		ASingleton::$test = true;
	}
	
	/**
	 * Test geting form index page.
	 *
	 * @return void
	 */
	public function testGetIndex()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$this->formStub = new AServiceFormStub();
		
		Url::getInstance();
		ASingleton::$test = false;
		$this->formStub->getIndex('test');
		$this->expectOutputString('FORM_TEST_INDEX-FORM_TEST_INDEX-');
	}
	
	/**
	 * Test successfuly submiting data.
	 *
	 * @return void
	 */
	public function testSubmitSuccess()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$_POST['textfield'] = 'SOME_TEXT';
		$_POST['emailfield'] = 'test@email.com';
		$this->formStub = new AServiceFormStub();
		$this->formStub->call('submit');
		$this->expectOutputString('FORM_TEST_INDEX-SUCCESS');
		$mySavedForm = R::load($this->formStub->getModelName(), 1);
		$this->assertEquals('SOME_TEXT', $mySavedForm->textfield);
		$this->assertEquals('test@email.com', $mySavedForm->emailfield);
	}
	
	/**
	 * Test submiting badly (bad data and no data).
	 *
	 * @return void
	 */
	public function testSubmitFail()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$_POST['textfield'] = '';
		$_POST['emailfield'] = 'test-email.com';
		$this->formStub = new AServiceFormStub();
		$this->formStub->call('submit');
		$this->expectOutputString('FORM_TEST_INDEX-FAIL');
		$myErrors = $this->formStub->getErrors();
		// validate np data in field with custom message
		$this->assertEquals('NO_DATA', $myErrors->textfield->error);
		$this->assertEquals('NO_DATA_TEXTFIELD_MESSAGE', $myErrors->textfield->message);
		
		// validate bad data in email field with default messages
		$this->assertEquals('BAD_DATA', $myErrors->emailfield->error);
		$this->assertEquals('BAD_DATA_DEFAULT_MESSAGE', $myErrors->emailfield->message);
	}
}