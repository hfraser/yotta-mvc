<?php
/**
 * Base abstract service class.
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
 * Base abstract service class
 *
 * Any service should inherit from this class at one level or the other it contains
 * the base information for services to be properly called from the Controller
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category Core
 * @package  Core
 */
abstract class AService {
	/**
	 * Server request object.
	 *
	 * @var Url
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
	 * @param string $aAction The action we want to call this defaults to index if no action is set.
	 */
	public function __construct($aAction=null)
	{
		$this->_request = App::getRequest();
		$this->helper = new HtmlHelper();
		$this->call($aAction);
	}

	/**
	 * Base control action.
	 *
	 * This function will redistribute the proper calls to the appropriate
	 * action in order to execute the desired operation.
	 * 
	 * @param string $aAction The action we want to call this defaults to index if no action is set.
	 * 
	 * @return mixed
	 */
	public function call($aAction)
	{
		if (is_null($aAction)) {
			$aAction = (isset($this->_request->path[0]))? '_' . $this->_request->path[0] . 'Action': '_indexAction';
		}
		if (method_exists($this, $aAction)) {
			return $this->$aAction();
		}
		$this->_indexAction();
	}

	/**
	 * Get indexAction values without having the object present.
	 *
	 * @param string $aAction The action we want to call this defaults to index if no action is set.
	 * 
	 * @return self
	 */
	public final static function getIndex($aAction='index')
	{
		return new static($aAction);
	}

	/**
	 * Base index action.
	 *
	 * This function should output the base for this
	 * module when it's called without arguments.
	 *
	 * @return void
	 */
	abstract protected function _indexAction();
}