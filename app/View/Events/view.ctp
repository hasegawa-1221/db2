								<h5 class="modal-title"><?php echo $event['Event']['title']; ?><small></small></h5>
								
								<h5>基本情報</h5>
								<div class="container mb-4">
									<div class="row">
										<div class="col-6">作成日：<?php echo $event['Event']['created']; ?></div>
										<div class="col-6">更新日：<?php echo $event['Event']['modified']; ?></div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">作成者：<?php echo $event['AddUser']['username']; ?></div>
										<div class="col-6">最終更新者：<?php echo $event['LatestUser']['username']; ?></div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">種別：<?php echo $event_type[$event['Event']['type']]; ?></div>
										<div class="col-6">企画番号：<?php echo $event['Event']['event_number']; ?></div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">ログインID：<?php echo $event['Event']['username']; ?></div>
										<div class="col-6">状態：<?php echo $event_status[$event['Event']['status']]; ?></div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">内部コメント:<br>
											<?php echo nl2br($event['Event']['comment']); ?>
										</div>
									</div>
									<hr>
								</div>

								<h5>企画の概要</h5>
								<div class="container mb-4">
									<div class="row">
										<div class="col-12">名称：<?php echo $event['Event']['title']; ?></div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											集会等のタイプ	：<br>
											<?php if ( !empty($event['EventTheme'] ) ): ?>
												<?php foreach ( $event['EventTheme'] as $theme ): ?>
													・<?php echo $theme['Theme']['name']; ?>
												<?php endforeach; ?>
											<?php endif; ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											連携相手の分野・業界：<br>
											<?php echo $event['Event']['field']; ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											キーワード：<br>
											<?php
											if ( !empty($event['EventKeyword']) )
											{
												foreach ( $event['EventKeyword'] as $event_keyword )
												{
													if ( !empty($event_keyword['title']) )
													{
														echo $event_keyword['title'], ',';
													}
												}
											}
											?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">
											主催機関：<?php echo $event['Event']['organization']; ?>
										</div>
										<div class="col-6">
											開催場所：<?php echo nl2br($event['Event']['place']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											開催時期：<?php echo $event['Event']['start']; ?> ～ <?php echo $event['Event']['end']; ?>
										</div>
									</div>
									<hr>
								</div>

								<h5>企画の詳細</h5>
								<div class="container mb-4">
									<div class="row">
										<div class="col-12">
											プログラム：<br>
											<?php echo nl2br($event['Event']['program']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											趣旨・目的：<br>
											<?php echo nl2br($event['Event']['purpose']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											取り扱うテーマ・トピックや解決すべき課題：<br>
											<?php echo nl2br($event['Event']['subject']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											考えられる数学・数理科学的アプローチ：<br>
											<?php echo nl2br($event['Event']['approach']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											これまでの準備状況：<br>
											<?php echo nl2br($event['Event']['prepare']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											終了後のフォローアップの計画：<br>
											<?php echo nl2br($event['Event']['follow']); ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-12">
											他機関からの支援：<br>
											<?php echo ($event['Event']['is_support'])?'有':'無'; ?>
										</div>
										<div class="col-12">
											有の場合は支援元：<br>
											<?php echo nl2br($event['Event']['support']); ?>
										</div>
									</div>
									<hr>
								</div>

								<h5>経費</h5>
								<div class="container">
									<?php if ( !empty($event['Expense']) ): ?>
										<?php
										$total = 0;
										$subtotal1 = 0;
										$subtotal2 = 0;
										$subtotal3 = 0;
										$subtotal4 = 0;
										?>
										<?php foreach ( $event['Expense'] as $type => $expense ): ?>
											<?php if ( $type == 1 ): ?>
												<div class="row">
													<div class="col-12"><?php echo '・旅費'; ?></div>
												</div>
												<?php foreach( $expense as $ex ): ?>
													<div class="row">
														<div class="col-2"><?php echo $ex['affiliation']; ?></div>
														<div class="col-2"><?php echo $ex['job']; ?></div>
														<div class="col-2"><?php echo $ex['lastname']; ?> <?php echo $ex['firstname']; ?></div>
														<div class="col-2"><?php echo $ex['title']; ?></div>
														<div class="col-2"><?php echo ($ex['request_price'])?number_format($ex['request_price']) . '円 ':' '; ?></div>
														<div class="col-2"><?php echo $ex['note']; ?></div>
													</div>
													<?php $subtotal1 += $ex['request_price']; ?>
													<?php $total += $ex['request_price']; ?>
												<?php endforeach; ?>
												<div class="row">
													<div class="col-12 text-right">小計：<?php echo number_format($subtotal1); ?>円</div>
												</div>
											<?php elseif ( $type == 2 ): ?>
												<div class="row">
													<div class="col-12"><?php echo '・諸謝金'; ?></div>
												</div>
												<?php foreach( $expense as $ex ): ?>
													<div class="row">
														<div class="col-2"><?php echo $ex['affiliation']; ?></div>
														<div class="col-2"><?php echo $ex['job']; ?></div>
														<div class="col-2"><?php echo $ex['lastname']; ?> <?php echo $ex['firstname']; ?></div>
														<div class="col-2"><?php echo $ex['title']; ?></div>
														<div class="col-2"><?php echo ($ex['request_price'])?number_format($ex['request_price']) . '円 ':' '; ?></div>
														<div class="col-2"><?php echo $ex['note']; ?></div>
													</div>
													<?php $subtotal2 += $ex['request_price']; ?>
													<?php $total += $ex['request_price']; ?>
												<?php endforeach; ?>
												<div class="row">
													<div class="col-12 text-right">小計：<?php echo number_format($subtotal2); ?>円</div>
												</div>
											<?php elseif ( $type == 3 ): ?>
												<div class="row">
													<div class="col-12"><?php echo '・印刷製本費'; ?></div>
												</div>
												<?php foreach( $expense as $ex ): ?>
													<div class="row">
														<div class="col-2"><?php echo $ex['title']; ?></div>
														<div class="col-2"><?php echo $ex['count']; ?></div>
														<div class="col-2"><?php echo $ex['price']; ?></div>
														<div class="col-2"><?php echo ($ex['request_price'])?number_format($ex['request_price']) . '円 ':' '; ?></div>
														<div class="col-2"><?php echo $ex['note']; ?></div>
													</div>
													<?php $subtotal3 += $ex['request_price']; ?>
													<?php $total += $ex['request_price']; ?>
												<?php endforeach; ?>
												<div class="row">
													<div class="col-12 text-right">小計：<?php echo number_format($subtotal3); ?>円</div>
												</div>
											<?php elseif ( $type == 4 ): ?>
												<div class="row">
													<div class="col-12"><?php echo '・その他 '; ?></div>
												</div>
												<?php foreach( $expense as $ex ): ?>
													<div class="row">
														<div class="col-2"><?php echo $ex['title']; ?></div>
														<div class="col-2"><?php echo $ex['count']; ?></div>
														<div class="col-2"><?php echo $ex['price']; ?></div>
														<div class="col-2"><?php echo ($ex['request_price'])?number_format($ex['request_price']) . '円 ':' '; ?></div>
														<div class="col-2"><?php echo $ex['note']; ?></div>
													</div>
													<?php $subtotal4 += $ex['request_price']; ?>
													<?php $total += $ex['request_price']; ?>
												<?php endforeach; ?>
												<div class="row">
													<div class="col-12 text-right">小計：<?php echo number_format($subtotal4); ?>円</div>
												</div>
											<?php endif; ?>
											<hr>
										<?php endforeach; ?>
										<div class="row">
											<div class="col-12 text-right">合計：<?php echo number_format($total); ?>円</div>
										</div>
										<hr>
									<?php endif; ?>
								</div>

								<h5>参加について</h5>
								<div class="container mb-4">
									<div class="row">
										<div class="col-6">
											参加制限：<?php echo ($event['Event']['qualification'])?'有':'無'; ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">
											有の場合は参加資格：<?php echo $event['Event']['qualification_other']; ?>
										</div>
									</div>
									<hr>
									<div class="row">
										<div class="col-6">
											参加申込：<?php echo $options1[$event['Event']['qualification_apply']]; ?>
										</div>
									</div>
									<hr>
								</div>

								<h5>運営責任者</h5>
								<div class="container mb-4">
									<?php if ( !empty($event['EventManager']) ): ?>
										<?php foreach ( $event['EventManager'] as $event_manager ): ?>
											<div class="row">
												<div class="col-12">
													メールアドレス：<?php echo $event_manager['email']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													氏名：<?php echo $event_manager['lastname']; ?> <?php echo $event_manager['firstname']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													フリガナ：<?php echo $event_manager['lastname_kana']; ?> <?php echo $event_manager['firstname_kana']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													所属機関：<?php echo $event_manager['organization']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													所属部局：<?php echo $event_manager['department']; ?>
												</div>
												<div class="col-6">
													職名：<?php echo $event_manager['job_title']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													郵便番号：<?php echo $event_manager['zip']; ?>
												</div>
												<div class="col-6">
													都道府県：<?php echo $prefectures[$event_manager['prefecture_id']]; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													市区町村：<?php echo $event_manager['city']; ?>
												</div>
												<div class="col-6">
													住所：<?php echo $event_manager['address']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													TEL：<?php echo $event_manager['tel']; ?>
												</div>
												<div class="col-6">
													FAX：<?php echo $event_manager['fax']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													URL：<?php echo $event_manager['url']; ?>
												</div>
											</div>
											<hr>
										<?php endforeach; ?>
									<?php endif; ?>
									<hr>
								</div>
								
								<h5>事務担当者</h5>
								<div class="container mb-4">
									<?php if ( !empty($event['EventAffair']) ): ?>
										<?php foreach ( $event['EventAffair'] as $event_manager ): ?>
											<div class="row">
												<div class="col-12">
													メールアドレス：<?php echo $event_manager['email']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													氏名：<?php echo $event_manager['lastname']; ?> <?php echo $event_manager['firstname']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													フリガナ：<?php echo $event_manager['lastname_kana']; ?> <?php echo $event_manager['firstname_kana']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													所属機関：<?php echo $event_manager['organization']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													所属部局：<?php echo $event_manager['department']; ?>
												</div>
												<div class="col-6">
													職名：<?php echo $event_manager['job_title']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													郵便番号：<?php echo $event_manager['zip']; ?>
												</div>
												<div class="col-6">
													都道府県：<?php echo $prefectures[$event_manager['prefecture_id']]; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													市区町村：<?php echo $event_manager['city']; ?>
												</div>
												<div class="col-6">
													住所：<?php echo $event_manager['address']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-6">
													TEL：<?php echo $event_manager['tel']; ?>
												</div>
												<div class="col-6">
													FAX：<?php echo $event_manager['fax']; ?>
												</div>
											</div>
											
											<div class="row">
												<div class="col-12">
													URL：<?php echo $event_manager['url']; ?>
												</div>
											</div>
											<hr>
										<?php endforeach; ?>
									<?php endif; ?>
									<hr>
								</div>
							</div>

<style>
nav.navbar {
	display:none;
}
</style>			