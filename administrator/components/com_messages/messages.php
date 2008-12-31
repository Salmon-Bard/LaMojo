<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage	Messages
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters, Inc. All rights reserved.
* @license		GNU General Public License, see LICENSE.php
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!JAcl::authorise('core', 'messages.manage')) {
	JFactory::getApplication()->redirect('index.php', JText::_('ALERTNOTAUTH'));
}

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

$controller	= new MessagesController();

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();