<?php
/**
 * Base Utility functions.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2013 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Core
 * @package    Core
 * @filesource
 */
namespace core;

/**
 * This class is a place hoder for utilitarian functions.
 *
 * Since most functions are static instantiating the class will be a waste of time.
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category Core
 * @package  Core
 */
class Utils
{
	/**
	 * Map an array to an stdClass.
	 *
	 * @param mixed $aValue The array to convert to an object.
	 *
	 * @return mixed
	 */
	public static function arrayToObj($aValue)
	{
		if (is_array($aValue)) {
			return (object) array_map(array(__CLASS__, __FUNCTION__), $aValue);
		} else {
			return $aValue;
		}
	}
}