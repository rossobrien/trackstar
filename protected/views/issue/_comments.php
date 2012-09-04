<? foreach($comments as $comment) : ?>
<div class="comment">
	<div class="author">
		<?=$comment->author->username; ?>
	</div>
	
	<div class="time">
		on <?=date('F j, Y \a\t h:i A', strtotime($comment->create_time)); ?>
	</div>
	
	<div class="content">
		<?=nl2br(CHtml::encode($comment->content)); ?>
	</div>
	<hr>
</div>
<? endforeach; ?>