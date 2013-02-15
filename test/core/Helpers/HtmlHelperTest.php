<?php
/**
 * HTML Helper Unit test.
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
use core\Patterns\ASingleton;
use core\App;
/**
 * HTML Helper Unit test.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
*/
class HtmlHelperTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Test setup make sure we have test data that match original configuration.
	 *
	 * @see    PHPUnit_Framework_TestCase::setUp()
	 * @return void
	 */
	public function setUp()
	{
		
	}
	
	/**
	 * Test setup make sure we have test data that match original configuration.
	 *
	 * @see    PHPUnit_Framework_TestCase::tearDown()
	 * @return void
	 */
	public function tearDown()
	{
		ASingleton::$test = true;
		App::getInstance();
		HtmlHelper::$styles = null;
	}
	
	/**
	 * Test constructor.
	 *
	 * @return void
	 */
	public function testConstructor()
	{
		$this->assertInstanceOf('core\Helpers\HtmlHelper', new HtmlHelper());
	}
	
	/**
	 * Test getAllCss() Not Minified.
	 *
	 * @return void
	 */
	public function testGetAllCss()
	{
		App::$config->minify = false;
		$myHelper = new HtmlHelper();
		$myHelper->getAllCSS();
		$expectedOut = '<link rel="stylesheet" href="/assets/css/main_css" media="screen" />' . "\n"
			. '<link rel="stylesheet" href="http://www.example.com/my.css" media="screen" />' . "\n"
			. '<link rel="stylesheet" href="/css/print.css" media="print" />' . "\n";
		//$this->assertEquals($expectedOut, $myData);
		unlink(CM_CACHE . 'css/main_css');
		rmdir(CM_CACHE . 'css');
		rmdir(CM_CACHE);
		$this->expectOutputString($expectedOut);
	}
	
	/**
	 * Test getAllCss() Minified.
	 *
	 * @return void
	 */
	public function testGetAllCssMinified()
	{
		$myHelper = new HtmlHelper();
		$myHelper->getAllCSS();
		// Hash are precalculated and should never change
		$expectedOut =
		'<link rel="stylesheet" href="http://www.example.com/my.css" media="screen" />' . "\n"
		. '<link rel="stylesheet" href="/assets/css/bbf10e97391588abaae1ed5a0d129f74" media="screen" />'
		. "\n"
		. '<link rel="stylesheet" href="/assets/css/f6a664314bc30662212b1b4f67e58b7e" media="print" />'
		. "\n";
		$this->clearMinified();
		$this->expectOutputString($expectedOut);
	}
	
	/**
	 * Test getSectionCSS().
	 *
	 * @return void
	 */
	public function testGetSectionCSS()
	{
		$myHelper = new HtmlHelper();
		$myHelper->getSectionCSS('main');
		$this->expectOutputString(
			'<link rel="stylesheet" href="http://www.example.com/my.css" media="screen" />' . "\n"
			. '<link rel="stylesheet" href="/assets/css/bbf10e97391588abaae1ed5a0d129f74" media="screen" />'
			. "\n"
		);
		$this->clearMinified();
	}
	
	/**
	 * Test getAllJS.
	 *
	 * @return void
	 */
	public function testGetAllJS()
	{
		App::$config->minify = false;
		$myHelper = new HtmlHelper();
		$myHelper->getAllJS();
		$this->expectOutputString(
				'<script type="text/javascript" src="/js/main.js" ></script>' . "\n"
				. '<script type="text/javascript" src="/js/test.js" ></script>' . "\n"
				. '<script type="text/javascript" src="http://www.example.com/my.js" ></script>' . "\n"
		);
	}
	
	/**
	 * Test getAllJS Minified.
	 *
	 * @return void
	 */
	public function testGetAllJSMinified()
	{
		$myHelper = new HtmlHelper();
		//$myHelper = new HtmlHelper();
		$myHelper->getAllJS();
		$this->expectOutputString(
				'<script type="text/javascript" src="/assets/js/b5a311481ff72d5b7f92710c884b2938" ></script>' . "\n"
				. '<script type="text/javascript" src="http://www.example.com/my.js" ></script>' . "\n"
				. '<script type="text/javascript" src="/assets/js/1ad15a2e283f03a744c40eb562586bde" ></script>' . "\n"
		);
		$this->clearMinified();
	}
	
	/**
	 * Test getSectionJS().
	 *
	 * @return void
	 */
	public function testGetSectionJS()
	{
		$myHelper = new HtmlHelper();
		$myHelper->getSectionJS('main');
		$this->expectOutputString(
				'<script type="text/javascript" src="/assets/js/b5a311481ff72d5b7f92710c884b2938" ></script>' . "\n"
		);
		$this->clearMinified();
	}
	
	/**
	 * Test getImage().
	 *
	 * @return void
	 */
	public function testGetImage()
	{
		$myHelper = new HtmlHelper();
		$this->assertEquals('/images/myImage.png', $myHelper->getImage('myImage.png'));
	}
	
	/**
	 * Clear minified data tmp files.
	 *
	 * @return void
	 */
	public function clearMinified()
	{
		unlink(CM_CACHE . 'css/bbf10e97391588abaae1ed5a0d129f74');
		unlink(CM_CACHE . 'css/f6a664314bc30662212b1b4f67e58b7e');
		unlink(CM_CACHE . 'js/1ad15a2e283f03a744c40eb562586bde');
		unlink(CM_CACHE . 'js/b5a311481ff72d5b7f92710c884b2938');
		unlink(CM_CACHE . 'styles.min.json');
		rmdir(CM_CACHE . 'css');
		rmdir(CM_CACHE . 'js');
		rmdir(CM_CACHE);
	}
}