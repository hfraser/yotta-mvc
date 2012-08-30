<?php
/**
 * Request parser.
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
use core\Patterns\ASingleton;

/**
 * Request parser.
 *
 * This class parses the full request in order to put it into
 * a manageable accessible object.
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category Core
 * @package  Core
 */
class Url extends ASingleton
{
	/**
	 * Parsed Url in an array form
	 *
	 * @var array
	 */
	protected static $_request = array(
		'protocol' => '',
		'lang'     => '',
		'host'     => '',
		'uri'      => '',
		'path'     => array(),
		'filters'  => array(),
		'post'     => array(),
		'files'     => array()
	);


	/**
	 * Url Constructor.
	 */
	protected function __construct()
	{
		$this->_parseUrl();
	}

	/**
	 * Class __get Magic method.
	 *
	 * This calls only allows public function calls or getting the current URL information.
	 *
	 * @param string $aKey Attribute of the URL we desire to get.
	 *
	 * @return array|bool Bollean false if called value does not exist.
	 */
	public function __get($aKey)
	{
		if (isset(self::$_request[$aKey])) {
			return self::$_request[$aKey];
		}
		return false;
	}

	/**
	 * Public setter for request current action.
	 *
	 * @param string $aAction Action string to set.
	 *
	 * @return void
	 */
	public function setCurrentAction($aAction)
	{
		self::$_request['currentAction'] = $aAction;
	}

	/**
	 * Public setter for request current action type.
	 *
	 * @param string $aActionType Action Type string to set.
	 *
	 * @return void
	 */
	public function setCurrentActionType($aActionType)
	{
		self::$_request['currentActionType'] = $aActionType;
	}

	/**
	 * Parse the currently called URL.
	 *
	 * @return self
	 */
	protected function _parseUrl()
	{
		// get the website and it's information
		self::$_request['host'] = $_SERVER['HTTP_HOST'];

		// clean up the url
		$myPath = $_SERVER['REQUEST_URI'];

		if (stripos($myPath, "?") !== false) {
			$myParts = explode('?', $myPath, 2);
			$myPath = $myParts[0];
		}

		// clean slashes in the url to compensate for user stupidity!!!
		if (stripos($myPath, URL_ROOT) === 0) {
			$myPath = substr($myPath, strlen(URL_ROOT),strlen($myPath));
			$myReplace = array(
				'#/(/+)#',
				'#^/*#',
				'#/$#'
			);
			$myPath = preg_replace($myReplace, array('/',''), $myPath);
		}

		// break the path apart
		self::$_request['path'] = explode('/', $myPath);

		// extract the current language from the URL if available
		if (in_array(self::$_request['path'][0], App::$config->lang->avail)) {
			self::$_request['lang'] = array_shift(self::$_request['path']);
		} else {
			// if not set the default language
			self::$_request['lang'] = App::$config->lang->default;
		}

		// extract the get (filters) values from the URL
		self::$_request['filters'] = $_GET;

		// extract actual request and defined path from the URL
		self::$_request['post'] = $_POST;
		self::$_request['files'] = $_FILES;
		self::$_request['uri'] = $myPath;
		self::$_request['protocol'] = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')
		? 'https' : 'http';
		return $this;
	}


	/**
	 * Remove the first element of the path.
	 *
	 * @return mixed
	 */
	public function shiftPath()
	{
		return array_shift(self::$_request['path']);
	}

	/**
	 * Create a URL with the proper data.
	 *
	 * If the route is invalid the function will return the current language
	 * or specified language Index path.
	 *
	 * @param string $aRoute  Route name.
	 * @param string $aLang   Url language version.
	 * @param array  $aAction Action we want to call on the route.
	 *
	 * @return string
	 */
	public static function getUrl($aRoute, $aLang = null, array $aAction = null)
	{
		$aLang = (is_null($aLang))? self::$_request['lang'] : $aLang;
		if (isset(App::$config->routing->$aRoute) && $aRoute != 'index') {
			$myUrl = URL_ROOT . $aLang . "/" . $aRoute;
			if (!is_null($aAction)) {
				$myUrl .= "/{$aAction}";
			}
			return $myUrl;
		}

		return URL_ROOT . "{$aLang}/";
	}
}