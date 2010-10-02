<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

require_once JPATH_BASE . '/libraries/joomla/database/tablenested.php';
require_once JPATH_BASE . '/libraries/joomla/database/table/category.php';
require_once JPATH_BASE . '/libraries/joomla/database/table/asset.php';
require_once JPATH_BASE . '/libraries/joomla/database/table/menu.php';
require_once JPATH_BASE . '/libraries/joomla/factory.php';
require_once 'PHPUnit/Extensions/Database/TestCase.php';
require_once 'PHPUnit/Extensions/Database/DataSet/CsvDataSet.php';

/**
 * Test class for JTableNested.
 * Generated by PHPUnit on 2009-10-08 at 22:01:58.
 */
class JTableNestedTest extends JoomlaDatabaseTestCase {
	/**
	 * @var	JTableNested
	 * @access protected
	 */
	protected $object;
	protected $db;
	
	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp() {
		parent::setup();
		$connect = parent::getConnection();
		$categories = $this->getDataSet();
		$this->_db = JFactory::getDbo();
		$this->object = new JTableCategory($this->_db);
		JFactory::$session = $this->getMock('JSession', array('_start'));
	}

	protected function getDataSet() {
		$dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet(',', '"', '\\');
		$refreshTestNames = array
		(
			'testGetPath' 
			,'testMove'
			,'testMoveByReference'
			,'testDelete'
			,'testStore'
			,'testGetRootId'
			,'testOrderUp'
			,'testRebuild'
			,'testRebuildPath'
			,'testSaveorder'
		);
		if (in_array($this->name, $refreshTestNames)) {
			$dataSet->addTable('jos_categories', JPATH_BASE . '/tests/unit/stubs/jos_categories.csv');
			$dataSet->addTable('jos_assets', JPATH_BASE . '/tests/unit/stubs/jos_assets.csv');
			$dataSet->addTable('jos_menu', JPATH_BASE . '/tests/unit/stubs/jos_menu.csv');
		}
		return $dataSet;
	}
	
	/**
	 * Tears down the fixture, for example, closes a network connection.
	 * This method is called after a test is executed.
	 *
	 * @access protected
	 */
	protected function tearDown() {
		parent::tearDown();
	}

	public function testGetPath($nodata = true) {
		// Get path of ROOT node
		$pathRoot = $this->object->getPath(1);
		$this->assertEquals(1, count($pathRoot), 'Line: '.__LINE__.' Root path should have 1 element');
		$this->assertEquals('1', $pathRoot[0]->id, 'Line: '.__LINE__.' Path of root should have id=1');
		
		// Get path of Components node
		$pathComponents = $this->object->getPath(21);
		$this->assertEquals(5, count($pathComponents), 'Line: '.__LINE__.' Components path should have 5 elements');
		$this->assertEquals('1', $pathComponents[0]->id, 'Line: '.__LINE__.' Element 0 should have id=1');
		$this->assertEquals('14', $pathComponents[1]->id, 'Element 1 should have id=14');
		$this->assertEquals('19', $pathComponents[2]->id, 'Element 2 should have id=19');
		$this->assertEquals('20', $pathComponents[3]->id, 'Element 3 should have id=20');
		$this->assertEquals('21', $pathComponents[4]->id, 'Element 4 should have id=21');
		
		// Get path of invalid id
		$pathInvalid = $this->object->getPath(999);
		$this->assertEquals(0, count($pathInvalid), 'Invalid path should have zero elements');
	}

	public function testGetTree($nodata = false) {
		// get Root as tree
		$treeRoot = $this->object->getTree('1');
		$this->assertEquals(68, count($treeRoot), 'Root tree should have 68 nodes');
		$this->assertEquals('1', $treeRoot[0]->id, 'id for element 0 should be 1');
		$this->assertEquals('18', $treeRoot[1]->id, 'id for element 1 should be 18');
		$this->assertEquals('31', $treeRoot[2]->id, 'id for element 2 should be 31');
		$this->assertEquals('9', $treeRoot[67]->id, 'id for element 2 should be 31');
		
		// get Templates as tree
		$treeTemplates = $this->object->getTree(23);
		$this->assertEquals(5, count($treeTemplates), 'Templates tree should have 5 nodes');
		$this->assertEquals('23', $treeTemplates[0]->id, 'id for element 0 should be 23');
		$this->assertEquals('69', $treeTemplates[1]->id, 'id for element 1 should be 69');
		$this->assertEquals('70', $treeTemplates[2]->id, 'id for element 2 should be 70');
		$this->assertEquals('68', $treeTemplates[3]->id, 'id for element 2 should be 68');
		$this->assertEquals('71', $treeTemplates[4]->id, 'id for element 2 should be 71');
		
		// get Plugins as tree
		$treePlugins = $this->object->getTree(25);
		$this->assertEquals(1, count($treePlugins), 'Templates tree should have 5 nodes');
		$this->assertEquals('25', $treePlugins[0]->id, 'd for element 0 should be 25');

		// Get invalid node as tree
		$treeInvalid = $this->object->getTree(99999);
		$this->assertEquals(0, count($treeInvalid), 'Invalid tree should have 0 nodes');
	}

	public function testIsLeaf() {
		$this->assertFalse($this->object->isLeaf(1), 'Root node is not a leaf');
		$this->assertFalse($this->object->isLeaf(23), 'Templates node is not a leaf');
		$this->assertFalse($this->object->isLeaf(-1), 'Invalid node is not a leaf');
		$this->assertTrue($this->object->isLeaf(25), 'Plugins is a leaf');
	}

	public function testSetLocation() {
		$table = $this->object;
		
		// Test default location 
		$table->setLocation(20);
		$this->assertAttributeEquals(20, '_location_id', $table);
		$this->assertAttributeEquals('after', '_location', $table);
		
		// Test with explicit locations
		$table->setLocation(20,'before');
		$this->assertAttributeEquals('before', '_location', $table);
		
		// Test that invalid location returns false
		$this->assertFalse($table->setLocation(20,'xxx'));
	}
	
	public function testMove() {
		$table = $this->object;
		
		// Save original tree for future reference
		$treeOriginal = $table->getTree('1');
		// Check original tree for starting integrity
		$this->checkLftRgt($treeOriginal);
		
		// Move Modules category (22) down
		$table->reset();
		$this->assertTrue($table->load(22), 'Line: '.__LINE__.' Load should be successful');
		$where = null;
		$this->assertTrue($table->move(1, $where), 'Line: '.__LINE__.' Move should be successful');
		
		// Get new root and check positions
		$treeRoot= $table->getTree('1');
		$this->checkLftRgt($treeRoot);
		$this->assertEquals(68, count($treeRoot), 'Line: '.__LINE__.' Root tree should have 68 nodes');
		$this->assertEquals('1', $treeRoot[0]->id, 'Line: '.__LINE__.' id for element 0 should be 1');
		$this->assertEquals('23', $treeRoot[9]->id, 'Line: '.__LINE__.' id for element 9 should be 23');
		$this->assertEquals('69', $treeRoot[10]->id, 'Line: '.__LINE__.' id for element 10 should be 69');
		$this->assertEquals('70', $treeRoot[11]->id, 'Line: '.__LINE__.' id for element 11 should be 70');
		$this->assertEquals('68', $treeRoot[12]->id, 'Line: '.__LINE__.' id for element 12 should be 68');
		$this->assertEquals('71', $treeRoot[13]->id, 'Line: '.__LINE__.' id for element 13 should be 71');
		$this->assertEquals('22', $treeRoot[14]->id, 'Line: '.__LINE__.' id for element 14 should be 23');
		$this->assertEquals('64', $treeRoot[15]->id, 'Line: '.__LINE__.' id for element 15 should be 69');
		$this->assertEquals('65', $treeRoot[16]->id, 'Line: '.__LINE__.' id for element 16 should be 70');
		$this->assertEquals('66', $treeRoot[17]->id, 'Line: '.__LINE__.' id for element 17 should be 68');
		$this->assertEquals('67', $treeRoot[18]->id, 'Line: '.__LINE__.' id for element 18 should be 71');
		$this->assertEquals('75', $treeRoot[19]->id, 'Line: '.__LINE__.' id for element 19 should be 71');
		
		// Rebuild and make sure nothing changes
		$table->rebuild();
		$newRoot = $table->getTree('1');
		$this->checkLftRgt($newRoot);
		$this->compareTrees($treeRoot, $newRoot);

		// Move node back up
		$table->reset();
		$this->assertTrue($table->load(22), 'Line: '.__LINE__.' Load should be successful');
		$this->assertTrue($table->move(-1, $where), 'Line: '.__LINE__.' Move should be successful');
		
		// Get the new tree -- should be the same as the original
		$treeOriginalNew = $table->getTree('1');
		$this->checkLftRgt($treeOriginalNew);
		$this->compareTrees($treeOriginal, $treeOriginalNew);
		
		// Move Sample Data Articles Down
		$table->reset();
		$this->assertTrue($table->load(14), 'Line: '.__LINE__.' Load should be successful');
		$this->assertTrue($table->move(1, $where), 'Line: '.__LINE__.' Move should be successful');
		$treeSampleDataDown = $table->getTree('1');
		$this->checkLftRgt($treeSampleDataDown);
		$this->assertEquals(68, count($treeRoot), 'Line: '.__LINE__.' Root tree should have 68 nodes');
		$this->assertEquals('14', $treeRoot[5]->id, 'Line: '.__LINE__.' id for element 0 should be 1');
		$this->assertEquals('9', $treeRoot[67]->id, 'Line: '.__LINE__.' id for element 9 should be 23');
		
		// Move Sample Data Articles back up
		$table->reset();
		$this->assertTrue($table->load(14), 'Line: '.__LINE__.' Load should be successful');
		$this->assertTrue($table->move(-1, $where), 'Line: '.__LINE__.' Move should be successful');
		$treeOriginalNew = $table->getTree('1');		
		$this->compareTrees($treeOriginal, $treeOriginalNew);
		
		// Move Articles Uncategorised Down (should return false and not change anything)
		$table->reset();
		$this->assertTrue($table->load(9), 'Line: '.__LINE__.' Load should be successful');
		$this->assertFalse($table->move(1, $where), 'Line: '.__LINE__.' Move should fail');
		$treeOriginalNew = $table->getTree('1');
		$this->assertEquals(68, count($treeOriginalNew), 'Root tree should have 68 nodes');		
		$this->compareTrees($treeOriginal, $treeOriginalNew);
		
		// Move Sample Data Weblinks up (should return false and not change anything)
		$table->reset();
		$this->assertTrue($table->load(18), 'Line: '.__LINE__.' Load should be successful');
		$this->assertFalse($table->move(-1, $where), 'Line: '.__LINE__.' Move should fail');
		$treeOriginalNew = $table->getTree('1');
		$this->assertEquals(68, count($treeOriginalNew), 'Root tree should have 68 nodes');		
		$this->compareTrees($treeOriginal, $treeOriginalNew);
		
		// Move Menu Module down (should return false and not change anything)
		$table->reset();
		$this->assertTrue($table->load(75), 'Line: '.__LINE__.' Load should be successful');
		$this->assertFalse($table->move(1, $where), 'Line: '.__LINE__.' Move should fail');
		$treeOriginalNew = $table->getTree('1');
		$this->assertEquals(68, count($treeOriginalNew), 'Line: '.__LINE__.' Root tree should have 68 nodes');		
		$this->compareTrees($treeOriginal, $treeOriginalNew);
		
		// Move Content Modules up (should return false and not change anything)
		$table->reset();
		$this->assertTrue($table->load(64), 'Line: '.__LINE__.' Load should be successful');
		$this->assertFalse($table->move(-1, $where), 'Line: '.__LINE__.' Move should fail');
		$treeOriginalNew = $table->getTree('1');
		$this->assertEquals(68, count($treeOriginalNew), 'Line: '.__LINE__.' Root tree should have 68 nodes');		
		$this->compareTrees($treeOriginal, $treeOriginalNew);

		// Move Content Modules down
		$table->reset();
		$this->assertTrue($table->load(64), 'Line: '.__LINE__.' Load should be successful');
		$this->assertTrue($table->move(1, $where), 'Line: '.__LINE__.' Move should succeed');
		$treeContentModulesDown = $table->getTree('1');
		$this->assertEquals(68, count($treeContentModulesDown), 'Line: '.__LINE__.' Root tree should have 68 nodes');		
		$this->checkLftRgt($treeContentModulesDown);
		$this->assertEquals('64', $treeContentModulesDown[11]->id, 'Line: '.__LINE__.' id for element 11 should be 64');
		$this->assertEquals('65', $treeContentModulesDown[10]->id, 'Line: '.__LINE__.' id for element 10 should be 65');
		
		// Move Content Modules up
		$table->reset();
		$this->assertTrue($table->load(64), 'Line: '.__LINE__.' Load should be successful');
		$this->assertTrue($table->move(-1, $where), 'Line: '.__LINE__.' Move should succeed');
		$treeOriginalNew = $table->getTree('1');		
		$this->assertEquals(68, count($treeOriginalNew), 'Line: '.__LINE__.' Root tree should have 68 nodes');		
		$this->compareTrees($treeOriginal, $treeOriginalNew);
		
		// Test Move with where clause
		// Move Weblinks Uncategorised Up with where clause
		$table->reset();
		$this->assertTrue($table->load(13), 'Line: '.__LINE__.' Load should be successful');
		$where = 'extension = ' . $this->_db->Quote('com_weblinks');
		$this->assertTrue($table->move(-1, $where), 'Line: '.__LINE__.' Move should succeed');
		$treeTemp = $table->getTree('1');
		$this->assertEquals('13', $treeTemp[1]->id, 'Line: '.__LINE__.' id for element 1 should be 13');
		$this->assertEquals('18', $treeTemp[2]->id, 'Line: '.__LINE__.' id for element 2 should be 18');
	}
	
	public function testMoveByReference() {
		$table = $this->object;
		
		// Save original tree for future reference
		$treeOriginal = $table->getTree('1');
		// Check original tree for starting integrity
		$this->checkLftRgt($treeOriginal);
		
		//Move Modules category (22) to after Plugins (25)
		$this->assertTrue($table->moveByReference('25', 'after', '22'), 'Line: '.__LINE__.' MovebyReference should succeed');
		$treeTemp = $table->getTree('1');
		$this->assertEquals(68, count($treeTemp), 'Line: '.__LINE__.' Root tree should have 68 nodes');	
		$this->checkLftRgt($treeTemp);	
		$this->assertEquals('23', $treeTemp[9]->id, 'Line: '.__LINE__.' id for element 9 should be 23');
		$this->assertEquals('69', $treeTemp[10]->id, 'Line: '.__LINE__.' id for element 10 should be 69');
		$this->assertEquals('24', $treeTemp[14]->id, 'Line: '.__LINE__.' id for element 14 should be 24');
		$this->assertEquals('25', $treeTemp[15]->id, 'Line: '.__LINE__.' id for element 15 should be 25');
		$this->assertEquals('22', $treeTemp[16]->id, 'Line: '.__LINE__.' id for element 16 should be 22');
		$this->assertEquals('25', $treeTemp[15]->id, 'Line: '.__LINE__.' id for element 21 should be 25');
		
		// Move Modules category (22) back to before Templates (23)
		$this->assertTrue($table->moveByReference('23', 'before', '22'), 'Line: '.__LINE__.' MovebyReference should succeed');
		$treeOriginalNew = $table->getTree('1');
		$this->assertEquals(68, count($treeOriginalNew), 'Line: '.__LINE__.' Root tree should have 68 nodes');	
		$this->compareTrees($treeOriginal, $treeOriginalNew);
		
		// Move Templates (23) to first child under Menu Module (75)
		$this->assertTrue($table->moveByReference('75', 'first-child', '23'), 'Line: '.__LINE__.' MovebyReference should succeed');
		$treeTemp = $table->getTree('1');
		$this->checkLftRgt($treeTemp);
		$this->assertEquals('23', $treeTemp[15]->id, 'Line: '.__LINE__.' id for element 15 should be 23');
		$this->assertEquals(6, $treeTemp[15]->level, 'Line: '.__LINE__.' level for element 15 should be 6');
		$this->assertEquals('75', $treeTemp[15]->parent_id, 'Line: '.__LINE__.' parent_id for element 15 should be 75');
		$this->assertEquals('69', $treeTemp[16]->id, 'Line: '.__LINE__.' id for element 16 should be 69');
		$this->assertEquals(7, $treeTemp[16]->level, 'Line: '.__LINE__.' level for element 16 should be 7');
		$this->assertEquals('23', $treeTemp[16]->parent_id, 'Line: '.__LINE__.' parent_id for element 16 should be 23');
		
		// Move Templates (23) back to original position - after Modules (22)
		$this->assertTrue($table->moveByReference('22', 'after', '23'), 'Line: '.__LINE__.' MovebyReference should succeed');
		$treeOriginalNew = $table->getTree('1');
		$this->assertEquals(68, count($treeOriginalNew), 'Line: '.__LINE__.' Root tree should have 68 nodes');	
		$this->compareTrees($treeOriginal, $treeOriginalNew);

		// Move Components (21) to be last-child of Extensions (20)
		$this->assertTrue($table->moveByReference('20', 'last-child', '21'), 'Line: '.__LINE__.' MovebyReference should succeed');
		$treeTemp = $table->getTree('1');
		$this->assertEquals(68, count($treeTemp), 'Line: '.__LINE__.' Root tree should have 68 nodes');	
		$this->checkLftRgt($treeTemp);
		$this->assertEquals('22', $treeTemp[8]->id, 'Line: '.__LINE__.' id for element 8 should be 22');
		$this->assertEquals('21', $treeTemp[21]->id, 'Line: '.__LINE__.' id for element 21 should be 21');
		
		// Move Components (21) back to first-child of Extensions (20)
		$this->assertTrue($table->moveByReference('20', 'first-child', '21'), 'Line: '.__LINE__.' MovebyReference should succeed');
		$treeOriginalNew = $table->getTree('1');
		$this->assertEquals(68, count($treeOriginalNew), 'Line: '.__LINE__.' Root tree should have 68 nodes');	
		$this->compareTrees($treeOriginal, $treeOriginalNew);
		
		// Try to move Modules (22) to first-child of User Modules (65)
		// Should be invalid since 65 is child of 22
		$this->assertFalse($table->moveByReference('65', 'first-child', '22'), 'Line: '.__LINE__.' MovebyReference should fail');
		
		// Move Sample Data Articles (14) to before Sample Data Weblinks (18)
		$this->assertTrue($table->moveByReference('18', 'before', '14'), 'Line: '.__LINE__.' MovebyReference should succeed');
		$treeTemp = $table->getTree('1');
		$this->assertEquals(68, count($treeTemp), 'Line: '.__LINE__.' Root tree should have 68 nodes');	
		$this->checkLftRgt($treeTemp);
		$this->assertEquals('14', $treeTemp[1]->id, 'Line: '.__LINE__.' id for element 1 should be 14');
		$this->assertEquals('19', $treeTemp[2]->id, 'Line: '.__LINE__.' id for element 2 should be 19');
		$this->assertEquals('18', $treeTemp[26]->id, 'Line: '.__LINE__.' id for element 26 should be 18');
		$this->assertEquals('31', $treeTemp[27]->id, 'Line: '.__LINE__.' id for element 27 should be 31');
		
		// Move Sample Data Weblinks (18) to be first-child of ROOT (1)
		// Should put it back to original state
		$this->assertTrue($table->moveByReference('1', 'first-child', '18'), 'Line: '.__LINE__.' MovebyReference should succeed');
		$treeOriginalNew = $table->getTree('1');
		$this->assertEquals(68, count($treeOriginalNew), 'Root tree should have 68 nodes');	
		$this->compareTrees($treeOriginal, $treeOriginalNew);

		// Move with blank reference id
		// Default behavior is to move the node to the last child of the root node (ignores the position parameter)
		$this->assertTrue($table->moveByReference('', 'before', '18'), 'Line: '.__LINE__.' MovebyReference should succeed');
		$treeTemp = $table->getTree('1');
		$this->assertEquals(68, count($treeTemp), 'Line: '.__LINE__.' Root tree should have 68 nodes');	
		$this->checkLftRgt($treeTemp);		
		$this->assertEquals('14', $treeTemp[1]->id, 'Line: '.__LINE__.' id for element 1 should be 14');
		$this->assertEquals('19', $treeTemp[2]->id, 'Line: '.__LINE__.' id for element 2 should be 19');
		$this->assertEquals('18', $treeTemp[64]->id, 'Line: '.__LINE__.' id for element 26 should be 18');
		$this->assertEquals('31', $treeTemp[65]->id, 'Line: '.__LINE__.' id for element 27 should be 31');

		// Call with no id should return false
		$this->assertFalse($table->moveByReference('', 'first-child', ''), 'Line: '.__LINE__.' MovebyReference should fail');
		
		// Can set id with load method
		$table->load('18');
		// Move Sample Data Weblinks back to first-child under Root
		$this->assertTrue($table->moveByReference('1', 'first-child'), 'Line: '.__LINE__.' MovebyReference should succeed');
		$treeOriginalNew = $table->getTree('1');
		$this->assertEquals(68, count($treeOriginalNew), 'Line: '.__LINE__.' Root tree should have 68 nodes');	
		$this->compareTrees($treeOriginal, $treeOriginalNew);		
	}

	public function testDelete() {
		$table = $this->object;
		$assetsTable =  new JTableAsset($this->_db);
		$treeAssetsOriginal = $assetsTable->getTree('1'); 
		$this->assertEquals(158, count($treeAssetsOriginal), 'Line: '.__LINE__.' Assets root tree should have 158 nodes');

		// Delete Sample Data weblinks (18) & children
		$table->load('18');
		$this->assertTrue($table->delete('18', true), 'Line: '.__LINE__.' Should delete 18');
		
		// Check categories table
		$treeTemp = $table->getTree('1');
		$this->assertEquals(64, count($treeTemp), 'Line: '.__LINE__.' Root tree should have 68 nodes');	
		$this->checkLftRgt($treeTemp);		
		$this->assertEquals('14', $treeTemp[1]->id, 'Line: '.__LINE__.' id for element 1 should be 14');
		$this->assertEquals('19', $treeTemp[2]->id, 'Line: '.__LINE__.' id for element 2 should be 19');
		$this->assertEquals(0, count($table->getTree('18')), 'Line: '.__LINE__.' Id 18 should have been deleted');
		$this->assertEquals(0, count($table->getTree('31')), 'Line: '.__LINE__.' Id 31 should have been deleted');
		
		// Check assets table
		$treeAssetsTemp = $assetsTable->getTree('1'); 
		$this->assertEquals(154, count($treeAssetsTemp), 'Line: '.__LINE__.' After delete, assets root tree should have 154 nodes');
		$this->assertEquals(0, count($assetsTable->getTree('43')), 'Line: '.__LINE__.' Id 43 should have been deleted from assets');
		$this->assertEquals(0, count($assetsTable->getTree('56')), 'Line: '.__LINE__.' Id 56 should have been deleted from assets');
		
		// Delete Modules (22) and don't delete children
		// Child nodes should be moved up to parent
		$table->load('22');
		$this->assertTrue($table->delete('22', false), 'Line: '.__LINE__.' Delete id 22 should be successful');
		// Check categories table
		$treeTemp = $table->getTree('1');
		$this->assertEquals(63, count($treeTemp), 'Line: '.__LINE__.' Root tree should have 63 nodes');	
		$this->checkLftRgt($treeTemp);		
		$this->assertEquals('64', $treeTemp[5]->id, 'Line: '.__LINE__.' id for element 5 should be 64');
		$this->assertEquals('4', $treeTemp[5]->level, 'Line: '.__LINE__.' level for element 5 should be 4');
		$this->assertEquals('65', $treeTemp[6]->id, 'Line: '.__LINE__.' id for element 6 should be 65');
		$this->assertEquals('4', $treeTemp[6]->level, 'Line: '.__LINE__.' id for element 6 should be 4');
		$this->assertEquals('66', $treeTemp[7]->id, 'Line: '.__LINE__.' id for element 7 should be 66');
		$this->assertEquals('4', $treeTemp[7]->level, 'Line: '.__LINE__.' id for element 7 should be 4');
		$this->assertEquals('67', $treeTemp[8]->id, 'Line: '.__LINE__.' id for element 8 should be 67');
		$this->assertEquals('4', $treeTemp[8]->level, 'Line: '.__LINE__.' id for element 8 should be 4');
		$this->assertEquals('75', $treeTemp[9]->id, 'Line: '.__LINE__.' id for element 9 should be 75');
		$this->assertEquals('4', $treeTemp[9]->level, 'Line: '.__LINE__.' id for element 9 should be 4');
		$this->assertEquals(0, count($table->getTree('22')), 'Line: '.__LINE__.' id 22 should be deleted');
		
		// Check assets table
		$treeAssetsTemp = $assetsTable->getTree('1'); 
		$this->assertEquals(153, count($treeAssetsTemp), 'Line: '.__LINE__.' After delete, assets root tree should have 153 nodes');
		$this->assertEquals(0, count($assetsTable->getTree('47')), 'Line: '.__LINE__.' Id 47 should have been deleted from assets');
		
		// Try using delete method without the table->load
		// It should fail and return false
		$table->reset();
		$this->assertFalse($table->delete('14'), 'Line: '.__LINE__.' Table delete should fail because table->load() was not run first');
		
		// Delete Templates (23) without arguments (using just the $table->load)
		$table->load('23');
		$this->assertTrue($table->delete(), 'Line: '.__LINE__.' Should delete id 23 and children');
		
		// Check categories
		$treeTemp = $table->getTree('1');
		$this->assertEquals(58, count($treeTemp), 'Line: '.__LINE__.' Root tree should have 58 nodes');	
		$this->checkLftRgt($treeTemp);		
		$this->assertEquals('24', $treeTemp[10]->id, 'Line: '.__LINE__.' id for element 10 should be 24');
		$this->assertEquals(0, count($table->getTree('23')), 'Line: '.__LINE__.' id 23 should be deleted');
		
		// Check assets
		$treeAssetsTemp = $assetsTable->getTree('1'); 
		$this->assertEquals(147, count($treeAssetsTemp), 'Line: '.__LINE__.' After delete, assets root tree should have 148 nodes');
		$this->assertEquals(0, count($assetsTable->getTree('48')), 'Line: '.__LINE__.' Id 48 should have been deleted from assets');
		$this->assertEquals(0, count($assetsTable->getTree('98')), 'Line: '.__LINE__.' Id 98 should have been deleted from assets');
		$this->assertEquals(0, count($assetsTable->getTree('147')), 'Line: '.__LINE__.' Id 147 should have been deleted from assets (article)');
		
		$table->load('21'); // Components no children
		$this->assertTrue($table->delete('21', false), 'Line: '.__LINE__.' Should delete id 21 but not children');
		// Check categories
		$treeTemp = $table->getTree('1');
		$this->assertEquals(57, count($treeTemp), 'Line: '.__LINE__.' Root tree should have 58 nodes');	
		$this->checkLftRgt($treeTemp);		
		$this->assertEquals('64', $treeTemp[4]->id, 'Line: '.__LINE__.' id for element 4 should be 64');
		$this->assertEquals(0, count($table->getTree('21')), 'Line: '.__LINE__.' id 21 should be deleted');
		
		// Check assets
		$treeAssetsTemp = $assetsTable->getTree('1'); 
		$this->assertEquals(146, count($treeAssetsTemp), 'Line: '.__LINE__.' After delete, assets root tree should have 146 nodes');
		
		// Test table with no assets
		$menuTable =  new JTableMenu($this->_db);
		$menuTable->load('2');
		$this->assertTrue($menuTable->delete('2', true), 'Line: '.__LINE__.' Menu id 2 should be deleted');
		$treeMenuTemp = $menuTable->getTree('1');
		$this->checkLftRgt($treeMenuTemp);
		$this->assertEquals(16, count($treeMenuTemp), 'Line: '.__LINE__.' Menu table should have 16 rows');
		$this->assertEquals(146, count($treeAssetsTemp), 'Line: '.__LINE__.' After delete, assets root tree should still have 146 nodes');
		
		// Check assets
		$treeAssetsTemp = $assetsTable->getTree('1'); 
		$this->assertEquals(146, count($treeAssetsTemp), 'Line: '.__LINE__.' After delete, assets root tree should have 146 nodes');		
	}

	public function testCheck() {
		$this->assertTrue(true, 'Test skipped since this method is overridden in all subclasses');
	}
	
	public function testStore() {
		// Use assets table since it does not override the JTableNested store() method
		$table =  new JTableAsset($this->_db);
		
		// Existing row nulls=false
		$table->load('3');
		$rules = $table->rules;
		$table->title = 'New Title';
		$table->rules = null;
		$this->assertTrue($table->store(), 'Line: '.__LINE__.' Table store should succeed'); 
		$table->reset();
		$table->load('3');
		$this->assertEquals('New Title', $table->title, 'Line: '.__LINE__.' Title should be updated');
		$this->assertEquals($rules, $table->rules, 'Line: '.__LINE__.' Rules should not be overwritten by null value');
		
		// Existing Row nulls=true
		$table->rules = null;
		$table->title = 'New Title Null';
		$this->assertFalse($table->store(true), 'Line: '.__LINE__.' Table store should fail since rules field is not null in db'); 
		$table->reset();
		$table->load('3');
		$this->assertEquals('New Title', $table->title, 'Line: '.__LINE__.' Title should not be updated');
		$this->assertEquals($rules, $table->rules, 'Line: '.__LINE__.' Rules should not be overwritten by null value');
		
		// Existing Row with new parent (implicit move)
		// Move id 3 to be first child of 4
		$table->reset();
		$table->load('3');
		$table->setLocation('4', 'first-child');
		$table->title = 'Move 3 to first child of 4';
		$this->assertTrue($table->store(), 'Line: '.__LINE__.' Table store should succeed');
		$treeTemp = $table->getTree('1');
		$this->assertEquals('4', $treeTemp[2]->id, 'Line: '.__LINE__.' id for element 2 should be 4');
		$this->assertEquals('3', $treeTemp[3]->id, 'Line: '.__LINE__.' id for element 3 should be 3');
		$this->assertEquals(2, $treeTemp[3]->level, 'Line: '.__LINE__.' level for element 3 should be 2');
		
		// New row with reference node
		$table->reset();
		$table->load('40');
		$table->id = null;
		$table->title = 'New Node Last Child of 4';
		$table->setLocation('4', 'last-child');
		$table->name = 'com.banners.category.999';
		
		// New row without reference node
		$this->assertTrue($table->store(), 'Line: '.__LINE__.' Table store should succeed');
		$treeTemp = $table->getTree('1');
		$this->assertEquals(159, count($treeTemp), 'Line: '.__LINE__.' Tree should now have 159 rows');
		$this->assertEquals('com.banners.category.999', $treeTemp[6]->name, 'Line: '.__LINE__.' New node should be in position 6');
		$this->assertEquals('4', $treeTemp[6]->parent_id, 'Line: '.__LINE__.' New node should parent id of 4');
		$this->assertEquals(2, $treeTemp[6]->level, 'Line: '.__LINE__.' New node level should be 2');
	}

	public function testPublish() {
		// Test with pk's in argument
		$table = $this->object;
		$pks = array('18', '31', '32');
		$this->assertTrue($table->publish($pks, '1'), 'Line: '.__LINE__.' Publish with pks should work');
		$treeTemp = $table->getTree('1');
		$this->assertEquals('1', $treeTemp[1]->published, 'Line: '.__LINE__.' Id 18 should be published');
		$this->assertEquals('1', $treeTemp[2]->published, 'Line: '.__LINE__.' Id 31 should be published');
		$this->assertEquals('1', $treeTemp[3]->published, 'Line: '.__LINE__.' Id 32 should be published');
		
		// Test with pk's set in instance fields
		$table->reset();
		$table->id = '18,31,32';
		$this->assertTrue($table->publish(null, '-1'), 'Line: '.__LINE__.' Publish with instance fields should work');
		$treeTemp = $table->getTree('1');
		$this->assertEquals('-1', $treeTemp[1]->published, 'Line: '.__LINE__.' Id 18 should be published');
		$this->assertEquals('-1', $treeTemp[2]->published, 'Line: '.__LINE__.' Id 31 should be published');
		$this->assertEquals('-1', $treeTemp[3]->published, 'Line: '.__LINE__.' Id 32 should be published');
		
		// Test with row checked out to same user
		$table->reset();
		$pks = array('18', '31', '32');
		$table->checkout('1', '18');
		$this->assertTrue($table->publish($pks, '1', '1'), 'Line: '.__LINE__.' Same user can change state');
		
		// Test with row checked out to different user
		$table->checkout('2', '18');
		$this->assertFalse($table->publish($pks, '-1', '1'), 'Line: '.__LINE__.' Different user should not be able to change state');
		
		// Test with child row checked out
		// Checkout Park Links (31) and try to unpublish parent Sample Data Links (18) 
		$table->reset();
		$table->checkout('2', '31');
		$pks = array('18');
		$this->assertFalse($table->publish($pks, '-1', '1'), 'Line: '.__LINE__.' Cannot change state if parent checked out');
		
		// Test with parent node unpublished
		// Try to publish Extensions (20) with parent unpublished
		$pks = array('20');
		$this->assertTrue($table->publish($pks, '1'), 'Line: '.__LINE__.' Can publish child if parent is not published');
		$treeTemp = $table->getTree('1');
		$this->assertEquals('20', $treeTemp[7]->id, 'Line: '.__LINE__.' Node 7 is id=20');
		$this->assertEquals('1', $treeTemp[7]->published, 'Line: '.__LINE__.' Id 20 should be published');
	}

	public function testOrderUp() {
		$table = $this->object;
		$this->assertTrue($table->orderUp('14'), 'Line: '.__LINE__.' orderUp should succeed');
		$treeTemp = $table->getTree('1');
		$this->assertEquals('14', $treeTemp[1]->id, 'Line: '.__LINE__.' Node 1 is id=14');
		$this->assertEquals('76', $treeTemp[25]->id, 'Line: '.__LINE__.' id is not correct');
		
		// Move it back down
		$this->assertTrue($table->orderDown('14'), 'Line: '.__LINE__.' orderDown should succeed');
		$this->assertEquals('14', $treeTemp[1]->id, 'Line: '.__LINE__.' id is not correct');
	}

	public function testOrderDown() {
		$this->assertTrue(true, 'Test skipped since this method is tested in the testOrderUp method');
	}

	public function testGetRootId() {
		$table = $this->object;
		
		// Test valid table
		$this->assertEquals('1', $table->getRootId(), 'Line: '.__LINE__.' root id of valid table is 1');
		
		// Test two rows with parent_id = 0
		$table->load('18', true);
		$table->parent_id = '0';
		$table->alias = 'alias18';
		$this->assertTrue($table->store());
		$this->assertEquals('1', $table->getRootId(), 'Line: '.__LINE__.' root id with two parent_id=0 is 1');
		
		// Test with two rows with lft=0
		$table->load('18', true);
		$table->lft = 0;
		$this->assertTrue($table->store(), 'Line: '.__LINE__.' Can update database');	

		$table->load('1', true);
		$table->alias = 'root';
		$this->assertTrue($table->store(), 'Line: '.__LINE__.' Can update database');	
		$this->assertEquals('1', $table->getRootId(), 'Line: '.__LINE__.' root id with two lft=0 rows is 1');
		
		// No alias = root
		$table->load('1', true);
		$table->alias = 'rootxxx';
		$this->assertTrue($table->store(), 'Line: '.__LINE__.' Can update database');			
		$this->assertFalse($table->getRootId(), 'Line: '.__LINE__.' cannot get root if no alias = root');
		
		// Test two rows with alias = root
		// Run Query to create duplicate root alias values
		$query = $this->_db->getQuery(true);
		$query->update($table->getTableName());
		$query->set('alias = '. $this->_db->quote('root'));
		$query->where('id in('. $this->_db->quote('1') . ',' . $this->_db->quote('18') . ')');

		$this->_db->setQuery($query);
		$this->assertTrue($this->_db->query(), 'Line: '.__LINE__.' Query to update duplicate root alias values');
		$this->assertFalse($table->getRootId(), 'Line: '.__LINE__.' cannot get root if >1 rows with alias = root');		
	}

	public function testRebuild() {
		// Without parent id
		$table = $this->object;
		$this->removeLftRgtValues($table);
		$this->assertEquals(136, $table->rebuild(), 'Line: '.__LINE__.' Rebuild should succeed');
		$tree = $table->getTree('1');
		$this->checkLftRgt($tree);
		
		// With parent id
		$this->removeLftRgtValues($table);
		$this->assertEquals(136, $table->rebuild('1'), 'Line: '.__LINE__.' Rebuild should return 136');
		$tree = $table->getTree('1');
		$this->checkLftRgt($tree);
		
		// With left id > 0
		$this->removeLftRgtValues($table);
		$this->assertEquals(149, $table->rebuild(null, 13), 'Line: '.__LINE__.' Rebuild should return 149');
		$tree = $table->getTree('1');
		$this->checkLftRgt($tree);
				
		// With level > 0
		$this->removeLftRgtValues($table);
		$this->assertEquals(136, $table->rebuild(null, 0, 3), 'Line: '.__LINE__.' Rebuild should return 136');
		$tree = $table->getTree('1');
		$this->assertEquals(3, $tree[0]->level, 'Line: '.__LINE__.' Root node should have level 3');
		$this->assertEquals(7, $tree[8]->level, 'Line: '.__LINE__.' node 8 should have level 7');
		
		// With path specified
		$this->removeLftRgtValues($table);
		$this->assertEquals(136, $table->rebuild(null, 0, 0, 'mypath'), 'Line: '.__LINE__.' Rebuild should return 136');
		$tree = $table->getTree('1');
		$this->checkLftRgt($tree);
		$this->assertEquals('mypath/uncategorised', $tree[1]->path, 'Line: '.__LINE__.' mypath should be prepended to normal path');
	}
	
	public function testRebuildPath() {
		$table = $this->object;
		$this->assertTrue($table->rebuildPath('64'));
		$tree = $table->getTree('1');
		$this->assertEquals('sample-data-articles/joomla/extensions/modules/content-modules', $tree[10]->path, 'Line: '.__LINE__.' mypath should be prepended to normal path');
	}
	
	public function testSaveorder() {
		$table = $this->object;
		// Swap orders of id's 21 and 22 (both level 4)
		// And reverse order of 64, 65, 66, 67, 75 (all level 5 with parent 22)
		$idArray = array('21', '22', '64', '65', '66', '67', '75');
		$lftArray = array(2, 1, 9 , 8, 7 ,6, 5);
		$this->assertEquals(136, $table->saveorder($idArray, $lftArray), 'Line: '.__LINE__.' saveorder should succeed');
		$tree = $table->getTree('1');
		$this->checkLftRgt($tree);
		$this->assertEquals('22', $tree[8]->id, 'Line: '.__LINE__.' 22 should be in position 8');
		$this->assertEquals('75', $tree[9]->id, 'Line: '.__LINE__.' 75 should be in position 9');
		$this->assertEquals('67', $tree[10]->id, 'Line: '.__LINE__.' 67 should be in position 10');
		$this->assertEquals('66', $tree[11]->id, 'Line: '.__LINE__.' 66 should be in position 11');
		$this->assertEquals('65', $tree[12]->id, 'Line: '.__LINE__.' 65 should be in position 12');
		$this->assertEquals('64', $tree[13]->id, 'Line: '.__LINE__.' 64 should be in position 13');
		$this->assertEquals('21', $tree[14]->id, 'Line: '.__LINE__.' 21 should be in position 14');
		
		// Test invalid cases
		// Different number of array elements
		$idArray = array('21', '22');
		$lftArray = array(1, 2, 3);
		$this->assertFalse($table->saveorder($idArray, $lftArray), 'Line: '.__LINE__.' saveorder should fail');
		
		// One argument not an array
		$this->assertFalse($table->saveorder('22', $lftArray), 'Line: '.__LINE__.' saveorder should fail');
		$this->assertFalse($table->saveorder($idArray, 1), 'Line: '.__LINE__.' saveorder should fail');
	}
	
	private function compareTrees($tree1, $tree2) {
		for ($i = 0; $i < count($tree1); $i++) {
			$this->assertEquals($tree1[$i]->id, $tree2[$i]->id, 'Line: '.__LINE__.' id#' . $i . ' should be equal');
			$this->assertEquals($tree1[$i]->lft, $tree2[$i]->lft, 'Line: '.__LINE__.' lft#' . $i . ' should be equal');
			$this->assertEquals($tree1[$i]->rgt, $tree2[$i]->rgt, 'Line: '.__LINE__.' rgt#' . $i . ' should be equal');
			$this->assertEquals($tree1[$i]->parent_id, $tree2[$i]->parent_id, 'Line: '.__LINE__.' parent_id#' . $i . ' should be equal');
			$this->assertEquals($tree1[$i]->level, $tree2[$i]->level, 'Line: '.__LINE__.' level#' . $i . ' should be equal');
		}
	}
	
	private function checkLftRgt($tree) {
		$lftRgtValues = array();
		// build array of all lft and rgt values
		$start = $tree[0]->lft;
		$end = $tree[0]->rgt;
		for ($i = 0; $i < count($tree); $i++) {
			$lftRgtValues[] = $tree[$i]->lft;
			$lftRgtValues[] = $tree[$i]->rgt;
		}
		
		// Check that size of tree is consistent with node 0's lft, rgt values
		$countOfValues = 1 + $end - $start;
		$this->assertEquals($countOfValues, count($lftRgtValues), 
			'Line: '.__LINE__.' Count of values (' . count($lftRgtValues) . ') should be 1 + node 0 rgt (' . $end . ') less lft (' . $start . ')');
		
		// Check that there are no missing integers between node 0's lft, rgt values
		for ($j = $start; $j < $end; $j++) {
			$this->assertTrue(in_array($j, $lftRgtValues), 'Line: '.__LINE__.' Number ' . $j . ' should be in lft or rgt values');
		}
		
		// Check that node 0 is valid (level=0, parent_id=0)
		$this->assertEquals(0, $tree[0]->level, 'Line: '.__LINE__.' Root node should be level 0');
		$this->assertEquals('0', $tree[0]->parent_id, 'Line: '.__LINE__.' Root node should have parent_id = 0');
	}
	
	/**
	 * 
	 * Privatate function to purposely break a valid table by removing all level, lft and rgt values
	 * @param JTableNested $table
	 */
	
	private function removeLftRgtValues($table) {
		$query = $this->_db->getQuery(true);
		$query->update($table->getTableName());
		$query->set('lft = 0, rgt = 0, level = 0');
		$this->_db->setQuery($query);
		$this->assertTrue($this->_db->query(), 'Query to break valid table');
	}
}
?>
