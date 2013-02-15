<?php
use core\Url;

/**
 * File Uploader Helper Unit test.
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
include_once(UNITTEST_ROOT . 'services/AServiceFormStub.php');
use core\App;

/**
 * File Uploader Helper Unit test.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class FileUploadHelperTest extends PHPUnit_Framework_TestCase
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
		R::debug(false);
		R::setup(
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
		$this->cleanUploads();
	}

	/**
	 * Test Uploading a single file.
	 *
	 * @return void
	 */
	public function testSingleFileUpload()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$_FILES['filefield'] = array(
				'name'     => 'Renamed.jpg',
				'type'     => 'image/jpeg',
				'tmp_name' => UNITTEST_ROOT . 'testImage.jpg',
				'error'    => UPLOAD_ERR_OK,
				'size'     => filesize(UNITTEST_ROOT . 'testImage.jpg')
		);

		$this->formStub = new AServiceFormStub();
		$this->formStub->setNewConfig(array(
				'modelName' => 'test',
				'templates' => array(
						'index' => 'forms/testIndex.php',
						'fail' => 'forms/testFail.php',
						'success' => 'forms/testSuccess.php'
				),
				'values' => array(
						'filefield' => array('file', true, '')
				),
				'messages' => array(
						'default' => array (
								'NO_DATA' => 'NO_DATA_DEFAULT_MESSAGE',
								'BAD_DATA' => 'BAD_DATA_DEFAULT_MESSAGE'
						)
				)
		));
		$this->formStub->call('submit');
		$this->expectOutputString('FORM_TEST_INDEX-SUCCESS');
		$this->assertFileExists(CM_UPLOAD_DIR . 'Renamed.jpg');
	}

	/**
	 * Test uploading multiple files.
	 *
	 * @return void
	 */
	public function testMultipleFileUpload()
	{
		$this->formStub = new AServiceFormStub();
		$this->formStub->setNewConfig(array(
				'modelName' => 'test',
				'templates' => array(
						'index' => 'forms/testIndex.php',
						'fail' => 'forms/testFail.php',
						'success' => 'forms/testSuccess.php'
				),
				'values' => array(
						'filefield' => array('filemultiple', true, '')
				),
				'messages' => array(
						'default' => array (
								'NO_DATA' => 'NO_DATA_DEFAULT_MESSAGE',
								'BAD_DATA' => 'BAD_DATA_DEFAULT_MESSAGE'
						)
				)
		));

		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$_FILES['filefield'] = array(
				'name'     => array(
						'Renamed.jpg',
						'Renamed1.jpg',
						'Renamed.jpg',
						'Renamed.jpg'
				),
				'type'     => array(
						'image/jpeg',
						'image/jpeg',
						'image/jpeg',
						'image/jpeg'
				),
				'tmp_name' => array(
						UNITTEST_ROOT . 'testImage.jpg',
						UNITTEST_ROOT . 'testImage.jpg',
						UNITTEST_ROOT . 'testImage.jpg',
						UNITTEST_ROOT . 'testImage.jpg'
				),
				'error'    => array(
						UPLOAD_ERR_OK,
						UPLOAD_ERR_OK,
						UPLOAD_ERR_OK,
						UPLOAD_ERR_OK
				),
				'size'     => array(
						filesize(UNITTEST_ROOT . 'testImage.jpg'),
						filesize(UNITTEST_ROOT . 'testImage.jpg'),
						filesize(UNITTEST_ROOT . 'testImage.jpg'),
						filesize(UNITTEST_ROOT . 'testImage.jpg')
				)
		);
		$this->formStub->call('submit');
		$this->expectOutputString('FORM_TEST_INDEX-SUCCESS');
		$this->assertFileExists(CM_UPLOAD_DIR . 'Renamed.jpg');
		$this->assertFileExists(CM_UPLOAD_DIR . 'Renamed1.jpg');
	}

	/**
	 * Test uploading with no files when files are required.
	 *
	 * @return void
	 */
	public function testFileUploadExceptionNoFile()
	{
		$this->formStub = new AServiceFormStub();
		$this->formStub->setNewConfig(array(
				'modelName' => 'test',
				'templates' => array(
						'index' => 'forms/testIndex.php',
						'fail' => 'forms/testFail.php',
						'success' => 'forms/testSuccess.php'
				),
				'values' => array(
						'filefield' => array('filemultiple', true, '')
				),
				'messages' => array(
						'default' => array (
								'NO_DATA' => 'NO_DATA_DEFAULT_MESSAGE',
								'BAD_DATA' => 'BAD_DATA_DEFAULT_MESSAGE'
						)
				)
		));
		$_FILES = array();
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';

		$this->formStub->call('submit');
		$this->expectOutputString('FORM_TEST_INDEX-FAIL');
	}

	/**
	 * Test uploading file in bad format.
	 *
	 * @return void
	 */
	public function testFileUploadExceptionBadFormat()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$_FILES['filefield'] = array(
				'name'     => 'Renamed.js',
				'type'     => 'text/javascript',
				'tmp_name' => UNITTEST_ROOT . 'testImage.js',
				'error'    => UPLOAD_ERR_OK,
				'size'     => filesize(UNITTEST_ROOT . 'testImage.jpg')
		);

		$this->formStub = new AServiceFormStub();
		$this->formStub->setNewConfig(array(
				'modelName' => 'test',
				'templates' => array(
						'index' => 'forms/testIndex.php',
						'fail' => 'forms/testFail.php',
						'success' => 'forms/testSuccess.php'
				),
				'values' => array(
						'filefield' => array('file', true, '')
				),
				'messages' => array(
						'default' => array (
								'NO_DATA' => 'NO_DATA_DEFAULT_MESSAGE',
								'BAD_DATA' => 'BAD_DATA_DEFAULT_MESSAGE'
						)
				)
		));

		$this->formStub->call('submit');
		$this->expectOutputString('FORM_TEST_INDEX-FAIL');
	}

	/**
	 * Test uploading multiple files to field that can only have 1 file.
	 *
	 * @return void
	 */
	public function testFileUploadExceptionBadMultiple()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$_FILES['filefield'] = array(
				'name'     => array(
						'Renamed.jpg',
						'Renamed1.jpg',
						'Renamed2.jpg',
						'Renamed3.jpg'
				),
				'type'     => array(
						'image/jpeg',
						'image/jpeg',
						'image/jpeg',
						'image/jpeg'
				),
				'tmp_name' => array(
						UNITTEST_ROOT . 'testImage.jpg',
						UNITTEST_ROOT . 'testImage.jpg',
						UNITTEST_ROOT . 'testImage.jpg',
						UNITTEST_ROOT . 'testImage.jpg'
				),
				'error'    => array(
						UPLOAD_ERR_OK,
						UPLOAD_ERR_OK,
						UPLOAD_ERR_OK,
						UPLOAD_ERR_OK
				),
				'size'     => array(
						filesize(UNITTEST_ROOT . 'testImage.jpg'),
						filesize(UNITTEST_ROOT . 'testImage.jpg'),
						filesize(UNITTEST_ROOT . 'testImage.jpg'),
						filesize(UNITTEST_ROOT . 'testImage.jpg')
				)
		);
		$this->formStub = new AServiceFormStub();
		$this->formStub->setNewConfig(array(
				'modelName' => 'test',
				'templates' => array(
						'index' => 'forms/testIndex.php',
						'fail' => 'forms/testFail.php',
						'success' => 'forms/testSuccess.php'
				),
				'values' => array(
						'filefield' => array('file', true, '')
				),
				'messages' => array(
						'default' => array (
								'NO_DATA' => 'NO_DATA_DEFAULT_MESSAGE',
								'BAD_DATA' => 'BAD_DATA_DEFAULT_MESSAGE'
						)
				)
		));
		$this->formStub->call('submit');
		$this->expectOutputString('FORM_TEST_INDEX-FAIL');
	}

	/**
	 * Test upload exception error on file.
	 *
	 * @return void
	 */
	public function testFileUploadExceptionFileError()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$_FILES['filefield'] = array(
				'name'     => 'Renamed.js',
				'type'     => 'text/javascript',
				'tmp_name' => UNITTEST_ROOT . 'testImage.js',
				'error'    => UPLOAD_ERR_EXTENSION,
				'size'     => filesize(UNITTEST_ROOT . 'testImage.jpg')
		);

		$this->formStub = new AServiceFormStub();
		$this->formStub->setNewConfig(array(
				'modelName' => 'test',
				'templates' => array(
						'index' => 'forms/testIndex.php',
						'fail' => 'forms/testFail.php',
						'success' => 'forms/testSuccess.php'
				),
				'values' => array(
						'filefield' => array('file', true, '')
				),
				'messages' => array(
						'default' => array (
								'NO_DATA' => 'NO_DATA_DEFAULT_MESSAGE',
								'BAD_DATA' => 'BAD_DATA_DEFAULT_MESSAGE'
						)
				)
		));

		$this->formStub->call('submit');
		$this->expectOutputString('FORM_TEST_INDEX-FAIL');
	}

	/**
	 * Test uploading multiple files with errors.
	 *
	 * @return void
	 */
	public function testMultipleFileUploadWithErrors()
	{
		$this->formStub = new AServiceFormStub();
		$this->formStub->setNewConfig(array(
				'modelName' => 'test',
				'templates' => array(
						'index' => 'forms/testIndex.php',
						'fail' => 'forms/testFail.php',
						'success' => 'forms/testSuccess.php'
				),
				'values' => array(
						'filefield' => array('filemultiple', true, '')
				),
				'messages' => array(
						'default' => array (
								'NO_DATA' => 'NO_DATA_DEFAULT_MESSAGE',
								'BAD_DATA' => 'BAD_DATA_DEFAULT_MESSAGE'
						)
				)
		));

		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$_FILES['filefield'] = array(
				'name'     => array(
						'Renamed.jpg',
						'Renamed1.jpg',
						'Renamed2.jpg',
						'Renamed3.jpg'
				),
				'type'     => array(
						'image/jpeg',
						'image/jpeg',
						'image/jpeg',
						'image/jpeg'
				),
				'tmp_name' => array(
						UNITTEST_ROOT . 'testImage.jpg',
						UNITTEST_ROOT . 'testImage.jpg',
						UNITTEST_ROOT . 'testImage.jpg',
						UNITTEST_ROOT . 'testImage.jpg'
				),
				'error'    => array(
						UPLOAD_ERR_EXTENSION,
						UPLOAD_ERR_CANT_WRITE,
						UPLOAD_ERR_INI_SIZE,
						UPLOAD_ERR_PARTIAL
				),
				'size'     => array(
						filesize(UNITTEST_ROOT . 'testImage.jpg'),
						filesize(UNITTEST_ROOT . 'testImage.jpg'),
						filesize(UNITTEST_ROOT . 'testImage.jpg'),
						filesize(UNITTEST_ROOT . 'testImage.jpg')
				)
		);
		$this->formStub->call('submit');
		$this->expectOutputString('FORM_TEST_INDEX-FAIL');
	}

	/**
	 * Test uploading With no Files when required.
	 *
	 * @return void
	 */
	public function testFileUploadWithNoFile()
	{
		$this->formStub = new AServiceFormStub();
		$this->formStub->setNewConfig(array(
				'modelName' => 'test',
				'templates' => array(
						'index' => 'forms/testIndex.php',
						'fail' => 'forms/testFail.php',
						'success' => 'forms/testSuccess.php'
				),
				'values' => array(
						'filefield' => array('filemultiple', true, '')
				),
				'messages' => array(
						'default' => array (
								'NO_DATA' => 'NO_DATA_DEFAULT_MESSAGE',
								'BAD_DATA' => 'BAD_DATA_DEFAULT_MESSAGE'
						)
				)
		));

		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$_FILES = array();
		$this->formStub->call('submit');
		$this->expectOutputString('FORM_TEST_INDEX-FAIL');
	}

	/**
	 * Move none existent file ... ERROR.
	 *
	 * @return void
	 */
	public function testFileUploadExceptionFileNotOnServer()
	{
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['REQUEST_URI'] = '/';
		$_FILES['filefield'] = array(
				'name'     => 'Renamed.png',
				'type'     => 'image/png',
				'tmp_name' => 'NOT_TO_BE_FOUND.png',
				'error'    => UPLOAD_ERR_OK,
				'size'     => '10'
		);

		$this->formStub = new AServiceFormStub();
		$this->formStub->setNewConfig(array(
				'modelName' => 'test',
				'templates' => array(
						'index' => 'forms/testIndex.php',
						'fail' => 'forms/testFail.php',
						'success' => 'forms/testSuccess.php'
				),
				'values' => array(
						'filefield' => array('file', true, '')
				),
				'messages' => array(
						'default' => array (
								'NO_DATA' => 'NO_DATA_DEFAULT_MESSAGE',
								'BAD_DATA' => 'BAD_DATA_DEFAULT_MESSAGE'
						)
				)
		));

		$this->formStub->call('submit');
		$this->expectOutputString('FORM_TEST_INDEX-FAIL');
	}

	/**
	 * Empty upload directory.
	 *
	 * @return void
	 */
	public function cleanUploads()
	{
		if (false === ($dh = @ opendir(CM_UPLOAD_DIR))) {
			throw FileSystemException::lastError(CM_UPLOAD_DIR);
		}
		
		while (false !== ($item = readdir($dh))) {
			if (('.' === $item) || ('..' === $item)) {
				continue;
			}
			unlink(CM_UPLOAD_DIR . $item);
		}
	}
}