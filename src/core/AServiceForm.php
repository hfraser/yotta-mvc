<?php
/**
 * Base abstract form class.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2012 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Core
 * @package    Core
 * @filesource
 */
namespace core;
use core\Helpers\FileUploadHelper;
use core\Helpers\FormHelper;
use core\Exceptions\CMFileUploadException;
use core\Exceptions\CMFileUploadExceptionInvalidFormat;
use core\Exceptions\CMPathNotWritable;
use core\Exceptions\CMFileUploadMissingException;

/**
 * Base abstract form class.
 *
 * This class takes care of all base operations for form handling.
 * It contains validation of forms and base definitions.
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category Core
 * @package  Core
 */
abstract class AServiceForm extends AService {
	
	/**
	 * No data Error constant.
	 *
	 * This is used only if the submited field has no data
	 * and the field is required.
	 *
	 * @var string
	 */
	const ERROR_NO_DATA = 'NO_DATA';

	/**
	 * Invalid data Error constant.
	 *
	 * This is used only if the submited field is not appropriately formated.
	 *
	 * @var string
	 */
	const ERROR_BAD_DATA = 'BAD_DATA';

	/**
	 * Index constant.
	 *
	 * @var string
	 */
	const INDEX_ACTION = 'index';

	/**
	 * Success constant.
	 *
	 * @var string
	 */
	const SUCCESS_ACTION = 'success';

	/**
	 * Fail constant.
	 *
	 * @var string
	 */
	const FAIL_ACTION = 'fail';

	/**
	 * Current Errors.
	 *
	 * @var \stdClass
	 */
	protected $_errors;

	/**
	 * XSD Validation Schema.
	 *
	 * @var string Name of the XSD validation schema.
	 */
	protected $_schema;

	/**
	 * Contains the form data when the data as been parsed.
	 *
	 * @var \RedBean_OODBBean
	 */
	protected $_formData;

	/**
	 * Base form configuration.
	 *
	 * @var \stdClass
	 */
	protected $_config;
	
	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		$this->_request = Url::getInstance();
		$this->_config = $this->_setConfig();
		$this->_errors = new \stdClass;
		$this->_schema = __DIR__ . DS . 'FormValidate.xsd';
		$this->_modelName = $this->_config->modelName;
		$this->_formData = \R::dispense($this->_modelName);
		parent::__construct();
	}

	/**
	 * Set Configuration for the current form.
	 *
	 * This function should return an array that contains the following
	 * informations in an array.
	 *
	 * @return array
	 */
	abstract protected function _setConfig();

	/**
	 * Save the data of the submited form when the form is properly filled.
	 *
	 * @param \RedBean_OODBBean $aData Form data to be saved.
	 *
	 * @return int ID of the currently saved form.
	 */
	protected function _saveData(\RedBean_OODBBean $aData)
	{
		return \R::store($aData);
	}

	/**
	 * Base index action.
	 *
	 * This function should output the base for this
	 * module when it's called without arguments.
	 *
	 * @return void
	 */
	protected function _indexAction()
	{
		echo $this->_renderConfigTemplate(self::INDEX_ACTION);
	}

	/**
	 * Called for form submission.
	 *
	 * This function will validate and call the proper return either
	 * save or return error messsages to the front end the return is
	 * defined by the form config.
	 *
	 * @return mixed
	 */
	protected function _submitAction()
	{
		$this->_validate();
		if (count(get_object_vars($this->_errors)) > 0) {
			return $this->_fail();
		}
		return $this->_success();
	}

	/**
	 * Return result from a successfully saved and validated post to a form.
	 *
	 * @return void
	 */
	protected function _success()
	{
		$this->_saveData($this->_formData);
		echo $this->_renderConfigTemplate(self::SUCCESS_ACTION);
	}

	/**
	 * Return result from a badlyvalidated post to a form.
	 *
	 * @return void
	 */
	protected function _fail()
	{
		echo $this->_renderConfigTemplate(self::FAIL_ACTION);
	}

	/**
	 * Form validation.
	 *
	 * @return void
	 */
	protected function _validate()
	{
		foreach ($this->_config->values as $key => $elConf) {
			if ($elConf[0] === 'file' || $elConf[0] === 'filemultiple') {
				try {
					$this->_formData->$key = FileUploadHelper::getInstance()->handleFormUpload($key, $elConf);
				} catch (CMFileUploadException $e) {
					$this->_addFormError($key, self::ERROR_BAD_DATA);
				} catch (CMFileUploadMissingException $e) {
					$this->_addFormError($key, self::ERROR_NO_DATA);
				} catch (CMFileUploadExceptionInvalidFormat $e) {
					$this->_addFormError($key, self::ERROR_NO_DATA);
				}
			} else {
				$tmpValue = (isset($this->_request->post[$key])) ? $this->_request->post[$key] : '';
				$this->_formData->$key = $tmpValue;
				if (!empty($tmpValue)) {
					if (!$this->_validateEL($elConf[0], $tmpValue)) {
						$this->_addFormError($key, self::ERROR_BAD_DATA);
					}
				} elseif ($elConf[1] === true) {
					$this->_addFormError($key, self::ERROR_NO_DATA);
				}
			}
		}
	}

	/**
	 * Add an error to the error list.
	 *
	 * @param string $aKey       Key of the form element.
	 * @param string $aErrorType Type of the error.
	 *
	 * @return void
	 */
	protected function _addFormError($aKey, $aErrorType)
	{
		$this->_errors->$aKey = new \stdClass;
		$this->_errors->$aKey->error = $aErrorType;
		$this->_errors->$aKey->message = $this->_getErrorString($aKey, $aErrorType);
	}
	/**
	 * Validate a single element agains the xsd.
	 *
	 * @param string $aType  Element type.
	 * @param string $aValue Element posted value.
	 *
	 * @return boolean True or false depending on wether the ellement passes or not validation.
	 */
	protected function _validateEL($aType, $aValue)
	{
		// create doc
		$myDoc = new \DOMDocument('1.0', 'UTF-8');
		$myRoot = $myDoc->createElement('validation');
		$myDoc->appendChild($myRoot);

		// import myElement
		$myEl = $myDoc->createElement($aType, $aValue);
		$myRoot->appendChild($myEl);
		// validate element
		libxml_use_internal_errors(true);
		if ($myDoc->schemaValidate($this->_schema)) {
			return true;
		} else {
			libxml_clear_errors();
			return false;
		}
	}

	/**
	 * Get the text value for the message string attached to a specific field.
	 *
	 * @param string $aField     Field we require the error message from.
	 * @param string $aErrorType The error type we need the message from.
	 *
	 * @return string Error type.
	 */
	protected function _getErrorString($aField, $aErrorType)
	{
		if (isset($this->_config->messages[$aField][$aErrorType])) {
			return $this->_config->messages[$aField][$aErrorType];
		}
		return $this->_config->messages['default'][$aErrorType];
	}
	
	/**
	 * Render a template integrated in this service configuration.
	 *
	 * @param string $myAction Key of configuration action name.
	 *
	 * @return string
	 */
	protected function _renderConfigTemplate($myAction)
	{
		$myFormHelper = new FormHelper();
		$myFormHelper->setErrors($this->_errors);
		return ViewFactory::getView()
			->loadTemplate($this->_config->templates[$myAction])
			->addData('formData', $this->_formData->export())
			->addHelper('form', $myFormHelper)
			->render();
	}
}