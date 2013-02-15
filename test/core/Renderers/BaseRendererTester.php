<?php
/**
 * Renderer generic test.
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
use core\Helpers\FileUploadHelper;
use core\ViewFactory;


/**
 * Test to integrate in order to test base implementation of renderers.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class BaseRendererTester extends PHPUnit_Framework_TestCase
{
	public $renderer;
	
	/**
	 * Set the renderer to use for this test.
	 *
	 * @param string $aRenderer The renderer to test.
	 *
	 * @return self
	 */
	public function setRenderer($aRenderer = null)
	{
		$this->renderer = $aRenderer;
		return $this;
	}
	
	/**
	 * Template directory for the tests.
	 *
	 * @param string $aTemplateDir Test template directory for this renderer.
	 *
	 * @return self
	 */
	public function setTemplateDir($aTemplateDir = null)
	{
		$this->templateDir = $aTemplateDir;
		return $this;
	}
	
	/**
	 * The template suffix.
	 *
	 * The template suffix is used in orer to test template renering this way all templates
	 * for all test can be named the same and the suffix can change regardless of the system used.
	 *
	 * @param string $aTemplateSuffix Suffix of the templates to test.
	 *
	 * @return self
	 */
	public function setTemplateSuffix($aTemplateSuffix = '.php')
	{
		$this->templateSuffix = $aTemplateSuffix;
		return $this;
	}
	
	/**
	 * Test templateDir Getter and Setter.
	 *
	 * @return void
	 */
	public function testTemplateDir()
	{
		$myView = ViewFactory::getView($this->renderer, false);
		$myView->setTemplateDir('template_dir_path');
		$this->assertEquals('template_dir_path', $myView->getTemplateDir());
	}
	
	/**
	 * Test template Getter and Setter.
	 *
	 * @return void
	 */
	public function testLoadTemplate()
	{
		$myView = ViewFactory::getView($this->renderer, false);
		$myView->loadTemplate('a_templateName');
		$this->assertEquals('a_templateName', $myView->getTemplate());
	}
	
	/**
	 * Test Helper Getter and Setter.
	 *
	 * @return void
	 */
	public function testAddHelper()
	{
		$myHelper = FileUploadHelper::getInstance();
		$myView = ViewFactory::getView($this->renderer, false);
		$myView->addHelper('file', $myHelper);
		$this->assertInstanceOf('core\Helpers\FileUploadHelper', $myView->getHelper('file'));
	}
	
	/**
	 * Test Exception Helper not an object.
	 *
	 * @expectedException BadFunctionCallException
	 * @return void
	 */
	public function testBadHelper()
	{
		$myHelper = 'a string';
		$myView = ViewFactory::getView($this->renderer, false);
		$myView->addHelper('file', $myHelper);
	}
	
	/**
	 * Test Exception Get non existing Helper.
	 *
	 * @expectedException BadFunctionCallException
	 * @return void
	 */
	public function testCallBadHelper()
	{
		$myView = ViewFactory::getView($this->renderer, false);
		$myView->getHelper('nothing');
	}
	
	
	/**
	 * Test Data Getter and Setter.
	 *
	 * @return void
	 */
	public function testAddData()
	{
		$myView = ViewFactory::getView($this->renderer, false);
		$myData = 'data_input';
		$myView->addData('test', $myData);
		$this->assertEquals($myData, $myView->getData('test'));
		$myData = array('a' => 'aa');
		$myView->addData('test2', $myData);
		$tmpData = $myView->getData('test2');
		$this->assertEquals('aa', $tmpData->a);
	}
	
	/**
	 * Test rendering of template.
	 *
	 * @return void
	 */
	public function testRender()
	{
		$myView = ViewFactory::getView($this->renderer, false);
		$myView->setTemplateDir($this->templateDir);
		$myView->addData('test', 'TEST_DATA');
		$myView->loadTemplate('testTemplate' . $this->templateSuffix);
		$this->assertEquals('RENDERED :: TEST_DATA', $myView->render());
	}
	
	/**
	 * Test Exception no template exists.
	 *
	 * @expectedException core\Exceptions\CMFileDoesNotExist
	 * @return void
	 */
	public function testRenderNoTemplate()
	{
		$myView = ViewFactory::getView($this->renderer, false);
		$myView->setTemplateDir($this->templateDir);
		$myView->addData('test', 'TEST_DATA');
		$myView->loadTemplate('testTemplateDoesNotExists' . $this->templateSuffix);
		$myView->render();
	}
}