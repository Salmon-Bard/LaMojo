<?php
/**
 * @version		$Id: article.php 17873 2010-06-25 18:24:21Z 3dentech $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controller');

/**
 * @package		Joomla.Site
 * @subpackage	com_project
 */
class ProjectsControllerTasks extends JController
{
	
	/**
	 * Constructor
	 */
	public function __construct($config = array())
	{
		parent::__construct($config);

		$this->registerTask('back',		'back');
	}
	
	/**
	 * Method to go back to project overview
	 *
	 * @since	1.6
	 */
	public function back()
	{
		$app = JFactory::getApplication();
		$this->setRedirect(JRoute::_('index.php?option=com_projects&view=project&layout=default&id='.$app->getUserState('project.id').'&Itemid='.ProjectsHelper::getMenuItemId(),false));
	}
}