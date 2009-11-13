<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport( 'joomla.application.component.controller' );

/**
 * The Menu Type Controller
 *
 * @package		Joomla.Administrator
 * @subpackage	com_menus
 * @since		1.6
 */
class MenusControllerMenu extends JController
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();

		// Register proxy tasks.
		$this->registerTask('apply',		'save');
	}

	/**
	 * Dummy method to redirect back to standard controller
	 *
	 * @return	void
	 */
	public function display()
	{
		$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menus', false));
	}

	/**
	 * Method to add a new menu item.
	 *
	 * @return	void
	 */
	public function add()
	{
		// Initialize variables.
		$app = &JFactory::getApplication();

		// Clear the menu item edit information from the session.
		$app->setUserState('com_menus.edit.menu.id', null);
		$app->setUserState('com_menus.edit.menu.data', null);

		// Redirect to the edit screen.
		$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menu&layout=edit', false));
	}

	/**
	 * Method to edit an existing menu item.
	 *
	 * @return	void
	 */
	public function edit()
	{
		// Initialize variables.
		$app	= &JFactory::getApplication();
		$ids	= JRequest::getVar('cid', array(), '', 'array');

		// Get the id of the group to edit.
		$id		=  (empty($ids) ? JRequest::getInt('menu_id') : (int) array_pop($ids));

		// Push the new row id into the session.
		$app->setUserState('com_menus.edit.menu.id',	$id);
		$app->setUserState('com_menus.edit.menu.data', null);
		$this->setRedirect('index.php?option=com_menus&view=menu&layout=edit');
		return true;
	}

	/**
	 * Method to cancel an edit
	 *
	 * Checks the item in, sets item ID in the session to null, and then redirects to the list page.
	 *
	 * @return	void
	 */
	public function cancel()
	{
		// Initialize variables.
		$app = &JFactory::getApplication();

		// Clear the menu item edit information from the session.
		$app->setUserState('com_menus.edit.menu.id', null);
		$app->setUserState('com_menus.edit.menu.data', null);

		// Redirect to the list screen.
		$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menus', false));
	}

	/**
	 * Method to save a menu item.
	 *
	 * @return	void
	 */
	public function save()
	{
		// Check for request forgeries.
		JRequest::checkToken() or jexit(JText::_('JInvalid_Token'));

		// Initialize variables.
		$app	= &JFactory::getApplication();
		$task	= $this->getTask();

		// Get the posted values from the request.
		$data	= JRequest::getVar('jform', array(), 'post', 'array');

		// Populate the row id from the session.
		$data['id'] = (int) $app->getUserState('com_menus.edit.menu.id');

		// Get the model and attempt to validate the posted data.
		$model	= &$this->getModel('Menu');
		$form	= &$model->getForm();
		if (!$form) {
			JError::raiseError(500, $model->getError());
			return false;
		}
		$data	= $model->validate($form, $data);

		// Check for validation errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if (JError::isError($errors[$i])) {
					$app->enqueueMessage($errors[$i]->getMessage(), 'notice');
				} else {
					$app->enqueueMessage($errors[$i], 'notice');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_menus.edit.menu.data', $data);

			// Redirect back to the edit screen.
			$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menu&layout=edit', false));
			return false;
		}

		// Attempt to save the data.
		if (!$model->save($data))
		{
			// Save the data in the session.
			$app->setUserState('com_menus.edit.menu.data', $data);

			// Redirect back to the edit screen.
			$this->setMessage(JText::sprintf('JError_Save_failed', $model->getError()), 'notice');
			$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menu&layout=edit', false));
			return false;
		}

		$this->setMessage(JText::_('JController_Save_success'));

		// Redirect the user and adjust session state based on the chosen task.
		switch ($task)
		{
			case 'apply':
				// Redirect back to the edit screen.
				$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menu&layout=edit', false));
				break;

			default:
				// Clear the menu item id and data from the session.
				$app->setUserState('com_menus.edit.menu.id', null);
				$app->setUserState('com_menus.edit.menu.data', null);

				// Redirect to the list screen.
				$this->setRedirect(JRoute::_('index.php?option=com_menus&view=menus', false));
				break;
		}
	}
}
