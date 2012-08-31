<?php
/*
 * Form to add user to project
 */
class ProjectUserForm extends CFormModel
{
	//Username to add
	public $username;
	
	//Role to add user as
	public $role;
	
	//Project to add user to
	public $project;
	
	//Validation rules
	public function rules()
	{
		return array(
				array('username, role', 'required'),
				array('username', 'exist', 'className' => 'User'),
				array('username','verify'),
		);
	}
	
	//Check for user in system and add to project
	public function verify($attribute,$params)
	{
		if (!$this->hasErrors())
		{
			$user = User::model()->findByAttributes(array('username' => $this->username));
			
			if ($this->project->isUserInProject($user))
			{
				$this->addError('username','User has already been added to this project!');
			} else
			{
				$this->project->associateUserToProject($user);
				$this->project->associateUserToRole($this->role,$user->id);
				$auth = Yii::app()->authManager;
				$bizRule = 'return isset($parmas["project"]) && $params["project"]->isUserInRole("' . $this->role . '");';
				$auth->assign($this->role, $user->id, $bizRule);
			}
		}
	}
}