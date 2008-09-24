<?php
/**
 * @version		$Id:bzip2.php 6961 2007-03-15 16:06:53Z tcp $
 * @package		Joomla.Framework
 * @subpackage	FileSystem
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

jimport('joomla.filesystem.stream');

/**
 * Bzip2 format adapter for the JArchive class
 *
 * @author		Louis Landry <louis.landry@joomla.org>
 * @package 	Joomla.Framework
 * @subpackage	FileSystem
 * @since		1.5
 */
class JArchiveBzip2 extends JObject
{
	/**
	 * Bzip2 file data buffer
	 * @var string
	 */
	var $_data = null;

	/**
	 * Constructor tries to load the bz2 extension of not loaded
	 *
	 * @access	protected
	 * @return	void
	 * @since	1.5
	 */
	function __construct()
	{
		// Is bz2 extension loaded?  If not try to load it
		if (!extension_loaded('bz2')) {
			if (JPATH_ISWIN) {
				@ dl('php_bz2.dll');
			} else {
				@ dl('bz2.so');
			}
		}
	}

	/**
	* Extract a Bzip2 compressed file to a given path
	*
	* @access	public
	* @param	string	$archive		Path to Bzip2 archive to extract
	* @param	string	$destination	Path to extract archive to
	* @param	array	$options		Extraction options [unused]
	* @return	boolean	True if successful
	* @since	1.5
	*/
	function extract($archive, $destination, $options = array ())
	{
		// Initialize variables
		$this->_data = null;
		
		if (!extension_loaded('bz2')) {
			$this->set('error.message', 'BZip2 Not Supported');
			return JError::raiseWarning(100, $this->get('error.message'));
		}

		/* // old style: read the whole file and then parse it
		if (!$this->_data = JFile::read($archive)) {
			$this->set('error.message', 'Unable to read archive');
			return JError::raiseWarning(100, $this->get('error.message'));
		}

		$buffer = bzdecompress($this->_data);
		unset($this->_data);
		if (empty ($buffer)) {
			$this->set('error.message', 'Unable to decompress data');
			return JError::raiseWarning(100, $this->get('error.message'));
		}

		if (JFile::write($destination, $buffer) === false) {
			$this->set('error.message', 'Unable to write archive');
			return JError::raiseWarning(100, $this->get('error.message'));
		}
		//*/
		
		// New style! streams!
		$input =& JFactory::getStream();
		$input->set('processingmethod','bz'); // use bzip
		if(!$input->open($archive)) {
			$this->set('error.message', 'Unable to read archive (bz2)');
			return JError::raiseWarning(100, $this->get('error.message'));
		}
		
		$output =& JFactory::getStream();
		if(!$output->open($destination, 'w')) {
			$this->set('error.message', 'Unable to write archive (bz2)');
			$input->close(); // close the previous file
			return JError::raiseWarning(100, $this->get('error.message'));
		}
		
		$written = 0;
		do {
			$this->_data = $input->read($input->get('chunksize', 8196));
			if($this->_data) {
				if(!$output->write($this->_data)) {
					$this->set('error.message', 'Unable to write file (bz2)');
					return JError::raiseWarning(100, $this->get('error.message'));
				}
			}
		} while ($this->_data);
		$output->close();
		$input->close();
		return true;
	}
}