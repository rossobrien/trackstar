<?php
class ProjectTest extends CDbTestCase
{
	public $fixtures = array(
			'projects' => 'Project',
			'users' => 'User',
			'projUsrAssign' => ':tbl_project_user_assignment',
			'projUsrRole' => ':tbl_project_user_role',
			'authAssign' => ':authassignment',
	);

	public function testCreate()
	{
		//Create new project
		$newProject = new Project;
		$newProjectName = 'Test Project 1';
		$newProject->setAttributes(
				array(
						'name' => $newProjectName,
						'description'		=> 'Unit test for project creation',
					)
		);
		
		//Set user
		Yii::app()->user->setID($this->users('user1')->id);
		
		$this->assertTrue( $newProject->save() );
		
		//Read newly created project
		$retrievedProject = Project::model()->findByPk( $newProject->id );
		$this->assertTrue( $retrievedProject instanceof Project );
		$this->assertEquals( $newProjectName, $retrievedProject->name );
		
		//Check user
		$this->assertEquals(Yii::app()->user->id, $retrievedProject->create_user_id);
	}
	
	public function testRead()
	{
		$retrievedProject = $this->projects('project1');
		$this->assertTrue( $retrievedProject instanceof Project );
		$this->assertEquals( 'Test Project 1', $retrievedProject->name );
	}
	
	public function testUpdate()
	{
		//Update project
		$project = $this->projects('project2');
		$updatedProjectName = 'Updated Test Project 2';
		$project->name = $updatedProjectName;
		$this->assertTrue( $project->save( false ) );
		
		//Test updated project
		$updatedProject = Project::model()->findByPk( $project->id );
		$this->assertTrue( $updatedProject instanceof Project );
		$this->assertEquals( $updatedProjectName, $updatedProject->name );
	}
	
	public function testDelete()
	{
		//Delete test project
		$project = $this->projects('project2');
		$savedProjectId = $project->id;
		$this->assertTrue( $project->delete() );
		$deletedProject = Project::model()->findByPk( $savedProjectId );
		$this->assertEquals( NULL, $deletedProject );
	}
	
	public function testGetUserOptions()
	{
		$project = $this->projects('project1');
		$options = $project->getUserOptions();
		$this->assertTrue( is_array( $options ) );
		$this->assertTrue( count( $options ) > 0 );
	}
	
	public function testUserRoleAssignment()
	{
		$project = $this->projects('project1');
		$user = $this->users('user1');
		$this->assertEquals(1, $project->associateUserToRole('owner', $user->id));
		$this->assertEquals(1, $project->removeUserFromRole('owner', $user->id));
	}
	
	public function testIsInRole()
	{
		$row1 = $this->projUsrRole['row1'];
		Yii::app()->user->setId($row1['user_id']);
		$project = Project::model()->findByPk($row1['project_id']);
		$this->assertTrue($project->isUserInRole('member'));
	}
	
	public function testUserAccessBasedOnProjectRole()
	{
		$row1 = $this->projUsrRole['row1'];
		Yii::app()->user->setId($row1['user_id']);
		$project = Project::model()->findByPk($row1['project_id']);
		$auth = Yii::app()->authManager;
		$bizRule = 'return isset($params["project"]) && $params["project"]->isUserInRole("member");';
		$auth->assign('member', $row1['user_id'], $bizRule);
		$params = array('project'=>$project);
		$this->assertTrue(Yii::app()->user->checkAccess('updateIssue', $params));
		$this->assertTrue(Yii::app()->user->checkAccess('readIssue', $params));
		$this->assertFalse(Yii::app()->user->checkAccess('updateProject', $params));
		
		//Check that user can't access projects they are not assigned to
		$project = Project::model()->findByPk(1);
		$params = array('project'=>$project);
		$this->assertFalse(Yii::app()->user->checkAccess('updateIssue', $params));
		$this->assertFalse(Yii::app()->user->checkAccess('readIssue', $params));
		$this->assertFalse(Yii::app()->user->checkAccess('updateProject', $params));
	}
	
	public function testGetUserRoleOptions()
	{
		$options = Project::getUserRoleOptions();
		$this->assertEquals(count($options), 3);
		$this->assertTrue(isset($options['reader']));
		$this->assertTrue(isset($options['member']));
		$this->assertTrue(isset($options['owner']));
	}
	
	public function testUserProjectAssignment()
	{
		$this->projects('project2')->associateUserToProject($this->users('user1'));
		$this->assertTrue($this->projects('project1')->isUserInProject($this->users('user1')));
	}
}