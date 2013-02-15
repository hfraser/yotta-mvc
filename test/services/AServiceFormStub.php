<?php
/**
 * Suggestion box form class. Used as a stub.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2012 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
use core\AServiceForm;

/**
 * Suggestion box class
 *
 * This class is an example of a form service in action.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class AServiceFormStub extends AServiceForm
{
	/**
	 * Configuration for the current form.
	 *
	 * @return array
	 */
	protected function _setConfig()
	{
		return (object)array(
			'modelName' => 'test',
			'templates' => array(
				self::INDEX_ACTION => 'forms/testIndex.php',
				self::FAIL_ACTION => 'forms/testFail.php',
				self::SUCCESS_ACTION => 'forms/testSuccess.php'
			),
			'values' => array(
				'textfield' => array('string250', true),
				'emailfield' => array('email', true),
			),
			'messages' => array(
				'default' => array (
					self::ERROR_NO_DATA => 'NO_DATA_DEFAULT_MESSAGE',
					self::ERROR_BAD_DATA => 'BAD_DATA_DEFAULT_MESSAGE'
				),
				'textfield' => array (
					self::ERROR_NO_DATA => 'NO_DATA_TEXTFIELD_MESSAGE',
					self::ERROR_BAD_DATA => 'BAD_DATA_TEXTFIELD_MESSAGE'
				)
			)
		);
	}
	
	/**
	 * Stub function for testing different config.
	 *
	 * @param array $aNewConfig New configurationto test.
	 *
	 * @return void
	 */
	public function setNewConfig(array $aNewConfig)
	{
		$this->_config = (object)$aNewConfig;
		$this->_errors = new \stdClass;
		$this->_modelName = $this->_config->modelName;
	}
	
	/**
	 * ModelName Getter.
	 *
	 * @return string
	 */
	public function getModelName()
	{
		return $this->_modelName;
	}
	
	/**
	 * Errors Getter.
	 *
	 * @return stdClass
	 */
	public function getErrors()
	{
		return $this->_errors;
	}
}