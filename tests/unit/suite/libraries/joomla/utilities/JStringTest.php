<?php
require_once 'PHPUnit/Framework.php';

require_once JPATH_BASE. DS . 'libraries' . DS . 'joomla' . DS . 'utilities' . DS . 'string.php';
require_once 'JString-helper-dataset.php';

/**
 * Test class for JString.
 * Generated by PHPUnit on 2009-10-26 at 22:29:34.
 */
class JStringTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var JString
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
//        $this->object = new JString;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

	static public function strposData() {
		return JStringTest_DataSet::$strposTests;
	}

	static public function strrposData() {
		return JStringTest_DataSet::$strrposTests;
	}

	static public function substrData() {
		return JStringTest_DataSet::$substrTests;
	}

	static public function strtolowerData() {
		return JStringTest_DataSet::$strtolowerTests;
	}

	static public function strtoupperData() {
		return JStringTest_DataSet::$strtoupperTests;
	}

	static public function strlenData() {
		return JStringTest_DataSet::$strlenTests;
	}

	/**
	 * @dataProvider strposData
	 */
    public function testStrpos($haystack, $needle, $offset = 0, $expect)
    {
		$actual = JString::strpos($haystack, $needle, $offset);
		$this->assertEquals($expect, $actual);
    }

	/**
	 * @dataProvider strrposData
	 */
    public function testStrrpos($haystack, $needle, $offset = 0, $expect)
    {
		$actual = JString::strrpos($haystack, $needle, $offset);
		$this->assertEquals($expect, $actual);
    }


	/**
	 * @dataProvider substrData
	 */
    public function testSubstr($string, $start, $length = false, $expect)
    {
		$actual = JString::substr($string, $start, $length);
		$this->assertEquals($expect, $actual);
    }

	/**
	 * @dataProvider strtolowerData
	 */
    public function testStrtolower($string, $expect)
    {
		$actual = JString::strtolower($string);
		$this->assertEquals($expect, $actual);
    }

    /**
	 * @dataProvider strtoupperData
     */
    public function testStrtoupper($string, $expect)
    {
		$actual = JString::strtoupper($string);
		$this->assertEquals($expect, $actual);
    }

	/**
	 * @dataProvider strlenData
	 */
    public function testStrlen($string, $expect)
    {
		$actual = JString::strlen($string);
		$this->assertEquals($expect, $actual);
    }

    /**
     * @todo Implement testStr_ireplace().
     */
    public function testStr_ireplace()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testStr_split().
     */
    public function testStr_split()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testStrcasecmp().
     */
    public function testStrcasecmp()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testStrcspn().
     */
    public function testStrcspn()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testStristr().
     */
    public function testStristr()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testStrrev().
     */
    public function testStrrev()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testStrspn().
     */
    public function testStrspn()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testSubstr_replace().
     */
    public function testSubstr_replace()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testLtrim().
     */
    public function testLtrim()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testRtrim().
     */
    public function testRtrim()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testTrim().
     */
    public function testTrim()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testUcfirst().
     */
    public function testUcfirst()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testUcwords().
     */
    public function testUcwords()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testUcwords_callback().
     */
    public function testUcwords_callback()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testTranscode().
     */
    public function testTranscode()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testValid().
     */
    public function testValid()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }

    /**
     * @todo Implement testCompliant().
     */
    public function testCompliant()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
?>
