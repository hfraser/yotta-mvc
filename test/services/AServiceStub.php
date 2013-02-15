<?php
/**
 * Abstract class AService Unit testing functional stub.
 *
 * @version	   Release: 1.0
 * @author	   Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2013 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
use core\AService;

/**
 * Abstract class AService Unit testing.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   PHPUnit
 * @package    PHPUnit
 * @subpackage Core
 * @filesource
 */
class AServiceStub extends AService
{
	/**
	 * Test action call on construct.
	 *
	 * @return void
	 */
	protected function _constructAction()
	{
		echo 'CONSTRUCT_ACTION_CALLED';
	}
	
	/**
	 * Test index action.
	 *
	 * @see    \core\AService::_indexAction()
	 * @return void
	 */
	protected function _indexAction()
	{
		// empty not to screw up testing
	}
	
	/**
	 * Test custom action.
	 *
	 * @return void
	 */
	protected function _customAction()
	{
		echo 'CUSTOM_ACTION_CALLED';
	}
	
	/**
	 * Test custom action.
	 *
	 * @return void
	 */
	protected function _paramsAction()
	{
		$args = func_get_args();
		echo implode('-', $args[0]);
	}
}