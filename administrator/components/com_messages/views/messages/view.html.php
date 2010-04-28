<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View class for a list of messages.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_messages
 * @since		1.6
 */
class MessagesViewMessages extends JView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		parent::display($tpl);
		$this->addToolbar();
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= MessagesHelper::getActions();

		JToolBarHelper::title(JText::_('COM_MESSAGES_MANAGER_MESSAGES'), 'inbox.png');

		if ($canDo->get('core.create')) {
			JToolBarHelper::addNew('message.add');
		}
		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::custom('messages.publish', 'publish.png', 'publish_f2.png','COM_MESSAGES_TOOLBAR_PUBLISH', true);
			JToolBarHelper::custom('messages.unpublish', 'unpublish.png', 'unpublish_f2.png','COM_MESSAGES_TOOLBAR_UNPUBLISH', true);
		}
		if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'messages.delete','JTOOLBAR_EMPTY_TRASH');
		} else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('messages.trash','JTOOLBAR_TRASH');
		}

		//JToolBarHelper::addNew('module.add');
		$bar = &JToolBar::getInstance('toolbar');
		$bar->appendButton('Popup', 'config', 'COM_MESSAGES_TOOLBAR_MY_SETTINGS', 'index.php?option=com_messages&amp;view=config&amp;tmpl=component', 850, 400);

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_messages');
		}
		JToolBarHelper::help('screen.messages.inbox','JTOOLBAR_HELP');
	}
}