<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once JPATH_BASE.'/libraries/joomla/application/component/controller.php';

/**
 * Test class for JController.
 * Generated by PHPUnit on 2009-10-08 at 21:18:27.
 */
class JControllerTest extends PHPUnit_Framework_TestCase
{
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 */
	protected function setUp()
	{
		if (!defined('JPATH_COMPONENT')) {
			define('JPATH_COMPONENT', JPATH_BASE.'/components/com_foobar');
		}

		include_once 'JControllerHelper.php';
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 */
	protected function tearDown()
	{
	}

	/**
	 * Test JController::__construct
	 *
	 * @since	1.6
	 */
	public function test__construct()
	{
		$controller = new TestTestController;
		$this->assertThat(
			$controller->getTasks(),
			$this->equalTo(
				array(
					'task5', 'task1', 'task2', 'display'
				)
			),
			'Line:'.__LINE__.' The available tasks should be the public tasks in _all_ the derived classes after controller plus "display".'
		);
	}

	/**
	 * Test JController::addModelPath
	 *
	 * @since	1.6
	 */
	public function testAddModelPath()
	{
		// Include JModel as this method is a proxy for JModel::addIncludePath
		require_once JPATH_BASE.'/libraries/joomla/application/component/model.php';

		$path = dirname(__FILE__).DS.'addmodelpath';
		JController::addModelPath($path);

		// The default path is the class file folder/forms
		$valid = JPATH_LIBRARIES.DS.'joomla'.DS.'form/fields';

		$this->assertThat(
			in_array($path, JModel::addIncludePath()),
			$this->isTrue(),
			'Line:'.__LINE__.' The path should be added to the JModel paths.'
		);
	}

	/**
	 * Test JController::addPath
	 *
	 * Note that addPath call JPath::check which will exit if the path is out of bounds.
	 * If execution halts for some reason, a bad path could be the culprit.
	 *
	 * @since	1.6
	 */
	public function testAddPath()
	{
		$controller = new JControllerInspector;

		$path = dirname(__FILE__).'//foobar';
		$controller->addPath('test', $path);
		$paths = $controller->getPaths();

		$this->assertThat(
			isset($paths['test']),
			$this->isTrue(),
			'Line:'.__LINE__.' The path type should be set.'
		);

		$this->assertThat(
			is_array($paths['test']),
			$this->isTrue(),
			'Line:'.__LINE__.' The path type should be an array.'
		);

		$this->assertThat(
			$paths['test'][0],
			$this->equalTo(dirname(__FILE__).DS.'foobar/'),
			'Line:'.__LINE__.' The path type should be present, clean and with a trailing slash.'
		);
	}

	/**
	 * Test JController::addViewPath
	 */
	public function testAddViewPath()
	{
		$controller = new JControllerInspector;

		$path = dirname(__FILE__).'/views';
		$controller->addViewPath($path);
		$paths = $controller->getPaths();

		$this->assertThat(
			isset($paths['view']),
			$this->isTrue(),
			'Line:'.__LINE__.' The path type should be set.'
		);

		$this->assertThat(
			is_array($paths['view']),
			$this->isTrue(),
			'Line:'.__LINE__.' The path type should be an array.'
		);

		$this->assertThat(
			$paths['view'][0],
			$this->equalTo(dirname(__FILE__).DS.'views/'),
			'Line:'.__LINE__.' The path type should be present, clean and with a trailing slash.'
		);
	}

	/**
	 * Test JController::authorize
	 */
	public function testAuthorize()
	{
		$this->markTestSkipped('This method is depracated.');
	}

	/**
	 * Test JController::createModel
	 */
	public function testCreateModel()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::createView
	 */
	public function testCreateView()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::display
	 */
	public function testDisplay()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::execute
	 */
	public function testExecute()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::getInstance
	 */
	public function testGetInstance()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::getModel
	 */
	public function testGetModel()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::getName
	 */
	public function testGetName()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::getTask
	 */
	public function testGetTask()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::getTasks
	 */
	public function testGetTasks()
	{
		$controller = new TestController;

		$this->assertThat(
			$controller->getTasks(),
			$this->equalTo(
				array(
					'task1', 'task2', 'display'
				)
			),
			'Line:'.__LINE__.' The available tasks should be the public tasks in the derived controller plus "display".'
		);
	}

	/**
	 * Test JController::getView
	 */
	public function testGetView()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::redirect
	 */
	public function testRedirect()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::registerDefaultTask
	 */
	public function testRegisterDefaultTask()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::registerTask
	 */
	public function testRegisterTask()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::setAccessControl
	 */
	public function testSetAccessControl()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::setMessage
	 */
	public function testSetMessage()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::setPath
	 */
	public function testSetPath()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * Test JController::setRedirect
	 */
	public function testSetRedirect()
	{
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}