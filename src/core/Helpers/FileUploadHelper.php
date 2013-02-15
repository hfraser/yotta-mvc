<?php
/**
 * File upload helper.
*
* @version    Release: 1.0
* @author     Hans-Frederic Fraser <hffraser@gmail.com>
* @copyright  2013 Hans-Frederic Fraser
* @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
* @category   Core
* @package    Core
* @subpackage Helpers
* @filesource
*/
namespace core\Helpers;
use core\Patterns\ASingleton;
use core\Url;
use core\Exceptions\CMFileUploadException;
use core\Exceptions\CMFileUploadMissingException;
use core\Exceptions\CMFileUploadExceptionInvalidFormat;

/**
 * File upload helper.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   Helpers
 * @package    Core
 * @subpackage Helpers
 */
class FileUploadHelper extends ASingleton
{
	/**
	 * Valid File array
	 *
	 * @var array
	 */
	protected $_validFiles = array (
			"image/gif",
			"image/jpeg",
			"image/png"
	);
	
	/**
	 * Handle a file upload for AServiceForms.
	 *
	 * Return the valid data to be saved into the model.
	 *
	 * @param string $aFileKey Request File element name.
	 * @param array  $aElConf  Form configuration of the element.
	 *
	 * @return string
	 */
	public function handleFormUpload($aFileKey, array $aElConf)
	{
		if (isset(Url::getInstance()->files[$aFileKey])) {
			$myFileData = Url::getInstance()->files[$aFileKey];
			// check if we have more then one uploaded files
			if (is_array($myFileData['name']) && $aElConf[0] === 'filemultiple') {
				$myValue = array();
				// process multiple files
				for ($i = 0; $i < count($myFileData['name']); $i++) {
					// check if there was an error with the file
					if ($myFileData['error'][$i]) {
						throw(new CMFileUploadException());
					} else {
						$fileType = $myFileData['type'][$i];
						$fname = $myFileData['name'][$i];
						$uploadPath = $aElConf[2];
						$tmpName = $myFileData['tmp_name'][$i];
						$myValue[] = $this->validateAndMoveFile($fileType, $tmpName, $uploadPath, $fname);
					}
				}
				return json_encode($myValue);
			} elseif (is_array($myFileData['name']) && $aElConf[0] === 'file') {
				throw(new CMFileUploadException('DO NOT UPLOAD MULTIPLE FILES'));
			} else {
				if ($myFileData['error']) {
					throw(new CMFileUploadException());
				} else {
					$fileType = $myFileData['type'];
					$fname = $myFileData['name'];
					$uploadPath = $aElConf[2];
					$tmpName = $myFileData['tmp_name'];
					if ($myResult = $this->validateAndMoveFile($fileType, $tmpName, $uploadPath, $fname)) {
						return $myResult;
					}
				}
			}
		} elseif ($aElConf[1] === true) {
			// Error no file
			throw(new CMFileUploadMissingException());
		}
		// @codeCoverageIgnoreStart
	}
	// @codeCoverageIgnoreEnd
		
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
	public function validateAndMoveFile($aFileType, $aTmpName, $aUploadDirPath, $aFname)
	{
		// validate file type
		if (in_array($aFileType, $this->_validFiles)) {
			// check that upload folder exists
			$mySavePath = CM_UPLOAD_DIR . $aUploadDirPath;
			if (!is_dir($mySavePath)) {
				// if folder does not exists create it
				// @codeCoverageIgnoreStart
				if (!@mkdir($mySavePath, 0744, true)) {
					throw(new CMPathNotWritable($mySavePath));
				}
			}
			// @codeCoverageIgnoreEnd
			// Check if the file allready exists
			if (file_exists($mySavePath . $aFname)) {
				// give a new name to the file
				$aFname = uniqid() . $aFname;
			}
			// Move file to proper folder.
			if ($this->_moveUploadedFile($aTmpName, $mySavePath . $aFname)) {
				return 'uploads/' . $aUploadDirPath . $aFname;
			} else {
				throw (new CMFileUploadException("move_uploaded_file({$aTmpName}, {$mySavePath}{$aFname})"));
			}
		} else {
			throw (new CMFileUploadExceptionInvalidFormat($aFileType));
		}
	}
	
	/**
	 * Make Uploads testable.
	 *
	 * @param string $aTmpName  Temporary file to move.
	 * @param string $aSavePath Destination.
	 *
	 * @return boolean
	 */
	protected function _moveUploadedFile($aTmpName, $aSavePath)
	{
		if (self::$test === true) {
			if (file_exists($aTmpName)) {
				return copy($aTmpName, $aSavePath);
			}
			return false;
		}
		// @codeCoverageIgnoreStart
		return move_uploaded_file($aTmpName, $aSavePath);
		// @codeCoverageIgnoreEnd
	}
}