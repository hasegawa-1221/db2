<?php
$this->assign('title', '企画応募 | 数理技術相談データベース');
?>
	<div class="container">
		<h2>企画応募（完了画面）</h2>
		
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
			<li class="disabled">入力内容確認</li>
			<li><i class="fa fa-angle-double-right" aria-hidden="true"></i></li>
			<li class="active">完了</li>
		</ul>
		<hr>
		
		<div class="row">
			<div class="col-12">
				<?php if ( !empty($mail_send) ): ?>
					<div class="alert alert-success">
						下記メールアドレスへメールを送信しました。<br>
						<ul>
							<?php foreach( $mail_send as $send ): ?>
								<li><?php echo $send; ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				
				<?php if ( !empty($mail_error) ): ?>
					<div class="alert alert-danger">
						下記メールアドレスへのメール送信に失敗しました。<br>
						<ul>
							<?php foreach( $mail_error as $error ): ?>
								<li><?php echo $error; ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
				
				<div class="alert alert-outline-success">
					この度は、企画にご応募いただき誠にありがとうございました。<br>
					<br>
					折り返し、応募完了のメールをお送りしております。<br>
					万が一メールが届かない場合、応募が完了されていない場合がございますので、<br>
					その際はお手数ではございますが、AIMaP事務局までまでお問い合わせください。<br>
					<br>
					また、企画の採択結果は後日ご登録のメールアドレス宛にお送りさせていただきます。<br>
					<br>
				</div>
				
				<div class="alert alert-secondary">
					お問い合わせ先：九州大学　マス・フォア・インダストリ研究所<br>
					AIMaP事務局　aimap@imi.kyushu-u.ac.jp
				<div>
			</div>
		</div>
		<hr>
	</div>
<br>
<br>