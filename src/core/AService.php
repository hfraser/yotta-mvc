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
	 */
	public function __construct()
	{
		$this->helper = new HtmlHelper();
		$this->call();
	}

	/**
	 * Base control action.
	 *
	 * This function will redistribute the proper calls to the appropriate
	 * action in order to execute the desired operation.
	 *
	 * @return mixed
	 */
	public function call()
	{
		$myAction = (isset($this->_request->path[0]))? '_' . $this->_request->path[0] . 'Action': '_indexAction';


		if (method_exists($this, $myAction)) {
			return $this->$myAction();
		}
		$this->_indexAction();
	}

	/**
	 * Get indexAction values without having the object present.
	 *
	 * @return self
	 */
	public final static function getIndex()
	{
		return new static;
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