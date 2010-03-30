<?php defined('_JEXEC') or die('Restricted access');

/**
 * @version		$Id: manage.php 14276 2010-01-18 14:20:28Z louis $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License, see LICENSE.php
 */
jimport('joomla.form.formfield');

/**
 * Form Field Place class.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_installer
 * @since		1.6
 */
class JFormFieldType extends JFormField
{

	/**
	 * The field type.
	 *
	 * @var		string
	 */
	protected $type = 'Type';

	/**
	 * Method to get the field input.
	 *
	 * @return	string		The field input.
	 */
	protected function getInput() 
	{
		$onchange	= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
		$options = array();
		foreach ($this->element->children() as $option) {
			$options[] = JHtml::_('select.option', $option->attributes('value'), JText::_(trim($option->data())));
		}
		$options[] = JHtml::_('select.option', 'component', JText::sprintf('INSTALLER_OPTION_MANAGE_TYPE_COMPONENT'));
		$options[] = JHtml::_('select.option', 'module', JText::sprintf('INSTALLER_OPTION_MANAGE_TYPE_MODULE'));
		$options[] = JHtml::_('select.option', 'plugin', JText::sprintf('INSTALLER_OPTION_MANAGE_TYPE_PLUGIN'));
		$options[] = JHtml::_('select.option', 'template', JText::sprintf('INSTALLER_OPTION_MANAGE_TYPE_TEMPLATE'));
		$options[] = JHtml::_('select.option', 'language', JText::sprintf('INSTALLER_OPTION_MANAGE_TYPE_LANGUAGE'));
		$options[] = JHtml::_('select.option', 'library', JText::sprintf('INSTALLER_OPTION_MANAGE_TYPE_LIBRARY'));
		$return = JHtml::_('select.genericlist', $options, $this->name, $onchange, 'value', 'text', $this->value, $this->id);
		return $return;
	}
}
