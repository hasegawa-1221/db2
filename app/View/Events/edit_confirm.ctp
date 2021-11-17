<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'edit_confirm/' . $event['Event']['id'])); ?>
	<div class="container">
		<h2>企画応募</h2>
		
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
			<li class="active">
				入力内容確認
			</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">
				完了
			</li>
		</ul>
		<hr>
		
		<h3>企画の概要</h3>
		
		<table class="table table-bordered">
			<tr>
				<th class="bg-light" style="width:25%;">名称</th>
				<td><?php echo $this->request->data['Edit1']['Event']['title']; ?></td>
			</tr>
			<tr>
				<th class="bg-light">該当する重点テーマ</th>
				<td>
					<?php
					if( !empty($this->request->data['Edit1']['EventTheme']) )
					{
						foreach ( $this->request->data['Edit1']['EventTheme'] as $id => $event_theme )
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
				<td><?php echo $this->request->data['Edit1']['Event']['field']; ?></td>
			</tr>
			<tr>
				<th class="bg-light">キーワード</th>
				<td>
					<?php echo $this->request->data['Edit1']['Event']['keyword1']; ?>
					<?php echo $this->request->data['Edit1']['Event']['keyword2']; ?>
					<?php echo $this->request->data['Edit1']['Event']['keyword3']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">主催機関</th>
				<td><?php echo $this->request->data['Edit1']['Event']['organization']; ?></td>
			</tr>
			<tr>
				<th class="bg-light">開催時期</th>
				<td>
					<?php echo $this->request->data['Edit1']['Event']['start']; ?>～
					<?php echo $this->request->data['Edit1']['Event']['end']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">開催場所</th>
				<td><?php echo $this->request->data['Edit1']['Event']['place']; ?></td>
			</tr>
		</table>
		<hr>
		<br>
		
		<h3>企画の詳細</h3>
		<table class="table table-bordered">
			<tr>
				<th class="bg-light" style="width:25%;">プログラム</th>
				<td>
					<?php echo nl2br($this->request->data['Edit2']['Event']['program']); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">趣旨・目的</th>
				<td>
					<?php echo nl2br($this->request->data['Edit2']['Event']['purpose']); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">解決すべき課題</th>
				<td>
					<?php echo nl2br($this->request->data['Edit2']['Event']['subject']); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">考えられる数学・<br>数理科学的アプローチ</th>
				<td>
					<?php echo nl2br($this->request->data['Edit2']['Event']['approach']); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">会議終了後に考えられる<br>フォローアップ</th>
				<td>
					<?php echo nl2br($this->request->data['Edit2']['Event']['follow']); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">他からの支援</th>
				<td>
					<?php echo nl2br($this->request->data['Edit2']['Event']['support']); ?>
				</td>
			</tr>
		</table>
		<hr>
		<br>
		
		<h3>経費</h3>
		<h4>旅費</h4>
		<?php if ( !empty($this->request->data['Edit3']['Expense']['1']) ): ?>
			<table class="table table-bordered">
				<thead class="bg-light">
					<tr>
						<th style="width:20%;">所属</th>
						<th style="width:10%;">姓</th>
						<th style="width:10%;">名</th>
						<th style="width:25%;">日程</th>
						<th style="width:10%;">申請金額</th>
						<th>備考</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$total = 0;
					$subtotal = 0;
					
					foreach ($this->request->data['Edit3']['Expense']['1'] as $i => $val):
						$subtotal += $val['request_price'];
					?>
						<tr>
							<td><?php echo $this->request->data['Edit3']['Expense']['1'][$i]['affiliation']; ?></td>
							<td><?php echo $this->request->data['Edit3']['Expense']['1'][$i]['lastname']; ?></td>
							<td><?php echo $this->request->data['Edit3']['Expense']['1'][$i]['firstname']; ?></td>
							<td><?php echo $this->request->data['Edit3']['Expense']['1'][$i]['title']; ?></td>
							<td class="text-right"><?php echo number_format($this->request->data['Edit3']['Expense']['1'][$i]['request_price']); ?> 円</td>
							<td><?php echo $this->request->data['Edit3']['Expense']['1'][$i]['note']; ?></td>
						</tr>
					<?php endforeach; ?>
					<tr>
						<th colspan="4" class="text-right bg-light">小計</th>
						<td class="text-right"><?php echo number_format($subtotal); ?> 円</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		<?php else: ?>
			<p>旅費の申請はありません。</p>
			<br>
		<?php endif; ?>
		
		<h4>諸謝金</h4>
		<?php if ( !empty($this->request->data['Edit3']['Expense']['2']) ): ?>
			<table class="table table-bordered">
				<thead class="bg-light">
					<tr>
						<th style="width:20%;">所属</th>
						<th style="width:10%;">姓</th>
						<th style="width:10%;">名</th>
						<th style="width:25%;">用務等</th>
						<th style="width:10%;">申請金額</th>
						<th>備考</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$subtotal = 0;
					foreach ($this->request->data['Edit3']['Expense']['2'] as $i => $val):
						$subtotal += $val['request_price'];
					?>
						<tr>
							<td><?php echo $this->request->data['Edit3']['Expense']['2'][$i]['affiliation']; ?></td>
							<td><?php echo $this->request->data['Edit3']['Expense']['2'][$i]['lastname']; ?></td>
							<td><?php echo $this->request->data['Edit3']['Expense']['2'][$i]['firstname']; ?></td>
							<td><?php echo $this->request->data['Edit3']['Expense']['2'][$i]['title']; ?></td>
							<td class="text-right"><?php echo number_format($this->request->data['Edit3']['Expense']['2'][$i]['request_price']); ?> 円</td>
							<td><?php echo $this->request->data['Edit3']['Expense']['2'][$i]['note']; ?></td>
						</tr>
					<?php endforeach; ?>
					<tr>
						<th colspan="4" class="text-right bg-light">小計</th>
						<td class="text-right"><?php echo number_format($subtotal); ?> 円</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		<?php else: ?>
			<p>諸謝金の申請はありません。</p>
			<br>
		<?php endif; ?>
		
		<h4>会議費</h4>
		<?php if ( !empty($this->request->data['Edit3']['Expense']['3']) ): ?>
			<table class="table table-bordered">
				<thead class="bg-light">
					<tr>
						<th style="width:25%;">件名</th>
						<th style="width:20%;">数量</th>
						<th style="width:20%;">単価</th>
						<th style="width:10%;">申請金額</th>
						<th>備考</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$subtotal = 0;
					foreach ($this->request->data['Edit3']['Expense']['3'] as $i => $val):
						$subtotal += $val['request_price'];
					?>
						<tr>
							<td><?php echo $this->request->data['Edit3']['Expense']['3'][$i]['title']; ?></td>
							<td><?php echo $this->request->data['Edit3']['Expense']['3'][$i]['count']; ?></td>
							<td><?php echo $this->request->data['Edit3']['Expense']['3'][$i]['price']; ?></td>
							<td class="text-right"><?php echo number_format($this->request->data['Edit3']['Expense']['3'][$i]['request_price']); ?> 円</td>
							<td><?php echo $this->request->data['Edit3']['Expense']['3'][$i]['note']; ?></td>
						</tr>
					<?php endforeach; ?>
					<tr>
						<th colspan="3" class="text-right bg-light">小計</th>
						<td class="text-right"><?php echo number_format($subtotal); ?> 円</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		<?php else: ?>
			<p>会議費の申請はありません。</p>
			<br>
		<?php endif; ?>
		
		<h4>その他</h4>
		<?php if ( !empty($this->request->data['Edit3']['Expense']['4']) ): ?>
			<table class="table table-bordered">
				<thead class="bg-light">
					<tr>
						<th style="width:15%;">品目</th>
						<th style="width:20%;">件名</th>
						<th style="width:15%;">数量</th>
						<th style="width:15%;">単価</th>
						<th style="width:10%;">申請金額</th>
						<th>備考</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$subtotal = 0;
					foreach ($this->request->data['Edit3']['Expense']['4'] as $i => $val):
						$subtotal += $val['request_price'];
					?>
						<tr>
							<td><?php echo $items[$this->request->data['Edit3']['Expense']['4'][$i]['item_id']]; ?></td>
							<td><?php echo $this->request->data['Edit3']['Expense']['4'][$i]['title']; ?></td>
							<td><?php echo $this->request->data['Edit3']['Expense']['4'][$i]['count']; ?></td>
							<td><?php echo $this->request->data['Edit3']['Expense']['4'][$i]['price']; ?></td>
							<td class="text-right"><?php echo number_format($this->request->data['Edit3']['Expense']['4'][$i]['request_price']); ?> 円</td>
							<td><?php echo $this->request->data['Edit3']['Expense']['4'][$i]['note']; ?></td>
						</tr>
					<?php endforeach; ?>
					<tr>
						<th colspan="4" class="text-right bg-light">小計</th>
						<td class="text-right"><?php echo number_format($subtotal); ?> 円</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		<?php else: ?>
			<p>その他の申請はありません。</p>
			<br>
		<?php endif; ?>
		<hr>
		<br>
		
		<h3>参加について</h3>
		<table class="table table-bordered">
			<tr>
				<th class="bg-light" style="width:25%;">参加資格</th>
				<td>
					<?php echo $this->request->data['Edit4']['Event']['qualification']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加申込みの要不要</th>
				<td>
					<?php echo $options1[$this->request->data['Edit4']['Event']['qualification_apply']]; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">申込方法</th>
				<td>
					<?php echo $this->request->data['Edit4']['Event']['qualification_method']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加費の有無</th>
				<td>
					<?php echo $options2[$this->request->data['Edit4']['Event']['is_qualification_cost']]; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加費の詳細</th>
				<td>
					<?php echo $this->request->data['Edit4']['Event']['qualification_cost']; ?>
				</td>
			</tr>
		</table>
		<hr>
		<br>
		
		
		<h3>責任者</h3>
		<h4>運営責任者</h4>
		<table class="table table-bordered">
			<tr>
				<th class="bg-light" nowrap="nowrap" style="width:25%;">参加者ID<br>(メールアドレス)</th>
				<td style="width:25%;">
					<?php echo $this->request->data['Edit5']['EventManager']['email']; ?>
				</td>
				<th class="bg-light" style="width:25%;">&nbsp;</th>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th class="bg-light" nowrap="nowrap">姓名</th>
				<td>
					<div class="container">
						<div class="row">
							<?php echo $this->request->data['Edit5']['EventManager']['lastname']; ?>&nbsp;
							<?php echo $this->request->data['Edit5']['EventManager']['firstname']; ?>
						</div>
					</div>
				</td>
				<th class="bg-light" nowrap="nowrap">フリガナ</th>
				<td>
					<div class="container">
						<div class="row">
							<?php echo $this->request->data['Edit5']['EventManager']['lastname_kana']; ?>&nbsp;
							<?php echo $this->request->data['Edit5']['EventManager']['firstname_kana']; ?>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th class="bg-light">所属機関</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventManager']['organization']; ?>
				</td>
				<th class="bg-light">所属部局</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventManager']['department']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">職名</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventManager']['job_title']; ?>
				</td>
				<th class="bg-light">URL</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventManager']['url']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light" nowrap="nowrap">郵便番号及び<br>ZIP CODE</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventManager']['zip']; ?>
				</td>
				<th class="bg-light">都道府県</th>
				<td>
					<?php echo $prefectures[ $this->request->data['Edit5']['EventManager']['prefecture_id']]; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">市区町村</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventManager']['city']; ?>
				</td>
				<th class="bg-light">住所</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventManager']['address']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">TEL</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventManager']['tel']; ?>
				</td>
				<th class="bg-light">FAX</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventManager']['fax']; ?>
				</td>
			</tr>
		</table>
		<hr>
		<br>
		
		<?php
		$i = 1;
		foreach ( $this->request->data['Edit5']['EventSubManager'] as $sub_manager ): ?>
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
					<?php echo $this->request->data['Edit5']['EventAffair']['email']; ?>
				</td>
				<th class="bg-light" style="width:25%;">&nbsp;</th>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<th class="bg-light" nowrap="nowrap">姓名</th>
				<td>
					<div class="container">
						<div class="row">
							<?php echo $this->request->data['Edit5']['EventAffair']['lastname']; ?>&nbsp;
							<?php echo $this->request->data['Edit5']['EventAffair']['firstname']; ?>
						</div>
					</div>
				</td>
				<th class="bg-light" nowrap="nowrap">フリガナ</th>
				<td>
					<div class="container">
						<div class="row">
							<?php echo $this->request->data['Edit5']['EventAffair']['lastname_kana']; ?>&nbsp;
							<?php echo $this->request->data['Edit5']['EventAffair']['firstname_kana']; ?>
						</div>
					</div>
				</td>
			</tr>
			<tr>
				<th class="bg-light">所属機関</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventAffair']['organization']; ?>
				</td>
				<th class="bg-light">所属部局</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventAffair']['department']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">職名</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventAffair']['job_title']; ?>
				</td>
				<th class="bg-light">URL</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventAffair']['url']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light" nowrap="nowrap">郵便番号及び<br>ZIP CODE</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventAffair']['zip']; ?>
				</td>
				<th class="bg-light">都道府県</th>
				<td>
					<?php echo $prefectures[$this->request->data['Edit5']['EventAffair']['prefecture_id']]; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">市区町村</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventAffair']['city']; ?>
				</td>
				<th class="bg-light">住所</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventAffair']['address']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">TEL</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventAffair']['tel']; ?>
				</td>
				<th class="bg-light">FAX</th>
				<td>
					<?php echo $this->request->data['Edit5']['EventAffair']['fax']; ?>
				</td>
			</tr>
		</table>
		<hr>
		<br>
		
		<div class="text-center">
			<?php echo $this->Html->link('戻る', array('action' => 'edit5', $event['Event']['id']), array('class' => 'btn btn-secondary')); ?>
			<?php echo $this->Form->submit('上記の内容で更新する', array('div' => false, 'class' => 'btn btn-success', 'name' => 'entry')); ?>
		</div>
		
	</div>
<?php echo $this->Form->end(); ?>
<br>
<br>
