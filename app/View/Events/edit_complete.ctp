	<div class="container">
		<h2>企画応募（完了画面）</h2>
		
		<ul class="page-navi">
			<li class="disabled">
				<?php echo $this->Html->link('企画の概要', array('action' => 'edit1', $event['Event']['id'])); ?>
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				<?php echo $this->Html->link('企画の詳細', array('action' => 'edit2', $event['Event']['id'])); ?>
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				<?php echo $this->Html->link('経費', array('action' => 'edit3', $event['Event']['id'])); ?>
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				<?php echo $this->Html->link('参加について', array('action' => 'edit4', $event['Event']['id'])); ?>
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				<?php echo $this->Html->link('責任者', array('action' => 'edit5', $event['Event']['id'])); ?>
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				入力内容確認
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="active">
				完了
			</li>
		</ul>
		<hr>

		<div class="row">
			<div class="col-12">
				ご応募ありがとうございました。<br>
				企画の採択の結果は後日、ご登録のメールアドレスにお送りさせて頂きます。
			</div>
		</div>
		<hr>
		
<br>
<br>