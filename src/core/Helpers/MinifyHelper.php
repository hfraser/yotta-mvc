<?php
/**
 * JS and CSS minifying helper class.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2012 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Helpers
 * @package    Core
 * @subpackage Helpers
 * @filesource
 */
namespace core\Helpers;
use core\Exceptions\CMPathNotWritable;
use core\App;
use core\Patterns\ASingleton;


/**
 * This class contains the necessary codes and links in order to implement minfying
 * of Javascript and CSS files.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   Helpers
 * @package    Core
 * @subpackage Helpers
 */
class MinifyHelper extends ASingleton
{
	
	const CSS_TYPE = 'css';
	const JS_TYPE = 'js';
	
	/**
	 * Class constructor.
	 */
	public function __construct()
	{}
	
	/**
	 * Minify all files in a Styles config file (config/styles.json).
	 *
	 * @param string $aStylesFile Path to the styles.json config file.
	 *
	 * @return void
	 */
	public function minifyStyles($aStylesFile)
	{
		// load styles config
		$myStyles = json_decode(file_get_contents($aStylesFile));
		// Parse CSS section
		foreach ($myStyles->css as $key => $value) {
			$myStyles->css->$key = $this->_parseCSSGroup($value);
		}
		
		// parse JS section
		foreach ($myStyles->js as $key => $value) {
			$myStyles->js->$key = $this->_parseJSGroup($value);
		}
		// Save new file data
		$myNewStylesData = json_encode($myStyles);
		App::writeCacheFile('styles.min.json', $myNewStylesData);
	}

	/**
	 * Minify a css group.
	 *
	 * Concatenante and minify a full CSS group keeping seperate the medias
	 *
	 * @param array|\stdClass $aCssFiles List of css files t group and minify.
	 *
	 * @return array
	 */
	private function _parseCSSGroup($aCssFiles)
	{
		$parseList = array();
		$myNewList = array();
		
		// iterate through the provided files
		foreach ($aCssFiles as $data) {
			if (strpos($data->path, "http://") === 0 || strpos($data->path, "https://") === 0) {
				// add the css to the list of files to minify only if it's content is available localy
				$myNewList[] = array(
						"media" => $data->media,
						"path" => $data->path
					);
			} else {
				// if content is not for local consumption add it to the save path
				$parseList[$data->media][] = $data->path;
			}
		}
		
		// now that we have our media based parse list
		// we can begin to create the proper minification files.
		foreach ($parseList as $key => $value) {
			$parseList[$key] = '/assets/css/' . $this->_minify($value, self::CSS_TYPE);
		}
		
		// bring it all back together
		foreach ($parseList as $key => $value) {
			$myNewList[] = array(
					"media" => $key,
					"path" => $value
				);
		}
		return $myNewList;
	}
	
	/**
	 * Minify a JS group.
	 *
	 * Concatenante and minify a full JS group keeping seperate the medias
	 *
	 * @param array|\stdClass $aJSFiles List of JS files to group and minify.
	 *
	 * @return array
	 */
	private function _parseJSGroup($aJSFiles)
	{
		$parseList = array();
		$myNewList = array();
		// iterate through the provided files
		foreach ($aJSFiles as $data) {
			if (strpos($data, "http://") === 0 || strpos($data, "https://") === 0) {
				// add the css to the list of files to minify only if it's content is available localy
				$myNewList[] = $data;
			} else {
				// if content is not for local consumption add it to the save path
				$parseList[] = $data;
			}
		}
		// Minify the JS Files
		$myNewList[] = '/assets/js/' . $this->_minify($parseList, self::JS_TYPE);
		// return the group data back
		return $myNewList;
	}
	
	/**
	 * Minify JS or CSS Data.
	 *
	 * @param array  $aFileList List of files to minify.
	 * @param string $aType     Type of files to minify.
	 *
	 * @return string
	 */
	private function _minify(array $aFileList, $aType)
	{
		$myFileData = $this->_concatenateFiles($aFileList, $aType);
		// minify file content
		switch ($aType) {
			case(self::CSS_TYPE):
				// Minify CSS
				$cssMin = new \CSSmin();
				$myFileData = $cssMin->run($myFileData);
				break;
				
			case(self::JS_TYPE):
				// Minify JS
				$myFileData = \JSmin::minify($myFileData);
				break;
		}
		// save file with proper name and hash
		$myFname = md5($myFileData);
		App::mkCacheDir($aType);
		App::writeCacheFile($aType . DS . $myFname, $myFileData);
		return $myFname;
	}
	
	/**
	 * Concatenate a group of files.
	 *
	 * @param array  $aFiles    List of files to concatenate.
	 * @param string $aBasePath Path relative to the public folder.
	 *
	 * @return string
	 */
	private function _concatenateFiles(array $aFiles, $aBasePath)
	{
		$myReturn = '';
		foreach ($aFiles as $data) {
			$tmpFilePath = PUBLIC_ROOT . $aBasePath . DS . $data;
			// check if it's a .less file
			$myContent = file_get_contents($tmpFilePath);
			
			if (substr($tmpFilePath, -5) == '.less') {
				$lessProc = new \lessc();
				$myContent = $lessProc->compile($myContent);
			}
			$myReturn .= $myContent . "\n";
		}
		return $myReturn;
	}
}