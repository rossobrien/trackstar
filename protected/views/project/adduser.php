<?php
$this->pageTitle = Yii::app()->name . ' - Add User To Project';

$this->breadcrumbs = array(
		$model->project->name => array('view','id'=>$model->project->id), 
		'Add User',
);

$this->menu = array(
		array(
				'label' => 'Back To Project',
				'url' => array('view', 'id' => $model->project->id)
		),
);
?>
<h1>Add User To <?=$model->project->name; ?></h1>

<? if (Yii::app()->user->hasFlash('success')) : ?>
<div class="successMessage">
	<?=Yii::app()->user->getFlash('success'); ?>
</div>
<? endif; ?>

<div class="form">
	<? $form = $this->beginWidget('CActiveForm'); ?>
	
	<p class="note">
		Fields with <span class="required">*</span> are required.
	</p>
	
	<div class="row">
		<?=$form->labelEx($model,'username'); ?>
		
		<? $this->widget('CAutoComplete', array(
				'model' => $model,
				'attribute' => 'username',
				'data' => $usernames,
				'multiple' => false,
				'htmlOptions' => array('size'=>25),
		)); ?>
		
		<?=$form->error($model,'username'); ?>
	</div>
	
	<div class="row">
		<?=$form->labelEx($model,'role'); ?>
		<?=$form->dropDownList($model,'role',Project::getUserRoleOptions()); ?>
		<?=$form->error($model,'role'); ?>
	</div>
	
	<div class="row buttons">
		<?=CHtml::submitButton('Add User'); ?>
	</div>
	
<? $this->endWidget(); ?>
</div>