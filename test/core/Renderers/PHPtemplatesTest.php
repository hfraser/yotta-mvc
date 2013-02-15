<?php
/**
 * PHP Template tester.
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
require_once 'BaseRendererTester.php';

/**
 * PHP Template tester.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class PHPtemplatesTest extends BaseRendererTester
{
	/**
	 * Constructor.
	 */
	public function __construct()
	{
		$this->setRenderer('PHPtemplates');
		$this->setTemplateDir(TEMPLATE_ROOT . 'PHPtemplates/');
		$this->setTemplateSuffix('.php');
	}
	
	/**
	 * Test Data Getter and Setter.
	 *
	 * @return void
	 */
	public function testAddData()
	{
		parent::testAddData();
		$myView = ViewFactory::getView($this->renderer, false);
		$myView->addData('testData',
				array(
					'data1' => 1,
					'data2' => array(
						'data3' => 3
					)
				)
			);
		
		$this->assertEquals(1, $myView->getData('testData/data1'));
		$this->assertEquals(3,$myView->getData('testData/data2/data3'));
		$this->assertNull($myView->getData('/testData/data4'));
	}
}