<?php
$this->assign('title', $case['ResearchCase']['title'] . ' | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('研究事例データベース', '/databases/cases/');
$this->Html->addCrumb($case['ResearchCase']['title'], '/databases/case_detail/' . $case['ResearchCase']['id']);
?>

<h2>研究事例データベース</h2>
<br>
<div class="container">
	<div class="row">
		<div class="col-12">
			<h4><?php echo $case['ResearchCase']['title']; ?></h4>
			<hr>
			<table class="table table-bordered">
				<tr>
					<th class="bg-light w-25">研究者</th>
					<td><?php echo nl2br($case['ResearchCase']['researcher']); ?></td>
				</tr>
				<tr>
					<th class="bg-light">キーワード</th>
					<td><?php echo nl2br($case['ResearchCase']['keyword']); ?></td>
				</tr>
				<?php if( !empty($case['ResearchCase']['body']) ): ?>
					<tr>
						<th class="bg-light">詳細</th>
						<td><?php echo nl2br($case['ResearchCase']['body']); ?></td>
					</tr>
				<?php endif; ?>
				
				<?php if( !empty($case['ResearchCase']['file']) ): ?>
					<tr>
						<th class="bg-light">添付ファイル</th>
						<td>
							<?php
							$base = $this->Html->url( Configure::read('App.site_url') . "files/research_case/file/" );
							//echo $this->Html->link( $case['ResearchCase']['file_org'], $base . $case['ResearchCase']['file_dir'] . "/" . $case['ResearchCase']['file'], array('escape' => false, 'target' => '_blank') );
							echo $this->Display->file3( $case );
							?>
						</td>
					</tr>
				<?php endif; ?>
			</table>
		</div>
	</div>
</div>