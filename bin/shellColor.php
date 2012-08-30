<?php
/**
 * Syntax coloring for Shell scripts mini library.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2012 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   CommandLine
 * @package    Bin
 * @filesource
 */

/**
 * Coller array for shell
 *
 * @var array
 */
$colors = array('black' => '0;30',
		'dark_gray' => '1;30',
		'blue' => '0;34',
		'light_blue' => '1;34',
		'green' => '0;32',
		'light_green' => '1;32',
		'cyan' => '0;36',
		'light_cyan' => '1;36',
		'red' => '0;31',
		'light_red' => '1;31',
		'purple' => '0;35',
		'light_purple' => '1;35',
		'brown' => '0;33',
		'yellow' => '1;33',
		'light_gray' => '0;37',
		'white' => '1;37');
/**
 * Echo a colored string.
 *
 * @param string $aString The string you want to output.
 * @param string $aColor  The color you want the string to be.
 * 
 * @return void
 */
function echocs($aString, $aColor = null)
{
	global $colors;
	$myString = $aString;
	if (!is_null($aColor)) {
		$myString = "";
		if (isset($colors[$aColor])) {
			$myString .= "\033[" . $colors[$aColor] . "m";
		}
		$myString .= $aString . "\033[0m";
	}
	echo "$myString\n";
}