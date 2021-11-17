<?php
$this->assign('title', $affiliation['Affiliation']['name'] . ' | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('研究機関データベース', '/databases/organizations/');
$this->Html->addCrumb($affiliation['Affiliation']['name'], '/databases/organization_detail/' . $affiliation['Affiliation']['id']);
?>

<div class="container">
	<div class="row">
		<div class="col-6">
			<div class="alert mb-2">
				<h4><?php echo $affiliation['Affiliation']['name']; ?></h4>
				<hr>
				<h5>所在地</h5>
				<p>
					〒 <?php echo $affiliation['Affiliation']['zip']; ?><br>
					<?php echo $prefectures[$affiliation['Affiliation']['prefecture_id']]; ?>&nbsp;<?php echo $affiliation['Affiliation']['city']; ?>&nbsp;<?php echo $affiliation['Affiliation']['address']; ?>
				</p>
				<h5 class="heading">連絡先</h5>
				<p>
					電話番号：<?php echo (!empty($affiliation['Affiliation']['tel']))?$affiliation['Affiliation']['tel']:'-'; ?><br>
					メールアドレス：<?php echo (!empty($affiliation['Affiliation']['email']))?$affiliation['Affiliation']['email']:'-'; ?>
				</p>
			</div>
		</div>
		<div class="col-6">
			<?php if ( !empty($affiliation['Affiliation']['lat']) && !empty($affiliation['Affiliation']['lng']) ): ?>
				<iframe src="http://maps.google.co.jp/maps?q=<?php echo $affiliation['Affiliation']['lat']; ?>,<?php echo $affiliation['Affiliation']['lng']; ?>&output=embed&t=m&z=16&hl=ja" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="600" height="450"></iframe>
			<?php elseif ( !empty($affiliation['Affiliation']['name']) ): ?>
				<iframe src="http://maps.google.co.jp/maps?q=<?php echo $affiliation['Affiliation']['name']; ?>&output=embed&t=m&z=16&hl=ja" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="600" height="450"></iframe>
			<?php endif; ?>
		</div>
	</div>
</div>