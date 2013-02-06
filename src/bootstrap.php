<?php
/**
 * System Bootstrap
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
 * Directory seperator shortcut
 *
 * @var string
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * Working directory
 *
 * @var string
 */
define('ROOT_DIR', __DIR__ . DS);

/**
 * Configuration directory
 *
 * @var string
 */
define('CONFIG_DIR', ROOT_DIR . 'config' . DS);

/**
 * Pages directory
 *
 * @var string
 */
define('LIBS_DIR', __DIR__ . DS . 'libs' . DS);

/**
 * locales directory
 *
 * @var string
 */
define('LOCALES_DIR', ROOT_DIR . 'locales' . DS );

/**
 * Pages directory
 *
 * @var string
 */
define('ACTION_ROOT', __DIR__ . DS . 'pages' . DS);

/**
 * Services directory
 *
 * @var string
 */
define('SERVICE_ROOT', __DIR__ . DS . 'services' . DS);

/**
 * Public directory
 *
 * @var string
 */
define('PUBLIC_ROOT', __DIR__ . DS . 'public' . DS);

/**
 * Data directory
 *
 * @var string
 */
define('DATA_DIR', __DIR__ . DS . 'data' . DS);

/**
 * Cache directory
 *
 * @var string
 */
define('CM_CACHE', DATA_DIR . 'cache' . DS);

/**
 * Cache directory
 *
 * @var string
 */
define('CM_UPLOAD_DIR', PUBLIC_ROOT . 'uploads' . DS);

/**
 * Include Autoload
 */
require 'core/Autoload.php';
$gAutoload = Autoload::getInstance();

/**
 * Initiate Application
 *
 * @var core\App
 */
$app = core\App::getInstance();
