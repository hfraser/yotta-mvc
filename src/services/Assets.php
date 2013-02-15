<?php
/**
 * Asset service.
 *
 * Provides assets from cache this is currently used for CSS and JS
 * when they are parsed and minified.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2012 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Services
 * @package    Service
 * @subpackage Assets
 * @filesource
 */

namespace services;
use core\App;
use core\AService;

/**
 * Asset service class.
 *
 * Asset service is designed to provide assets from cache such as minified CSS and JS.
 * It is also designed to provide assets that might need to be computed.
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category Core
 * @package  Core
 * @uses Url
 * @uses PageParser
 */
class Assets extends AService
{
	/**
	 * CSS Constant.
	 *
	 * @var string
	 */
	const CSS_TYPE = 'css';
	
	/**
	 * JS Constant.
	 *
	 * @var string
	 */
	const JS_TYPE = 'js';

	/**
	 * Index base action.
	 *
	 * @see    \core\AService::_indexAction()
	 * @return void
	 */
	protected function _indexAction()
	{
		App::throw404();
	}

	/**
	 * Get css from cache action.
	 *
	 * @param array $aFile The file to load.
	 *
	 * @return void
	 */
	protected function _cssAction(array $aFile = null)
	{
		$this->_returnFile($aFile[0], self::CSS_TYPE);
	}

	/**
	 * Get js from cache action.
	 *
	 * @param array $aFile The file to load.
	 *
	 * @return void
	 */
	protected function _jsAction(array $aFile = null)
	{
		$this->_returnFile($aFile[0], self::JS_TYPE);
	}

	/**
	 * Pipe a specific file to the client browser with proper headers.
	 *
	 * This function will set expiration in the far future and make sure that content is in gzip.
	 *
	 * @param string $aFile     File name to retur to the client.
	 * @param string $aFileType File type of the returned file.
	 *
	 * @return void
	 */
	protected function _returnFile($aFile, $aFileType)
	{
		switch ($aFileType) {
			case "css":
				$contentType = "text/css";
				break;
			case "js":
				$contentType = "text/javascript";
				break;
		}
		if (!headers_sent()) {
			// @codeCoverageIgnoreStart
			header("Content-Type: " . $contentType . "; charset=UTF-8");
		}
		// @codeCoverageIgnoreEnd
		
		if (file_exists(CM_CACHE . $aFileType . DS . $aFile)) {
			// @codeCoverageIgnoreStart
			if (!headers_sent()) {
				if (extension_loaded("zlib") && (ini_get("output_handler") != "ob_gzhandler")) {
					ini_set("zlib.output_compression", 1);
					header("Content-Encoding: gzip");
				}
				header('Cache-Control: max-age=32850000');
				header("File-Name: " . $aFile);
			}
			// @codeCoverageIgnoreEnd
			readfile(CM_CACHE . $aFileType . DS . $aFile);
		} else {
			App::throw404();
		}
	}
}