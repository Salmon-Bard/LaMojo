<?php
/**
 * JAuthenticationTest.php -- unit testing file for JAuthentication
 *
 * @version		$Id: JAuthenticationTest.php 16638 2010-05-01 17:31:23Z hackwar $
 * @package	Joomla.UnitTest
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
require_once JPATH_BASE.'/tests/unit/JoomlaDatabaseTestCase.php';

/**
 * Test class for JAuthentication.
 * Generated by PHPUnit on 2009-10-26 at 22:45:21.
 *
 * @package	Joomla.UnitTest
 * @subpackage User
 */
class JAuthenticationTest extends JoomlaDatabaseTestCase
{
	/**
	 * @var JAuthentication
	 */
	protected $object;

	/**
	 * Receives the callback from JError and logs the required error information for the test.
	 *
	 * @param	JException	The JException object from JError
	 *
	 * @return	bool	To not continue with JError processing
	 */
	static function errorCallback( $error )
	{
		JAuthenticationTest::$actualError['code'] = $error->get('code');
		JAuthenticationTest::$actualError['msg'] = $error->get('message');
		JAuthenticationTest::$actualError['info'] = $error->get('info');
		return false;
	}

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @return void
	 */
	protected function setUp()
	{
		include_once JPATH_BASE . '/libraries/joomla/event/dispatcher.php';
		include_once JPATH_BASE . '/libraries/joomla/user/authentication.php';

		parent::setUp();

		$this->saveErrorHandlers();
		$this->setErrorCallback('JAuthenticationTest');
		JAuthenticationTest::$actualError = array();

		$this->saveFactoryState();
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @return void
	 */
	protected function tearDown()
	{
		$this->restoreFactoryState();
		$this->setErrorhandlers($this->savedErrorState);
	}

	/**
	 * Testing GetInstance().
	 *
	 * @return void
	 */
	public function testGetInstance()
	{
		include_once JPATH_BASE . '/libraries/joomla/plugin/helper.php';
		include_once JPATH_BASE . '/libraries/joomla/user/user.php';
		include_once JPATH_BASE . '/libraries/joomla/session/session.php';

		$user = new JUser;
		/**$mockSession = $this->getMock('JSession', array( '_start', 'get'));
		$mockSession->expects($this->any())->method('get')->with($this->equalTo('user'))->will(
			$this->returnValue($user)
		);**/

		$mockSession = new Mock_SessionJAuthenticate();
		$mockSession->set('user', $user);
		JFactory::$session = $mockSession;

		$instance1 = JAuthentication::getInstance();
/***
 *	These calls involving getError cause really bad things to happen in certain circumstances on some setups.
 *	Commenting them out until we can figure out what and why (or fix it so it doesn't happen)
		$error = JError::getError();
		$this->assertThat(
			$error,
			$this->equalTo(null)
		);
 */

		$instance2 = JAuthentication::getInstance();
		$error = JError::getError();

/***
 *	These calls involving getError cause really bad things to happen in certain circumstances on some setups.
 *	Commenting them out until we can figure out what and why (or fix it so it doesn't happen)
		$this->assertThat(
			$error,
			$this->equalTo(null)
		);
 */
		$this->assertThat(
			$instance1,
			$this->equalTo($instance2)
		);
	}

	/**
	 * Testing authenticate
	 *
	 * @return void
	 * @todo Implement testAuthenticate().
	 */
	public function testAuthenticate()
	{
		include_once JPATH_BASE . '/libraries/joomla/plugin/helper.php';
		include_once JPATH_BASE . '/libraries/joomla/user/user.php';
		include_once JPATH_BASE . '/libraries/joomla/session/session.php';

		$user = new JUser;
		/*
		 * The lines below are commented out because they cause an error, but I don't understand why
		 * they do, so I'm leaving them here in case it's a bug that is later fixed and they're needed.
		 */
		$mockSession = $this->getMock('JSession', array( '_start', 'get'));
		//$mockSession->expects($this->any())->method('get')->with($this->equalTo('user'))->will(
		//	$this->returnValue($user)
		//);
		JFactory::$session = $mockSession;

		$this->object = JAuthentication::getInstance();
		$tester = $this->getDatabaseTester();
		$tester->onSetUp();

		$credentials['username'] = 'admin';
		$credentials['password'] = 'testing';
		$options = array();
		$response = $this->object->authenticate($credentials, $options);

		$this->assertThat(
			true,
			$this->equalTo((bool)$response->status)
		);
	}

	/**
	 * Testing the response creation
	 *
	 * @return void
	 */
	public function testAuthenticationResponse()
	{
		$response = new JAuthenticationResponse;

		$this->assertThat(
			$response,
			$this->isInstanceOf('JAuthenticationResponse')
		);
	}
}

class Mock_SessionJAuthenticate extends JObject
{
	function getFormToken($data)
	{
		return (bool) $data;
	}
}
?>
