<?php
/**
 * Suggestion box form class.
 *
 * @version    Release: 1.0
 * @author     Hans-Frederic Fraser <hffraser@gmail.com>
 * @copyright  2012 Hans-Frederic Fraser
 * @license    http://www.gnu.org/licenses/gpl-3.0-standalone.html gpl-3.0
 * @category   Form
 * @package    Services
 * @filesource
 */
namespace services;
use core\AServiceForm;

/**
 * Suggestion box class
 *
 * This class is an example of a form service in action.
 *
 * @version  Release: 1.0
 * @author   Hans-Frederic Fraser <hffraser@gmail.com>
 * @category Form
 * @package  Services
 * @filesource
 */
class SuggestionBox extends AServiceForm
{
	/**
	 * Configuration for the current form.
	 *
	 * @return array
	 */
	protected function _setConfig()
	{
		return (object)array(
			'modelName' => 'suggestion',
			'templates' => array(
				self::INDEX_ACTION => ACTION_ROOT . 'forms/SuggestForm.php',
				self::FAIL_ACTION => ACTION_ROOT . 'forms/SuggestForm.php',
				self::SUCCESS_ACTION => ACTION_ROOT . 'forms/SuggestFormSuccess.php'
			),
			'values' => array(
				'yourname' => array('string250', true),
				'email' => array('email', true),
				'suggestion' => array('string1000', true),
				'image' => array('file', true, 'pictures/')
			),
			'messages' => array(
				'default' => array (
					self::ERROR_NO_DATA => _('You Need To fill this field!'),
					self::ERROR_BAD_DATA => _('You need to fill this field properly!')
				),
				'yourname' => array (
					self::ERROR_NO_DATA => _('You Need To fill your name in!'),
					self::ERROR_BAD_DATA => _('You need to fill your name properly!')
				),
				'email' => array (
					self::ERROR_NO_DATA => _('You Need To fill your email!'),
					self::ERROR_BAD_DATA => _('You need to fill your email properly!')
				),
				'suggestion' => array (
					self::ERROR_NO_DATA => _('You Need To add a suggestion!'),
					self::ERROR_BAD_DATA => _('Your suggestion must be at least 3 characters long and not longer then 1000!')
				)
			)
		);
	}

	/**
	 * Save Form {@inheritdoc}.
	 *
	 * @param \RedBean_OODBBean $aData Current data in the _data model.
	 *
	 * @see    core.AServiceForm::_saveData()
	 * @return void
	 */
	protected function _saveData(\RedBean_OODBBean $aData)
	{
		$aData->culture = $this->_request->lang;
		parent::_saveData($aData);
	}
}