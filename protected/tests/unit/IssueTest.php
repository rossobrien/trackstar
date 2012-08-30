<?php
class IssueTest extends CDbTestCase
{
	public $fixtures = array(
			'projects' => 'Project',
			'issues' => 'Issue',
	);
	
	public function testGetTypes()
	{
		$options = Issue::model()->typeOptions;
		$this->assertTrue( is_array( $options ) );
		$this->assertTrue( 3 == count( $options ) );
		$this->assertTrue( in_array( 'Bug', $options ) );
		$this->assertTrue( in_array( 'Feature', $options ) );
		$this->assertTrue( in_array( 'Task', $options ) );
	}
	
	public function testGetStatus()
	{
		$options = Issue::model()->statusOptions;
		$this->assertTrue( is_array( $options ) );
		$this->assertTrue( 3 == count( $options ) );
		$this->assertTrue( in_array( 'New', $options ) );
		$this->assertTrue( in_array( 'In Progress', $options ) );
		$this->assertTrue( in_array( 'Finished', $options ) );
	}
	
	public function testGetStatusText()
	{
		$this->assertTrue('In Progress' == $this->issues('issueBug')->getStatusText());
	}
	
	public function testGetTypeText()
	{
		$this->assertTrue('Bug' == $this->issues('issueBug')->getTypeText());
	}
}