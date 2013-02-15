<?php
/**
 * Base task abstact class.
 *
 * @version   Release: 1.0
 * @author    Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright 2012 Hans-Frederic Fraser
 * @license   http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category  CommandLine
 * @package   Bin
 * @filesource
 */
namespace bin;

/**
 * Base task abstact class.
 *
 * This class is to be inherited by all tasks that are to be handled by the
 * base command line tool.
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category CommandLine
 * @package  Bin
 */
abstract class ATask
{
	/**
	 * Task registered actions
	 *
	 * @var \stdClass
	 */
	protected $_actions;

	/**
	 * Current arguments
	 *
	 * @var array
	 */
	protected $_args;

	/**
	 * Class construtor.
	 */
	public function __construct()
	{
		$this->_actions = new \stdClass;
	}

	/**
	 * Class __call magic function.
	 *
	 * This call will prevent any tasks to be called out of context.
	 *
	 * @param string $aName      Task action.
	 * @param array  $aArguments Function arguments.
	 *
	 * @return mixed
	 */
	public function __call($aName, array $aArguments = array())
	{
		if (isset($this->_actions->$aName)) {
			$this->parseOpts($aName, $this->getOpts($aName));
			return call_user_func_array(array($this, '_' . $aName), $aArguments);
		} elseif (in_array($aName, array('getInfo', 'getOpts', 'parseOpts'))) {
			return call_user_func_array(array($this, '_' . $aName), $aArguments);
		}
		echocs('ERROR : Call to an undefined action!', 'red');
		die($this->getInfo());
	}

	/**
	 * Get full info about the Task and its available actions.
	 *
	 * @return void
	 */
	public function getInfo()
	{
		echocs('Callalble actions for this task:', 'red');
		$myClassName = str_replace(__NAMESPACE__ . "\\" , '', get_class($this)) . ':';
		echocs($myClassName, 'purple');
		foreach ($this->_actions as $key => $value) {
			echocs("	{$myClassName}{$key}		{$value->description}", 'purple');
		}
	}

	/**
	 * Register an ation with the current task.
	 *
	 * @param string $aActionName  Action name there should be a matching function for this action.
	 * @param string $aDescription Action description.
	 *
	 * @return \bin\ATask Return self.
	 */
	protected function _addAction($aActionName, $aDescription = '')
	{
		if (!is_object($this->_actions)) {
			$this->_actions = new \stdClass;
		}
		if (!isset($this->_actions->$aActionName)) {
			$this->_actions->$aActionName = new \stdClass;
			$this->_actions->$aActionName->description = $aDescription;
		} else {
			throw(new \BadFunctionCallException("ERROR : _addAction action {$aActionName} Allready defined"));
		}
		return $this;
	}

	/**
	 * Add an arguments to an action.
	 *
	 * @param string $aActionName Action name.
	 * @param string $aArgName    Name of the argument.
	 *
	 * @return self
	 */
	protected function _addActionArgs($aActionName, $aArgName)
	{
		if (!isset($this->_actions->$aActionName)) {
			throw(
				new \BadFunctionCallException(
					"ERROR : _addActionArgs action {$aActionName} Does not exists!"
				)
			);
		}
		if (!isset($this->_actions->$aActionName->args)) {
			$this->_actions->$aActionName->args = array();
		}
		$this->_actions->$aActionName->args[$aArgName]  = '';

		return $this;
	}

	/**
	 * Parse the options of the current call.
	 *
	 * @param string $aActionName Action for wich we are parsing the options.
	 * @param array  $aOptValues  Values of the options.
	 *
	 * @return void
	 */
	public function parseOpts($aActionName, array $aOptValues)
	{
		if (isset($this->_actions->$aActionName->args)) {
			// Make Parsable string
			foreach ($this->_args as $key => $value) {
				if (isset($aOptValues[$key]) && !empty($aOptValues[$key])) {
					$this->_args[$key] = $aOptValues[$key];
				}
			}
		}
	}

	/**
	 * Get pasrable options for extraction of the command line.
	 *
	 * @param string $aActionName Name of the action we want to extract the options for.
	 *
	 * @return array Array of options to extract.
	 */
	public function getOpts($aActionName)
	{
		$myLongOpt = array();
		if (isset($this->_actions->$aActionName->args)) {
			$this->_args = $this->_actions->$aActionName->args;
			// Make Parsable string
			$myLongOpt = array();
			foreach ($this->_args as $key => $value) {
				$myLongOpt[] = "{$key}::";
			}
		}
		return $myLongOpt;
	}
}