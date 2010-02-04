<?php
/**
 * JSimpleXMLTest.php -- unit testing file for JSimpleXML
 *
 * @version		$Id$
 * @package	Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
require_once JPATH_BASE.'/tests/unit/JoomlaTestCase.php';
/**
 * Test class for JSimpleXML.
 * Generated by PHPUnit on 2009-10-26 at 22:30:14.
 *
 * @package	Joomla.UnitTest
 * @subpackage Utilities
 *
 */
class JSimpleXMLTest extends JoomlaTestCase
{
	/**
	 * @var JSimpleXML
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		include_once JPATH_BASE . '/libraries/joomla/utilities/simplexml.php';

		$this->saveErrorHandlers();
		$this->setErrorCallback('JSimpleXMLTest');
		JSimpleXMLTest::$actualError = array();

		$this->object = new JSimpleXML( array(XML_OPTION_SKIP_WHITE => true) );
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		$this->setErrorhandlers($this->savedErrorState);
	}

	/**
	 * Test cases for loadString
	 *
	 * @return array
	 *
	 */
	function casesLoadString()
	{
		return array(
			'simple' => array(
				'<one><two>fred</two></one>',
				true,
				array(),
			),
			'bad' => array(
				'<one><two>fred</one>',
				true,
				array(
					'code' => 'SOME_ERROR_CODE',
					'msg' => 'XML Parsing Error at 1:17. Error 76: Mismatched tag',
					'info' => '',
				),
			),
			'larger' => array(
				'<one><two>fred</two><three>fred<ten>Next</ten></three><four>fred</four></one>',
				true,
				array(),
			),
		);
	}
	/**
	 * Testing loadString().
	 *
	 * @param string  The XML document structure.
	 * @param boolean The expected return from the function.
	 * @param array   The error information array generated by the JError stub.
	 *
	 * @return void
	 * @dataProvider casesLoadString
	 */
	public function testLoadString( $xml, $expected, $error )
	{
		$this->assertThat(
			$this->object->loadString($xml),
			$this->equalTo($expected)
		);

		$this->assertThat(
			JSimpleXMLTest::$actualError,
			$this->equalTo($error)
		);
	}

	/**
	 * Test case for loadFile
	 *
	 * @return array
	 */
	function casesLoadFile()
	{
		return array(
			'good' => array(
				JPATH_BASE. '/unittest/stubs/xmlFile.xml',
				true,
				'JSimpleXMLElement',
			),
			'bad' => array(
				JPATH_BASE. '/unittest/stubs/fred.xml',
				false,
				null,
			),
			'empty' => array(
				JPATH_BASE. '/unittest/stubs/empty.xml',
				false,
				null,
			),
		);
	}
	/**
	 * Testing testLoadFile().
	 *
	 * @param string Path to xml
	 * @param bool   Result of load
	 * @param string Class of result
	 *
	 * @return void
	 * @dataProvider casesLoadFile
	 */
	public function testLoadFile( $path, $expected, $class )
	{
		$this->assertThat(
			is_null($this->object->document),
			$this->isTrue()
		);
		$this->assertThat(
			$this->object->loadFile($path),
			$this->equalTo($expected)
		);
		if (!is_null($class))
		{
			$this->assertThat(
				$this->object->document,
				$this->isInstanceOf($class)
			);
		}
		else
		{
			$this->assertThat(
				$this->object->document,
				$this->isNull()
			);
		}
	}

	/**
	 * Testing testImportDOM().
	 *
	 * This function was a puzzle to test, because it doesn't seem to have
	 * any reason at all for existing. In the end, I decided that since the
	 * actions of it were allegedly similar to loadString, we should use the same
	 * sort of testing approach should be used. But, since the code itself does
	 * nothing at the moment but return false, that would be all we test for
	 * and that way of the function is ever activated in the future, the test
	 * would flag it and new tests could be written.
	 *
	 * @param string  DOM string to parse
	 * @param boolean Function always seems to return false
	 * @param array   Error structure
	 *
	 * @return void
	 * @dataProvider casesLoadString
	 */
	public function testImportDOM( $xml, $expected, $error )
	{
		$this->assertThat(
			$this->object->importDOM($xml),
			$this->isFalse()
		);
	}

	/**
	 * Testing testGetParser().
	 *
	 * @return void
	 */
	public function testGetParser()
	{
		$this->assertThat(
			get_resource_type($this->object->getparser()),
			$this->equalTo('xml')
		);
	}

	/**
	 * Testing testSetParser().
	 *
	 * @return void
	 */
	public function testSetParser()
	{
		$parser = xml_parser_create('');
		$this->object->setParser($parser);
		$this->assertThat(
			$this->object->getparser(),
			$this->equalTo($parser)
		);
	}
}
?>
