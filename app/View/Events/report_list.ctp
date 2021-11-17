<h2>報告書一覧</h2>
<p>報告書を提出されたデータが表示されます。</p>
<?php if ( !empty($events) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tr>
				<th><?php echo $this->Paginator->sort('Event.id',					'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.type',					'種別'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.event_number',			'企画番号'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.title',				'企画タイトル'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.start',				'実施日'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.status',				'ステータス'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.created',				'データ作成日'); ?></th>
				<th colspan="4">編集</th>
			</tr>
			<?php
			$i = 0;
			foreach ( $events as $event ): ?>
				<tr>
					<td><?php echo $event['Event']['id']; ?></td>
					<td><?php echo $this->Display->get_event_type($event['Event']['type']); ?></td>
					<td><?php echo $event['Event']['event_number']; ?></td>
					<td><?php echo $this->Html->link($event['Event']['title'], '#', array('data-toggle' => 'modal', 'data-target' => '.bd-example-modal-lg-' . $event['Event']['id'])); ?></td>
					<td><?php echo date('Y/m/d', strtotime($event['Event']['start'])); ?>～<?php echo date('Y/m/d', strtotime($event['Event']['end'])); ?></td>
					<td><?php echo $event_status[$event['Event']['status']]; ?></td>
					<td><?php echo date('Y/m/d', strtotime($event['Event']['created'])); ?></td>
					<td><?php echo $this->Html->link('管理',			array('action' => 'report_add1', $event['Event']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('概要',			array('action' => 'report_add1', $event['Event']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('報告書の詳細',	array('action' => 'report_add2', $event['Event']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('添付ファイル',	array('action' => 'report_add3', $event['Event']['id']), array('escape' => false)); ?></td>
				</tr>
				
				
				<div class="modal fade bd-example-modal-lg-<?php echo $event['Event']['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
					<div class="modal-dialog modal-lg">
						 <div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title"><?php echo $event['Event']['title']; ?><small>（<?php echo $event['Event']['event_number']; ?>）</small></h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
							<div class="modal-body">
								
								<h4>プログラム</h4>
								最終プログラム:<br>
								<?php echo nl2br($event['Event']['program']); ?>
								<hr>
								参加者数:<?php echo $event['Event']['join_number']; ?>
								<hr>
								<br>
								
								<h4>報告書の概要</h4>
								名称:<?php echo $event['Event']['title']; ?><br>
								<hr>
								企画番号:<?php echo $event['Event']['event_number']; ?><br>
								<hr>
								集会等のタイプ:<br>
								<?php
								if( !empty($themes) )
								{
									foreach ( $themes as $id => $theme )
									{
										echo $event['EventTheme'][$id]['id'];
										echo '<br>';
									}
								}
								?>
								<hr>
								連携先の分野・業界:<br>
								<?php echo $event['Event']['field']; ?>
								<hr>
								重点テーマ:<br>
								<?php echo nl2br($event['Event']['important']); ?>
								<hr>
								キーワード:<br>
								<?php
								if ( !empty($event['EventKeyword']) )
								{
									foreach ( $event['EventKeyword'] as $key => $event_keyword )
									{
										echo $event['EventKeyword'][$key]['title'];
										echo '&nbsp;';
									}
								}
								?>
								<hr>
								主催機関:<br>
								<?php echo $event['Event']['organization']; ?>
								<hr>
								開催時期:<br>
								<?php echo $event['Event']['start']; ?>～<?php echo $event['Event']['end']; ?>
								<hr>
								開催場所:<?php echo $event['Event']['place']; ?>
								<hr>
								<br>
								
								<h4>報告書の詳細</h4>
								当日の論点:<br>
								<?php echo nl2br($event['Event']['issue']); ?>
								<hr>
								
								研究の現状と課題（既にできていること、できていないことの切り分け）:<br>
								<?php echo nl2br($event['Event']['subject']); ?>
								<hr>
								
								新たに明らかになった課題:<br>
								<?php echo nl2br($event['Event']['new_subject']); ?>
								<hr>
								
								今後解決すべきこと、<br>今後の展開・フォローアップ:<br>
								<?php echo nl2br($event['Event']['follow']); ?>
								<hr>
								<br>
								
								<?php if ( !empty($this->request->data['EventManager']) ): ?>
									<?php $i = 0; ?>
									<?php foreach ( $this->request->data['EventManager'] as $key => $event_manager ): ?>
										<h4>運営責任者<?php echo $i+ 1; ?></h4>
										参加者ID:<?php echo $event['EventManager'][$key]['email']; ?><br>
										姓名:<?php echo $event['EventManager'][$key]['lastname']; ?>&nbsp;<?php echo $event['EventManager'][$key]['firstname']; ?><br>
										フリガナ:<?php echo $event['EventManager'][$key]['lastname_kana']; ?>&nbsp;<?php echo $event['EventManager'][$key]['firstname_kana']; ?><br>
										所属機関:<?php echo $event['EventManager'][$key]['organization']; ?><br>
										所属部局:<?php echo $event['EventManager'][$key]['department']; ?><br>
										職名:<?php echo $event['EventManager'][$key]['job_title']; ?><br>
										URL:<?php echo $event['EventManager'][$key]['url']; ?><br>
										郵便番号:<?php echo $event['EventManager'][$key]['zip']; ?><br>
										都道府県:<?php echo $prefectures[$event['EventManager'][$key]['prefecture_id']]; ?><br>
										市区町村:<?php echo $event['EventManager'][$key]['city']; ?><br>
										住所:<?php echo $event['EventManager'][$key]['address']; ?><br>
										TEL:<?php echo $event['EventManager'][$key]['tel']; ?><br>
										FAX:<?php echo $event['EventManager'][$key]['fax']; ?>
										<hr>
										<?php $i++; ?>
									<?php endforeach; ?>
									<br>
								<?php endif; ?>
								
								<?php if ( !empty($this->request->data['EventAffair']) ): ?>
									<?php $i = 0; ?>
									<?php foreach ( $this->request->data['EventAffair'] as $key => $event_affair ): ?>
										<h4>事務担当者<?php echo $i+ 1; ?></h4>
										参加者ID:<?php echo $event['EventAffair'][$key]['email']; ?><br>
										姓名:<?php echo $event['EventAffair'][$key]['lastname']; ?>&nbsp;<?php echo $event['EventAffair'][$key]['firstname']; ?><br>
										フリガナ:<?php echo $event['EventAffair'][$key]['lastname_kana']; ?>&nbsp;<?php echo $event['EventAffair'][$key]['firstname_kana']; ?><br>
										所属機関:<?php echo $event['EventAffair'][$key]['organization']; ?><br>
										所属部局:<?php echo $event['EventAffair'][$key]['department']; ?><br>
										職名:<?php echo $event['EventAffair'][$key]['job_title']; ?><br>
										URL:<?php echo $event['EventAffair'][$key]['url']; ?><br>
										郵便番号:<?php echo $event['EventAffair'][$key]['zip']; ?><br>
										都道府県:<?php echo $prefectures[$event['EventAffair'][$key]['prefecture_id']]; ?><br>
										市区町村:<?php echo $event['EventAffair'][$key]['city']; ?><br>
										住所:<?php echo $event['EventAffair'][$key]['address']; ?><br>
										TEL:<?php echo $event['EventAffair'][$key]['tel']; ?><br>
										FAX:<?php echo $event['EventAffair'][$key]['fax']; ?>
										<hr>
										<?php $i++; ?>
									<?php endforeach; ?>
								<?php endif; ?>
								
 								<?php $i=0; ?>
								<?php foreach ($event['EventFile'] as $key => $event_file): ?>
									<h4><?php echo '添付ファイル ' . ($i + 1); ?></h4>
									<?php if ( isset($event['EventFile'][$key]['file_org']) && is_string($event['EventFile'][$key]['file_org']) ): ?>
										<?php echo '添付ファイル ' . ($i + 1); ?>:<?php echo $this->Html->link($event['EventFile'][$key]['file_org'], '/app/webroot/files/event_file/file/' . $event['EventFile'][$key]['id'] . '/' . $event['EventFile'][$key]['file'], array('target' => '_blank') ); ?>
									<?php endif; ?>
									<hr>
									<?php $i++; ?>
								<?php endforeach; ?>
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							</div>
						</div>
					</div>
				</div>
				
				
			<?php endforeach; ?>
		</table>
	</div>
	<?php echo $this->Element('paginate'); ?>
<?php else: ?>
	<p>データが存在しません。</p>
<?php endif; ?>