<div class="row">
	<div class="col-xs-6 col-sm-6">
		<h2>事故カード</h2>
	</div>
	<div class="col-xs-6 col-sm-6">
		<table class="table table-bordered table-detail">
			<tr>
				<th style="width:20%;">社長</th>
				<th style="width:20%;">常務</th>
				<th style="width:20%;">工務</th>
				<th style="width:20%;">営業部長</th>
				<th style="width:20%;">部署責任者</th>
			</tr>
			<tr>
				<td>&nbsp;<br>&nbsp;</td>
				<td>&nbsp;<br>&nbsp;</td>
				<td>&nbsp;<br>&nbsp;</td>
				<td>&nbsp;<br>&nbsp;</td>
				<td>&nbsp;<br>&nbsp;</td>
			</tr>
		</table>
	</div>
</div>
<br>
<div class="table-responsive">
	<table class="table table-bordered table-detail">
		<tr>
			<th style="width:200px;">発覚月日</th>
			<td>
				<?php echo date('Y年m月d日', strtotime($accident['Accident']['date'])); ?>
			</td>
			<th style="width:200px;">営業担当</th>
			<td>
				<?php echo $accident['User']['name']; ?>
			</td>
		</tr>
		<tr>
			<th>得意先名</th>
			<td colspan="3"><?php echo $accident['Customer']['name']; ?></td>
		</tr>
		<tr>
			<th>品名</th>
			<td><?php echo $accident['Accident']['title']; ?></td>
			<th>数量</th>
			<td><?php echo number_format($accident['Accident']['count']); ?>部（枚）</td>
		</tr>
		
		<tr>
			<th>発生場所</th>
			<td>
				<?php echo $accident['Place']['name']; ?>
				<?php
				if ( !empty($accident['Accident']['place_other']) )
				{
					echo '（';
					echo $accident['Accident']['place_other'];
					echo '）';
				}
				?>
			</td>
			<th>発見場所</th>
			<td>
				<?php echo $accident['Trouble']['name']; ?>
				<?php
				if ( !empty($accident['Accident']['trouble_other']) )
				{
					echo '（';
					echo $accident['Accident']['trouble_other'];
					echo '）';
				}
				?>
			</td>
		</tr>
		
		<tr>
			<th colspan="4">事故の概要と処理</th>
		</tr>
		<tr>
			<td colspan="4">
				<?php
				if ( !empty($accident['Accident']['description']) )
				{
					echo nl2br($accident['Accident']['description']);
				}
				else
				{
					echo '&nbsp;';
				}
				?>
			</td>
		</tr>
		
		<tr>
			<th colspan="4">今後の対策</th>
		</tr>
		<tr>
			<td colspan="4">
				<?php
				if ( !empty($accident['Accident']['countermeasure']) )
				{
					echo nl2br($accident['Accident']['countermeasure']);
				}
				else
				{
					echo '&nbsp;';
				}
				?>
			</td>
		</tr>
		
		<tr>
			<th colspan="4">部署責任者のコメント</th>
		</tr>
		<tr>
			<td colspan="4">
				<?php
				if ( !empty($accident['Accident']['manager_comment']) )
				{
					echo nl2br($accident['Accident']['manager_comment']);
				}
				else
				{
					echo '&nbsp;';
				}
				?>
			</td>
		</tr>
		
		<tr>
			<th colspan="2">委員会からのコメント</th>
			<th colspan="2">経費処理（営業）</th>
		</tr>
		<tr>
			<td colspan="2" rowspan="2">
				<?php echo nl2br($accident['Accident']['committee_comment']); ?>
			</td>
			<td colspan="2">
				&yen;<?php echo number_format($accident['Accident']['expense']); ?>
			</td>
		</tr>
		<tr>
			<td colspan="2">
				<?php
				if ( !empty($accident['Accident']['expense_comment']) )
				{
					echo nl2br($accident['Accident']['expense_comment']);
				}
				else
				{
					echo '&nbsp;';
				}
				?>
			</td>
		</tr>
	</table>
</div>