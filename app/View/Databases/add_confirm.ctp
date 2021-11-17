<?php
$this->assign('title', '企画応募 | 数理技術相談データベース');
?>

<?php echo $this->Form->create(null, array('type' => 'post', 'url' => 'add_confirm')); ?>
	<div class="container">
		<h2>企画応募</h2>
		
		<ul class="page-navi">
			<li class="disabled">企画の概要</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">企画の詳細</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">経費</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">参加について</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">責任者</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="active">入力内容確認</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="disabled">完了</li>
		</ul>
		<hr>
		
		<h4>企画の概要</h4>
		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light" style="width:25%;">ログインID</th>
				<td><?php echo $this->request->data['Add1']['Event']['username']; ?></td>
			</tr>
			<tr>
				<th class="bg-light">パスワード</th>
				<td>
					<?php
					for ( $i = 0; $i < mb_strlen($this->request->data['Add1']['Event']['password']); $i++)
					{
						echo '*';
					}
					?>
				</td>
			</tr>
		</table>
		
		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light" style="width:25%;">名称</th>
				<td><?php echo $this->request->data['Add1']['Event']['title']; ?></td>
			</tr>
			<tr>
				<th class="bg-light">該当する重点テーマ</th>
				<td>
					<?php
					if( !empty($this->request->data['Add1']['EventTheme']) )
					{
						foreach ( $this->request->data['Add1']['EventTheme'] as $id => $event_theme )
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
				<td><?php echo $this->request->data['Add1']['Event']['field']; ?></td>
			</tr>
			<tr>
				<th class="bg-light">キーワード</th>
				<td>
					<?php
					if ( !empty($this->request->data['Add1']['EventKeyword']) )
					{
						foreach ( $this->request->data['Add1']['EventKeyword'] as $event_keyword )
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
				<td><?php echo $this->request->data['Add1']['Event']['organization']; ?></td>
			</tr>
			<tr>
				<th class="bg-light">開催時期</th>
				<td>
					<?php echo $this->request->data['Add1']['Event']['start']; ?>～
					<?php echo $this->request->data['Add1']['Event']['end']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">開催場所</th>
				<td><?php echo $this->request->data['Add1']['Event']['place']; ?></td>
			</tr>
		</table>
		<hr>
		<br>
		
		<h4>企画の詳細</h4>
		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light" style="width:25%;">プログラム</th>
				<td>
					<?php echo nl2br($this->request->data['Add2']['Event']['program']); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">趣旨・目的</th>
				<td>
					<?php echo nl2br($this->request->data['Add2']['Event']['purpose']); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">解決すべき課題</th>
				<td>
					<?php echo nl2br($this->request->data['Add2']['Event']['subject']); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">考えられる数学・<br>数理科学的アプローチ</th>
				<td>
					<?php echo nl2br($this->request->data['Add2']['Event']['approach']); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">会議終了後に考えられる<br>フォローアップ</th>
				<td>
					<?php echo nl2br($this->request->data['Add2']['Event']['follow']); ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">他からの支援</th>
				<td>
					<?php echo nl2br($this->request->data['Add2']['Event']['support']); ?>
				</td>
			</tr>
		</table>
		<hr>
		<br>
		
		<h4>経費</h4>
		<h5>旅費</h5>
		<?php $total = 0; ?>
		<?php if ( !empty($this->request->data['Add3']['Expense']['1']) ): ?>
			<table class="table table-bordered table-sm">
				<thead class="bg-light">
					<tr>
						<th style="width:20%;">所属</th>
						<th style="width:10%;">職位</th>
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
					
					foreach ($this->request->data['Add3']['Expense']['1'] as $i => $val):
						$subtotal += $val['request_price'];
					?>
						<tr>
							<td><?php echo $this->request->data['Add3']['Expense']['1'][$i]['affiliation']; ?></td>
							<td><?php echo $this->request->data['Add3']['Expense']['1'][$i]['job']; ?></td>
							<td><?php echo $this->request->data['Add3']['Expense']['1'][$i]['lastname']; ?></td>
							<td><?php echo $this->request->data['Add3']['Expense']['1'][$i]['firstname']; ?></td>
							<td>
								<?php // echo $this->request->data['Add3']['Expense']['1'][$i]['title']; ?>
								
								<?php echo $this->request->data['Add3']['Expense']['1'][$i]['date_start']; ?>～<?php echo $this->request->data['Add3']['Expense']['1'][$i]['date_end']; ?>
							</td>
							<td class="text-right"><?php echo ($this->request->data['Add3']['Expense']['1'][$i]['request_price'])?number_format($this->request->data['Add3']['Expense']['1'][$i]['request_price']):''; ?> 円</td>
							<td><?php echo $this->request->data['Add3']['Expense']['1'][$i]['note']; ?></td>
						</tr>
					<?php endforeach; ?>
					<?php $total = $total + $subtotal; ?>
					<tr>
						<th colspan="5" class="text-right bg-light">小計</th>
						<td class="text-right"><?php echo number_format($subtotal); ?> 円</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		<?php else: ?>
			<p>旅費の申請はありません。</p>
			<br>
		<?php endif; ?>
		
		<h5>諸謝金</h5>
		<?php if ( !empty($this->request->data['Add3']['Expense']['2']) ): ?>
			<table class="table table-bordered table-sm">
				<thead class="bg-light">
					<tr>
						<th style="width:20%;">所属</th>
						<th style="width:10%;">職位</th>
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
					foreach ($this->request->data['Add3']['Expense']['2'] as $i => $val):
						$subtotal += $val['request_price'];
					?>
						<tr>
							<td><?php echo $this->request->data['Add3']['Expense']['2'][$i]['affiliation']; ?></td>
							<td><?php echo $this->request->data['Add3']['Expense']['2'][$i]['job']; ?></td>
							<td><?php echo $this->request->data['Add3']['Expense']['2'][$i]['lastname']; ?></td>
							<td><?php echo $this->request->data['Add3']['Expense']['2'][$i]['firstname']; ?></td>
							<td><?php echo $this->request->data['Add3']['Expense']['2'][$i]['title']; ?></td>
							<td class="text-right"><?php echo ($this->request->data['Add3']['Expense']['2'][$i]['request_price'])?number_format($this->request->data['Add3']['Expense']['2'][$i]['request_price']):''; ?> 円</td>
							<td><?php echo $this->request->data['Add3']['Expense']['2'][$i]['note']; ?></td>
						</tr>
					<?php endforeach; ?>
					<?php $total = $total + $subtotal; ?>
					<tr>
						<th colspan="5" class="text-right bg-light">小計</th>
						<td class="text-right"><?php echo number_format($subtotal); ?> 円</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		<?php else: ?>
			<p>諸謝金の申請はありません。</p>
			<br>
		<?php endif; ?>
		
		<h5>印刷製本費</h5>
		<?php if ( !empty($this->request->data['Add3']['Expense']['3']) ): ?>
			<table class="table table-bordered table-sm">
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
					foreach ($this->request->data['Add3']['Expense']['3'] as $i => $val):
						$subtotal += $val['request_price'];
					?>
						<tr>
							<td><?php echo $this->request->data['Add3']['Expense']['3'][$i]['title']; ?></td>
							<td><?php echo $this->request->data['Add3']['Expense']['3'][$i]['count']; ?></td>
							<td><?php echo $this->request->data['Add3']['Expense']['3'][$i]['price']; ?></td>
							<td class="text-right"><?php echo ($this->request->data['Add3']['Expense']['3'][$i]['request_price'])?number_format($this->request->data['Add3']['Expense']['3'][$i]['request_price']):''; ?> 円</td>
							<td><?php echo $this->request->data['Add3']['Expense']['3'][$i]['note']; ?></td>
						</tr>
					<?php endforeach; ?>
					<?php $total = $total + $subtotal; ?>
					<tr>
						<th colspan="3" class="text-right bg-light">小計</th>
						<td class="text-right"><?php echo number_format($subtotal); ?> 円</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		<?php else: ?>
			<p>印刷製本費の申請はありません。</p>
			<br>
		<?php endif; ?>
		
		<h5>その他</h5>
		<?php if ( !empty($this->request->data['Add3']['Expense']['4']) ): ?>
			<table class="table table-bordered table-sm">
				<thead class="bg-light">
					<tr>
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
					foreach ($this->request->data['Add3']['Expense']['4'] as $i => $val):
						$subtotal += $val['request_price'];
					?>
						<tr>
							<td><?php echo $this->request->data['Add3']['Expense']['4'][$i]['title']; ?></td>
							<td><?php echo $this->request->data['Add3']['Expense']['4'][$i]['count']; ?></td>
							<td><?php echo $this->request->data['Add3']['Expense']['4'][$i]['price']; ?></td>
							<td class="text-right"><?php echo ($this->request->data['Add3']['Expense']['4'][$i]['request_price'])?number_format($this->request->data['Add3']['Expense']['4'][$i]['request_price']):''; ?> 円</td>
							<td><?php echo $this->request->data['Add3']['Expense']['4'][$i]['note']; ?></td>
						</tr>
					<?php endforeach; ?>
					<?php $total = $total + $subtotal; ?>
					<tr>
						<th colspan="3" class="text-right bg-light">小計</th>
						<td class="text-right"><?php echo number_format($subtotal); ?> 円</td>
						<td>&nbsp;</td>
					</tr>
				</tbody>
			</table>
		<?php else: ?>
			<p>その他の申請はありません。</p>
			<br>
		<?php endif; ?>
		
		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light">経費合計</th>
				<td><?php echo number_format($total); ?> 円</td>
			</tr>
		</table>
		
		<hr>
		<br>
		
		<h4>参加について</h4>
		<table class="table table-bordered table-sm">
			<tr>
				<th class="bg-light" style="width:25%;">参加制限</th>
				<td>
					<?php
					if ( isset($this->request->data['Add4']['Event']['qualification']) && $this->request->data['Add4']['Event']['qualification'] == 1 )
					{
						echo '有';
					}
					else
					{
						echo '無';
					}
					?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">有の場合は参加資格</th>
				<td>
					<?php
					 if ( isset($this->request->data['Add4']['Event']['qualification_other']) )
					 {
					 	echo $this->request->data['Add4']['Event']['qualification_other'];
					 }
					 ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加申込</th>
				<td>
					<?php
					if ( isset($this->request->data['Add4']['Event']['qualification_apply']) && $this->request->data['Add4']['Event']['qualification_apply'] == 1 )
					{
						echo '必要';
					}
					else
					{
						echo '不要';
					}
					?>
				</td>
			</tr>
			
			<?php /*
			<tr>
				<th class="bg-light" style="width:25%;">参加資格</th>
				<td>
					<?php echo $this->request->data['Add4']['Event']['qualification']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加申込みの要不要</th>
				<td>
					<?php echo $options1[$this->request->data['Add4']['Event']['qualification_apply']]; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">申込方法</th>
				<td>
					<?php echo $this->request->data['Add4']['Event']['qualification_method']; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加費の有無</th>
				<td>
					<?php echo $options2[$this->request->data['Add4']['Event']['is_qualification_cost']]; ?>
				</td>
			</tr>
			<tr>
				<th class="bg-light">参加費の詳細</th>
				<td>
					<?php echo $this->request->data['Add4']['Event']['qualification_cost']; ?>
				</td>
			</tr>
			*/ ?>
		</table>
		<hr>
		<br>
		
		
		<h4>運営責任者</h4>
		<?php if ( !empty($this->request->data['Add5']['EventManager']) ): ?>
			<?php foreach ( $this->request->data['Add5']['EventManager'] as $event_manager): ?>
				<table class="table2 table-bordered table-sm">
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
						<th class="bg-light" nowrap="nowrap">郵便番号</th>
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
		<?php if ( !empty($this->request->data['Add5']['EventAffair']) ): ?>
			<?php foreach ( $this->request->data['Add5']['EventAffair'] as $event_affair): ?>
				<table class="table table-bordered table-sm">
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
						<th class="bg-light" nowrap="nowrap">郵便番号</th>
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
				
		<div class="alert alert-warning" role="alert">
			ご登録の運営責任者、事務担当者のメールアドレスへ登録内容の記載されたメールが送信されます。
		</div>
		<br>
		
		<div class="text-center">
			<?php echo $this->Html->link('戻る', array('action' => 'add5'), array('class' => 'btn btn-secondary')); ?>
			<?php echo $this->Form->submit('上記の内容で申請する', array('div' => false, 'class' => 'btn btn-success', 'name' => 'entry', 'onclick' => 'submit();')); ?>
		</div>
		
	</div>
<?php echo $this->Form->end(); ?>
<br>
<br>
