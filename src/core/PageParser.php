<?php
/**
 * Base Page display class
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
use core\Helpers\HtmlHelper;

/**
 * Base Page display class
 *
 * This class does parsing and rendering of the static php pages.
 * It also includes acces to the html helper and the current request.
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category Core
 * @package  Core
 */
class PageParser
{

	/**
	 * Server request object
	 * @var core\Url
	 */
	protected $_request;

	/**
	 * Html Helper class.
	 *
	 * @var HtmlHelper
	 */
	public $helper;

	/**
	 * Class Constructor.
	 *
	 * @param string $aRoute The route for wich we are loading the page.
	 */
	public function __construct($aRoute)
	{
		$this->_request = Url::getInstance();
		$this->helper = new HtmlHelper();
		$this->_loadPage($aRoute);
	}

	/**
	 * Load the page.
	 *
	 * @param string $aRoute The route for the page we want to load.
	 *
	 * @return boolean
	 */
	protected function _loadPage($aRoute)
	{
		if (is_null($aRoute)) {
			throw(new BadMethodCallException('Missing Parameter'));
		}
		if (isset( App::$config->routing->$aRoute )) {
			$myPath = ACTION_ROOT . App::$config->routing->$aRoute->link;
			if (file_exists($myPath)) {
				include($myPath);
				return true;
			}
			throw (new BadMethodCallException("Page file does not exist {$myPath}"));
		}
		throw(new BadMethodCallException(
			"No inclusion possible ROUTE : {$aRoute} does not exists in the routing table"
		));
	}

	/**
	 * Get content from a specific page by route name.
	 *
	 * @param string $aRoute Route we desire the content from.
	 *
	 * @return self
	 */
	public static function getContent($aRoute)
	{
		return new static($aRoute);
	}
}