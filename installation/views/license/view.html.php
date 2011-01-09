<?php
/**
 * @version		$Id$
 * @package		Joomla.Installation
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.view');
jimport('joomla.html.html');

/**
 * The HTML Joomla Core License View
 *
 * @package		Joomla.Installation
 * @since		1.6
 */
class JInstallationViewLicense extends JView
{
	/**
	 * Display the view
	 *
	 * @access	public
	 */
	function display($tpl = null)
	{
		$state = $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}

		$this->assignRef('state', $state);

		parent::display($tpl);
	}
}