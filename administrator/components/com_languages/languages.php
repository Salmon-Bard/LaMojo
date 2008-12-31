<?php
/**
* @version		$Id$
* @package		Joomla
* @subpackage	Languages
* @copyright	Copyright (C) 2005 - 2008 Open Source Matters, Inc. All rights reserved.
* @license		GNU General Public License, see LICENSE.php
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

if (!JAcl::authorise('core', 'languages.manage')) {
	JFactory::getApplication()->redirect('index.php', JText::_('ALERTNOTAUTH'));
}

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

$controller	= new LanguagesController();

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();