<?php
class ProjectTest extends CDbTestCase
{
	public $fixtures = array(
			'projects' => 'Project',
			'users' => 'User',
			'projUsrAssign' => ':tbl_project_user_assignment',
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
}