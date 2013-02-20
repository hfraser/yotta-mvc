#!/usr/bin/php
<?php
/**
 * Cache Clear (All Caches).
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2012 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Core
 * @package    bin
 * @filesource
 */

// validate that we are running from a shell command
if (!defined('STDIN')) {
	die("This script is made to be ran only from the shell");
}

require_once('bootstrap.php');

if (is_dir(CM_CACHE) && is_writable(CM_CACHE)) {
	echo("[INFO] Clearing cache \n");
	exec('rm -rf ' . CM_CACHE . '*');
} else {
	echo("[ERROR] Directory is not writable :: " . CM_CACHE . "\n");
}