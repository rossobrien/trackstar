<?php

class IssueController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';
	
	/*
	 * @var private property containing the project model
	 */
	private $_project = NULL;

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			'postOnly + delete', // we only allow deletion via POST request
			'projectContext + create index admin', // only allow creation via valid context
		);
	}
	
	/**
	 * Filter method to ensure we are in a valid project context
	 * 
	 * @param $filterChain
	 */
	public function filterProjectContext( $filterChain )
	{
		//Get project ID from GET/POST request
		$projectId = NULL;
		if ( isset( $_GET['pid'] ) )
		{
			$projectId = $_GET['pid'];
		} else if ( isset( $_POST['pid'] ) )
		{
			$projectId = $_POST['pid'];
		}
		
		//Load project based on ID
		$this->loadProject( $projectId );
		
		//Run other filters
		$filterChain->run();
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'index', 'view', 'create', and 'update' actions
				'actions'=>array('index','view', 'create','update'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Displays a particular model.
	 * @param integer $id the ID of the model to be displayed
	 */
	public function actionView($id)
	{
		$issue =  $this->loadModel($id);
		$comment = $this->createComment($issue);
		
		$this->render('view',array(
			'model'=>$issue,
			'comment'=>$comment,
		));
	}

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Issue;
		$model->project_id = $this->_project->id;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Issue']))
		{
			$model->attributes=$_POST['Issue'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);
		$this->loadProject($model->project_id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Issue']))
		{
			$model->attributes=$_POST['Issue'];
			if($model->save())
				$this->redirect(array('view','id'=>$model->id));
		}

		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();

		// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
		if(!isset($_GET['ajax']))
			$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Issue', array(
				'criteria' => array(
						'condition' => 'project_id=:projectId',
						'params' => array(':projectId'=>$this->_project->id),						
				),
		));
		$this->render('index',array(
			'dataProvider'=>$dataProvider,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Issue('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Issue']))
			$model->attributes=$_GET['Issue'];

		$model->project_id = $this->_project->id;
		
		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Issue::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}
	
	/**
	 * Load project model
	 * 
	 * @param $project_id Primary ID of project
	 * @throws CHttpException
	 * 
	 * @return object The project model
	 */
	protected function loadProject( $project_id )
	{
		//No project, so get project by ID
		if ( $this->_project === NULL )
		{
			$this->_project = Project::model()->findByPk( $project_id );
			
			//No project, throw error
			if ( $this->_project === NULL )
			{
				throw new CHttpException(404, 'The requested project does not exist!');
			}
		}
		
		return $this->_project;
	}
	
	/**
	 * Return project model
	 */
	public function getProject()
	{
		return $this->_project;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='issue-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
	
	protected function createComment($issue)
	{
		$comment = new Comment;
		if(isset($_POST['Comment']))
		{
			$comment->attributes = $_POST['Comment'];
			if ($issue->addComment($comment))
			{
				Yii::app()->user->setFlash('commentSubmitted', "Your comment has been added.");
				$this->refresh();
			}
		}
		return $comment;
	}
}
