<?php
/**
 * View factory
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
use core\Renderers\ARenderer;
use core\Helpers\HtmlHelper;
use core\Url;

/**
 * View Factory
 *
 * This class is implemented in order to provide template redering systems.
 * Based on the global configuration. It will also inject base data and base helpers.
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category Core
 * @package  Core
 */
class ViewFactory
{
	/**
	 * Renderer object.
	 *
	 * @var core\Renderer\ARenderer
	 */
	static protected $_renderer;

	/**
	 * Set the base data from the application.
	 *
	 * The data set will be accessible to all templates. and all renderer.
	 *
	 * @param string  $aRenderer    Name of the render to provide. [optional]
	 *                              If a renderer is not provided the system will use the
	 *                              global configuration.
	 * @param boolean $aSetBaseData Tell the rendered to not des the base sada [default = true].
	 *
	 * @return ARenderer
	 */
	public static function getView($aRenderer = null, $aSetBaseData = true)
	{
		if (!isset(self::$_renderer)) {
			self::$_renderer = (isset(App::$config->renderer))?
				App::$config->renderer : 'PHPtemplates';
		}
		
		$myRendererClass = ($aRenderer)?: self::$_renderer;
		$myRendererClass = "core\\Renderers\\{$myRendererClass}";
		/**
		 * @var ARenderer
		 */
		$myRenderer = new $myRendererClass;
		$myRenderer->setTemplateDir(TEMPLATE_ROOT);
		
		if ($aSetBaseData) {
			self::_setBaseData($myRenderer);
		}
		
		return $myRenderer;
	}

	/**
	 * Set the base data from the application.
	 *
	 * The data set will be accessible to all templates. and all renderer.
	 *
	 * @param object $aRenderer Renderer to attach data to (an instance inherited of ARenderer).
	 *
	 * @return void
	 */
	protected static function _setBaseData($aRenderer)
	{
		$request = Url::getInstance();
		$aRenderer->addHelper('request', $request);
		$aRenderer->addHelper('html', new HtmlHelper());
		$aRenderer->addData('lang', $request->lang);
		$aRenderer->addData('languages', $request->getAllLang());
		$aRenderer->addData('defaultLang', $request->getDefaultLang());
		$aRenderer->addData('basepath', $request->basepath);
		$aRenderer->addData('urlroot', $request->basepath);
	}
}