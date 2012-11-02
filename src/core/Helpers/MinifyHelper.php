<?php
/**
 * JS and CSS minifying helper class.
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
	
	/**
	 * Class constructor
	 */
	public function __construct()
	{}
	
	/**
	 * Minify all files in a Styles config file (config/styles.json)
	 * 
	 * @param string $aStylesFile Path to the styles.json config file
	 * 
	 * @return void
	 */
	public function minify($aStylesFile)
	{
		// load styles config
		$myStyles = json_decode(file_get_contents($aStylesFile));
		// Parse CSS section
		foreach ($myStyles->css as $key=>$value) {
			$myStyles->css->$key = $this->_parseCSSGroup($value);
		}
		
		// parse JS section
		foreach ($myStyles->js as $key=>$value) {
			$myStyles->js->$key = $this->_parseJSGroup($value);
		}
		// Save new file data
		$myNewStylesData = json_encode($myStyles);
		file_put_contents(CM_CACHE . 'styles.min.json', $myNewStylesData);
	}

	/**
	 * Minify a css group
	 * 
	 * Concatenante and minify a full CSS group keeping seperate the medias
	 *  
	 * @param array|\stdClass $aCssFiles List of css files t group and minify
	 * 
	 * @return array
	 */
	private function _parseCSSGroup($aCssFiles)
	{
		$parseList = array();
		$myNewList = array();
		
		// iterate through the provided files
		foreach ($aCssFiles as $data) {
			if (strpos($data->path, "http://" === 0) && strpos($data->path, "https://" === 0)) {
				// add the css to the list of files to minify only if it's content is available localy
				$myNewList[] = $myNewList[] = array(
						"media" => $data->media,
						"path" => $data->path
					);
			} else {
				// if content is not for local consumption add it to the save path
				$parseList[$data->media][] = $data->path;
			}
		}
		
		
		// now that we have our media based parse list we can begin to create the proper minification files.
		foreach ($parseList as $key => $value) {
			$parseList[$key] = $this->_minifyCSS($value);
		}
		
		// bring it all back together
		foreach ($parseList as $key => $value)
		{
			$myNewList[] = array(
					"media" => $key,
					"path" => $value
				);
		}
		return $myNewList;
	}
	
	/**
	 * Minify and concatenate a group of css
	 * 
	 * @param array $aFileList List of css file to group and minify
	 * 
	 * @return string
	 */
	private function _minifyCSS($aFileList)
	{
		$myFileData = $this->_concatenateFiles($aFileList, 'css');
		// minify file content
		$cssMin = new \CSSmin();
		$myFileData = $cssMin->run($myFileData);
		// save file with proper name and hash
		$myFname = md5($myFileData) . '.css';
		file_put_contents(PUBLIC_ROOT . 'css' . DS . $myFname, $myFileData);
		// return filename
		return $myFname;
	}
	
	/**
	 * Minify a JS group
	 * 
	 * Concatenante and minify a full JS group keeping seperate the medias
	 *  
	 * @param array|\stdClass $aJSFiles List of JS files to group and minify
	 * 
	 * @return array
	 */
	private function _parseJSGroup($aJSFiles)
	{
		$parseList = array();
		$myNewList = array();
	
		// iterate through the provided files
		foreach ($aJSFiles as $data) {
			if (strpos($data, "http://" === 0) && strpos($data, "https://" === 0)) {
				// add the css to the list of files to minify only if it's content is available localy
				$myNewList[] = $data->path;
			} else {
				// if content is not for local consumption add it to the save path
				$parseList[] = $data;
			}
		}
		// Minify the JS Files
		$myNewList[] = $this->_minifyJS($parseList);
		
		// return the group data back
		return $myNewList;
	}
	
	/**
	 * Minify and concatenate a group of js
	 * 
	 * @param array $aFileList List of JS file to group and minify
	 * 
	 * @return string
	 */
	private function _minifyJS($aFileList)
	{
		$myFileData = $this->_concatenateFiles($aFileList, 'js');
		// minify file content
		$myFileData = \JSmin::minify($myFileData);
		// save file with proper name and hash
		$myFname = md5($myFileData) . '.js';
		file_put_contents(PUBLIC_ROOT . 'js' . DS . $myFname, $myFileData);
		// return filename
		return $myFname;
	}
	
	/**
	 * Concatenate a group of files
	 * 
	 * @param array  $aFiles    List of files to concatenate
	 * @param string $aBasePath Path relative to the public folder
	 * 
	 * @return string
	 */
	private function _concatenateFiles($aFiles, $aBasePath)
	{
		$myReturn = '';
		foreach ($aFiles as $data) {
			$myReturn .= file_get_contents(PUBLIC_ROOT . $aBasePath . DS . $data) . "\n";
		}
		return $myReturn;
	}
}