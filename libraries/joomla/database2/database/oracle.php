<?php
/**
 * @version     $Id$
 * @package     Joomla.Framework
 * @subpackage  Database
 * @copyright   Copyright (C) 2005 - 2009 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die();

/**
 * Oracle database driver
 *
 * @package		Joomla.Framework
 * @subpackage	Database
 * @since		1.5
 */
class JDatabaseOracle extends JDatabase
{
	/**
	 * The name of the database driver
	 *
	 * @var string
	 */
	public $name = 'oracle';

	/**
	 *  The null or zero representation of a timestamp for MySQL.
	 *
	 * @var string
	 */
	protected $_nullDate = '0000-00-00 00:00:00';

	/**
	 * The character used to quote SQL statement names such as table names or
     * field names, etc.
	 *
	 * @var string
	 */
	protected $_nameQuote		= '"';

	/**
	 * The parsed query sql string
	 *
	 * This will actually be an Oracle statement identifier,
	 * not a normal string
	 *
	 * @var resource
	 */
	protected $_prepared			= '';

    /**
     * The variables to be bound by oci_bind_by_name
     *
     * @var array
     */
    protected $_bounded            = '';
    
    /**
    * The number of rows affected by the previous 
    * INSERT, UPDATE, REPLACE or DELETE query executed
    * @var int
    */
    protected $_affectedRows       = '';
    
    /**
    * The number of rows returned by the previous 
    * SELECT query executed
    * @var int
    */
    protected $_numRows       = '';
    
    /**
    * Returns the current dateformat
    * 
    * @var mixed
    */
    protected $_dateformat    = '';
    
    /**
    * Returns the current character set
    * 
    * @var mixed
    */
    protected $_charset       = '';
    
    /**
    * Is used to decide whether a result set
    * should generate lowercase field names
    * 
    * @var boolean
    */
    protected $_tolower = true;
    
    /**
    * Is used to decide whether a result set
    * should return the LOB values or the LOB objects
    */
    protected $_returnlobs = true;
    
	/**
	* Oracle database driver constructor
	*
    * @see        JDatabase
    * @throws    JException
    * @param    array    Array of options used to configure the connection.
    * @return    void
	* @since	1.5
	*/
	protected function __construct( $options )
	{
		$host		= isset($options['host'])	? $options['host']		: 'localhost';
		$user		= isset($options['user'])	? $options['user']		: '';
		$password	= isset($options['password'])	? $options['password']	: '';
		$database	= isset($options['database'])	? $options['database']	: '';
		$prefix		= isset($options['prefix'])	? $options['prefix']	: 'jos_';
		$select		= isset($options['select'])	? $options['select']	: true;
        $port       = isset($options['port'])    ? $options['port']      : '1521';
        $charset    = isset($options['charset']) ? $options['charset']   : 'AL32UTF8';
        $dateformat = isset($options['dateformat']) ? $options['dateformat'] : 'RRRR-MM-DD HH24:MI:SS';

		// perform a number of fatality checks, then return gracefully
		if (!function_exists( 'oci_connect' )) {
			$this->_errorNum = 1;
			$this->_errorMsg = 'The Oracle adapter "oracle" is not available.';
			return;
		}

		// connect to the server
		if (!($this->_resource = @oci_connect( $user, $password, "//$host:$port/$database" ))) {
			throw new JException('Could not connect to Oracle.', 2);
		}

        /**
        * Sets the default dateformat for the session
        * If the next two lines aren't executed on construction
        * then dates will be returned in the default Oracle Date Format of
        */        
        $this->setDateFormat($dateformat);
        $this->_dateformat = $dateformat;
        
        $this->_charset = $charset;
        
		// finalize initialization
		parent::__construct($options);
	}

	/**
	 * Oracle database driver object destructor.  Tidy up any residual
     * database connection resources.
	 *
	 * @return void
	 * @since 1.5
	 */
	public function __destruct()
	{
		if (is_resource($this->_resource)) {
			$return = oci_close($this->_resource);
		}
	}

	/**
	 * Test to see if the Oracle connector is available
	 *
	 * @static
	 * @access public
	 * @return boolean  True on success, false otherwise.
	 */
	public static function test()
	{
		return (function_exists( 'oci_connect' ));
	}

	/**
	 * Determines if the connection to the server is active.
	 *
	 * @access	public
	 * @return	boolean
	 * @since	1.5
	 */
	public function connected()
	{
		if(is_resource($this->_resource)) {
			//return mysql_ping($this->_resource);
			// TODO See if there is a more elegant way to achieve this with Oracle DB
			return true;
		}
		return false;
	}

	/**
	 * Determines UTF support. Oracle versions 9.2+ will
     * return true
	 *
	 * @access	public
	 * @return boolean True - UTF is supported
	 */
	public function hasUTF()
	{
		$verParts = explode( '.', $this->getVersion() );
		return ($verParts[0] > 9 || ($verParts[0] == 9 && $verParts[1] == 2) );
	}

	/**
	 * Custom settings for UTF support
	 *
	 * @access	public
	 */
	public function setUTF()
	{
        // Doesn't really work right now
		//$this->setCharset();
	}

	/**
	 * Get a database escaped string
	 *
	 * @param	string	The string to be escaped
	 * @param	boolean	Optional parameter to provide extra escaping
	 * @return	string
	 * @access	public
	 * @abstract
	 */
	// TODO Figure out how to do this for Oracle...does oci_bind_by_name do the same thing?
	public function getEscaped( $text, $extra = false )
	{
		/*
		$result = mysql_real_escape_string( $text, $this->_resource );
		if ($extra) {
			$result = addcslashes( $result, '%_' );
		}
		return $result;
		*/
        return $text;
	}

	/**
	 * Execute the query
	 *
	 * @access	public
	 * @return mixed A database resource if successful, FALSE if not.
	 */
	public function query()
	{
		if (!is_resource($this->_resource)) {
			return false;
		}

		if ($this->_limit > 0 || $this->_offset > 0) {
			$this->_sql = "SELECT joomla2.*
            FROM (
                SELECT ROWNUM AS joomla_db_rownum, joomla1.*
                FROM (
                    " . $this->_sql . "
                ) joomla1
            ) joomla2
            WHERE joomla2.joomla_db_rownum BETWEEN " . ($this->_offset+1) . " AND " . ($this->_offset+$this->_limit);
            $this->setQuery($this->_sql);
            $this->bindVars();            
		}
		if ($this->_debug) {
			$this->_ticker++;
			$this->_log[] = $this->_sql;
		}
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		$this->_cursor = oci_execute( $this->_prepared );
        
		if (!$this->_cursor)
		{
			$error = oci_error( $this->_prepared );
			$this->_errorNum = $error['code'];
			$this->_errorMsg = $error['message']." SQL=$this->_sql";

			if ($this->_debug) {
				JError::raiseError(500, 'JDatabaseOracle::query: '.$this->_errorNum.' - '.$this->_errorMsg );
			}
			return false;
		}
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_affectedRows = oci_num_rows( $this->_prepared );
		return $this->_prepared;
	}

	/**
	 * Sets the SQL query string for later execution.
	 *
	 * This function replaces a string identifier <var>$prefix</var> with the
	 * string held is the <var>_table_prefix</var> class variable.
	 *
	 * @access public
	 * @param string The SQL query
	 * @param string The offset to start selection
	 * @param string The number of results to return
	 * @param string The common table prefix
	 */
	public function setQuery( $sql, $offset = 0, $limit = 0, $prefix='#__' )
	{
		$this->_sql		= $this->replacePrefix( $sql, $prefix );
		$this->_prepared= oci_parse($this->_resource, $this->_sql);
		$this->_limit	= (int) $limit;
		$this->_offset	= (int) $offset;
	}

    /**
     * Adds a variable array to the bounded associative array.
     *
     * This method adds a new value to the bounded associative array 
     * using the placeholder variable as the key.
     *
     * @access public
     * @param string The Oracle placeholder in your SQL query
     * @param string The PHP variable you want to bind the placeholder to
     */
    public function setVar( $placeholder, &$var, $maxlength=-1, $type=SQLT_CHR )
    {
        $this->_bounded[$placeholder] = array($var, (int)$maxlength, (int)$type);
    }
    
    /**
     * Binds all variables in the bounded associative array
     *
     * This method uses oci_bind_by_name to bind all entries in the bounded associative array. 
     *
     * @access public
     * @return boolean
     */
    public function bindVars()
    {
        if ($this->_bounded)
        {
            foreach($this->_bounded as $placeholder => $params)
            {
                $variable =& $params[0];
                $maxlength = $params[1];
                $type = $params[2];
                if(!oci_bind_by_name($this->_prepared, $placeholder, $variable, $maxlength, $type))
                {
                    $error = oci_error( $this->_prepared );
                    $this->_errorNum = $error['code'];
                    $this->_errorMsg = $error['message']." BINDVARS=$placeholder, $variable, $maxlength, $type";

                    if ($this->_debug) 
                    {
                        JError::raiseError(500, 'JDatabaseOracle::query: '.$this->_errorNum.' - '.$this->_errorMsg );
                    }
                    return false;        
                }
            }
        }
        
        // Reset the bounded variable for subsequent queries
        $this->_bounded = '';
        return true;
    }
    
    public function defineVar($placeholder, &$variable, $type=SQLT_CHR)
    {
        if(!oci_define_by_name($this->_prepared, $placeholder, $variable, $type))
        {
            $error = oci_error( $this->_prepared );
            $this->_errorNum = $error['code'];
            $this->_errorMsg = $error['message']." DEFINEVAR=$placeholder, $variable, $type";

            if ($this->_debug) 
            {
                JError::raiseError(500, 'JDatabaseOracle::query: '.$this->_errorNum.' - '.$this->_errorMsg );
            }
            return false;        
        }    
        
        return true;
    }
    
    /**
    * Sets the Oracle Date Format for the session
    * Default date format for Oracle is = DD-MON-RR
    * The default date format for this driver is:
    * 'RRRR-MM-DD HH24:MI:SS' since it is the format
    * that matches the MySQL one used within most Joomla
    * tables.
    * 
    * @param mixed $dateformat
    */
    public function setDateFormat($dateformat='DD-MON-RR')
    {
        $this->setQuery("alter session set nls_date_format = '$dateformat'");
        if (!$this->query()) {
            return false;
        }
        $this->_dateformat = $dateformat;
        return true;
    }
    
    /**
    * Returns the current date format
    * This method should be useful in the case that
    * somebody actually wants to use a different
    * date format and needs to check what the current
    * one is to see if it needs to be changed.
    * 
    */
    public function getDateFormat()
    {
        /*
        $this->setQuery("select value from nls_database_parameters where parameter = 'NLS_DATE_FORMAT'");
        return $this->loadResult();
        */
        // Commented out the above since it will always return the default, 
        // rather than current date format.
        return $this->_dateformat;
    }
    
    /**
    * Sets the Oracle Charset for the session
    * Default date format for Oracle is = DD-MON-RR
    * The default date format for this driver is:
    * 'RRRR-MM-DD HH24:MI:SS' since it is the format
    * that matches the MySQL one used within most Joomla
    * tables.
    * 
    * @param mixed $dateformat
    */
    public function setCharset($charset='AL32UTF8')
    {
        /* Doesn't really work right now
        $this->setQuery("alter session set nls_characterset = '$charset'");
        if (!$this->query()) {
            return false;
        }
        $this->_charset;
        */
        return true;
    }
    
    /**
    * Returns the current character set
    * This method should be useful in the case that
    * somebody actually wants to use a different
    * character set and needs to check what the current
    * one is to see if it needs to be changed.
    * 
    */
    public function getCharset()
    {
        /*
        $this->setQuery("select value from nls_database_parameters where parameter = 'NLS_CHARACTERSET'");
        return $this->loadResult();
        */
        // Commented out the above since it will always return the default, 
        // rather than current character set.
        return $this->_charset;
    }
    
    /**
    * Creates a new descriptor object for use in setVar, setDefine
    * above.
    * 
    * @param mixed $type
    * @return OCI-Lob
    */
    public function createDescriptor($type)
    {
        if ($type == OCI_D_FILE || $type == OCI_D_LOB || $type == OCI_D_ROWID)
        {
            return oci_new_descriptor($this->_resource, $type);
        }
        return false;
    }
    
	/**
	 * This function replaces a string identifier <var>$prefix</var> with the
	 * string held is the <var>_table_prefix</var> class variable.
	 *
	 * @access public
	 * @param string The SQL query
	 * @param string The common table prefix
	 */
	public function replacePrefix( $sql, $prefix='#__' )
	{
		$sql = trim( $sql );

		$escaped = false;
		$quoteChar = '';

		$n = strlen( $sql );

		$startPos = 0;
		$literal = '';
		while ($startPos < $n) {
			$ip = strpos($sql, $prefix, $startPos);
			if ($ip === false) {
				break;
			}

			$j = strpos( $sql, "'", $startPos );
			$k = strpos( $sql, '"', $startPos );
			if (($k !== FALSE) && (($k < $j) || ($j === FALSE))) {
				$quoteChar	= '"';
				$j			= $k;
			} else {
				$quoteChar	= "'";
			}

			if ($j === false) {
				$j = $n;
			}

			$literal .= str_replace( $prefix, $this->_table_prefix,substr( $sql, $startPos, $j - $startPos ) );
			$startPos = $j;

			$j = $startPos + 1;

			if ($j >= $n) {
				break;
			}

			// quote comes first, find end of quote
			while (TRUE) {
				$k = strpos( $sql, $quoteChar, $j );
				$escaped = false;
				if ($k === false) {
					break;
				}
				$l = $k - 1;
				while ($l >= 0 && $sql{$l} == '\\') {
					$l--;
					$escaped = !$escaped;
				}
				if ($escaped) {
					$j	= $k+1;
					continue;
				}
				break;
			}
			if ($k === FALSE) {
				// error in the query - no end quote; ignore it
				break;
			}
			$literal .= substr( $sql, $startPos, $k - $startPos + 1 );
			$startPos = $k+1;
		}
		if ($startPos < $n) {
			$literal .= substr( $sql, $startPos, $n - $startPos );
		}
		return $literal;
	}

	/**
	 * Get the active query
	 *
	 * @access public
	 * @return string The current value of the internal SQL variable
	 */
	public function getPreparedQuery()
	{
		return $this->_prepared;
	}
    
    /**
     * Get the bounded associative array
     *
     * @access public
     * @return string The current value of the internal SQL variable
     */
    public function getBindVars()
    {
        return $this->_bounded;
    }

	/**
	 * Gets the number of affected rows from
     * the previous INSERT, UPDATE, DELETE, etc.
     * operation.
	 *
	 * @access	public
	 * @return int The number of affected rows in the previous operation
	 * @since 1.0.5
	 */
	public function getAffectedRows()
	{
		return $this->_affectedRows;
	}

	/**
	 * Execute a batch query. For Oracle support
     * has not been added for batch queries that 
     * also require parameters to be bound.
	 *
	 * @access	public
	 * @return  boolean TRUE if successful, FALSE if not.
	 */
	public function queryBatch( $abort_on_error=true, $p_transaction_safe = false)
	{
		$this->_errorNum = 0;
		$this->_errorMsg = '';
		if ($p_transaction_safe) {
			$this->_sql = rtrim($this->_sql, '; \t\r\n\0');
			$si = $this->getVersion();
			preg_match_all( "/(\d+)\.(\d+)\.(\d+)/i", $si, $m );
			if ($m[1] >= 4) {
				$this->_sql = 'START TRANSACTION;' . $this->_sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 19) {
				$this->_sql = 'BEGIN WORK;' . $this->_sql . '; COMMIT;';
			} else if ($m[2] >= 23 && $m[3] >= 17) {
				$this->_sql = 'BEGIN;' . $this->_sql . '; COMMIT;';
			}
		}
		$query_split = $this->splitSql($this->_sql);
		$error = 0;
		foreach ($query_split as $command_line) {
			$command_line = trim( $command_line );
			if ($command_line != '') {
                $this->setQuery($command_line);
                $this->query();
				if (!$this->_cursor) {
					$error = 1;
					$this->_errorNum .= oci_error( $this->_resource ) . ' ';
					$this->_errorMsg .= " SQL=$command_line <br />";
					if ($abort_on_error) {
						return $this->_cursor;
					}
				}
			}
		}
		return $error ? false : true;
	}

	/**
	 * Diagnostic function.
     * Checks USER_TABLES first to see if the
     * user already has a table named PLAN_TABLES
     * created. If not, it is created and then
     * the EXPLAIN query is run and the results
     * retrieved from PLAN_TABLE and then deleted.
	 *
	 * @access	public
	 * @return	string
	 */
	public function explain()
	{
		$temp = $this->_sql;
        
        $this->setQuery("SELECT TABLE_NAME
                         FROM USER_TABLES
                         WHERE USER_TABLES.TABLE_NAME = 'PLAN_TABLE'");
        
        // If result then that means the plan_table exists
        $result = $this->loadResult();
        
        if (!$result)
        {
            $this->setQuery('CREATE TABLE "PLAN_TABLE" (
                                          "STATEMENT_ID"  VARCHAR2(30),
                                          "TIMESTAMP"  DATE,
                                          "REMARKS"  VARCHAR2(80),
                                          "OPERATION"  VARCHAR2(30),
                                          "OPTIONS"  VARCHAR2(30),
                                          "OBJECT_NODE"  VARCHAR2(128),
                                          "OBJECT_OWNER"  VARCHAR2(30),
                                          "OBJECT_NAME"  VARCHAR2(30),
                                          "OBJECT_INSTANCE"  NUMBER(22),
                                          "OBJECT_TYPE"  VARCHAR2(30),
                                          "OPTIMIZER"  VARCHAR2(255),
                                          "SEARCH_COLUMNS"  NUMBER(22),
                                          "ID"  NUMBER(22),
                                          "PARENT_ID"  NUMBER(22),
                                          "POSITION"  NUMBER(22),
                                          "COST"  NUMBER(22),
                                          "CARDINALITY"  NUMBER(22),
                                          "BYTES"  NUMBER(22),
                                          "OTHER_TAG"  VARCHAR2(255),
                                          "OTHER"  LONG)'
                           );
            if (!($cur = $this->query())) {
                return null;
            }
        }
        
        
		$this->_sql = "EXPLAIN PLAN FOR $temp";
        $this->setQuery($this->_sql);
        
        // This will add the results of the EXPLAIN PLAN
        // into the PLAN_TABLE
		if (!($cur = $this->query())) {
			return null;
		}
        
		$first = true;

		$buffer = '<table id="explain-sql">';
		$buffer .= '<thead><tr><td colspan="99">'.$this->getQuery().'</td></tr>';
        
        // SELECT rows that were just added to the PLAN_TABLE 
        $this->setQuery("SELECT * FROM PLAN_TABLE");
        if (!($cur = $this->query())) {
            return null;
        }
        
		while ($row = oci_fetch_assoc( $cur )) {
			if ($first) {
				$buffer .= '<tr>';
				foreach ($row as $k=>$v) {
                    if ($k == 'STATEMENT_ID' || $k == 'REMARKS' || $k == 'OTHER_TAG' || $k == 'OTHER') {
                        continue;
                    }
					$buffer .= '<th>'.$k.'</th>';
				}
				$buffer .= '</tr>';
				$first = false;
			}
			$buffer .= '</thead><tbody><tr>';
			foreach ($row as $k=>$v) {
                if ($k == 'STATEMENT_ID' || $k == 'REMARKS' || $k == 'OTHER_TAG' || $k == 'OTHER') {
                    continue;
                }
				$buffer .= '<td>'.$v.'</td>';
			}
			$buffer .= '</tr>';
		}
		$buffer .= '</tbody></table>';
        
        $this->setQuery("DELETE PLAN_TABLE");
        
        if (!($cur = $this->query())) {
            return null;
        }
		oci_free_statement( $cur );

		$this->_sql = $temp;
        $this->setQuery($this->_sql);

		return $buffer;
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return int The number of rows returned from the most recent query.
	 */
	// TODO Check validity of this method, I don't feel it is the correct way to do it
	public function getNumRows( $cur=null )
	{
		return $this->_numRows;
	}

	/**
	 * This method loads the first field of the first row returned by the query.
	 *
	 * @access	public
	 * @return The value returned in the query or null if the query failed.
	 */
	public function loadResult()
	{
		if (!($cur = $this->query())) {
			return null;
		}
        
        $mode = $this->getMode(true);
        
		$ret = null;
		if ($row = oci_fetch_array( $cur, $mode )) {
			$ret = $row[0];
		}
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_numRows = oci_num_rows( $this->_prepared );
		oci_free_statement( $cur );
		return $ret;
	}

	/**
	 * Load an array of single field results into an array
	 *
	 * @access	public
	 */
	public function loadResultArray($numinarray = 0)
	{
		if (!($cur = $this->query())) {
			return null;
		}
        
        $mode = $this->getMode(true);
        
		$array = array();
		while ($row = oci_fetch_array( $cur, $mode )) {
			$array[] = $row[$numinarray];
		}
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_numRows = oci_num_rows( $this->_prepared );
		oci_free_statement( $cur );
		return $array;
	}

	/**
	* Fetch a result row as an associative array
	*
	* @access	public
	* @return array
	*/
	public function loadAssoc()
	{
        $tolower = $this->_tolower;
		if (!($cur = $this->query())) {
			return null;
		}
        
        $mode = $this->getMode();
        
		$ret = null;
		if ($array = oci_fetch_array( $cur, $mode )) {
            if ($tolower) {
                foreach($array as $field => $value) {
                    $lowercase = strtolower($field);
                    $array[$lowercase] = $value;
                    unset($array[$field]);
                }
            }
            
			$ret = $array;
		}
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_numRows = oci_num_rows( $this->_prepared );
		oci_free_statement( $cur );
		return $ret;
	}

	/**
	* Load a assoc list of database rows
	*
	* @access	public
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	*/
	public function loadAssocList($key='')
	{
        $tolower = $this->_tolower;
		if (!($cur = $this->query())) {
			return null;
		}
        
        $mode = $this->getMode();
        
		$array = array();
		while ($row = oci_fetch_array( $cur, $mode )) {
            
            if ($tolower) {
                foreach($row as $field => $value) {
                    $lowercase = strtolower($field);
                    $row[$lowercase] = $value;
                    unset($row[$field]);
                }
            }
            
			if ($key) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_numRows = oci_num_rows( $this->_prepared );
		oci_free_statement( $cur );
		return $array;
	}

	/**
	* This global function loads the first row of a query into an object
	*
	* @access	public
	* @return 	object
	*/
	public function loadObject()
	{
        $tolower = $this->_tolower;
        $returnlobs = $this->_returnlobs;
		if (!($cur = $this->query())) {
			return null;
		}
		$ret = null;
		if ($object = oci_fetch_object( $cur )) {
		    if ($returnlobs) {
                foreach($object as $field => $value) {
                    if (get_class($value) == 'OCI-Lob') {
                        $object->$field = $value->load();
                    }
                }
            }
            if ($tolower) {
                $obj = new stdClass();
                foreach($object as $field => $value) {
                    $lowercase = strtolower($field);
                    //$uppercase = strtoupper($field);
                    $obj->$lowercase = $value;
                    unset($object->$field);
                }
                unset($value);
                unset($object);
                $object = &$obj;
            }
        	$ret = $object;
		}
        
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_numRows = oci_num_rows( $this->_prepared );
		oci_free_statement( $cur );
		return $ret;
	}

	/**
	* Load a list of database objects
	*
	* If <var>key</var> is not empty then the returned array is indexed by the value
	* the database key.  Returns <var>null</var> if the query fails.
	*
	* @access	public
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	*/
	public function loadObjectList($key='')
	{
        $tolower = $this->_tolower;
        $returnlobs = $this->_returnlobs;
		if (!($cur = $this->query())) {
			return null;
		}
		$array = array();
		while ($row = oci_fetch_object( $cur )) {
                     
            if ($returnlobs) {
                foreach($row as $field => $value) {
                    if (get_class($value) == 'OCI-Lob') {
                        $row->$field = $value->load();
                    }
                }
            }
            
            if ($tolower) {
                $obj = new stdClass();
                foreach($row as $field => $value) {
                    $lowercase = strtolower($field);
                    $obj->$lowercase = $value;
                    unset($row->$field);
                }
                unset($value);
                unset($row);
            }
            
			if ($key) {
                if ($tolower) {
                    $lowercase = strtolower($key);
                    $array[$obj->$lowercase] = $obj;
                } else {
                    $array[$row->$key] = $row;
                }
				
			} else {
                if ($tolower) {
                    $array[] = $obj;
                } else {
                    $array[] = $row;
                }
			}
		}
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_numRows = oci_num_rows( $this->_prepared );
		oci_free_statement( $cur );
		return $array;
	}

	/**
	 * Description
	 *
	 * @access	public
	 * @return The first row of the query.
	 */
	public function loadRow()
	{
		if (!($cur = $this->query())) {
			return null;
		}
        
        $mode = $this->getMode(true);
        
		$ret = null;
		if ($row = oci_fetch_array( $cur, $mode )) {
			$ret = $row;
		}
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_numRows = oci_num_rows( $this->_prepared );
		oci_free_statement( $cur );
		return $ret;
	}

	/**
	* Load a list of database rows (numeric column indexing)
	*
	* @access public
	* @param string The field name of a primary key
	* @return array If <var>key</var> is empty as sequential list of returned records.
	* If <var>key</var> is not empty then the returned array is indexed by the value
	* the database key.  Returns <var>null</var> if the query fails.
	*/
	public function loadRowList($key=null)
	{
		if (!($cur = $this->query())) {
			return null;
		}
        
        $mode = $this->getMode(true);
        
		$array = array();
		while ($row = oci_fetch_array($cur, $mode)) {
			if ($key !== null) {
				$array[$row[$key]] = $row;
			} else {
				$array[] = $row;
			}
		}
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_numRows = oci_num_rows($this->_prepared);
		oci_free_statement($cur);
		return $array;
	}
    
    /**
     * Load the next row returned by the query.
     *
     * @return    mixed    The result of the query as an array, false if there are no more rows, or null on an error.
     *
     * @since    1.6.0
     */
    public function loadNextRow()
    {
        static $cur;
       
        if (is_null($cur)) {
            if (!($cur = $this->query())) {
                return null;
            }    
        }
        
        $mode = $this->getMode(true);
        
        if ($row = oci_fetch_array($cur, $mode)) {
            return $row;
        }
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_numRows = oci_num_rows($this->_prepared);
        oci_free_statement($cur);
        $cur = null;

        return false;
    }
    
    /**
     * Load the next row returned by the query.
     *
     * @return    mixed    The result of the query as an array, false if there are no more rows, or null on an error.
     *
     * @since    1.6.0
     */
    public function loadNextAssoc()
    {
        static $cur;
       
        if (is_null($cur)) {
            if (!($cur = $this->query())) {
                return null;
            }    
        }
        
        $mode = $this->getMode();
        $tolower = $this->_tolower;
        
        if ($array = oci_fetch_array($cur, $mode)) {
            if ($tolower) {
                foreach($array as $field => $value) {
                    $lowercase = strtolower($field);
                    $array[$lowercase] = $value;
                    unset($array[$field]);
                }
            }
            return $array;
        }
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_numRows = oci_num_rows($this->_prepared);
        oci_free_statement($cur);
        $cur = null;

        return false;
    }

    /**
     * Load the next row returned by the query.
     *
     * @return    mixed    The result of the query as an object, false if there are no more rows, or null on an error.
     *
     * @since    1.6.0
     */
    public function loadNextObject()
    {
        static $cur;
        
        $tolower = $this->_tolower;
        $returnlobs = $this->_returnlobs;
        if (is_null($cur)) {
            if (!($cur = $this->query())) {
                return null;
            }    
        }

        if ($object = oci_fetch_object($cur)) {
            if ($returnlobs) {
                foreach($object as $field => $value) {
                    if (get_class($value) == 'OCI-Lob') {
                        $object->$field = $value->load();
                    }
                }
            }
            if ($tolower) {
                $obj = new stdClass();
                foreach($object as $field => $value) {
                    $lowercase = strtolower($field);
                    //$uppercase = strtoupper($field);
                    $obj->$lowercase = $value;
                    unset($object->$field);
                }
                unset($value);
                unset($object);
                $object = &$obj;
            }
            return $object;
        }
        
        //Updates the affectedRows variable with the number of rows returned by the query
        $this->_numRows = oci_num_rows( $this->_prepared );
        oci_free_statement( $cur );
        $cur = null;

        return false;
    }

	/**
	 * Inserts a row into a table based on an objects properties
	 *
	 * @access	public
	 * @param	string	The name of the table
	 * @param	object	An object whose properties match table fields
	 * @param	string	The name of the primary key. If provided the object property is updated.
	 */
	public function insertObject( $table, &$object, $keyName = NULL )
	{
		$fmtsql = "INSERT INTO $table ( %s ) VALUES ( %s ) ";
		$fields = array();
        $values = array();
		foreach (get_object_vars( $object ) as $k => $v) {
			if (is_array($v) or is_object($v) or $v === NULL) {
				continue;
			}
			if ($k[0] == '_') { // internal field
				continue;
			}
            
			$fields[] = $k;
            
            if ( $k == $keyName ) { 
                $values[] = $this->nextinsertid($table);
            } else {
                $values[] = $this->Quote($v);
            }
		}
        // Next two lines for debugging generated SQL statement
        //$query = sprintf( $fmtsql, implode( ",", $fields ) ,  implode( ",", $values ) );
        //return $query;
		$this->setQuery( sprintf( $fmtsql, implode( ",", $fields ) ,  implode( ",", $values ) ) );
		if (!$this->query()) {
			return false;
		}
		return true;
	}

	/**
    * Updates a row in a table based on an objects properties.
    * 
    * @param mixed $table
    * @param mixed $object
    * @param mixed $keyName
    * @param mixed $updateNulls
    */
	public function updateObject( $table, &$object, $keyName, $updateNulls=true )
	{
		$fmtsql = "UPDATE $table SET %s WHERE %s";
		$tmp = array();
		foreach (get_object_vars( $object ) as $k => $v)
		{
			if( is_array($v) or is_object($v) or $k[0] == '_' ) { // internal or NA field
				continue;
			}
			if( $k == $keyName ) { // PK not to be updated
				$where = $keyName . '=' . $this->Quote( $v );
				continue;
			}
			if ($v === null)
			{
				if ($updateNulls) {
					$val = 'NULL';
				} else {
					continue;
				}
			} else {
				$val = $this->isQuoted( $k ) ? $this->Quote( $v ) : (int) $v;
			}
			$tmp[] = $k . '=' . $val;
		}
		$this->setQuery( sprintf( $fmtsql, implode( ",", $tmp ) , $where ) );
        if (!$this->query()) {
            return false;
        }
		return true;
	}

    /**
    * Returns the latest sequence value for
    * a table
    * 
    * @param mixed $tableName
    * @param mixed $primaryKey
    * @return string
    */
	public function insertid($tableName = null, $primaryKey = null)
	{
        if ($tableName !== null) {
            $sequenceName = $tableName;
            if ($primaryKey) {
                $sequenceName .= "_$primaryKey";
            }
            $sequenceName .= '_SEQ';
            return $this->lastSequenceId($sequenceName);
        }
        // No support for IDENTITY columns; return null
        return null;
	}
    
    /**
     * Return the most recent value from the specified sequence in the database.
     * This is supported only on RDBMS brands that support sequences
     * (e.g. Oracle, PostgreSQL, DB2).  Other RDBMS brands return null.
     *
     * @param string $sequenceName
     * @return string
     */
    public function lastSequenceId($sequenceName)
    {
        $this->_sql = 'SELECT '.$sequenceName.'.CURRVAL FROM dual';
        $this->setQuery($this->_sql);
        $value = $this->loadResult();
        return $value;
    }
    
    /**
    * Returns the next sequence value for
    * a table
    * 
    * @param mixed $tableName
    * @param mixed $primaryKey
    * @return string
    */
    public function nextInsertId($tableName = null, $primaryKey = null)
    {
        if ($tableName !== null) {
            $sequenceName = $tableName;
            if ($primaryKey) {
                $sequenceName .= "_$primaryKey";
            }
            $sequenceName .= '_SEQ';
            return $this->nextSequenceId($sequenceName);
        }
        // No support for IDENTITY columns; return null
        return null;
    }
    
    /**
     * Generate a new value from the specified sequence in the database, and return it.
     * This is supported only on RDBMS brands that support sequences
     * (e.g. Oracle, PostgreSQL, DB2).  Other RDBMS brands return null.
     *
     * @param string $sequenceName
     * @return string
     */
    public function nextSequenceId($sequenceName)
    {
        $this->_sql = 'SELECT '.$sequenceName.'.NEXTVAL FROM dual';
        $this->setQuery($this->_sql);
        $value = $this->loadResult();
        return $value;
    }
    
	/**
	 * Returns the Oracle version number
	 *
	 * @access public
	 */
	public function getVersion()
	{
        /*
        $server_sentence = oci_server_version( $this->_resource );
        $server_sentence = explode(' ', $server_sentence);
        foreach($server_sentence as $word)
        {
            if (is_numeric($word[0]) && strlen($word) > 3)
            {
                $server_version = $word;
            }
        }
        return $server_version;
        */
        
        $this->setQuery("select value from nls_database_parameters where parameter = 'NLS_RDBMS_VERSION'");
        return $this->loadResult();
        
	}

	/**
	 * Assumes database collation in use by the value
     * of the NLS_CHARACTERSET parameter
	 *
	 * @access	public
	 * @return string Collation in use
	 */
	public function getCollation()
	{
		return $this->getCharset();
	}

	/**
	 * Gets list of all table_names
     * for current user
	 *
	 * @access	public
	 * @return array A list of all the tables in the database
	 */
	// TODO Check is this is valid for Oracle DB
	// Visit this link for later review http://forums.devshed.com/oracle-development-96/show-tables-in-oracle-135613.html
	public function getTableList()
	{
        $this->_sql = 'SELECT table_name FROM all_tables';
        $this->setQuery($this->_sql);
        return $this->loadResultArray();
	}

	/**
	 * Shows the CREATE TABLE statement that creates the given tables
	 *
	 * @access	public
	 * @param 	array|string 	A table name or a list of table names
	 * @return 	array A list the create SQL for the tables
	 */
	public function getTableCreate( $tables )
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval) {
			$this->setQuery( "select dbms_metadata.get_ddl('TABLE', '".$tblval."') from dual");
			$statement = $this->loadResult();
			$result[$tblval] = $statement;
		}

		return $result;
	}

	/**
	 * Retrieves information about the given tables
	 *
	 * @access	public
	 * @param 	array|string 	A table name or a list of table names
	 * @param	boolean			Only return field types, default true
	 * @return	array An array of fields by table
	 */
	public function getTableFields( $tables, $typeonly = true )
	{
		settype($tables, 'array'); //force to array
		$result = array();

		foreach ($tables as $tblval)
		{
            $tblval = strtoupper($tblval);
			$this->setQuery( "SELECT *
                              FROM ALL_TAB_COLUMNS
                              WHERE table_name = '".$tblval."'");
			$fields = $this->loadObjectList('', false);
            
			if($typeonly)
			{
				foreach ($fields as $field) {
					$result[$tblval][$field->COLUMN_NAME] = preg_replace("/[(0-9)]/",'', $field->DATA_TYPE );
				}
			}
			else
			{
				foreach ($fields as $field) {
					$result[$tblval][$field->COLUMN_NAME] = $field;
				}
			}
		}

		return $result;
	}
    
    /**
    * Sets the $_tolower variable to true
    * so that field names will be created
    * using lowercase values.
    * 
    * @return void
    */
    public function toLower()
    {
        $this->_tolower = true;
    }
    
    /**
    * Sets the $_tolower variable to false
    * so that field names will be created
    * using uppercase values.
    * 
    * @return void
    */
    public function toUpper()
    {
        $this->_tolower = false;
    }
    
    /**
    * Sets the $_returnlobs variable to true
    * so that LOB object values will be 
    * returned rather than an OCI-Lob Object.
    * 
    * @return void
    */
    public function returnLobValues()
    {
        $this->_returnlobs = true;
    }
    
    /**
    * Sets the $_returnlobs variable to false
    * so that OCI-Lob Objects will be returned.
    * 
    * @return void
    */
    public function returnLobObjects()
    {
        $this->_returnlobs = false;
    }
    
    /**
    * Depending on the value for _returnlobs,
    * this method returns the proper constant
    * combinations to be passed to the oci* functions
    * 
    * @return int
    */
    public function getMode($numeric = false)
    {
        if ($numeric === false) {
            if ($this->_returnlobs) {
                $mode = OCI_ASSOC+OCI_RETURN_NULLS+OCI_RETURN_LOBS;
            }
            else {
                $mode = OCI_ASSOC+OCI_RETURN_NULLS;
            }    
        } else {
            if ($this->_returnlobs) {
                $mode = OCI_NUM+OCI_RETURN_NULLS+OCI_RETURN_LOBS;
            }
            else {
                $mode = OCI_NUM+OCI_RETURN_NULLS;
            }            
        }

        return $mode;
    }
}