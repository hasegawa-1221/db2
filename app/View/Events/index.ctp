<h2>企画応募一覧</h2>
<div class="text-right">
	<?php echo $this->Html->link('新規作成', array('controller' => 'events', 'action' => 'add'), array('class' => 'btn btn-lg btn-danger')); ?>
</div>

<div class="well">
	<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'index', 'class' => 'form-inline')); ?>
	<?php echo $this->Form->end(); ?>
</div>

<?php if ( !empty($events) ): ?>
	<?php echo $this->Element('paginate'); ?>
	<div class="table-responsive">
		<table class="table table-bordered table-striped">
			<tr>
				<th><?php echo $this->Paginator->sort('Event.id',					'DB-ID'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.is_finished',			'申請'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.type',					'種別'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.event_number',			'企画番号'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.title',				'企画タイトル'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.start',				'実施日'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.status',				'ステータス'); ?></th>
				<th><?php echo $this->Paginator->sort('Event.created',				'データ作成日'); ?></th>
				<th colspan="6">編集</th>
				<th>表示</th>
			</tr>
			<?php
			$i = 0;
			foreach ( $events as $event ): ?>
				<tr>
					<td><?php echo $event['Event']['id']; ?></td>
					<td>
						<?php
						if ( $event['Event']['is_finished'] )
						{
							echo '<span class="badge badge-success">完了</span>';
						}
						?>
					</td>
					<td><?php echo $this->Display->get_event_type($event['Event']['type']); ?></td>
					<td><?php echo $event['Event']['event_number']; ?></td>
					<td><?php echo $this->Html->link($event['Event']['title'], '#', array('data-toggle' => 'modal', 'data-target' => '.bd-example-modal-lg-' . $event['Event']['id'])); ?></td>
					<td><?php echo date('Y/m/d', strtotime($event['Event']['start'])); ?>～<?php echo date('Y/m/d', strtotime($event['Event']['end'])); ?></td>
					<td><?php echo $event_status[$event['Event']['status']]; ?></td>
					<td><?php echo date('Y/m/d', strtotime($event['Event']['created'])); ?></td>
					<td><?php echo $this->Html->link('管理',	array('action' => 'edit', $event['Event']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('概要',	array('action' => 'edit1', $event['Event']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('詳細',	array('action' => 'edit2', $event['Event']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('経費',	array('action' => 'edit3', $event['Event']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('参加',	array('action' => 'edit4', $event['Event']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('責任者',	array('action' => 'edit5', $event['Event']['id']), array('escape' => false)); ?></td>
					<td><?php echo $this->Html->link('表示',	array('action' => 'view', $event['Event']['id']), array('escape' => false, 'target' => '_blank')); ?></td>
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
								
								<h5>基本情報</h5>
								<div class="container mb-4">
									<div class="row">
										<div class="col-6">作成日：<?php echo $event['Event']['created']; ?></div>
										<div class="col-6">更新日：<?php echo $event['Event']['modified']; ?></div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">作成者：<?php echo $event['AddUser']['username']; ?></div>
										<div class="col-6">最終更新者：<?php echo $event['LatestUser']['username']; ?></div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">種別：<?php echo $event_type[$event['Event']['type']]; ?></div>
										<div class="col-6">企画番号：<?php echo $event['Event']['event_number']; ?></div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">ログインID：<?php echo $event['Event']['username']; ?></div>
										<div class="col-6">状態：<?php echo $event_status[$event['Event']['status']]; ?></div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">内部コメント:<br>
											<?php echo nl2br($event['Event']['comment']); ?>
										</div>
									</div>
									<hr>
								</div>

								<h5>企画の概要</h5>
								<div class="container mb-4">
									<div class="row">
										<div class="col-12">名称：<?php echo $event['Event']['title']; ?></div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											集会等のタイプ	：<br>
											<?php if ( !empty($event['EventTheme'] ) ): ?>
												<?php foreach ( $event['EventTheme'] as $theme ): ?>
													・<?php echo $theme['Theme']['name']; ?>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											連携相手の分野・業界：<br>
											<?php echo $event['Event']['field']; ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											キーワード：<br>
											<?php
											if ( !empty($event['EventKeyword']) )
											{
												foreach ( $event['EventKeyword'] as $event_keyword )
												{
													if ( !empty($event_keyword['title']) )
													{
														echo $event_keyword['title'], ',';
													}
												}
											}
											?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">
											主催機関：<?php echo $event['Event']['organization']; ?>
										</div>
										<div class="col-6">
											開催場所：<?php echo nl2br($event['Event']['place']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											開催時期：<?php echo $event['Event']['start']; ?> ～ <?php echo $event['Event']['end']; ?>
										</div>
									</div>
									<hr>
								</div>

								<h5>企画の詳細</h5>
								<div class="container mb-4">
									<div class="row">
										<div class="col-12">
											プログラム：<br>
											<?php echo nl2br($event['Event']['program']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											趣旨・目的：<br>
											<?php echo nl2br($event['Event']['purpose']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											取り扱うテーマ・トピックや解決すべき課題：<br>
											<?php echo nl2br($event['Event']['subject']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											考えられる数学・数理科学的アプローチ：<br>
											<?php echo nl2br($event['Event']['approach']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											これまでの準備状況：<br>
											<?php echo nl2br($event['Event']['prepare']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											終了後のフォローアップの計画：<br>
											<?php echo nl2br($event['Event']['follow']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											他機関からの支援：<br>
											<?php echo ($event['Event']['is_support'])?'有':'無'; ?>
										</div>
										<div class="col-12">
											有の場合は支援元：<br>
											<?php echo nl2br($event['Event']['support']); ?>
										</div>
									</div>
									<hr>
								</div>

								<h5>経費</h5>
								<div class="container">
									<?php if ( !empty($event['Expense']) ): ?>
										<?php foreach ( $event['Expense'] as $type => $expense ): ?>
											<?php if ( $type == 1 ): ?>
												<div class="row">
													<div class="col-12"><?php echo '・旅費'; ?></div>
												</div>
												<?php foreach( $expense as $ex ): ?>
													<div class="row">
														<div class="col-2"><?php echo $ex['affiliation']; ?></div>
														<div class="col-2"><?php echo $ex['job']; ?></div>
														<div class="col-2"><?php echo $ex['lastname']; ?> <?php echo $ex['firstname']; ?></div>
														<div class="col-2"><?php echo $ex['title']; ?></div>
														<div class="col-2"><?php echo ($ex['request_price'])?number_format($ex['request_price']) . '円 ':' '; ?></div>
														<div class="col-2"><?php echo $ex['note']; ?></div>
													</div>
												<?php endforeach; ?>
											<?php elseif ( $type == 2 ): ?>
												<div class="row">
													<div class="col-12"><?php echo '・諸謝金'; ?></div>
												</div>
												<?php foreach( $expense as $ex ): ?>
													<div class="row">
														<div class="col-2"><?php echo $ex['affiliation']; ?></div>
														<div class="col-2"><?php echo $ex['job']; ?></div>
														<div class="col-2"><?php echo $ex['lastname']; ?> <?php echo $ex['firstname']; ?></div>
														<div class="col-2"><?php echo $ex['title']; ?></div>
														<div class="col-2"><?php echo ($ex['request_price'])?number_format($ex['request_price']) . '円 ':' '; ?></div>
														<div class="col-2"><?php echo $ex['note']; ?></div>
													</div>
												<?php endforeach; ?>
											<?php elseif ( $type == 3 ): ?>
												<div class="row">
													<div class="col-12"><?php echo '・印刷製本費'; ?></div>
												</div>
												<?php foreach( $expense as $ex ): ?>
													<div class="row">
														<div class="col-2"><?php echo $ex['title']; ?></div>
														<div class="col-2"><?php echo $ex['count']; ?></div>
														<div class="col-2"><?php echo $ex['price']; ?></div>
														<div class="col-2"><?php echo ($ex['request_price'])?number_format($ex['request_price']) . '円 ':' '; ?></div>
														<div class="col-2"><?php echo $ex['note']; ?></div>
													</div>
												<?php endforeach; ?>
											<?php elseif ( $type == 4 ): ?>
												<div class="row">
													<div class="col-12"><?php echo '・その他 '; ?></div>
												</div>
												<?php foreach( $expense as $ex ): ?>
													<div class="row">
														<div class="col-2"><?php echo $ex['title']; ?></div>
														<div class="col-2"><?php echo $ex['count']; ?></div>
														<div class="col-2"><?php echo $ex['price']; ?></div>
														<div class="col-2"><?php echo ($ex['request_price'])?number_format($ex['request_price']) . '円 ':' '; ?></div>
														<div class="col-2"><?php echo $ex['note']; ?></div>
													</div>
												<?php endforeach; ?>
											<?php endif; ?>
											<hr>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>

								<h5>参加について</h5>
								<div class="container mb-4">
									<div class="row">
										<div class="col-6">
											参加制限：<?php echo ($event['Event']['qualification'])?'有':'無'; ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">
											有の場合は参加資格：<?php echo $event['Event']['qualification_other']; ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">
											参加申込：<?php echo $options1[$event['Event']['qualification_apply']]; ?>
										</div>
									</div>
									<hr>
								</div>

								<h5>運営責任者</h5>
								<div class="container mb-4">
									<?php if ( !empty($event['EventManager']) ): ?>
										<?php foreach ( $event['EventManager'] as $event_manager ): ?>
											<div class="row">
												<div class="col-12">
													メールアドレス：<?php echo $event_manager['email']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													氏名：<?php echo $event_manager['lastname']; ?> <?php echo $event_manager['firstname']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													フリガナ：<?php echo $event_manager['lastname_kana']; ?> <?php echo $event_manager['firstname_kana']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													所属機関：<?php echo $event_manager['organization']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													所属部局：<?php echo $event_manager['department']; ?>
												</div>
												<div class="col-6">
													職名：<?php echo $event_manager['job_title']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													郵便番号：<?php echo $event_manager['zip']; ?>
												</div>
												<div class="col-6">
													都道府県：<?php echo $prefectures[$event_manager['prefecture_id']]; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													市区町村：<?php echo $event_manager['city']; ?>
												</div>
												<div class="col-6">
													住所：<?php echo $event_manager['address']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													TEL：<?php echo $event_manager['tel']; ?>
												</div>
												<div class="col-6">
													FAX：<?php echo $event_manager['fax']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													URL：<?php echo $event_manager['url']; ?>
												</div>
											</div>
											<hr>
										<?php endforeach; ?>
									<?php endif; ?>
									<hr>
								</div>
								
								<h5>事務担当者</h5>
								<div class="container mb-4">
									<?php if ( !empty($event['EventAffair']) ): ?>
										<?php foreach ( $event['EventAffair'] as $event_manager ): ?>
											<div class="row">
												<div class="col-12">
													メールアドレス：<?php echo $event_manager['email']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													氏名：<?php echo $event_manager['lastname']; ?> <?php echo $event_manager['firstname']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													フリガナ：<?php echo $event_manager['lastname_kana']; ?> <?php echo $event_manager['firstname_kana']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													所属機関：<?php echo $event_manager['organization']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													所属部局：<?php echo $event_manager['department']; ?>
												</div>
												<div class="col-6">
													職名：<?php echo $event_manager['job_title']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													郵便番号：<?php echo $event_manager['zip']; ?>
												</div>
												<div class="col-6">
													都道府県：<?php echo $prefectures[$event_manager['prefecture_id']]; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													市区町村：<?php echo $event_manager['city']; ?>
												</div>
												<div class="col-6">
													住所：<?php echo $event_manager['address']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													TEL：<?php echo $event_manager['tel']; ?>
												</div>
												<div class="col-6">
													FAX：<?php echo $event_manager['fax']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													URL：<?php echo $event_manager['url']; ?>
												</div>
											</div>
											<hr>
										<?php endforeach; ?>
									<?php endif; ?>
									<hr>
								</div>

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