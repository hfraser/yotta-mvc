<?php
/**
 * Singleton default pattern.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2012 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Pattterns
 * @package    Core
 * @subpackage Patterns
 * @filesource
 */
namespace core\Patterns;

/**
 * Singleton default pattern.
 *
 * Direct implementations of the singleton pattern just extend your class with the current
 * and it becomes a singleton.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   Pattterns
 * @package    Core
 * @subpackage Patterns
 */
abstract class ASingleton
{
	/**
	 * Class constructor.
	 */
	protected function __construct()
	{}

	/**
	 * Database is not clonable.
	 *
	 * @access public
	 * @return void
	 * @throws BadMethodCallException Cloning not allowed.
	 */
	public final function __clone()
	{
		throw new BadMethodCallException("Clone is not allowed");
	}

	/**
	 * Singleton instance getter.
	 *
	 * @return self
	 */
	public final static function getInstance()
	{
		/**
		 * Instance of self.
		 *
		 * @var self
		 */
		static $instance = null;
		return $instance  ?: $instance  = new static;
	}
}