<?php
/**
 * System Bootstrap.
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
 * Directory seperator shortcut.
 *
 * @var string
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Working directory.
 *
 * @var string
 */
define('ROOT_DIR', __DIR__ . DS);

if (!defined('CONFIG_DIR')) {
	/**
	 * Configuration directory.
	 *
	 * @var string
	 */
	define('CONFIG_DIR', ROOT_DIR . 'config' . DS);
}

/**
 * Libs directory.
 *
 * @var string
 */
define('LIBS_DIR', ROOT_DIR . 'libs' . DS);

/**
 * locales directory.
 *
 * @var string
 */
define('LOCALES_DIR', ROOT_DIR . 'locales' . DS );

if (!defined('TEMPLATE_ROOT')) {
	/**
	 * Pages directory.
	 *
	 * @var string
	 */
	define('TEMPLATE_ROOT', ROOT_DIR . 'pages' . DS);
}

/**
 * Services directory.
 *
 * @var string
 */
define('SERVICE_ROOT', ROOT_DIR . 'services' . DS);

if (!defined('PUBLIC_ROOT')) {
	/**
	 * Public directory.
	 *
	 * @var string
	 */
	define('PUBLIC_ROOT', ROOT_DIR . 'public' . DS);
}
if (!defined('DATA_DIR')) {
	/**
	 * Data directory.
	 *
	 * @var string
	 */
	define('DATA_DIR', ROOT_DIR . 'data' . DS);
}

/**
 * Cache directory.
 *
 * @var string
 */
define('CM_CACHE', DATA_DIR . 'cache' . DS);

if (!defined('CM_UPLOAD_DIR')) {
	/**
	 * Cache directory.
	 *
	 * @var string
	 */
	define('CM_UPLOAD_DIR', PUBLIC_ROOT . 'uploads' . DS);
}

/**
 * Include Autoload.
 */
require 'core/Autoload.php';
$gAutoload = Autoload::getInstance();

/**
 * Initiate Application.
 *
 * @var core\App
 */
core\App::getInstance();
