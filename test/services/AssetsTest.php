<?php
use core\Patterns\ASingleton;

/**
 * Assets service unit testing.
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
use core\Helpers\HtmlHelper;
use core\App;
use services\Assets;

/**
 * Assets service unit testing.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class AssetsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test set up.
	 *
	 * @return void
	 */
	public function setUp()
	{
		ASingleton::$test = true;
		App::getInstance();
		App::$config->DEBUG = true;
	}
	
	/**
	 * Test tear down.
	 *
	 * @return void
	 */
	public function tearDown()
	{
		App::$config->DEBUG = false;
		HtmlHelper::$styles = null;
	}
	
	/**
	 * Test getting parsed less css.
	 *
	 * @return void
	 */
	public function testGetCss()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = 'css/main_css';
		App::$config->minify = false;
		ob_start();
		$myHelper = new HtmlHelper();
		$myHelper->getAllCSS();
		ob_clean();
		$myClass = new Assets('css');
		$this->expectOutputString(file_get_contents(CM_CACHE . 'css/main_css'));
		unlink(CM_CACHE . 'css/main_css');
		rmdir(CM_CACHE . 'css');
	}

	/**
	 * Test getting a non existant css.
	 *
	 * @return void
	 */
	public function testGetBadCss()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = 'css/some_bad_css';
		$myClass = new Assets('css');
		$this->expectOutputString('TEST_404_PAGE');
	}
	
	/**
	 * Test getting js.
	 *
	 * @return void
	 */
	public function testGetJS()
	{
		if (file_exists(CM_CACHE . 'styles.min.json')) {
			unlink(CM_CACHE . 'styles.min.json');
		}
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = 'js/b5a311481ff72d5b7f92710c884b2938';
		$myHelper = new HtmlHelper();
		ob_start();
		$myHelper->getSectionJS('main');
		$expectOut =
			'<script type="text/javascript" src="/assets/js/b5a311481ff72d5b7f92710c884b2938" ></script>'
			. "\n";
		ob_clean();
		$myClass = new Assets('js');
		$this->expectOutputString(
				file_get_contents(CM_CACHE . 'js/b5a311481ff72d5b7f92710c884b2938')
			);
		unlink(CM_CACHE . 'css/bbf10e97391588abaae1ed5a0d129f74');
		unlink(CM_CACHE . 'css/f6a664314bc30662212b1b4f67e58b7e');
		unlink(CM_CACHE . 'js/1ad15a2e283f03a744c40eb562586bde');
		unlink(CM_CACHE . 'js/b5a311481ff72d5b7f92710c884b2938');
		unlink(CM_CACHE . 'styles.min.json');
		rmdir(CM_CACHE . 'css');
		rmdir(CM_CACHE . 'js');
		rmdir(CM_CACHE);
	}
	
	/**
	 * Test getting a non existant css.
	 *
	 * @return void
	 */
	public function testGetBadJS()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = 'css/some_bad_css';
		$myClass = new Assets('css');
		$this->expectOutputString('TEST_404_PAGE');
	}
}