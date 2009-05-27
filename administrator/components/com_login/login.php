<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Login
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// no direct access
defined('_JEXEC') or die;

// Require the base controller
require_once JPATH_COMPONENT.DS.'controller.php';

$controller	= new LoginController();

// Perform the Request task
$controller->execute(JRequest::getCmd('task'));
$controller->redirect();