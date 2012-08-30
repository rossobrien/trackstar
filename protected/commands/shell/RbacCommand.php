<?php
class RbacCommand extends CConsoleCommand
{
	private $_authManager;

	public function getHelp()
	{
		return <<<EOD
		USAGE
			rbac

		DESCRIPTION
			This commands generates an initial RBAC authorization hierarchy.
		
EOD;
	}
	
	/*
	 * Execute the action
	 * @param array command line params specific for this command
	 */
	public function run($args)
	{
		//Check that authManager is defined
		if(($this->_authManager = Yii::app()->authManager)===NULL)
		{
			echo "Error: No Authorization Manager defined. Please quit and configure 'authManager'\n";
			return;
		}
		
		//Prompt user for authorization to run command
		echo "This command will create Owner, Member, and Reader roles and CRUD permissions for:\n";
		echo "users, projects, and issues.\n";
		echo "Would you like to continue? [Yes|No] ";
		
		//Check input
		if(!strncasecmp(trim(fgets(STDIN)),'y',1))
		{
			$this->_authManager->clearAll();
			
			//Create basic operations for users
			$this->_authManager->createOperation('createUser', 'create a new user');
			$this->_authManager->createOperation('readUser', 'read user info');
			$this->_authManager->createOperation('updateUser', 'update user info');
			$this->_authManager->createOperation('deleteUser', 'delete a user from project');
			
			//Create basic operations for projects
			$this->_authManager->createOperation('createProject', 'create a new project');
			$this->_authManager->createOperation('readProject', 'read project info');
			$this->_authManager->createOperation('updateProject', 'update project info');
			$this->_authManager->createOperation('deleteProject', 'delete a project');
			
			//Create basic operations for issues
			$this->_authManager->createOperation('createIssue', 'create a new issue');
			$this->_authManager->createOperation('readIssue', 'read issue info');
			$this->_authManager->createOperation('updateIssue', 'update issue info');
			$this->_authManager->createOperation('deleteIssue', 'delete an issue from project');
			
			//create reader role and add permissions
			$role = $this->_authManager->createRole('reader');
			$role->addChild('readUser');
			$role->addChild('readProject');
			$role->addChild('readIssue');
			
			//create member role and add permissions
			$role = $this->_authManager->createRole('member');
			$role->addChild('reader');
			$role->addChild('createIssue');
			$role->addChild('updateIssue');
			$role->addChild('deleteIssue');
			
			//create owner role and add permissions
			$role = $this->_authManager->createRole('owner');
			$role->addChild('reader');
			$role->addChild('member');
			$role->addChild('createUser');
			$role->addChild('updateUser');
			$role->addChild('deleteUser');
			$role->addChild('createProject');
			$role->addChild('updateProject');
			$role->addChild('deleteProject');
			
			//Display success message
			echo "Authorization hierarchy successfully generated.";	
		}
	}
}