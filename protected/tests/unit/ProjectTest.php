<?php
class ProjectTest extends CDbTestCase
{
	public function testCRUD()
	{
		//Create new project
		$newProject = new Project;
		$newProjectName = 'Test Project 1';
		$newProject->setAttributes(
				array(
						'name' => $newProjectName,
						'description'		=> 'Unit test for project table',
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
		
		//Update project
		$updatedProjectName = 'Updated Test Project 1';
		$newProject->name = $updatedProjectName;
		$this->assertTrue( $newProject->save( false ) );
		
		//Test updated project
		$updatedProject = Project::model()->findByPk( $newProject->id );
		$this->assertTrue( $updatedProject instanceof Project );
		$this->assertEquals( $updatedProjectName, $updatedProject->name );
		
		//Delete test project
		$newProjectId = $newProject->id;
		$this->assertTrue( $newProject->delete() );
		$deletedProject = Project::model()->findByPk( $newProjectId );
		$this->assertEquals( NULL, $deletedProject );
	}
	
}