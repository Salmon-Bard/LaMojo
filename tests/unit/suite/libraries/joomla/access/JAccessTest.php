<?php
/**
 * @version		$Id$
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @package		JoomlaFramework
 */
                
 //Complusoft JoomlaTeam - Support: JoomlaTeam@Complusoft.es
require_once JPATH_BASE.'/libraries/joomla/access/access.php';
require_once JPATH_BASE.'/tests/unit/JoomlaDatabaseTestCase.php';
/**
 * Test class for JAccess.
 * Generated by PHPUnit on 2009-10-08 at 11:50:03.
 * @package		JoomlaFramework
 */

class JAccessTest extends JoomlaDatabaseTestCase {
	/**
	 * @var		JAccess
	 * @access	protected
	 */
	protected $object;
        var $have_db = false;

	/**
	 * Sets up the fixture, for example, opens a network connection.
	 * This method is called before a test is executed.
	 *
	 * @access protected
	 */
	protected function setUp() {
	  $this->object = new JAccess;  
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
	 * @todo Implement testCheck().
	 */

	public function testCheck() {
            $access = new JAccess();
            $access2 = new JAccess();
           /* $GroupUser42 = array(
			0	=> 1,
			1	=> 2,
			2	=> 6,
			3	=> 7,
                        4	=> 8
		);*/
            //"core.login.site":{"6":1,"2":1}
            //"core.login.admin":{"6":1}
            //"core.admin":{"8":1,"7":1}
            //"core.manage":{"7":1,"10":1,"6":1},
            //"core.create":{"6":1}
            //"core.delete":{"6":1}
            //"core.edit":{"6":1}
            //"core.edit.state":{"6":1}}';
            //$this->assertTrue($access2->check('78',4,234));
            $this->assertThat(
			Null,
			$this->equalTo($access->check('58','core.login.site',3))
		);
            $this->assertThat(
			Null,
			$this->equalTo($access->check('42','complusoft',3))
		);
            $this->assertThat(
			Null,
			$this->equalTo($access->check('42','core.login.site',345))
		);
            $this->assertTrue($access->check('42','core.login.site',3));
            $this->assertTrue($access->check('42','core.login.admin',3));
            $this->assertTrue($access->check('42','core.admin',3));
            $this->assertTrue($access->check('42','core.manage',3));
            $this->assertTrue($access->check('42','core.create',3));
            $this->assertTrue($access->check('42','core.delete',3));
            $this->assertTrue($access->check('42','core.edit',3));
            $this->assertTrue($access->check('42','core.edit.state',3));    
        }


	/**
	 * @todo Implement testGetAssetRules().
	 */
	public function testGetAssetRules() {
		$access = new JAccess();
                $ObjArrayJrules = $access->getAssetRules(3, True);
                $string1 = '{"core.login.site":{"6":1,"2":1},"core.login.admin":{"6":1},"core.admin":{"8":1,"7":1},"core.manage":{"7":1,"10":1,"6":1},"core.create":{"6":1},"core.delete":{"6":1},"core.edit":{"6":1},"core.edit.state":{"6":1}}';
                $this->assertThat(
			$string1,
			$this->equalTo((string)$ObjArrayJrules)
		);

                $ObjArrayJrules = $access->getAssetRules(3, False);
                $string1 = '{"core.admin":{"7":1},"core.manage":{"6":1},"core.create":[],"core.delete":[],"core.edit":[],"core.edit.state":[]}';
                $this->assertThat(
			$string1,
			$this->equalTo((string)$ObjArrayJrules)
		);

                $ObjArrayJrules = $access->getAssetRules(1550, False);
                $string1 = '[]';
                $this->assertThat(
			$string1,
			$this->equalTo((string)$ObjArrayJrules)
		);
        
	}

        public function testGetUsersByGroup() {
		$access = new JAccess();
		$array1 = array(
			0	=> 42
		);
                $this->assertThat(
			$array1,
			$this->equalTo($access->getUsersByGroup(8, True))
		);
                $this->assertThat(
			$array1,
			$this->equalTo($access->getUsersByGroup(7, True))
		);

                $array2 = array();
                $this->assertThat(
			$array2,
			$this->equalTo($access->getUsersByGroup(7, False))
		);
	}

	/**
	 * @todo Implement testGetGroupsByUser().
	 */
	public function testGetGroupsByUser() {

                $access = new JAccess();
		$array1 = array(
			0	=> 1,
			1	=> 2,
			2	=> 6,
			3	=> 7,
                        4	=> 8
		);
                $this->assertThat(
			$array1,
			$this->equalTo($access->getGroupsByUser(42, True))
		);
                $array2 = array(
                  0     => 8
                );
                $this->assertThat(
			$array2,
			$this->equalTo($access->getGroupsByUser(42, False))
		);

	}

	/**
	 * @todo Implement testGetAuthorisedViewLevels().
	 */
	public function testGetAuthorisedViewLevels() {
		$access = new JAccess();
		$array1 = array(
			0	=> 1,
                        1       => 2,
                        2       => 3
		);
               
                
                $this->assertThat(
			$array1,
			$this->equalTo($access->getAuthorisedViewLevels(42))
		);
                
                $array2 = array(
                    0       => 1
                );
                $this->assertThat(
			$array2,
			$this->equalTo($access->getAuthorisedViewLevels(50))
		);
               


	}

	/**
	 * @todo Implement testGetActions().
	 */
	public function testGetActions() {
		$access = new JAccess();
                $array1 = array(
			'name'	      => "core.admin",
                        'title'       => "JAction_Admin",
                        'description' => "JAction_Admin_Component_Desc"
		);
                $array2 = array(
			'name'	      => "core.manage",
                        'title'       => "JAction_Manage",
                        'description' => "JAction_Manage_Component_Desc"
		);
                $array3 = array(
			'name'	      => "core.create",
                        'title'       => "JAction_Create",
                        'description' => "JAction_Create_Component_Desc"
		);
                $array4 = array(
			'name'	      => "core.delete",
                        'title'       => "JAction_Delete",
                        'description' => "JAction_Delete_Component_Desc"
		);
                $array5 = array(
			'name'	      => "core.edit",
                        'title'       => "JAction_Edit",
                        'description' => "JAction_Edit_Component_Desc"
		);
                 $array6 = array(
			'name'	      => "core.edit.state",
                        'title'       => "JAction_Edit_State",
                        'description' => "JAction_Edit_State_Component_Desc"
		);
               
                
               

		$obj= $access->getActions('com_banners', 'component');
                $arraystdClass =  (array)$obj[0];
                $this->assertThat(
			$array1,
			$this->equalTo($arraystdClass)
		);
                $arraystdClass =  (array)$obj[1];
                $this->assertThat(
			$array2,
			$this->equalTo($arraystdClass)
		);
                $arraystdClass =  (array)$obj[2];
                $this->assertThat(
			$array3,
			$this->equalTo($arraystdClass)
		);
                $arraystdClass =  (array)$obj[3];
                $this->assertThat(
			$array4,
			$this->equalTo($arraystdClass)
		);
                $arraystdClass =  (array)$obj[4];
                $this->assertThat(
			$array5,
			$this->equalTo($arraystdClass)
		);
                $arraystdClass =  (array)$obj[5];
                $this->assertThat(
			$array6,
			$this->equalTo($arraystdClass)
		);

                
                $this->assertThat(
			$array7 = array(),
			$this->equalTo($access->getActions('com_complusoft', 'component'))
		);
               
        }
}
