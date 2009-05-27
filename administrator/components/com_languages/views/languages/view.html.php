<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Languages
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * HTML View class for the Languages component
 *
 * @static
 * @package		Joomla.Administrator
 * @subpackage	Languages
 * @since 1.0
 */
class LanguagesViewLanguages extends JView
{
	protected $client;
	protected $ftp;
	protected $filter;
	protected $pagination;
	protected $rows;
	protected $user;
	function display($tpl = null)
	{
		// Set toolbar items for the page
		JToolBarHelper::title(JText::_('Language Manager'), 'langmanager.png');
		JToolBarHelper::makeDefault('publish');
		JToolBarHelper::help('screen.languages');

		// Set FTP credentials, if given
		jimport('joomla.client.helper');
		$ftp = &JClientHelper::setCredentialsFromRequest('ftp');

		// Get data from the model
		$rows		= & $this->get('Data');
		$total		= & $this->get('Total');
		$pagination = & $this->get('Pagination');
		$filter		= & $this->get('Filter');
		$client		= & $this->get('Client');

		if ($client->id == 1) {
			JSubMenuHelper::addEntry(JText::_('Site'),'#" onclick="javascript:document.adminForm.client.value=\'0\';submitbutton(\'\');');
			JSubMenuHelper::addEntry(JText::_('Administrator'), '#" onclick="javascript:document.adminForm.client.value=\'1\';submitbutton(\'\');', true);
		} else {
			JSubMenuHelper::addEntry(JText::_('Site'), '#" onclick="javascript:document.adminForm.client.value=\'0\';submitbutton(\'\');', true);
			JSubMenuHelper::addEntry(JText::_('Administrator'), '#" onclick="javascript:document.adminForm.client.value=\'1\';submitbutton(\'\');');
		}

		$this->assignRef('user',		JFactory::getUser());
		$this->assignRef('rows',		$rows);
		$this->assignRef('pagination',	$pagination);
		$this->assignRef('filter',		$filter);
		$this->assignRef('ftp',			$ftp);
		$this->assignRef('client',		$client);

		parent::display($tpl);
	}
}
