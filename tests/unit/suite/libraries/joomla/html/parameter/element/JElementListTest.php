<?php
require_once 'PHPUnit/Framework.php';

require_once JPATH_BASE. DS . 'libraries' . DS . 'joomla' . DS . 'html' . DS . 'parameter' . DS . 'element' . DS . 'list.php';

/**
 * Test class for JElementList.
 * Generated by PHPUnit on 2009-10-27 at 16:19:33.
 */
class JElementListTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var JElementList
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->object = new JElementList;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @todo Implement testFetchElement().
     */
    public function testFetchElement()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
          'This test has not been implemented yet.'
        );
    }
}
?>
