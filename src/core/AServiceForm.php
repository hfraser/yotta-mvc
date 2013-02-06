<?php
/**
 * Base abstract form class.
 *
 * @todo Add documentation and tutorial on how to create new forms
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
	protected $_formData = array();

	/**
	 * Valid File array
	 * 
	 * @var array
	 */
	protected $_validFiles = array (
				"image/png",
			);
	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		$this->_request = App::getRequest();
		$this->_config = $this->_setConfig();
		$this->_errors = new \stdClass;
		$this->_schema = __DIR__ . DS . 'FormValidate.xsd';
		$this->_modelName = $this->_config->modelName;
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
		include($this->_config->templates[self::INDEX_ACTION]);
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
		include($this->_config->templates[self::SUCCESS_ACTION]);
	}

	/**
	 * Return result from a badlyvalidated post to a form.
	 *
	 * @return void
	 */
	protected function _fail()
	{
		include($this->_config->templates[self::FAIL_ACTION]);
	}

	/**
	 * Form validation.
	 *
	 * @return void
	 */
	protected function _validate()
	{
		$this->_formData = \R::dispense($this->_modelName);
		foreach ($this->_config->values as $key => $elConf) {
			if ($elConf[0] === 'file') {
				$this->_formData->$key = $this->_handleUpload($key);
			} else {
				$tmpValue = (isset($this->_request->post[$key])) ? $this->_request->post[$key] : '';
				$this->_formData->$key = $tmpValue;
				if (!empty($tmpValue)) {
					if (!$this->_validateEL($elConf[0], $tmpValue)) {
						$this->_errors->$key = new \stdClass;
						$this->_errors->$key->error = self::ERROR_BAD_DATA;
						$this->_errors->$key->message = $this->_getErrorString($key, self::ERROR_BAD_DATA);
					}
				} elseif ($elConf[1] === true) {
					$this->_errors->$key = new \stdClass;
					$this->_errors->$key->error = self::ERROR_NO_DATA;
					$this->_errors->$key->message = $this->_getErrorString($key, self::ERROR_NO_DATA);
				}
			}
		}
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
	 * Get a specific error message for a specific form field.
	 *
	 * @param string $aField Form field that we require the error message from.
	 *
	 * @return boolean|string This function returns either the message string
	 *                        or false if there is no error on the specific field.
	 */
	protected function _getError($aField)
	{
		return isset($this->_errors->$aField->message)? $this->_errors->$aField->message : false;
	}

	/**
	 * Get a specific error type for a specific form field.
	 *
	 * @param string $aField Form field that we require the error type from.
	 *
	 * @return boolean|string This function returns either the type string
	 *                        or false if there is no error on the specific field.
	 */
	protected function _getErrorType($aField)
	{
		return isset($this->_errors->$aField->error)? $this->_errors->$aField->error : false;
	}

	/**
	 * Get the data for a specific field.
	 *
	 * @param string $aField Form field you want the data for.
	 *
	 * @return mixed Field data.
	 */
	protected function _getData($aField)
	{
		$myValue = null;
		if (strpos($aField, "[") !== false && strpos($aField, "]") !== false) {
			$myMatches = preg_split('@[\]\[]|\[|\]@i', $aField, -1, PREG_SPLIT_NO_EMPTY);
			foreach ($myMatches as $idx => $arrayKey) {
				if (is_null($myValue)) {
					$myValue = $this->_formData;
				}
				if (!isset($myValue[$arrayKey])) {
					return '';
				}
				if (!is_array($myValue[$arrayKey]) && $idx < count($myMatches) - 1) {
					return '';
				}
				$myValue = $myValue[$arrayKey];
			}
			return $myValue;
		}
		if (!isset($this->_formData[$aField])) {
			return '';
		}
		if (is_array($this->_formData[$aField])) {
			return htmlspecialchars(implode(',', $this->_formData[$aField]));
		}
		$myValue = (isset($this->_formData[$aField]))? htmlspecialchars($this->_formData[$aField]) : '';
		return $myValue;
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
	 * Handle a file upload and attach the result to the model.
	 * 
	 * @param string $aFileKey Request File element name.
	 * 
	 * @return string
	 */
	protected function _handleUpload($aFileKey)
	{
		$myElConfig = $this->_config->values[$aFileKey];
		if ($myFileData = $this->_request->files[$aFileKey]) {
			// check if we have more then one uploaded files
			if (is_array($myFileData['name'])) {
				$myValue = array();
				// process multiple files
				for ($i = 0; $i < count($myFileData['name']); $i++) {
					// check if there was an error with the file
					if ($myFileData['error'][$i]) {
						$this->_errors->$aFileKey = new \stdClass;
						$this->_errors->$aFileKey->error = self::ERROR_BAD_DATA;
						$this->_errors->$aFileKey->message = $this->_getErrorString($key, self::ERROR_BAD_DATA);
					} else {
						$fileType = $myFileData['type'][$i];
						$fname = $myFileData['name'][$i];
						$uploadPath = $myElConfig[2][$i];
						$tmpName = $myFileData['tmp_name'][$i];
						$myValue[] = $this->_validateAndMoveFile($fileType, $tmpName, $uploadPath, $fname);
					}
				}
				return json_encode($myValue);
			} else {
				if ($myFileData['error'][$aFileKey]) {
					$this->_errors->$aFileKey = new \stdClass;
					$this->_errors->$aFileKey->error = self::ERROR_BAD_DATA;
					$this->_errors->$aFileKey->message = $this->_getErrorString($key, self::ERROR_BAD_DATA);
				} else {
					$fileType = $myFileData['type'];
					$fname = $myFileData['name'];
					$uploadPath = $myElConfig[2];
					$tmpName = $myFileData['tmp_name'];
					if ($myResult = $this->_validateAndMoveFile($fileType, $tmpName, $uploadPath, $fname)) {
						return $myResult;
					}
				}
			}
		} elseif ($myElConfig[1] === true) {
			// Error no file
			$this->_errors->$key = new \stdClass;
			$this->_errors->$key->error = self::ERROR_NO_DATA;
			$this->_errors->$key->message = $this->_getErrorString($key, self::ERROR_NO_DATA);
		}
	}
	
	/**
	 * Validate and move an uploaded file.
	 * 
	 * @param string $aFileType      PHP Upload File type.
	 * @param string $aTmpName       PHP Upload tmp_name.
	 * @param string $aUploadDirPath Upload directory to move the file to.
	 * @param string $aFname         File Name.
	 * 
	 * @return string Path in the upload directory.
	 */
	protected function _validateAndMoveFile($aFileType, $aTmpName, $aUploadDirPath, $aFname)
	{
		// validate file type
		if (in_array($aFileType, $this->_validFiles)) {
			// check that upload folder exists
			$mySavePath = CM_UPLOAD_DIR . $aUploadDirPath;
			if (!is_dir($mySavePath)) {
				// if folder does not exists create it
				mkdir($mySavePath, 777, true);
			}
			// Check if the file allready exists
			if (file_exists($mySavePath . $aFname)) {
				// give a new name to the file
				$aFname = uniqid() . $aFname;
			}
			// Move file to proper folder.
			move_uploaded_file($aTmpName, $mySavePath . $aFname);
			return 'uploads/' . $aUploadDirPath . $aFname;
		} else {
			return false;
		}
	}
}