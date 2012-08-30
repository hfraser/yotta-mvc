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
	 * Styles config
	 *
	 * Loaded from styles.json
	 *
	 * @var \stdClass
	 */
	public static $styles;

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
		$this->getSectionCSS('css');
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
		if (isset(self::_loadStyles()->$aSection)) {
			$myCSS = self::_loadStyles()->$aSection;
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
		$this->getSectionJS('js');
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
		if (isset(self::_loadStyles()->$aSectionName)) {
			$myJS = self::_loadStyles()->$aSectionName;
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
			self::$styles = json_decode(file_get_contents(CONFIG_DIR . 'styles.json'));
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
			$myPath = URL_ROOT . 'js/' . $aPath;
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
			$myPath = URL_ROOT . 'css/' . $aCSS->path;
		}
		echo('<link rel="stylesheet" href="' . $myPath . '" media="' . $aCSS->media . "\" />\n");
	}
}