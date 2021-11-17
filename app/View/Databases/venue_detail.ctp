<?php
$this->assign('title', $venue['Venue']['name'] . ' | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('研究会場データベース', '/databases/venues/');
$this->Html->addCrumb($venue['Venue']['name'], '/databases/venue_detail/' . $venue['Venue']['id']);
?>

<div class="container">
	<div class="row">
		<div class="col-6">
			<div class="alert mb-2">
				<h4><?php echo $venue['Venue']['name']; ?></h4>
				<hr>
				<h5>所在地</h5>
				<p>
					〒 <?php echo $venue['Venue']['zip']; ?><br>
					<?php echo $prefectures[$venue['Venue']['prefecture_id']]; ?>&nbsp;<?php echo $venue['Venue']['city']; ?>&nbsp;<?php echo $venue['Venue']['address']; ?>
				</p>
				<h5 class="heading">連絡先</h5>
				<p>
					電話番号：<?php echo (!empty($venue['Venue']['tel']))?$venue['Venue']['tel']:'-'; ?><br>
					メールアドレス：<?php echo (!empty($venue['Venue']['email']))?$venue['Venue']['email']:'-'; ?>
				</p>
			</div>
		</div>
		<div class="col-6">
			<?php if ( !empty($venue['Venue']['lat']) && !empty($venue['Venue']['lng']) ): ?>
				<iframe src="http://maps.google.co.jp/maps?q=<?php echo $venue['Venue']['lat']; ?>,<?php echo $venue['Venue']['lng']; ?>&output=embed&t=m&z=16&hl=ja" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="600" height="450"></iframe>
			<?php elseif ( !empty($venue['Venue']['name']) ): ?>
				<iframe src="http://maps.google.co.jp/maps?q=<?php echo $venue['Venue']['name']; ?>&output=embed&t=m&z=16&hl=ja" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" width="600" height="450"></iframe>
			<?php endif; ?>
		</div>
	</div>
</div>