<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2011 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */
require_once JPATH_BASE.'/libraries/joomla/document/document.php';
require_once JPATH_BASE.'/libraries/joomla/document/json/json.php';

/**
 * Test class for JDocumentJSON.
 * Generated by PHPUnit on 2009-10-09 at 13:57:08.
 */
class JDocumentJSONTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var	JDocumentJSON
	 * @access protected
	 */
	protected $object;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp() {
		$this->object = new JDocumentJSON;
	}

	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown() {
	}

	/**
	 * @todo Implement testRender().
	 */
	public function testRender() {
		JResponse::clearHeaders();
		JResponse::allowCache(true);

		$this->object->setBuffer('Unit Test Buffer');

		$this->assertThat(
			$this->object->render(),
			$this->equalTo('Unit Test Buffer'),
			'We did not get the buffer back properly'
		);

		$headers = JResponse::getHeaders();

		$expires = false;
		$disposition = false;

		foreach($headers AS $head) {
			if ($head['name'] == 'Expires') {
				$this->assertThat(
					$head['value'],
					$this->stringContains('GMT'),
					'The expires header was not set properly (was parent::render called?)'
				);
				$expires = true;
			}
			if ($head['name'] == 'Content-disposition') {
				$this->assertThat(
					$head['value'],
					$this->stringContains('.json'),
					'The content disposition did not include json extension'
				);
				$disposition = true;
			}
		}
		$this->assertThat(
			JResponse::allowCache(),
			$this->isFalse(),
			'Caching was not disabled'
		);
	}

	/**
	 * This method does nothing.
	 */
	public function testGetHeadData() {
		$this->object->getHeadData();
	}

	/**
	 * This method does nothing.
	 */
	public function testSetHeadData() {
		$this->object->setHeadData('Head Data');
	}

	/**
	 * We test both at once
	 */
	public function testGetAndSetName() {
		$this->object->setName('unittestfilename');

		$this->assertThat(
			$this->object->getName(),
			$this->equalTo('unittestfilename'),
			'setName or getName did not work'
		);
	}

}
