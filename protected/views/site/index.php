<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<? if (!Yii::app()->user->isGuest) : ?>
<p>
	You last logged in on <?=date('l, F d, Y, g:i a', Yii::app()->user->lastLoginTime);?>
</p>

<p>
	<?=CHtml::link('Projects', array('project/index'));?>
</p>
<p>
	<?=CHtml::link('Users', array('user/index'));?>
</p>
<? endif; ?>

<p>
	Hi! This is the homepage!
</p>