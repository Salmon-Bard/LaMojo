<?php

/**
 * @version		$Id: article.php 17873 2010-06-25 18:24:21Z 3dentech $
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.controlleradmin');

/**
 * @package		Joomla.Site
 * @subpackage	com_project
 */
class ProjectsControllerTasks extends JControllerAdmin
{
	protected $text_prefix;
    /**
     * Constructor
     */
    public function __construct($config = array()) {
        parent::__construct($config);

        // States
        $this->registerTask('publish', 'publish'); // value = 0 	APPROVED TASK
        //$this->registerTask('unpublish', 'publish'); // value = 0	NOT APROVED
        $this->registerTask('archive', 'publish'); // value = 2 	FINISHED TASK
        //$this->registerTask('trash', 'publish'); // value = -2
        $this->registerTask('report', 'publish'); // value = -3 	REPORTED ticket
        $this->registerTask('orderup', 'reorder');
        $this->registerTask('orderdown', 'reorder');

        $this->registerTask('setTasks', 'aprove');
    }

    /**
     * Method to change tickets to tasks
     *
     * @since	1.6
     */
    public function aprove() {
        // Check for request forgeries
        JRequest::checkToken() or die(JText::_('JINVALID_TOKEN'));
        $app = JFactory::getApplication();

        $id = JRequest::getInt('id', 0);
        if ($id) {
            $id = array($id); // make an array out of it
        } else {
            $id = JRequest::getVar('cid', array(), 'default', 'array');
            JArrayHelper::toInteger($id);
        }

        require_once JPATH_COMPONENT . '/helpers/tasks.php';
        $c = count($id);
        for ($i = 0; $i < $c; $i++) {
            $result = TasksHelper::setTypeTask($id[$i]); // change a ticket to a task
            if (!$result) {
                return JError::raiseError(500, JText::_('COM_PROJECTS_TASKS_ERROR_CHANGE_STATE_TICKET'));
            }
        }

        $app = JFactory::getApplication();
        $text = JText::sprintf('COM_PROJECTS_TASKS_SUCCESS_CHANGE_TICKET', $c);
        if ($c > 1)
            $text = JText::sprintf('COM_PROJECTS_TASKS_SUCCESS_CHANGE_TICKET_PLURAL', $c);
        $this->setRedirect(ProjectsHelper::getLink('tasks',$app->getUserState('project.id').'&type='.$this->getModel()->getState('task.type')), $text);
    }

    /**
     * Method to get a model object, loading it if required.
     *
     * @param	string	The model name. Optional.
     * @param	string	The class prefix. Optional.
     * @param	array	Configuration array for model. Optional.
     *
     * @return	object	The model.
     * @since	1.6
     */
    public function getModel($name = 'Task', $prefix = 'ProjectsModel', $config = null) {
        return parent::getModel($name, $prefix, $config);
    }

    /**
     * Method to go back to project overview
     *
     * @since	1.6
     */
    public function back() {
        $app = JFactory::getApplication();
        $this->setRedirect(ProjectsHelpers::getLink('project',$app->getUserState('project.id')));
    }

}