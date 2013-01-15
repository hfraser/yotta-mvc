<?php
/**
 * I18N String exctraction task.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2012 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   CommandLine
 * @package    bin
 * @filesource
 */
namespace bin;
use core\App;

/**
 * I18N String exctraction task.
 *
 * This class is to be inherited by all tasks that are to be handled by the
 * base command line tool.
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category CommandLine
 * @package  Bin
 * @see      bin\ATask
 */
class i18n extends ATask
{
	/**
	 * Task constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->_addAction('extract', "This function will extract all" .
						" translatable strings and update the available" .
						" languages of this project.");
		
		$this->_addAction('compile', "Compile all .po files for" .
				" gettext into .mo files for all languages");
		
	}

	/**
	 * Extract I18N strings action.
	 * 
	 * The extract action will fully extract the strings of a project
	 * and put them into a PO. All available languages for the project
	 * will be populated. If the languages have been extracted and the
	 * system will merge the new strings with the current translation.
	 *
	 * @return void
	 */
	protected function _extract()
	{
		var_dump($this->_args);
		if (file_exists('new.po')) {
			unlink('new.po');
		}

		echocs("Extracting all strings\n", 'blue');
		touch('tmp.po');
		echo exec(
			"find src -type f -iname \"*.php\" |" .
			" xgettext --keyword=__ --keyword=_e -o tmp.po --from-code utf-8 -j -f -"
		);
		exec('sed -e s/CHARSET/UTF-8/ tmp.po > new.po');
		unlink('tmp.po');
		echocs("Parsing Locales and updating them\n", 'blue');

		foreach (App::$config->lang->avail as $value) {
			$currentLocaleFile = LOCALES_DIR . $value . DS . 'LC_MESSAGES' . DS . 'messages.po';

			if (file_exists($currentLocaleFile)) {
				echocs("	Locale {$value} allready exists : merging changes\n", 'green');
				touch("new{$value}.po");
				echo(exec("msgmerge -o new{$value}.po {$currentLocaleFile} new.po"));
				rename("new{$value}.po", $currentLocaleFile);
			} else {
				echocs("	Locale {$value} was not present the new locale as been added", 'red');
				copy('new.po', $currentLocaleFile);
			}
		}
		echocs("Extract Complete!", 'green');
		unlink('new.po');
	}
	
	protected function _compile()
	{
		echocs("Compiling Locales\n", 'blue');
		foreach (App::$config->lang->avail as $value) {
			$currentLocaleFile = LOCALES_DIR . $value . DS . 'LC_MESSAGES' . DS . 'messages';
			if (file_exists($currentLocaleFile . '.po')) {
				// delete existing MO if present
				if (file_exists($currentLocaleFile . '.mo')) {
					unlink($currentLocaleFile . '.mo');
				}
				echocs("	Locale {$value} exists : compiling {$currentLocaleFile}.mo\n", 'green');
				echo(exec("msgfmt -cv -o {$currentLocaleFile}.mo {$currentLocaleFile}.po"));
			} else {
				echocs("	Locale {$value} was not present no file has been compiled for this locale!\n", 'red');
			}
		}
		
	}
}