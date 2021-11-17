<?php $this->assign('title', '報告書作成 | 数理技術相談データベース'); ?>

<?php echo $this->Form->create(null, array('type' => 'file', 'url' => 'report_complete')); ?>
	<div class="container">
		<h2>報告書の作成</h2>
		
		<ul class="page-navi">
			<li class="disabled">報告書の概要</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">報告書の詳細</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">添付ファイル</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="active">入力内容確認</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">完了</li>
		</ul>
		<hr>
		
		<div class="row">
			<div class="col-12">
				<h4>報告書の概要</h4>
				<table class="table table-bordered">
					<tr>
						<th class="bg-light w-25">名称</th>
						<td><?php echo $this->request->data['Event']['title']; ?></td>
					</tr>
					<tr>
						<th class="bg-light">企画番号</th>
						<td><?php echo $this->request->data['Event']['event_number']; ?></td>
					</tr>
					<tr>
						<th class="bg-light">該当する重点テーマ</th>
						<td>
							<?php
							if( !empty($this->request->data['EventTheme']) )
							{
								foreach ( $this->request->data['EventTheme'] as $id => $event_theme )
								{
									if ( $event_theme['id'] == 1 )
									{
										echo '・' . $themes[$id];
										echo '<br>';
									}
								}
							}
							?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">連携分野</th>
						<td><?php echo $this->request->data['Event']['field']; ?></td>
					</tr>
					<tr>
						<th class="bg-light">キーワード</th>
						<td>
							<?php
							if ( !empty($this->request->data['EventKeyword']) )
							{
								foreach ( $this->request->data['EventKeyword'] as $event_keyword )
								{
									if ( !empty($event_keyword['title']) )
									{
										echo '・' . $event_keyword['title'];
										echo '<br>';
									}
								}
							}
							?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">主催機関</th>
						<td><?php echo $this->request->data['Event']['organization']; ?></td>
					</tr>
					<tr>
						<th class="bg-light">開催時期</th>
						<td>
							<?php echo $this->request->data['Event']['start']; ?>～
							<?php echo $this->request->data['Event']['end']; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">開催場所</th>
						<td><?php echo $this->request->data['Event']['place']; ?></td>
					</tr>
				</table>
				<br>
				
				<h4>運営責任者</h4>
				<?php if ( !empty($this->request->data['EventManager']) ): ?>
					<?php foreach ( $this->request->data['EventManager'] as $event_manager): ?>
						<table class="table2 table-bordered">
							<tr>
								<th class="bg-light" nowrap="nowrap" style="width:25%;">参加者ID<br>(メールアドレス)</th>
								<td style="width:25%;">
									<?php echo $event_manager['email']; ?>
								</td>
								<th class="bg-light" style="width:25%;">&nbsp;</th>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<th class="bg-light" nowrap="nowrap">姓名</th>
								<td>
									<div class="container">
										<div class="row">
											<?php echo $event_manager['lastname']; ?>&nbsp;
											<?php echo $event_manager['firstname']; ?>
										</div>
									</div>
								</td>
								<th class="bg-light" nowrap="nowrap">フリガナ</th>
								<td>
									<div class="container">
										<div class="row">
											<?php echo $event_manager['lastname_kana']; ?>&nbsp;
											<?php echo $event_manager['firstname_kana']; ?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th class="bg-light">所属機関</th>
								<td>
									<?php echo $event_manager['organization']; ?>
								</td>
								<th class="bg-light">所属部局</th>
								<td>
									<?php echo $event_manager['department']; ?>
								</td>
							</tr>
							<tr>
								<th class="bg-light">職名</th>
								<td>
									<?php echo $event_manager['job_title']; ?>
								</td>
								<th class="bg-light">URL</th>
								<td>
									<?php echo $event_manager['url']; ?>
								</td>
							</tr>
							<tr>
								<th class="bg-light" nowrap="nowrap">郵便番号及び<br>ZIP CODE</th>
								<td>
									<?php echo $event_manager['zip']; ?>
								</td>
								<th class="bg-light">都道府県</th>
								<td>
									<?php echo $prefectures[ $event_manager['prefecture_id']]; ?>
								</td>
							</tr>
							<tr>
								<th class="bg-light">市区町村</th>
								<td>
									<?php echo $event_manager['city']; ?>
								</td>
								<th class="bg-light">住所</th>
								<td>
									<?php echo $event_manager['address']; ?>
								</td>
							</tr>
							<tr>
								<th class="bg-light">TEL</th>
								<td>
									<?php echo $event_manager['tel']; ?>
								</td>
								<th class="bg-light">FAX</th>
								<td>
									<?php echo $event_manager['fax']; ?>
								</td>
							</tr>
						</table>
						<br>
					<?php endforeach; ?>
					<br>
				<?php endif; ?>
				
				<h4>事務担当者</h4>
				<?php if ( !empty($this->request->data['EventAffair']) ): ?>
					<?php foreach ( $this->request->data['EventAffair'] as $event_affair): ?>
						<table class="table table-bordered">
							<tr>
								<th class="bg-light" nowrap="nowrap" style="width:25%;">参加者ID<br>(メールアドレス)</th>
								<td style="width:25%;">
									<?php echo $event_affair['email']; ?>
								</td>
								<th class="bg-light" style="width:25%;">&nbsp;</th>
								<td>&nbsp;</td>
							</tr>
							<tr>
								<th class="bg-light" nowrap="nowrap">姓名</th>
								<td>
									<div class="container">
										<div class="row">
											<?php echo $event_affair['lastname']; ?>&nbsp;
											<?php echo $event_affair['firstname']; ?>
										</div>
									</div>
								</td>
								<th class="bg-light" nowrap="nowrap">フリガナ</th>
								<td>
									<div class="container">
										<div class="row">
											<?php echo $event_affair['lastname_kana']; ?>&nbsp;
											<?php echo $event_affair['firstname_kana']; ?>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th class="bg-light">所属機関</th>
								<td>
									<?php echo $event_affair['organization']; ?>
								</td>
								<th class="bg-light">所属部局</th>
								<td>
									<?php echo $event_affair['department']; ?>
								</td>
							</tr>
							<tr>
								<th class="bg-light">職名</th>
								<td>
									<?php echo $event_affair['job_title']; ?>
								</td>
								<th class="bg-light">URL</th>
								<td>
									<?php echo $event_affair['url']; ?>
								</td>
							</tr>
							<tr>
								<th class="bg-light" nowrap="nowrap">郵便番号及び<br>ZIP CODE</th>
								<td>
									<?php echo $event_affair['zip']; ?>
								</td>
								<th class="bg-light">都道府県</th>
								<td>
									<?php echo $prefectures[$event_affair['prefecture_id']]; ?>
								</td>
							</tr>
							<tr>
								<th class="bg-light">市区町村</th>
								<td>
									<?php echo $event_affair['city']; ?>
								</td>
								<th class="bg-light">住所</th>
								<td>
									<?php echo $event_affair['address']; ?>
								</td>
							</tr>
							<tr>
								<th class="bg-light">TEL</th>
								<td>
									<?php echo $event_affair['tel']; ?>
								</td>
								<th class="bg-light">FAX</th>
								<td>
									<?php echo $event_affair['fax']; ?>
								</td>
							</tr>
						</table>
						<br>
					<?php endforeach; ?>
					<br>
				<?php endif; ?>
				
				<h4>プログラム</h4>
				<table class="table table-bordered">
					<tr>
						<th class="bg-light w-25">プログラム</th>
						<td><?php echo nl2br($this->request->data['EventProgram']['program']); ?></td>
					</tr>
				</table>
				<br>
				

				<h4>報告書の詳細</h4>
				<table class="table table-bordered">
					<tr>
						<th class="bg-light w-25">当日の論点</th>
						<td>
							<?php echo $this->request->data['Event']['issue']; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">研究の現状と課題<br>（既にできていること、<br>できていないことの切り分け）</th>
						<td>
							<?php echo $this->request->data['Event']['subject']; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">新たに明らかになった課題、<br>今後解決すべきこと</th>
						<td>
							<?php echo $this->request->data['Event']['new_subject']; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">今後の展開・フォローアップ</th>
						<td>
							<?php echo $this->request->data['Event']['follow']; ?>
						</td>
					</tr>
				</table>
				
				<h4>添付ファイル</h4>
				<?php if ( !empty($event2['EventFile']) ): ?>
					<?php $i = 1; ?>
					<?php foreach ( $event2['EventFile'] as $key => $event_file ): ?>
						<table class="table table-bordered">
							<tr>
								<th class="bg-light w-25">添付ファイル <?php echo $i; ?></th>
								<td>
									<?php
									if( isset($event_file['file_org']) && is_string($event_file['file_org']) )
									{
										echo $this->Html->link($event_file['file_org'], '/app/webroot/files/event_file/file/' . $event_file['id'] . '/' .  $event_file['file'], array('target' => '_blank') );
										
									}
									?>
								</td>
							</tr>
						</table>
						<?php $i++; ?>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
	
	<div class="text-center">
		<?php echo $this->Html->link('戻る', array('action' => 'report_add3'), array('class' => 'btn btn-secondary')); ?>&nbsp;
		<?php echo $this->Form->submit('上記の内容で報告書を作成する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>