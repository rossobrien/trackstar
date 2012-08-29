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
						'create_time'		=> '2012-08-29 10:00:00',
						'create_user_id'	=> 1,
						'update_time'		=> '2012-08-29 10:00:00',
						'update_user_id'	=> 1,
					)
		);
		$this->assertTrue( $newProject->save( false ) );
		
		//Read newly created project
		$retrievedProject = Project::model()->findByPk( $newProject->id );
		$this->assertTrue( $retrievedProject instanceof Project );
		$this->assertEquals( $newProjectName, $retrievedProject->name );
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