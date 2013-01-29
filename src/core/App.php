<?php
/**
 * Base App.
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
use core\Exceptions\CMPathNotWritable;


/**
 * Base App.
 *
 * This App is designed to create fast integration (front end) based sites, microsites ...
 * it basicaly handles language management and routing.
 * It also allows for the support of rest services to be implemented.
 * This class is a singleton class
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category Core
 * @package  Core
 * @uses Url
 * @uses PageParser
 */
class App extends ASingleton{
	/**
	 * Base route constant (according to configuration file).
	 *
	 * @var string
	 */
	const CM_PAGES = 'page';

	/**
	 * Base Service constant (according to configuration file).
	 *
	 * @var string
	 */
	const CM_SERVICES = 'service';

	/**
	 * Holder for the URL Object.
	 *
	 * @var Url
	 */
	public $request;

	/**
	 * Configuration Object Holder this is pulled from the JSON config file.
	 *
	 * @var \stdClass
	 */
	public static $config;

	/**
	 * Supported locales and relative to their defined language code.
	 *
	 * @var array
	 */
	public $locales = array(
					'en' => 'en_CA',
					'fr' => 'fr_CA'
	);

	/**
	 * Class Constructor.
	 */
	protected function __construct()
	{
		$this->loadConfig();
	}

	/**
	 * Start the application and run with it!.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->_initORM();
		self::getRequest();
		// Initialize translations
		$this->_initGettext();
		// get the action to call
		$myAction = $this->_getAction();
		// load Action
		if ($this->request->currentActionType == self::CM_PAGES) {
			PageParser::getContent($myAction);
		} elseif ($this->request->currentActionType == self::CM_SERVICES) {
			new $myAction();
		}
	}

	/**
	 * Load JSON Config File.
	 *
	 * @return void
	 */
	public function loadConfig()
	{
		self::$config = json_decode(file_get_contents(CONFIG_DIR . 'config.json'));
		$myRoutes = json_decode(file_get_contents(CONFIG_DIR . 'routing.json'));
		foreach ($myRoutes as $key => $value) {
			self::$config->$key = $value;
		}
	}

	/**
	 * Init ORM Redbean.
	 *
	 * @return void
	 */
	protected function _initORM()
	{
		if (self::$config->DB->type === 'sqlite') {
			\R::setup(
				'sqlite:' . self::$config->DB->host,
			self::$config->DB->user,
			self::$config->DB->password);
		} else {
			\R::setup(
				"self::$config->DB->type:host={self::$config->DB->host};dbname={self::$config->DB->name}",
			self::$config->DB->user,
			self::$config->DB->password);
		}
		\R::debug(self::$config->DEBUG);
	}

	/**
	 * Get the current action from the request.
	 *
	 * This Will determine if we are calling a service or a standard page.
	 *
	 * @return string Returns the action to be taken.
	 */
	protected function _getAction()
	{
		$myAction = 'index';
		$myType = self::CM_PAGES;
		$myRoute = (isset($this->request->path[0])) ? $this->request->path[0] : false;
			
		if ($myRoute && count($this->request->path) > 0) {
			$this->request->shiftPath();
			if (isset(self::$config->routing->$myRoute)) {
				$myType = self::$config->routing->$myRoute->type;
				$myAction = $myRoute;
			} else {
				self::throw404();
			}
		}
		
		$this->request->currentActionType = $myType;
		$this->request->currentAction = $myAction;
		if ($myType == self::CM_PAGES && $myAction != '404') {
			return $myAction;
		} elseif ($myType == self::CM_SERVICES) {
			return 'services\\' . self::$config->routing->$myAction->link;
		} else {
			self::throw404();
		}
	}

	/**
	 * General Request Getter.
	 *
	 * This function should never be called without having the App allready initialized.
	 *
	 * @return Url
	 */
	public static function getRequest()
	{
		if (is_null(self::getInstance()->request)) {
			self::getInstance()->request = Url::getInstance();
		}
		return self::getInstance()->request;
	}

	/**
	 * Initialize gettext to handle al multilingual strings.
	 *
	 * @return void
	 */
	private function _initGettext()
	{
		$myLocale = $this->locales[$this->request->lang] . '.utf8';
		putenv('LANG=' . $myLocale);
		setlocale(LC_ALL,"");
		if (!setlocale(LC_MESSAGES,$myLocale)) {
			$myLocale = $this->locales[$this->request->lang];
			putenv('LANG=' . $myLocale);
			setlocale(LC_MESSAGES,$myLocale);
		}
		setlocale(LC_CTYPE,$myLocale);
		bindtextdomain('messages', LOCALES_DIR);
		bind_textdomain_codeset('messages', 'UTF-8');
		textdomain('messages');
	}
	
	/**
	 * Throw 404 page from anywhere.
	 * 
	 * @return void
	 */
	public static function throw404()
	{
		header("HTTP/1.0 404 Not Found");
		PageParser::getContent('404');
		exit();
	}
	
	/**
	 * Make a cache directory.
	 *
	 * @param string $aDirName Name of the directory.
	 *
	 * @throws core\Exceptions\CMPathNotWritable This error is thrown in case the
	 *                                           path that is needed to write the
	 *                                           cache is not writable.
	 * @return string
	 */
	public static function mkCacheDir($aDirName)
	{
		$myDirName = CM_CACHE . $aDirName;
		if (!is_dir(CM_CACHE . $aDirName)) {
			if (!@mkdir($myDirName , '0744')) {
				throw(new CMPathNotWritable($myDirName));
			}
		}
		return $myDirName;
	}
	
	/**
	 * Write file content to a specific cache directory.
	 *
	 * @param string $aFilePath File path to write.
	 * @param string $aContent  File content.
	 *
	 * @throws core\Exceptions\CMPathNotWritable This error is thrown in case the
	 *                                           path that is needed to write the
	 *                                           cache is not writable.
	 * @return void
	 */
	public static function writeCacheFile($aFilePath, $aContent)
	{
		if (file_put_contents(CM_CACHE . $aFilePath, $aContent) === false) {
			throw(new CMPathNotWritable(CM_CACHE . $aFilePath, $aContent));
		}
	}
}