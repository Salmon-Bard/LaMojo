<?php
require_once 'PHPUnit/Framework.php';

require_once JPATH_BASE. DS . 'libraries' . DS . 'joomla' . DS . 'html' . DS . 'parameter' . DS . 'element' . DS . 'menuitem.php';

/**
 * Test class for JElementMenuItem.
 * Generated by PHPUnit on 2009-10-27 at 16:50:41.
 */
class JElementMenuItemTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @var JElementMenuItem
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		$this->object = new JElementMenuItem;
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
