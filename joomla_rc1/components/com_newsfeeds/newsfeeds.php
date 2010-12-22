<?php
/**
* version $Id: newsfeeds.php 18650 2010-08-26 13:28:49Z ian $
 * @package		Joomla.Site
 * @subpackage	Newsfeeds
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

// Require the com_content helper library
jimport('joomla.application.component.controller');
require_once JPATH_COMPONENT.'/helpers/route.php';
JTable::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR.DS.'tables');

$controller	= JController::getInstance('Newsfeeds');
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();