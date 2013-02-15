
<?php
/**
 * Unit testing bootstrap.
 *
 * This file requires the src bootstrap.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2010 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   UnitTesting
 * @package    UnitTest
 * @filesource
 */
use core\Patterns\ASingleton;

/**
 * Unit test Base root path.
 *
 * @global
 * @var string
 */
define('UNITTEST_ROOT', __DIR__ . DIRECTORY_SEPARATOR);

/**
 * Configuration directory.
 *
 * @var string
 */
define('CONFIG_DIR', UNITTEST_ROOT . 'config' . DIRECTORY_SEPARATOR);

/**
 * Data directory.
 *
 * @var string
 */
define('DATA_DIR', __DIR__ . DIRECTORY_SEPARATOR . 'tmpTestData' . DIRECTORY_SEPARATOR);

/**
 * Cache directory.
 *
 * @var string
 */
define('CM_UPLOAD_DIR', DATA_DIR . 'uploads' . DIRECTORY_SEPARATOR);

/**
 * Templates directory.
 *
 * @var string
 */
define('TEMPLATE_ROOT', UNITTEST_ROOT . 'templates' . DIRECTORY_SEPARATOR);

/**
 * Public directory.
 *
 * @var string
 */
define('PUBLIC_ROOT', UNITTEST_ROOT . 'public' . DIRECTORY_SEPARATOR);

// Make sure that temporary test data is empty.
if (file_exists(DATA_DIR . 'data.sqlite')) {
	unlink(DATA_DIR . 'data.sqlite');
}

require_once __DIR__ . '/../src/bootstrap.php';
ASingleton::$test = true;