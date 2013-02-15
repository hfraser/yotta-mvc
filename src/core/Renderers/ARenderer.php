<?php
/**
 * Abtract Renderer Class
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2013 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Core
 * @package    Core
 * @subpackage Renderers
 * @filesource
 */
namespace core\Renderers;

/**
 * Abstract renderer class
 *
 * This class is designed to be implemented for different types of renderers
 * that will be available. reneres are configurable throught the global configuration.
 * Renderers need to be created into the core/Renderers/ folder.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @category   Core
 * @package    Core
 * @subpackage Renderers
 */
abstract class ARenderer
{
	/**
	 * Path to the template directory.
	 *
	 * @var string
	 */
	protected $_templateDir;
	
	/**
	 * Data for the template
	 *
	 * @var \stdClass|array
	 */
	protected $_data;

	/**
	 * Available helpers for the template
	 *
	 * @var \stdClass|array
	 */
	protected $_helpers;

	/**
	 * Name of the template to be rendered.
	 *
	 * @var \stdClass|array
	 */
	protected $_template;

	/**
	 * Base renderer Constructor.
	 */
	abstract public function __construct();

	/**
	 * Set the base directory template for the renderer.
	 *
	 * @param string $aTempalteDir Path to the base template directory.
	 *
	 * @return self
	 */
	public function setTemplateDir($aTempalteDir)
	{
		$this->_templateDir = $aTempalteDir;
		return $this;
	}
	
	/**
	 * Get the base directory template for the renderer.
	 *
	 * @return string
	 */
	public function getTemplateDir()
	{
		return $this->_templateDir;
	}
	
	/**
	 * Load template.
	 *
	 * Template to load.
	 *
	 * @param string $aTemplate Template path (within the template folder).
	 *
	 * @return self
	*/
	public function loadTemplate($aTemplate)
	{
		$this->_template = $aTemplate;
		return $this;
	}
	
	/**
	 * Get the loaded template.
	 *
	 * @return string
	 */
	public function getTemplate()
	{
		return $this->_template;
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
	abstract public function addHelper($aName, $aHelper);

	/**
	 * Get a helper from the helper collection.
	 *
	 * @param string $aName Helper Access name.
	 *
	 * @return mixed
	 * @throws \BadFunctionCallException If the helper is not found.
	 */
	abstract public function getHelper($aName);
	
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
	abstract public function addData($aDomain, $aData);

	/**
	 * Get Data from injected data.
	 *
	 * @param string $aDataName The data to be retreived.
	 *
	 * @return mixed
	 */
	abstract public function getData($aDataName);
	
	
	
	/**
	 * Rendering function.
	 *
	 * Render the template.
	 *
	 * @return string
	 */
	abstract public function render();
}