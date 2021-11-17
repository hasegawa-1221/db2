<div class="row">
	<div class="col-md-12">
		<ul class="new-pagination">
			<?php echo $this->Paginator->prev(__('＜'), array('tag' => 'li'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a')); ?>
			<?php echo $this->Paginator->numbers(array('separator' => '','currentTag' => 'a', 'currentClass' => 'active','tag' => 'li','first' => 1, 'ellipsis' => '<li class="disabled"><a>...</a></li>')); ?>
			<?php echo $this->Paginator->next(__('＞'), array('tag' => 'li','currentClass' => 'disabled'), null, array('tag' => 'li','class' => 'disabled','disabledTag' => 'a')); ?>
		</ul>
	</div>
</div>