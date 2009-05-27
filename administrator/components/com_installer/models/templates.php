<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Menus
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// no direct access
defined('_JEXEC') or die;

// Import library dependencies
require_once dirname(__FILE__).DS.'extension.php';
jimport('joomla.filesystem.folder');

/**
 * Extension Manager Templates Model
 *
 * @package		Joomla.Administrator
 * @subpackage	Installer
 * @since		1.5
 */
class InstallerModelTemplates extends InstallerModel
{
	/**
	 * Extension Type
	 * @var	string
	 */
	var $_type = 'template';

	/**
	 * Overridden constructor
	 * @access	protected
	 */
	function __construct()
	{
		$mainframe = JFactory::getApplication();

		// Call the parent constructor
		parent::__construct();

		// Set state variables from the request
		$this->setState('filter.string', $mainframe->getUserStateFromRequest("com_installer.templates.string", 'filter', '', 'string'));
		$this->setState('filter.client', $mainframe->getUserStateFromRequest("com_installer.templates.client", 'client', -1, 'int'));
	}

	function _loadItems()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		$db = &JFactory::getDbo();

		if ($this->_state->get('filter.client') < 0) {
			$client = 'all';
			// Get the site templates
			$templateDirs = JFolder::folders(JPATH_SITE.DS.'templates');

			for ($i=0; $i < count($templateDirs); $i++) {
				$template = new stdClass();
				$template->folder = $templateDirs[$i];
				$template->client = 0;
				$template->baseDir = JPATH_SITE.DS.'templates';

				if ($this->_state->get('filter.string')) {
					if (strpos($template->folder, $this->_state->get('filter.string')) !== false) {
						$templates[] = $template;
					}
				} else {
					$templates[] = $template;
				}
			}
			// Get the admin templates
			$templateDirs = JFolder::folders(JPATH_ADMINISTRATOR.DS.'templates');

			for ($i=0; $i < count($templateDirs); $i++) {
				$template = new stdClass();
				$template->folder = $templateDirs[$i];
				$template->client = 1;
				$template->baseDir = JPATH_ADMINISTRATOR.DS.'templates';

				if ($this->_state->get('filter.string')) {
					if (strpos($template->folder, $this->_state->get('filter.string')) !== false) {
						$templates[] = $template;
					}
				} else {
					$templates[] = $template;
				}
			}
		} else {
			$clientInfo = &JApplicationHelper::getClientInfo($this->_state->get('filter.client'));
			$client = $clientInfo->name;
			$templateDirs = JFolder::folders($clientInfo->path.DS.'templates');

			for ($i=0; $i < count($templateDirs); $i++) {
				$template = new stdClass();
				$template->folder = $templateDirs[$i];
				$template->client = $clientInfo->id;
				$template->baseDir = $clientInfo->path.DS.'templates';

				if ($this->_state->get('filter.string')) {
					if (strpos($template->folder, $this->_state->get('filter.string')) !== false) {
						$templates[] = $template;
					}
				} else {
					$templates[] = $template;
				}
			}
		}

		// Get a list of the currently active templates
		$query = 'SELECT DISTINCT template,client_id'.
				' FROM #__menu_template ';
		$db->setQuery($query);
		$activeList = $db->loadResultArray();
		$activeArr = array();
		for ($i = 0, $n = count($this->rows); $i < $n; $i++) {
			$activeArr[$activeLIst[$i]->client_id][$activeLIst[$i]->template]=true;
		}
		
		$rows = array();
		$rowid = 0;
		// Check that the directory contains an xml file
		foreach($templates as $template)
		{
			$dirName = $template->baseDir .DS. $template->folder;
			$xmlFilesInDir = JFolder::files($dirName,'.xml$');

			foreach($xmlFilesInDir as $xmlfile)
			{
				$data = JApplicationHelper::parseXMLInstallFile($dirName . DS. $xmlfile);

				$row = new StdClass();
				$row->id 		= $rowid;
				$row->client_id	= $template->client;
				$row->directory = $template->folder;
				$row->baseDir	= $template->baseDir;

				// Is the template active?
				if (!empty($activeArr[$template->client][$rowid])) {
					$row->active = true;
				} else {
					$row->active = false;
				}

				if ($data) {
					foreach($data as $key => $value) {
						$row->$key = $value;
					}
				}

				$row->checked_out = 0;
				$row->jname = JString::strtolower(str_replace(' ', '_', $row->name));

				$rows[] = $row;
				$rowid++;
			}
		}
		$this->setState('pagination.total', count($rows));
		if ($this->_state->get('pagination.limit') > 0) {
			$this->_items = array_slice($rows, $this->_state->get('pagination.offset'), $this->_state->get('pagination.limit'));
		} else {
			$this->_items = $rows;
		}
	}
}