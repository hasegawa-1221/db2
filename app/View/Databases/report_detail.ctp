<?php
$this->assign('title', $event_program['EventProgram']['title'] . ' | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('数学講演課題データベース', '/databases/reports/');
$this->Html->addCrumb($event_program['EventProgram']['title'], '/databases/report_detail/' . $event_program['EventProgram']['id']);
?>

<div class="container">
	<div class="row">
		<div class="col-12">
			<div class="alert mb-2">
				<div class="row">
					<div class="col-12">
						<h5><?php echo $event_program['EventProgram']['title']; ?></h5>
					</div>
					<div class="col-12">
						<?php if ( $event_program['EventPerformer'] ): ?>
							<?php $i= 1; ?>
							<?php foreach ( $event_program['EventPerformer'] as $event_performer ): ?>
								<div class="row ml-2 mb-2">
									<div class="col-12">
										講演者<?php echo $i; ?>：
										<?php echo $event_performer['organization']; ?>&nbsp;
										<?php echo $event_performer['role']; ?>&nbsp;
										<?php echo $event_performer['lastname']; ?>&nbsp;
										<?php echo $event_performer['firstname']; ?>
									</div>
								</div>
								<?php $i++; ?>
							<?php endforeach; ?>
						<?php endif; ?>
						<div class="row ml-2 pt-2">
							<div class="col-12">
								■研究集会情報<br>
								<?php echo $this->Html->link($event_program['Meeting']['event_number'] . ' ' .$event_program['Meeting']['title'], array('action' => 'meeting_detail', $event_program['Meeting']['id']), array()); ?><br>
								主催機関：<?php echo $event_program['Meeting']['organization']; ?><br>
								開催場所：<?php echo $event_program['Meeting']['place']; ?><br>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>