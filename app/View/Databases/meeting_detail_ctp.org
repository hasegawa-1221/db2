<?php
$this->assign('title', $meeting['Meeting']['title'] . ' | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('研究集会データベース', '/databases/meetings/');
$this->Html->addCrumb($meeting['Meeting']['title'], '/databases/meeting_detail/' . $meeting['Meeting']['id']);
?>
<h2>研究集会データベース</h2>
<br>
<div class="container">
	<div class="row">
		<div class="col-12">
			<h4><?php echo $meeting['Meeting']['event_number']; ?> <?php echo $meeting['Meeting']['title']; ?></h4>
			<hr>
			<table class="table table-bordered">
				<!--
				<tr>
					<th class="bg-light w-25">該当する重点テーマ</th>
					<td><?php echo nl2br($meeting['Meeting']['theme']); ?></td>
				</tr>
				<tr>
					<th class="bg-light">連携分野</th>
					<td><?php echo $meeting['Meeting']['field']; ?></td>
				</tr>
				<tr>
					<th class="bg-light">キーワード</th>
					<td><?php echo nl2br($meeting['Meeting']['keyword']); ?></td>
				</tr>
				-->
				<tr>
					<th class="bg-light w-25">主催機関</th>
					<td><?php echo $meeting['Meeting']['organization']; ?></td>
				</tr>
				<tr>
					<th class="bg-light">開催時期</th>
					<td><?php echo date('Y年n月j日', strtotime($meeting['Meeting']['start'])); ?> ～ <?php echo date('Y年n月j日', strtotime($meeting['Meeting']['end'])); ?></td>
				</tr>
				<tr>
					<th class="bg-light">開催場所</th>
					<td><?php echo $meeting['Meeting']['place']; ?></td>
				</tr>
				<tr>
					<th class="bg-light">趣旨・目的</th>
					<td><?php echo nl2br($meeting['Meeting']['purpose']); ?></td>
				</tr>
				<tr>
					<th class="bg-light">研究の現状と課題</th>
					<td><?php echo nl2br($meeting['Meeting']['subject']); ?></td>
				</tr>
				<tr>
					<th class="bg-light">考えられる数学・<br>数理科学的アプローチ</th>
					<td><?php echo nl2br($meeting['Meeting']['approach']); ?></td>
				</tr>
				<tr>
					<th class="bg-light">今後の展開・フォローアップ</th>
					<td><?php echo nl2br($meeting['Meeting']['follow']); ?></td>
				</tr>
				<tr>
					<th class="bg-light">他からの支援</th>
					<td><?php echo nl2br($meeting['Meeting']['support']); ?></td>
				</tr>
				<tr>
					<th class="bg-light">当日の論点</th>
					<td><?php echo nl2br($meeting['Meeting']['issue']); ?></td>
				</tr>
				<tr>
					<th class="bg-light">新たに明らかになった課題、<br>今後解決すべきこと</th>
					<td><?php echo nl2br($meeting['Meeting']['new_subject']); ?></td>
				</tr>
				<tr>
					<th class="bg-light">添付資料</th>
					<td>
						<?php
						if ( !empty($meeting['MeetingFile']) )
						{
							$i=1;
							foreach ( $meeting['MeetingFile'] as $meeting_file )
							{
								echo '<dl class="row">';
									echo '<dt class="col-12">添付資料' . $i . '</dt>';
									echo '<dd class="col-12 ml-2">' . $this->Display->file2( $meeting_file ) . '<dd>';
								echo '</dl>';
								$i++;
							}
						}
						?>
					</td>
				</tr>
				<tr>
					<th class="bg-light">プログラム</th>
					<td><?php echo nl2br($meeting['Meeting']['program']); ?></td>
				</tr>
			</table>
		</div>
	</div>
</div>