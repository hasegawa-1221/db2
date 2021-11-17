
<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'report_complete')); ?>
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
							<?php echo $this->request->data['Event']['keyword1']; ?>
							<?php echo $this->request->data['Event']['keyword2']; ?>
							<?php echo $this->request->data['Event']['keyword3']; ?>
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
				<table class="table table-bordered">
					<tr>
						<th class="bg-light" nowrap="nowrap" style="width:25%;">参加者ID<br>(メールアドレス)</th>
						<td style="width:25%;">
							<?php echo $this->request->data['EventManager']['email']; ?>
						</td>
						<th class="bg-light" style="width:25%;">&nbsp;</th>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<th class="bg-light" nowrap="nowrap">姓名</th>
						<td>
							<div class="container">
								<div class="row">
									<?php echo $this->request->data['EventManager']['lastname']; ?>&nbsp;
									<?php echo $this->request->data['EventManager']['firstname']; ?>
								</div>
							</div>
						</td>
						<th class="bg-light" nowrap="nowrap">フリガナ</th>
						<td>
							<div class="container">
								<div class="row">
									<?php echo $this->request->data['EventManager']['lastname_kana']; ?>&nbsp;
									<?php echo $this->request->data['EventManager']['firstname_kana']; ?>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">所属機関</th>
						<td>
							<?php echo $this->request->data['EventManager']['organization']; ?>
						</td>
						<th class="bg-light">所属部局</th>
						<td>
							<?php echo $this->request->data['EventManager']['department']; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">職名</th>
						<td>
							<?php echo $this->request->data['EventManager']['job_title']; ?>
						</td>
						<th class="bg-light">URL</th>
						<td>
							<?php echo $this->request->data['EventManager']['url']; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light" nowrap="nowrap">郵便番号及び<br>ZIP CODE</th>
						<td>
							<?php echo $this->request->data['EventManager']['zip']; ?>
						</td>
						<th class="bg-light">都道府県</th>
						<td>
							<?php echo $prefectures[ $this->request->data['EventManager']['prefecture_id']]; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">市区町村</th>
						<td>
							<?php echo $this->request->data['EventManager']['city']; ?>
						</td>
						<th class="bg-light">住所</th>
						<td>
							<?php echo $this->request->data['EventManager']['address']; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">TEL</th>
						<td>
							<?php echo $this->request->data['EventManager']['tel']; ?>
						</td>
						<th class="bg-light">FAX</th>
						<td>
							<?php echo $this->request->data['EventManager']['fax']; ?>
						</td>
					</tr>
				</table>
				<hr>
				<br>
				
				<?php
				$i = 1;
				foreach ( $this->request->data['EventSubManager'] as $sub_manager ): ?>
					<h4>その他の運営責任者<?php echo $i; ?></h4>
					<table class="table table-bordered">
						<tr>
							<th class="bg-light" nowrap="nowrap" style="width:25%;">参加者ID<br>(メールアドレス)</th>
							<td style="width:25%;">
								<?php echo $sub_manager['email']; ?>
							</td>
							<th class="bg-light" style="width:25%;">&nbsp;</th>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="bg-light" nowrap="nowrap">姓名</th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $sub_manager['lastname']; ?>&nbsp;
										<?php echo $sub_manager['firstname']; ?>
									</div>
								</div>
							</td>
							<th class="bg-light" nowrap="nowrap">フリガナ</th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $sub_manager['lastname_kana']; ?>&nbsp;
										<?php echo $sub_manager['firstname_kana']; ?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th class="bg-light">所属機関</th>
							<td>
								<?php echo $sub_manager['organization']; ?>
							</td>
							<th class="bg-light">所属部局</th>
							<td>
								<?php echo $sub_manager['department']; ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">職名</th>
							<td>
								<?php echo $sub_manager['job_title']; ?>
							</td>
							<th class="bg-light">URL</th>
							<td>
								<?php echo $sub_manager['url']; ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light" nowrap="nowrap">郵便番号及び<br>ZIP CODE</th>
							<td>
								<?php echo $sub_manager['zip']; ?>
							</td>
							<th class="bg-light">都道府県</th>
							<td>
								<?php echo $prefectures[$sub_manager['prefecture_id']]; ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">市区町村</th>
							<td>
								<?php echo $sub_manager['city']; ?>
							</td>
							<th class="bg-light">住所</th>
							<td>
								<?php echo $sub_manager['address']; ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">TEL</th>
							<td>
								<?php echo $sub_manager['tel']; ?>
							</td>
							<th class="bg-light">FAX</th>
							<td>
								<?php echo $sub_manager['fax']; ?>
							</td>
						</tr>
					</table>
					<hr>
					<br>
				<?php
					$i++;
				endforeach; ?>
		
				<h4>事務担当者</h4>
				<table class="table table-bordered">
					<tr>
						<th class="bg-light" nowrap="nowrap" style="width:25%;">参加者ID<br>(メールアドレス)</th>
						<td style="width:25%;">
							<?php echo $this->request->data['EventAffair']['email']; ?>
						</td>
						<th class="bg-light" style="width:25%;">&nbsp;</th>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<th class="bg-light" nowrap="nowrap">姓名</th>
						<td>
							<div class="container">
								<div class="row">
									<?php echo $this->request->data['EventAffair']['lastname']; ?>&nbsp;
									<?php echo $this->request->data['EventAffair']['firstname']; ?>
								</div>
							</div>
						</td>
						<th class="bg-light" nowrap="nowrap">フリガナ</th>
						<td>
							<div class="container">
								<div class="row">
									<?php echo $this->request->data['EventAffair']['lastname_kana']; ?>&nbsp;
									<?php echo $this->request->data['EventAffair']['firstname_kana']; ?>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th class="bg-light">所属機関</th>
						<td>
							<?php echo $this->request->data['EventAffair']['organization']; ?>
						</td>
						<th class="bg-light">所属部局</th>
						<td>
							<?php echo $this->request->data['EventAffair']['department']; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">職名</th>
						<td>
							<?php echo $this->request->data['EventAffair']['job_title']; ?>
						</td>
						<th class="bg-light">URL</th>
						<td>
							<?php echo $this->request->data['EventAffair']['url']; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light" nowrap="nowrap">郵便番号及び<br>ZIP CODE</th>
						<td>
							<?php echo $this->request->data['EventAffair']['zip']; ?>
						</td>
						<th class="bg-light">都道府県</th>
						<td>
							<?php echo $prefectures[$this->request->data['EventAffair']['prefecture_id']]; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">市区町村</th>
						<td>
							<?php echo $this->request->data['EventAffair']['city']; ?>
						</td>
						<th class="bg-light">住所</th>
						<td>
							<?php echo $this->request->data['EventAffair']['address']; ?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">TEL</th>
						<td>
							<?php echo $this->request->data['EventAffair']['tel']; ?>
						</td>
						<th class="bg-light">FAX</th>
						<td>
							<?php echo $this->request->data['EventAffair']['fax']; ?>
						</td>
					</tr>
				</table>
			
				<?php
				// 開始日と終了日から開催期間を取得し、その日数分ループ用の配列を作成
				$diff = ((strtotime($this->request->data['Event']['end']) - strtotime($this->request->data['Event']['start'])) / 60 / 60 / 24) + 1;
				$date = array();
				for ( $i = 0; $i < $diff; $i++ )
				{
					$day = date('Ymd', strtotime($this->request->data['Event']['start']) + ($i * 86400));
					$day2 = date('Y年n月j日', strtotime($this->request->data['Event']['start']) + ($i * 86400));
					$date[$day] = $day2;
				}
				?>
				<h4>プログラム</h4>
				<hr>
				<?php foreach ( $date as $key => $dt ): ?>
					<div class="mb-5">
						<h6><?php echo $dt; ?> 講演課題</h6>
						<div class="session-area-<?php echo $key; ?>">
							<?php if ( !empty($this->request->data['ReportProgram'][$key]) ): ?>
								<?php foreach ( $this->request->data['ReportProgram'][$key] as $key2 => $program ): ?>
									<?php $next = (int)($key2 + 1); ?>
									<div class="row  session-<?php echo $key; ?>-<?php echo $key2; ?> session-<?php echo $key; ?> session-program mb-2">
										<div class="col-12">
											<table class="table table-bordered mb-2">
												<tr>
													<th class="bg-light text-center" rowspan="2" style="width:100px;">
														並び順<br>
														<?php echo $this->request->data['ReportProgram'][$key][$key2]['sort']; ?>
													</th>
													<td style="width:200px;">
														<div class="form-inline">
															<?php echo $this->request->data['ReportProgram'][$key][$key2]['start']['hour']; ?>：<?php echo $this->request->data['ReportProgram'][$key][$key2]['start']['min']; ?>～
															<?php echo $this->request->data['ReportProgram'][$key][$key2]['end']['hour']; ?>：<?php echo $this->request->data['ReportProgram'][$key][$key2]['end']['min']; ?>
														</div>
													</td>
													<td>
														<?php echo $this->request->data['ReportProgram'][$key][$key2]['title']; ?>
													</td>
												</tr>
												<tr>
													<td colspan="3">
														<div class="program-<?php echo $key; ?>-<?php echo $key2; ?>">
															<?php if ( isset($program['program']) && !empty($program['program']) ): ?>
																<?php foreach ( $program['program'] as $key3 =>$_program ): ?>
																	<div class="row mb-2 program-line">
																		<div class="col-12">
																			<?php echo $this->request->data['ReportProgram'][$key][$key2]['program'][$key3]['lastname']; ?>&nbsp;
																			<?php echo $this->request->data['ReportProgram'][$key][$key2]['program'][$key3]['firstname']; ?>
																			<?php
																			if ( !empty($this->request->data['ReportProgram'][$key][$key2]['program'][$key3]['organization']) )
																			{
																				echo '（' . $this->request->data['ReportProgram'][$key][$key2]['program'][$key3]['organization'] . '）';
																			}
																			?>
																		</div>
																	</div>
																<?php endforeach; ?>
															<?php endif; ?>
														</div>
													</td>
												</tr>
											</table>
										</div>
										<hr>
									</div>
									
								<?php endforeach; ?>
								
							<?php endif; ?>
							
						</div>
						<hr>
					</div>
				<?php endforeach; ?>
				

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
				<table class="table table-bordered">
					<tr>
						<th class="bg-light w-25">添付ファイル1</th>
						<td>
							<?php
							if ( isset($this->request->data['Event']['file1']['name']) && !empty($this->request->data['Event']['file1']['name']) )
							{
								$this->request->data['Event']['file1']['name'];
							}
							elseif( isset($event['Event']['file1_org']) && is_string($event['Event']['file1_org']) )
							{
								echo $event['Event']['file1_org'];
							}
							?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">添付ファイル2</th>
						<td>
							<?php
							if ( isset($this->request->data['Event']['file2']['name']) && !empty($this->request->data['Event']['file2']['name']) )
							{
								$this->request->data['Event']['file2']['name'];
							}
							elseif( isset($event['Event']['file2_org']) && is_string($event['Event']['file2_org']) )
							{
								echo $event['Event']['file2_org'];
							}
							?>
						</td>
					</tr>
					<tr>
						<th class="bg-light">添付ファイル3</th>
						<td>
							<?php
							if ( isset($this->request->data['Event']['file3']['name']) && !empty($this->request->data['Event']['file3']['name'])  )
							{
								$this->request->data['Event']['file3']['name'];
							}
							elseif( isset($event['Event']['file3_org']) && is_string($event['Event']['file3_org']) )
							{
								echo $event['Event']['file3_org'];
							}
							?>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	
	<div class="text-center">
		<?php echo $this->Html->link('戻る', array('action' => 'report_add3'), array('class' => 'btn btn-info')); ?>&nbsp;
		<?php echo $this->Form->submit('上記の内容で登録する', array('div' => false, 'class' => 'btn btn-success')); ?>
	</div>
<?php echo $this->Form->end(); ?>