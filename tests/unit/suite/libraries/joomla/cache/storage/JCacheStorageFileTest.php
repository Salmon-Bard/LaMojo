<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */


/**
 * Test class for JCacheStorageFile.
 * Generated by PHPUnit on 2009-10-08 at 21:45:40.
 */
class JCacheStorageFileTest extends PHPUnit_Framework_TestCase {
	/**
	 * @var    JCacheStorageFile
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
		include_once JPATH_BASE.'/libraries/joomla/cache/storage.php';
		include_once JPATH_BASE.'/libraries/joomla/cache/storage/file.php';
		
		$this->object = JCacheStorage::getInstance('file', array('cachebase' => JPATH_BASE.'/cache'));
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
	 * Test Cases for get() / store()
	 *
	 * @return array
	 */
	function casesStore()
	{
		return array(
			'souls' => array(
				42,
				'testing',
				'And this is the cache that tries men\'s souls',
				true,
				false,
			),
			'again' => array(
				43,
				'testing',
				'The summer coder and the sunshine developer.',
				true,
				false,
			),
		);
	}

	/**
	 * Testing store() and get()
	 *
	 * @param	string	cache element ID
	 * @param	string	cache group
	 * @param	string	data to be cached
	 * @param	string	expected return
	 *
	 * @return void
	 * @dataProvider casesStore
	 */
	public function testStoreAndGet( $id, $group, $data, $checktime, $expected )
	{
		$this->assertThat(
			$this->object->store($id, $group, $data),
			$this->isTrue(),
			'Should store the data properly'
		);

		$this->assertThat(
			$this->object->get($id, $group, $checktime),
			$this->equalTo($data),
			'Should retrieve the data properly'
		);
	}

	/**
	 * @todo Implement testRemove().
	 */
	public function testRemove() {
		$this->object->store(42, 'testing', 'And this is the cache that tries men\'s souls');
		
		$this->assertThat(
			$this->object->get(42, 'testing', true),
			$this->equalTo('And this is the cache that tries men\'s souls')
		);
		$this->assertThat(
			$this->object->remove(42, 'testing'),
			$this->isTrue()
		);
		$this->assertThat(
			$this->object->get(42, 'testing', true),
			$this->isFalse()
		);
	}

	/**
	 * @todo Implement testClean().
	 */
	public function testClean() {
		$this->object->store(42, 'testing', 'And this is the cache that tries men\'s souls');
		$this->object->store(43, 'testing', 'The summer coder and the sunshine developer.');
		$this->object->store(44, 'nottesting', 'Now is the time for all good developers to cry');
		$this->object->store(45, 'testing', 'Do not go gentle into that good night');
		
		$this->assertThat(
			$this->object->get(42, 'testing', true),
			$this->equalTo('And this is the cache that tries men\'s souls')
		);
		$this->assertThat(
			$this->object->clean('testing', 'group'),
			$this->isTrue()
		);
		$this->assertThat(
			$this->object->get(42, 'testing', true),
			$this->isFalse()
		);
		$this->assertThat(
			$this->object->get(43, 'testing', true),
			$this->isFalse()
		);
		$this->assertThat(
			$this->object->get(44, 'nottesting', true),
			$this->equalTo('Now is the time for all good developers to cry')
		);
		$this->assertThat(
			$this->object->get(45, 'testing', true),
			$this->isFalse()
		);
		$this->assertThat(
			(bool)$this->object->clean('testing', 'notgroup'),
			$this->equalTo(true)
		);
		$this->assertThat(
			$this->object->get(44, 'nottesting', true),
			$this->isFalse()
		);
	}

	/**
	 * @todo Implement testGc().
	 */
	public function testGc() {
		$this->assertThat(
			(bool)$this->object->gc(),
			$this->isTrue()
		);
	}

	/**
	 * Testing test().
	 */
	public function testTest() {
		$this->assertThat(
			$this->object->test(),
			$this->isTrue(),
			'Claims File is not loaded.'
		);
	}

	/**
	 * @todo Implement test_setExpire().
	 */
	public function test_setExpire() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}

	/**
	 * @todo Implement test_getFilePath().
	 */
	public function test_getFilePath() {
		// Remove the following lines when you implement this test.
		$this->markTestIncomplete('This test has not been implemented yet.');
	}
}
?>
