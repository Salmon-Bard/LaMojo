<?php
/**
 * @version		$Id$
 * @package		Joomla.Framework
 * @subpackage	Error
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

/**
 * Joomla! Exception object.
 *
 * @package 	Joomla.Framework
 * @subpackage	Error
 * @since		1.5
 */
class JException extends Exception 
{
	/**
	 * Error level
	 * @var string
	 */
	public $level		= null;

	/**
	 * Error code
	 * @var string
	 */
	public $code		= null;
 
	/**
	 * Error message
	 * @var string
	 */
	public $message	= null;

	/**
	 * Additional info about the error relevant to the developer
	 *  - e.g. if a database connect fails, the dsn used
	 * @var string
	 */
	public $info		= '';

	/**
	 * Name of the file the error occurred in [Available if backtrace is enabled]
	 * @var string
	 */
	public $file		= null;

	/**
	 * Line number the error occurred in [Available if backtrace is enabled]
	 * @var int
	 */
	public $line		= 0;

	/**
	 * Name of the method the error occurred in [Available if backtrace is enabled]
	 * @var string
	 */
	public $function	= null;

	/**
	 * Name of the class the error occurred in [Available if backtrace is enabled]
	 * @var string
	 */
	public $class		= null;

	/**
     * Error type
	 * @var string
	 */
	public $type		= null;

	/**
	 * Arguments recieved by the method the error occurred in [Available if backtrace is enabled]
	 * @var array
	 */
	public $args		= array();

	/**
	 * Backtrace information
	 * @var mixed
	 */
	public $backtrace	= null;

	/**
	 * Constructor
	 * 	- used to set up the error with all needed error details.
	 *
	 * @access	protected
	 * @param	string	$msg		The error message
	 * @param	string	$code		The error code from the application
	 * @param	int		$level		The error level (use the PHP constants E_ALL, E_NOTICE etc.).
	 * @param	string	$info		Optional: The additional error information.
	 * @param	boolean	$backtrace	True if backtrace information is to be collected
	 */
	public function __construct( $msg, $code = 0, $level = null, $info = null, $backtrace = false )
	{
		$this->level	=	$level;
		$this->code		=	$code;
		$this->message	=	$msg;

		if ($info != null) {
			$this->info = $info;
		}

		if ($backtrace && function_exists( 'debug_backtrace' ))
		{
			$this->backtrace = debug_backtrace();

			for( $i = count( $this->backtrace ) - 1; $i >= 0; --$i )
			{
				++$i;
				if (isset( $this->backtrace[$i]['file'] )) {
					$this->file		= $this->backtrace[$i]['file'];
				}
				if (isset( $this->backtrace[$i]['line'] )) {
					$this->line		= $this->backtrace[$i]['line'];
				}
				if (isset( $this->backtrace[$i]['class'] )) {
					$this->class	= $this->backtrace[$i]['class'];
				}
				if (isset( $this->backtrace[$i]['function'] )) {
					$this->function	= $this->backtrace[$i]['function'];
				}
				if (isset( $this->backtrace[$i]['type'] )) {
					$this->type		= $this->backtrace[$i]['type'];
				}

				$this->args		= false;
				if (isset( $this->backtrace[$i]['args'] )) {
					$this->args		= $this->backtrace[$i]['args'];
				}
				break;
			}
		}
		parent::__construct($this->message, $this->code);
	}
}
