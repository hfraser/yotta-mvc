<?php
/**
 * PHP Templates Renderer Class
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2013 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Core
 * @package    Core
 * @filesource
 */
namespace core\Renderers;
use core\Utils;
use core\Exceptions\CMFileDoesNotExist;

/**
 * PHP Templates redering class.
 *
 * Use this rendere in order to use plain php as a templating language.
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category Core
 * @package  Core
 */
class PHPtemplates extends ARenderer
{
	/**
	 * Class constructor.
	 */
	public function __construct()
	{
		$this->_data = new \stdClass();
		$this->_helpers = new \stdClass();
	}

	/**
	 * Add helper to the renderer.
	 *
	 * Helpers are classes that have some public functions accessible
	 * in order to provide those functionnalities to the rendering system.
	 *
	 * @param string $aName   Helper Access name.
	 * @param object $aHelper Helper object.
	 *
	 * @return self
	 * @throws \BadFunctionCallException If the helper is not a proper object.
	 */
	public function addHelper($aName, $aHelper)
	{
		if (is_object($aHelper)) {
			$this->_helpers->$aName = $aHelper;
		} else {
			throw(new \BadFunctionCallException());
		}
		return $this;
	}

	/**
	 * Add data for the template to be rendered.
	 *
	 * Rendered templates will only render using the data provided by this function.
	 *
	 * @param string $aDomain Domain name of the data
	 *                        (ie: in the template this will output like :$this->data->DomainName).
	 * @param mixed  $aData   Data to be attached. The data attached
	 *                        can be an array a class or a single scalar value.
	 *
	 * @return self
	 */
	public function addData($aDomain, $aData)
	{
		if (is_array($aData)) {
			$aData = Utils::arrayToObj($aData);
		}
		$this->_data->$aDomain = $aData;
		return $this;
	}

	/**
	 * Get Data from injected data.
	 *
	 * @param string $aDataName The data to be retreived.
	 *
	 * @return mixed
	 */
	function getData($aDataName)
	{
		$myVarName = $aDataName;
		// check if we are looking for a deep reference
		if (strstr($myVarName, '/')) {
			$tmpVarArray = explode('/', $myVarName);
			return $this->_getDeepReference($tmpVarArray, $this->_data);
		}
		
		if (isset($this->_data->$myVarName)) {
			return $this->_data->$myVarName;
		}
		// @todo add debug mode
		return null;
	}

	/**
	 * Get a data that is deeply referenced.
	 *
	 * @param array $aArray The deep reference array.
	 * @param mixed $aData  Data object.
	 *
	 * @return mixed
	 */
	protected function _getDeepReference(array $aArray, $aData)
	{
		if ((is_object($aData))
				&& count($aArray) > 1 && isset($aData->$aArray[0])) {
			$aData = $aData->$aArray[0];
			array_shift($aArray);
			return $this->_getDeepReference($aArray, $aData);
		}
		if (isset($aData->$aArray[0])) {
			return $aData->$aArray[0];
		}
		// @todo add debug mode
		return null;
	}

	/**
	 * Get the data of a domain.
	 *
	 * @param string $aDomain Domain to get.
	 *
	 * @return mixed
	 */
	public function getHelper($aDomain)
	{
		if (isset($this->_helpers->$aDomain)) {
			return $this->_helpers->$aDomain;
		}
		throw (new \BadFunctionCallException());
	}

	/**
	 * Rendering function.
	 *
	 * Render the template.
	 *
	 * @return string
	 */
	public function render()
	{
		if (file_exists($this->_templateDir . $this->_template)) {
			ob_start();
			include($this->_templateDir . $this->_template);
			return ob_get_clean();
		}
		throw(new CMFileDoesNotExist("Template :: {$this->_template}"));
	}
}