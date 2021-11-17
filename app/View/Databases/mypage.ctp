<?php $this->assign('title', 'マイページ | 数理技術相談データベース'); ?>

<div class="container">
	<div class="row">
		<div class="col-12 col-sm-8 offset-sm-2">
			<div class="card-group">
				<?php if ( $event['Event']['status'] == 0 ): ?>
					<div class="card border-success mb-3">
						<div class="card-body">
							<h5 class="card-title">
								<?php echo $this->Html->link('企画応募の修正', array('action' => 'edit1'), array('class' => 'text-success')); ?>
							</h5>
							<p class="card-text">
								企画の内容を修正したい場合はこちらより修正して下さい。<br>
								<br><br>
							</p>
						</div>
						<div class="card-footer">
							<small class="text-muted">
								<?php echo $this->Html->link('<i class="fa fa-arrow-circle-right text-success" aria-hidden="true"></i> 修正する', array('action' => 'edit1'), array('escape' => false, 'class' => 'text-success')); ?>
							</small>
						</div>
					</div>
				<?php else: ?>
					<div class="card border-secondary mb-3">
						<div class="card-body">
							<h5 class="card-title">
								企画応募の修正
							</h5>
							<p class="card-text">
								現在企画の内容を編集出来ません。
							</p>
						</div>
						<div class="card-footer">
							<small class="text-muted">
								&nbsp;
							</small>
						</div>
					</div>
				<?php endif; ?>
				
				<?php if ( $event['Event']['status'] == 3 || $event['Event']['status'] == 4 ): ?>
					<div class="card border-info mb-3">
						<div class="card-body">
							<h5 class="card-title">
								<?php echo $this->Html->link('企画報告書の作成・修正', array('action' => 'report_add1'), array('class' => 'text-info')); ?>
							</h5>
							<p class="card-text">
								報告書はこちらより作成して下さい。
							</p>
						</div>
						<div class="card-footer">
							<small class="text-muted">
								<?php echo $this->Html->link('<i class="fa fa-arrow-circle-right text-info" aria-hidden="true"></i> 作成・修正する', array('action' => 'report_add1'), array('escape' => false, 'class' => 'text-info')); ?>
							</small>
						</div>
					</div>
				<?php else: ?>
					<div class="card border-secondary mb-3">
						<div class="card-body">
							<h5 class="card-title">
								企画報告書の提出
							</h5>
							<p class="card-text">
								企画実施後に報告書の提出が可能になります。
							</p>
						</div>
						<div class="card-footer">
							<small class="text-muted">
								&nbsp;
							</small>
						</div>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>