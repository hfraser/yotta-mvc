<?php
/**
 * Base HTML template helper.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2012 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Core
 * @package    Core
 * @filesource
 */
namespace core\Helpers;
use core\App;
use core\Url;

/**
 * Base HTML template helper.
 *
 * This class this class is a fast access helper to be included.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   Helpers
 * @package    Core
 * @subpackage Helpers
 */


class HtmlHelper
{
	
	/**
	 * Short link to the URL object
	 * 
	 * @var Url
	 */
	protected $_request;
	/**
	 * Styles config
	 *
	 * Loaded from styles.json
	 *
	 * @var \stdClass
	 */
	public static $styles;

	/**
	 * Class Constructor
	 */
	public function __construct()
	{
		$this->_request = App::getRequest();
	}
	/**
	 * Output all CSS files.
	 *
	 * This function will output all CSS from the styles.json file that
	 * are unter the "css" property. In order to output a specific block
	 * of css you can create other sections just like the "css" section
	 * and use the {@link self::getSectionCSS}
	 *
	 *  @return void
	 */
	public function getAllCSS()
	{
		foreach (self::_loadStyles()->css as $key=>$value) {
			$this->getSectionCSS($key);
		}
	}

	/**
	 * Output a specific CSS section from styles.json.
	 *
	 * @param string $aSection CSS section name.
	 *
	 * @see    self::getAllCSS
	 * @return void
	 */
	public function getSectionCSS($aSection)
	{
		if (isset(self::_loadStyles()->css->$aSection)) {
			$myCSS = self::_loadStyles()->css->$aSection;
			// output specified CSS files
			foreach ($myCSS as $value) {
				$this->_outputCssTag($value);
			}
		}
	}

	/**
	 * Output all js files.
	 *
	 * This function will output all js files under the "js" section of
	 * the styles.json configuration file. In order to output other custom
	 * sections of js files from the styles.json you can use {@link self::getSectionJS}.
	 *
	 * @return void
	 */
	public function getAllJS()
	{
		foreach (self::_loadStyles()->js as $key=>$value) {
			$this->getSectionJS($key);
		}
	}

	/**
	 * Output a specific js section from styles.json.
	 *
	 * @param string $aSectionName Section to output.
	 *
	 * @return void
	 */
	public function getSectionJS($aSectionName)
	{
		if (isset(self::_loadStyles()->js->$aSectionName)) {
			$myJS = self::_loadStyles()->js->$aSectionName;
			// output specified JS files not activated just output the data
			foreach ($myJS as $value) {
				$this->_outputJsTag($value);
			}
		}
	}

	/**
	 * Load the styles.json config file.
	 *
	 * This fucntion will load the file is needed and return
	 * the result if allready loaded.
	 *
	 * @return \stdClass
	 */
	protected function _loadStyles()
	{
		if (!isset(self::$styles)) {
			if(App::$config->minify === true) {
				if (!file_exists(CM_CACHE . 'styles.min.json') || App::$config->DEBUG === true) {
					// minify CSS and JS
					$myCssHelper = new MinifyHelper();
					$myCssHelper->minify(CONFIG_DIR . 'styles.json');
				}
				self::$styles = json_decode(file_get_contents(CM_CACHE . 'styles.min.json'));
			} else {
				self::$styles = json_decode(file_get_contents(CM_CACHE . 'styles.min.json'));
			}
		}
		return self::$styles;
	}

	/**
	 * Output a js html file tag.
	 *
	 * @param string $aPath Javasctipt file path inside the js folder as a root.
	 *
	 * @return void
	 */
	protected function _outputJsTag($aPath)
	{
		if (strpos($aPath, "http://") === 0) {
			$myPath = $aPath;
		} else {
			$myPath = $this->_request->basepath . 'js/' . $aPath;
		}
		
		echo('<script type="text/javascript" src="' . $myPath . "\" ></script>\n");
	}

	/**
	 * Output a css html file tag.
	 *
	 * @param \stdClass $aCSS CSS description object.
	 *
	 * @return void
	 */
	protected function _outputCssTag(\stdClass $aCSS)
	{
		if (strpos($aCSS->path, "http://") === 0) {
			$myPath = $aCSS->path;
		} else {
			$myPath = $this->_request->basepath . 'css/' . $aCSS->path;
		}
		echo('<link rel="stylesheet" href="' . $myPath . '" media="' . $aCSS->media . "\" />\n");
	}
	
	
	/**
	 * Get an image path relative to the public/images directory
	 * 
	 * @param string $aImg Image name and path
	 * 
	 * @return string
	 */
	public function getImage($aImg)
	{
		return $this->_request->basepath . 'images' . DS . $aImg;
	}
}