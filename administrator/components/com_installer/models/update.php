<?php
/**
 * @version		$Id$
 * @package		Joomla.Administrator
 * @subpackage	Menus
 * @copyright	Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 */

// Import library dependencies
require_once(dirname(__FILE__).DS.'extension.php');
jimport('joomla.installer.installer');
jimport('joomla.updater.updater');
jimport('joomla.updater.update');

/**
 * Installer Manage Model
 *
 * @package		Joomla.Administrator
 * @subpackage	Installer
 * @since		1.5
 */
class InstallerModelUpdate extends InstallerModel
{
	/**
	 * Extension Type
	 * @var	string
	 */
	var $_type = 'update';

	var $_message = '';

	/**
	 * Current extension list
	 */

	function _loadItems()
	{
		$mainframe = JFactory::getApplication();
		$option = JRequest::getCmd('option');

		jimport('joomla.filesystem.folder');

		/* Get a database connector */
		$db = &JFactory::getDbo();

		$query = 'SELECT *' .
				' FROM #__updates' .
				//' WHERE extension_id != 0' . // we only want actual updates
				' ORDER BY type, client_id, folder, name';
		$db->setQuery($query);
		try {
			$rows = $db->loadObjectList();
		} catch (JException $e) {
			JError::raiseWarning(-1, $e->getMessage());
			return false;
		}

		$apps = &JApplicationHelper::getClientInfo();

		$numRows = count($rows);
		for ($i=0;$i < $numRows; $i++)
		{
			$row = &$rows[$i];
			$row->jname = JString::strtolower(str_replace(" ", "_", $row->name));
			if (isset($apps[$row->client_id])) {
				$row->client = ucfirst($apps[$row->client_id]->name);
			} else {
				$row->client = $row->client_id;
			}
		}
		$this->setState('pagination.total', $numRows);
		if ($this->_state->get('pagination.limit') > 0) {
			$this->_items = array_slice($rows, $this->_state->get('pagination.offset'), $this->_state->get('pagination.limit'));
		} else {
			$this->_items = $rows;
		}
	}

	function findUpdates($eid=0) {
		$updater = &JUpdater::getInstance();
		$results = $updater->findUpdates($eid);
		return true;
	}

	function purge() {
		$db = &JFactory::getDbo();
		$db->setQuery('TRUNCATE TABLE #__updates');
		if ($db->Query()) {
			$this->_message = JText::_('Purged updates');
			return true;
		} else {
			$this->_message = JText::_('Failed to purge updates');
			return false;
		}
	}

	function update($uids) {
		$result = true;
		foreach($uids as $uid) {
			$update = new JUpdate();
			$instance = &JTable::getInstance('update');
			$instance->load($uid);
			$update->loadFromXML($instance->detailsurl);
			$res = $update->install();
			if ($res) {
				$msg = JText::sprintf('INSTALLEXT', JText::_($update->get('type','IUnknown')), JText::_('Success'));
			} else {
				$msg = JText::sprintf('INSTALLEXT', JText::_($update->get('type','IUnknown')), JText::_('Error'));
			}
			$result = $res & $result;
			// Set some model state values
			$mainframe = JFactory::getApplication();
			$mainframe->enqueueMessage($msg);
			$this->setState('name', $update->get('name'));
			
			$this->setState('message', $update->message);
			$this->setState('extension_message', $update->get('extension_message'));

		}
		$this->setState('result', $result);
	}
}