<?php
/**
 * Autoload class
 *
 * @todo Add documentation on how to add personal loaders
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2012 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Core
 * @package    Core
 * @filesource
 */

/**
 * Autoload class
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category Core
 * @package  Core
 */
class Autoload
{
	/**
	 *
	 */
	public static $test = false;
	/**
	 * Singleton current instance
	 *
	 * @var self
	 */
	private static $_instance;

	/**
	 * Autoloader list.
	 *
	 * @var array
	 */
	private $_loaders = array();

	/**
	 * Namespaces registered with the proper root paths.
	 *
	 * @var \stdClass
	 */
	private $_registeredNamespaces;

	/**
	 * Class Constructor.
	 *
	 * Register Base loading methods.
	 */
	private function __construct()
	{
		$this->_registeredNamespaces = new \stdClass();

		// add the base cmantix loaders
		$this->_loaders[] = 'Autoload::namespaceAutoload';
		$this->_loaders[] = 'Autoload::baseAutoload';
		$this->_loaders[] = 'Autoload::libsAutoload';
		foreach ($this->_loaders as $loader) {
			spl_autoload_register($loader);
		}
	}

	/**
	 * Autoload is not clonable.
	 *
	 * @return void
	 * @throws BadMethodCallException Not Clonable.
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
	public static final function getInstance()
	{
		if (!isset(self::$_instance) || self::$test) {
			$class = __CLASS__;
			self::$_instance = new $class;
		}
		return self::$_instance;
	}

	/**
	 * Reload all the autoloaders.
	 *
	 * This operation will unregister all the autoloaders and then
	 * register them with the current $this->_loaders array.
	 *
	 * @return void
	 */
	public function reload()
	{
		// iterate through the list of loaders and register them with SPL
		foreach ($this->_loaders as $loader) {
			spl_autoload_unregister($loader);
		}
	}

	/**
	 * Add and register an autoload function to the autoload stack.
	 *
	 * @param string  $aLoadFunction Autoloading function.
	 * @param boolean $aLoadNow      Load the autoloading function now or later default is true for
	 *                               load it now.
	 * @param boolean $aPrepend      Add the autoload function to the top of the stack.
	 *
	 * @return boolean Return true on success false if the function was allready registered
	 *                 or the registering failed.
	 */
	public function addLoader($aLoadFunction, $aLoadNow = true, $aPrepend = false)
	{
		if (!in_array($aLoadFunction, $this->_loaders)) {
			if ($aPrepend) {
				array_unshift($this->_loaders, $aLoadFunction);
			} else {
				$this->_loaders[] = $aLoadFunction;
			}

			if ($aLoadNow) {
				return spl_autoload_register($aLoadFunction, false, $aPrepend);
			}
			return true;
		}
		return false;
	}

	/**
	 * Delete an Autoload function from the stack.
	 *
	 * @param string  $aLoadFunction Autoloading function to delete.
	 * @param boolean $aDeleteNow    Default to true the function is automaticaly unregistered.
	 *                               Using False will unregister the function at the next reload.
	 *
	 * @return boolean True or false on success.
	 */
	public function deleteLoader($aLoadFunction, $aDeleteNow = true)
	{
		if (in_array($aLoadFunction, $this->_loaders)) {
			$this->_loaders = array_diff($this->_loaders, array($aLoadFunction));
			$this->_loaders = array_values($this->_loaders);
			if ($aDeleteNow) {
				return spl_autoload_unregister($aLoadFunction);
			}
			return true;
		}
		return false;
	}

	/**
	 * Get the current loader list.
	 *
	 * @return array
	 */
	public function getLoaders()
	{
		return $this->_loaders;
	}

	/**
	 * Add a namespace to the list.
	 *
	 * @param string $aName Namespace to register.
	 * @param string $aPath Path of the namespace.
	 *
	 * @return void
	 */
	public function setNamespace($aName, $aPath)
	{
		$this->_registeredNamespaces->$aName = $aPath;
	}

	/**
	 * Unregister a namespace from the available list.
	 *
	 * @param string $aName Namespace to unregister.
	 *
	 * @return void
	 */
	public function removeNamespace($aName)
	{
		if (isset($this->_registeredNamespaces->$aName)) {
			unset($this->_registeredNamespaces->$aName);
		}
	}

	/**
	 * Get the list of registered namespace.
	 *
	 * @return \stdClass
	 */
	public function getNamespaceList()
	{
		return $this->_registeredNamespaces;
	}

	/**
	 * Check if a particular namespace is registered.
	 *
	 * @param string $aName Namespace to check.
	 *
	 * @return boolean
	 */
	public function namespaceIsRegistered($aName)
	{
		return isset($this->_registeredNamespaces->$aName);
	}

	/**
	 * Autoload Defined Namespaces.
	 *
	 * This function can also be use to override existing files and components.
	 *
	 * @param string $aClassName Name of the class to be loaded.
	 *
	 * @return boolean Return success.
	 */
	public static function namespaceAutoload($aClassName)
	{
		if (count(self::$_instance->_registeredNamespaces)) {
			foreach (self::$_instance->_registeredNamespaces as $name => $path) {
				if (stripos($aClassName, $name) === 0) {
					$myClassPath = str_replace($name, $path, $aClassName);
					$myClassPath = str_replace('\\', DS, $myClassPath) . '.php';
					return self::$_instance->_load($myClassPath);
				}
			}
		}
	}

	/**
	 * General Autoloader.
	 *
	 * The General autoloader will load anthing with a properly defined namespace that has it's root
	 * at the same level as the app folder
	 *
	 * @param string $aClassName Class name that will be loaded.
	 *
	 * @return boolean Return success
	 */
	public static function baseAutoload($aClassName)
	{
		$myClassPath = ROOT_DIR . str_replace(array('\\','_'), DS, $aClassName) . '.php';
		return self::$_instance->_load($myClassPath);
	}

	/**
	 * Load non namespaced librairies.
	 *
	 * Libs loader for non namespaced librairies included in ROOT_DIR/libs.
	 *
	 * The General autoloader will load anthing in the libs folder even poorman namespacing.
	 *
	 * @param string $aClassName Class name that will be loaded.
	 *
	 * @return boolean Return success
	 */
	public static function libsAutoload($aClassName)
	{
		$myClassPath = LIBS_DIR . str_replace('\\', DS, $aClassName) . '.php';
		return self::$_instance->_load($myClassPath);
	}

	/**
	 * Include the desired file.
	 *
	 * @param string $aFile File path.
	 *
	 * @return boolean
	 */
	private static function _load($aFile)
	{
		if (file_exists($aFile)) {
			require_once($aFile);
			return true;
		}
		return false;
	}
}