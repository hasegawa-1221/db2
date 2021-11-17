<?php $this->assign('title', '報告書作成 | 数理技術相談データベース'); ?>

<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'report_add1')); ?>
	<div class="container">
		<h2>報告書の作成</h2>
		
		<ul class="page-navi">
			<li class="active">報告書の概要</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">報告書の詳細</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">添付ファイル</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">入力内容確認</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">完了</li>
		</ul>
		<hr>

		<h4>報告書の概要</h4>
		<table class="table table-bordered">
			<tr>
				<th class="bg-light w-25">名称<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">企画番号<span class="text-danger">*</span></th>
				<td>
					<?php echo $this->request->data['Event']['event_number']; ?>
					<?php echo $this->Form->input('Event.event_number', array('type' => 'hidden')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">重点テーマ<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.important', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">集会等のタイプ<span class="text-danger">*</span></th>
				<td>
					<?php
					if( !empty($themes) )
					{
						foreach ( $themes as $id => $theme )
						{
							echo $this->Form->input('EventTheme.' . $id . '.id', array('type' => 'checkbox', 'div' => false, 'label' => '&nbsp;' . $theme));
							echo '<br>';
						}
					}
					echo $this->Form->error('EventTheme.id');
					?>
				</td>
			</tr>
			
			<tr>
				<th class="bg-light">キーワード<span class="text-danger">*</span></th>
				<td>
					<div class="keyword-area mb-2">
						<?php
						if ( empty($this->request->data['EventKeyword']) )
						{
							for ( $i=0; $i<3; $i++ )
							{
								echo $this->Form->input('EventKeyword.' . $i . '.id', array('type' => 'hidden'));
								echo $this->Form->input('EventKeyword.' . $i . '.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 mb-2 d-inline data-keyword'));
								echo '&nbsp;';
							}
						}
						else
						{
							foreach ( $this->request->data['EventKeyword'] as $key => $event_keyword )
							{
								echo $this->Form->input('EventKeyword.' . $key . '.id', array('type' => 'hidden'));
								echo $this->Form->input('EventKeyword.' . $key . '.title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 mb-2 d-inline data-keyword'));
								echo '&nbsp;';
							}
						}
						?>
					</div>
					<div class="text-right">
						<?php echo $this->Html->link('キーワードを増やす', 'javascript:void(0);', array('class' => 'btn btn-info btn-add-keyword')); ?>
					</div>
				</td>
			</tr>
			<tr>
				<th class="bg-light">主催機関<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">開催時期<br>（YYYY-MM-DD）<span class="text-danger">*</span></th>
				<td>
					<?php echo $this->Form->input('Event.start', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 d-inline datepicker')); ?>～
					<?php echo $this->Form->input('Event.end', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-3 d-inline datepicker')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">開催場所<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.place', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
			<tr>
				<th class="bg-light">連携先の分野・業界<span class="text-danger">*</span></th>
				<td><?php echo $this->Form->input('Event.field', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?></td>
			</tr>
		</table>
		<br>
		
		<h4>報告書の詳細</h4>
		<table class="table table-bordered">
			<tr>
				<th class="bg-light w-25">当日の論点<span class="text-danger">*</span></th>
				<td>
					<?php echo $this->Form->input('Event.issue', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">研究の現状と課題<br>（既にできていること、<br>できていないことの切り分け）<span class="text-danger">*</span></th>
				<td>
					<?php echo $this->Form->input('Event.subject', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">新たに明らかになった課題<span class="text-danger">*</span></th>
				<td>
					<?php echo $this->Form->input('Event.new_subject', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">今後解決すべきこと、<br>今後の展開・フォローアップ<span class="text-danger">*</span></th>
				<td>
					<?php echo $this->Form->input('Event.follow', array('type' => 'textarea', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
				</td>
			</tr>
		</table>
		<br>
		
		<div class="manager-area">
			<?php if ( !empty($this->request->data['EventManager']) ): ?>
				<?php $i = 0; ?>
				<?php foreach ( $this->request->data['EventManager'] as $key => $event_manager ): ?>
					<h4>運営責任者<?php echo $i+ 1; ?></h4>
					<?php
					if ( $i != 0 )
					{
						echo '<div class="alert alert-secondary">';
							// 1人め以外は削除可能
							echo $this->Form->input('EventManager.' . $key . '.is_delete', array('type' => 'checkbox', 'div' => false, 'label' => 'この運営責任者を削除する'));
							echo '<br>';
							echo '<span class="text-danger">※チェックを付けた場合一時保存ボタンを押して保存して下さい。</span>';
						echo '</div>';
					}
					?>
					<table class="table2 table-bordered">
						<tr>
							<th class="bg-light" nowrap="nowrap">参加者ID<br>(メールアドレス)<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.id', array('type' => 'hidden')); ?>
								<?php echo $this->Form->input('EventManager.' . $key . '.email', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">&nbsp;</th>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="bg-light" nowrap="nowrap">姓名<span class="text-danger">*</span></th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $this->Form->input('EventManager.' . $key . '.lastname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-5', 'placeholder' => '姓')); ?>&nbsp;
										<?php echo $this->Form->input('EventManager.' . $key . '.firstname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-5', 'placeholder' => '名')); ?>
									</div>
								</div>
							</td>
							<th class="bg-light" nowrap="nowrap">フリガナ<span class="text-danger">*</span></th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $this->Form->input('EventManager.' . $key . '.lastname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-5', 'placeholder' => 'せい')); ?>&nbsp;
										<?php echo $this->Form->input('EventManager.' . $key . '.firstname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-5', 'placeholder' => 'めい')); ?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th class="bg-light">所属機関<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">所属部局<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.department', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">職名</th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.job_title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">URL</th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.url', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light" nowrap="nowrap">郵便番号<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.zip', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">都道府県<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.prefecture_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $prefectures)); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">市区町村<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.city', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">住所<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.address', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">TEL<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.tel', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">FAX</th>
							<td>
								<?php echo $this->Form->input('EventManager.' . $key . '.fax', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
						</tr>
					</table>
					<hr>
					<br>
					<?php $i++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		
		<div class="text-right">
			<?php echo $this->Form->submit('運営責任者を増やす', array('div' => false, 'name' => 'manager', 'class' => 'btn btn-info')); ?>
		</div>
		<br>
		
		<div class="affair-area">
			
			
			<?php if ( !empty($this->request->data['EventAffair']) ): ?>
				<?php $i = 0; ?>
				<?php foreach ( $this->request->data['EventAffair'] as $key => $event_affair ): ?>
					<h4>事務担当者<?php echo $i+ 1; ?></h4>
					<?php
					if ( $i != 0 )
					{
						echo '<div class="alert alert-secondary">';
							// 1人め以外は削除可能
							echo $this->Form->input('EventAffair.' . $key . '.is_delete', array('type' => 'checkbox', 'div' => false, 'label' => 'この運営責任者を削除する'));
							echo '<br>';
							echo '<span class="text-danger">※チェックを付けた場合一時保存ボタンを押して保存して下さい。</span>';
						echo '</div>';
					}
					?>
					<table class="table2 table-bordered">
						<tr>
							<th class="bg-light" nowrap="nowrap">参加者ID<br>(メールアドレス)<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.id', array('type' => 'hidden')); ?>
								<?php echo $this->Form->input('EventAffair.' . $key . '.email', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">&nbsp;</th>
							<td>&nbsp;</td>
						</tr>
						<tr>
							<th class="bg-light" nowrap="nowrap">姓名<span class="text-danger">*</span></th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $this->Form->input('EventAffair.' . $key . '.lastname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-5', 'placeholder' => '姓')); ?>&nbsp;
										<?php echo $this->Form->input('EventAffair.' . $key . '.firstname', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-5', 'placeholder' => '名')); ?>
									</div>
								</div>
							</td>
							<th class="bg-light" nowrap="nowrap">フリガナ<span class="text-danger">*</span></th>
							<td>
								<div class="container">
									<div class="row">
										<?php echo $this->Form->input('EventAffair.' . $key . '.lastname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-5', 'placeholder' => 'せい')); ?>&nbsp;
										<?php echo $this->Form->input('EventAffair.' . $key . '.firstname_kana', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control col-5', 'placeholder' => 'めい')); ?>
									</div>
								</div>
							</td>
						</tr>
						<tr>
							<th class="bg-light">所属機関<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.organization', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">所属部局<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.department', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">職名</th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.job_title', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">URL</th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.url', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light" nowrap="nowrap">郵便番号<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.zip', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">都道府県<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.prefecture_id', array('type' => 'select', 'div' => false, 'label' => false, 'class' => 'form-control', 'options' => $prefectures)); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">市区町村<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.city', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">住所<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.address', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
						</tr>
						<tr>
							<th class="bg-light">TEL<span class="text-danger">*</span></th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.tel', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
							<th class="bg-light">FAX</th>
							<td>
								<?php echo $this->Form->input('EventAffair.' . $key . '.fax', array('type' => 'text', 'div' => false, 'label' => false, 'class' => 'form-control')); ?>
							</td>
						</tr>
					</table>
					<hr>
					<?php $i++; ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<div class="text-right">
			<?php echo $this->Form->submit('事務担当者を増やす', array('div' => false, 'name' => 'affair', 'class' => 'btn btn-info')); ?>
		</div>
		<br>
		
		<div class="text-center">
			<?php echo $this->Form->submit('一時保存', array('div' => false, 'class' => 'btn btn-primary', 'name' => 'save')); ?>
			<?php echo $this->Form->submit('次へ', array('div' => false, 'class' => 'btn btn-success', 'name' => 'confirm', 'onclick' => 'submit();')); ?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>