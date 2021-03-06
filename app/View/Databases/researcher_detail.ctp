<?php
$this->assign('title', $researcher['Researcher']['name_ja'] . ' | 数理技術相談データベース');
// パンくずリスト設定
$this->Html->addCrumb('研究者データベース', '/databases/researchers/');
$this->Html->addCrumb($researcher['Researcher']['name_ja'], '/databases/researchers/' . $researcher['Researcher']['id']);

// 各項目のページネーションの初期化
$pager_count['career']				= 0;
$pager_count['academic_background']	= 0;
$pager_count['committee_career']	= 0;
$pager_count['prize']				= 0;
$pager_count['paper']				= 0;
$pager_count['biblio']				= 0;
$pager_count['conference']			= 0;
$pager_count['teaching_experience']	= 0;
$pager_count['competitive_fund']	= 0;
$pager_count['patent']				= 0;
$pager_count['social_contribution']	= 0;
$pager_count['other']				= 0;
?>

<div class="container">
	<div class="row">
		<div class="col-12">
			<h2>
				<small><small><?php echo $researcher['Researcher']['name_kana']; ?></small></small><br>
				<?php echo $researcher['Researcher']['name_ja'] . ' / ' . $researcher['Researcher']['name_en']; ?>
			</h2>
			<h5>
				<?php
				if ( !empty($researcher['Researcher']['affiliation']) )
				{
					echo $researcher['Researcher']['affiliation'];
				}
				?>
			</h5>
			<?php
			if ( !empty($researcher['Researcher']['section']) )
			{
				echo $researcher['Researcher']['section'];
			}
			if ( !empty($researcher['Researcher']['job']) )
			{
				echo ' / ' . $researcher['Researcher']['job'];
			}
			if ( !empty($researcher['Researcher']['degree']) )
			{
				echo $researcher['Researcher']['degree'];
			}
			?>
			<br/>
			<?php
			if ( !empty($researcher['Researcher']['other_affiliation_id']) )
			{
				echo $researcher['Researcher']['other_affiliation_id'];
			}
			if ( !empty($researcher['Researcher']['other_affiliation']) )
			{
				echo $researcher['Researcher']['other_affiliation'];
			}
			if ( !empty($researcher['Researcher']['other_affiliation_section']) )
			{
				echo $researcher['Researcher']['other_affiliation_section'];
			}
			if ( !empty($researcher['Researcher']['other_affiliation_job']) )
			{
				echo $researcher['Researcher']['other_affiliation_job'];
			}
			?>
			<br/>
			<?php
			if ( !empty($researcher['Researcher']['specialty']) )
			{
				echo nl2br($researcher['Researcher']['specialty']);
			}
			?>
			<br/>
			<?php
			if ( !empty($researcher['Researcher']['rm_id']) )
			{
				$rmurl=$researcher['Researcher']['rm_id'];
				echo "<a href=\"" . $rmurl . "\" target=_blank>$rmurl</a><br/>";
			}
			?>
			<br/>
			<?php
			if ( !empty($researcher['Researcher']['profile']) )
			{
				echo nl2br($researcher['Researcher']['profile']);
			}
			?>
			<hr>
			
			<?php if ( !empty( $researcher['ResearcherResearchKeyword'] ) ): ?>
				<h3>研究キーワード</h3>
				<div class="mb-5">
					<?php
					$keywords = array();
					foreach( $researcher['ResearcherResearchKeyword'] as $detail )
					{
						$keywords[] = $detail['title'];
					}
					echo implode('、', $keywords);
					?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherResearchArea'] ) ): ?>
				<h3>研究分野</h3>
				<div class="mb-5">
					<?php
					$fields = array();
					foreach( $researcher['ResearcherResearchArea'] as $detail )
					{
						$fields[] = $detail['field_name'] . ' / ' . $detail['subject_name'];
					}
					echo implode('、', $fields);
					?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherCareer'] ) ): ?>
				<h3>経歴</h3>
				<div class="mb-5">
					<table class="table">
					<?php
					$i = 1;
					$x = 1;
					$p = 10;
					$last = count($researcher['ResearcherCareer']);
					$pager_count['career'] = ceil( $last / $p );
					?>
					<?php
					foreach( $researcher['ResearcherCareer'] as $detail )
					{
						if ( $i % $p == 1 )
						{
							echo '<tbody class="selection-career" id="career-' .$x . '">';
							$x++;
						}
						echo '<tr>';
							echo '<td>';
								echo $this->Display->rm_date_format($detail['from_date']);
							echo '</td>';
							
							echo '<td>';
								echo '-';
							echo '</td>';
							
							echo '<td>';
								echo $this->Display->rm_date_format($detail['to_date']);
							echo '</td>';
							echo '<td>';
								if ( !empty($detail['affiliation']) )
								{
									echo $detail['affiliation'] . ' ';
								}
								if ( !empty($detail['section']) )
								{
									echo $detail['section'] . ' ';
								}
								if ( !empty($detail['job']) )
								{
									echo $detail['job'];
								}
							echo '</td>';
						echo '</tr>';
						if ( $i % $p == 0 || $i == $last )
						{
							echo '</tbody>';
						}
						$i++;
					}
					?>
					</table>
					<?php if ($pager_count['career'] > 1): ?>
						<div class="pager-career"></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherAcademicBackground'] ) ): ?>
				<h3>学歴</h3>
				<div class="mb-5">
					<?php
					$i = 1;
					$x = 1;
					$p = 10;
					$last = count($researcher['ResearcherAcademicBackground']);
					$pager_count['academic_background'] = ceil( $last / $p );
					?>
					<table class="table">
						<?php
						foreach( $researcher['ResearcherAcademicBackground'] as $detail )
						{
							if ( $i % $p == 1 )
							{
								echo '<tbody class="selection-academic_background" id="academic_background-' .$x . '">';
								$x++;
							}
							echo '<tr>';
								echo '<td>';
									echo $this->Display->rm_date_format($detail['from_date']);
								echo '</td>';
								echo '<td>-</td>';
								echo '<td>';
									echo $this->Display->rm_date_format($detail['to_date']);
								echo '</td>';
								echo '<td>';
									if ( !empty($detail['title']) )
									{
										echo $detail['title'] . ' ';
									}
									if ( !empty($detail['department_name']) )
									{
										echo $detail['department_name'] . ' ';
									}
									if ( !empty($detail['subject_name']) )
									{
										echo $detail['subject_name'];
									}
								echo '</td>';
							echo '</tr>';
							
							if ( $i % $p == 0 || $i == $last )
							{
								echo '</tbody>';
							}
							$i++;
						}
						?>
					</table>
					<?php if ($pager_count['academic_background'] > 1): ?>
						<div class="pager-academic_background"></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherCommitteeCareer'] ) ): ?>
				<h3>委員歴</h3>
				<div class="mb-5">
					<table class="table">
					<?php
					$i = 1;
					$x = 1;
					$p = 10;
					$last = count($researcher['ResearcherCommitteeCareer']);
					$pager_count['committee_career'] = ceil( $last / $p );
					?>
					<?php
					foreach( $researcher['ResearcherCommitteeCareer'] as $detail )
					{
						if ( $i % $p == 1 )
						{
							echo '<tbody class="selection-committee_career" id="committee_career-' .$x . '">';
							$x++;
						}
						echo '<tr>';
							echo '<td>';
								echo $this->Display->rm_date_format($detail['from_date']);
							echo '</td>';
							echo '<td>-</td>';
							echo '<td>';
								echo $this->Display->rm_date_format($detail['to_date']);
							echo '</td>';
							echo '<td>';
								if ( !empty($detail['association']) )
								{
									echo $detail['association'] . ' ';
								}
								if ( !empty($detail['title']) )
								{
									echo $detail['title'];
								}
							echo '</td>';
						echo '</tr>';
						if ( $i % $p == 0 || $i == $last )
						{
							echo '</tbody>';
						}
						$i++;
					}
					?>
					</table>
					<?php if ($pager_count['committee_career'] > 1): ?>
						<div class="pager-committee_career"></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherPrize'] ) ): ?>
				<h3>受賞</h3>
				<div class="mb-5">
					<table class="table">
					<?php
					$i = 1;
					$x = 1;
					$p = 10;
					$last = count($researcher['ResearcherPrize']);
					$pager_count['prize'] = ceil( $last / $p );
					?>
					<?php
					foreach( $researcher['ResearcherPrize'] as $detail )
					{
						if ( $i % $p == 1 )
						{
							echo '<tbody class="selection-prize" id="prize-' .$x . '">';
							$x++;
						}
						
						echo '<tr>';
							echo '<td>';
								if ( !empty($detail['publication_date']) )
								{
									echo $this->Display->rm_date_format($detail['publication_date']);
								}
							echo '</td>';
							echo '<td>';
								if ( !empty($detail['title']) )
								{
									echo $detail['title'];
								}
							echo '</td>';
						echo '</tr>';
						if ( $i % $p == 0 || $i == $last )
						{
							echo '</tbody>';
						}
						$i++;
					}
					?>
					</table>
					<?php if ($pager_count['prize'] > 1): ?>
						<div class="pager-prize"></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherPaper'] ) ): ?>
				<h3>論文</h3>
				<div class="mb-5">
					<?php
					$i = 1;
					$x = 1;
					$p = 10;
					$last = count($researcher['ResearcherPaper']);
					$pager_count['paper'] = ceil($last/$p);
					?>
					<?php foreach( $researcher['ResearcherPaper'] as $detail ): ?>
						<?php if ( $i % $p == 1 ): ?>
							<div class="selection-paper" id="paper-<?php echo $x; ?>">
						<?php
							$x++;
						endif; ?>
						
						<div class="mb-2" style="word-wrap:no-wrap; word-break:break-all;">
							<hr>
							<h5>
								<?php
								if ( !empty($detail['link']) )
								{
									echo $this->Html->link($detail['title'], $detail['link'], array('target' => '_blank'));
								}
								else
								{
									echo $detail['title'];
								}
								?>
							</h5>
							<?php
							if ( !empty($detail['author']) )
							{
								echo $detail['author'];
								echo '<br>';
							}
							if ( !empty($detail['summary']) )
							{
								echo $this->Text->truncate($detail['summary'], 300);
								echo '<br>';
							}
							$txt = '';
							if ( !empty($detail['journal']) )
							{
								$txt .= $detail['journal'];
							}
							if ( !empty($detail['publisher']) )
							{
								$txt .= '（' . $detail['publisher']. '）';
							}
							if ( !empty($detail['publication_name']) )
							{
								$txt .= '　' . $detail['publication_name'];
							}
							if ( !empty($detail['volume']) )
							{
								$txt .= '　' . $detail['volume'] . '巻';
							}
							if ( !empty($detail['number']) )
							{
								$txt .= $detail['number'] . '号';
							}
							if ( !empty($detail['starting_page']) )
							{
								$txt .= '　' . $detail['starting_page'] . '～';
							}
							if ( !empty($detail['ending_page']) )
							{
								$txt .= $detail['ending_page'];
							}
							if ( !empty($detail['publication_date']) )
							{
								$txt .= '　' . $this->Display->rm_date_format($detail['publication_date']);
							}
							echo $txt;
							?>
							<?php echo ($detail['referee'])?'<span class="badge badge-info">査読有り</span>':''; ?>
							<?php echo ($detail['invited'])?'<span class="badge badge-warning">招待論文</span>':''; ?>
						</div>
						
						<?php if ( $i % $p == 0 || $i == $last ): ?>
							</div>
						<?php endif; ?>
						
					<?php
					$i++;
					endforeach; ?>
					<?php if ($pager_count['paper'] > 1): ?>
						<div class="pager-paper"></div>
					<?php endif; ?>
					<hr>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherBiblio'] ) ): ?>
				<h3>書籍等出版物</h3>
				<div class="mb-5">
					<?php
					$i = 1;
					$x = 1;
					$p = 10;
					$last = count($researcher['ResearcherBiblio']);
					$pager_count['biblio'] = ceil( $last / $p );
					?>
					<?php foreach( $researcher['ResearcherBiblio'] as $detail ): ?>
						<?php if ( $i % $p == 1 ): ?>
							<div class="selection-biblio" id="biblio-<?php echo $x; ?>">
						<?php
							$x++;
						endif; ?>
						<div class="mb-2">
							<hr>
							<h5><?php echo $detail['title']; ?></h5>
							<?php
							if ( !empty($detail['author']) )
							{
								echo $detail['author'];
								echo '<br>';
							}
							if ( !empty($detail['publisher']) )
							{
								echo $detail['publisher'] . '　';
							}
							if ( !empty($detail['publication_date']) )
							{
								echo $this->Display->rm_date_format($detail['publication_date']) . '　';
							}
							if ( !empty($detail['isbn']) )
							{
								echo 'ISBN：' . $detail['isbn'];
							}
							?>
						</div>
						
						<?php if ( $i % $p == 0 || $i == $last ): ?>
							</div>
						<?php endif; ?>
						
					<?php
					$i++;
					endforeach; ?>
					<?php if ($pager_count['biblio'] > 1): ?>
						<div class="pager-biblio"></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherConference'] ) ): ?>
				<h3>講演・口頭発表等</h3>
				<div class="mb-5">
					<?php
					$i = 1;
					$x = 1;
					$p = 10;
					$last = count($researcher['ResearcherConference']);
					$pager_count['conference'] = ceil( $last / $p );
					?>
					<?php foreach( $researcher['ResearcherConference'] as $detail ): ?>
						<?php if ( $i % $p == 1 ): ?>
							<div class="selection-conference" id="conference-<?php echo $x; ?>">
						<?php
							$x++;
						endif; ?>
						<div class="mb-2">
							<hr>
							<h5>
								<?php
								if ( strpos($detail['link'], 'researchmap.jp') === false )
								{
									echo $this->Html->link($detail['title'], $detail['link'], array('target' => '_blank'));
								}
								else
								{
									echo $detail['title'];
								}
								?>
							</h5>
							<?php
							if ( !empty($detail['author']) )
							{
								echo $detail['author'];
								echo '<br>';
							}
							?>
							<?php
							if ( !empty($detail['journal']) )
							{
								echo $detail['journal'] . '　';
							}
							if ( !empty($detail['publication_date']) )
							{
								echo $this->Display->rm_date_format($detail['publication_date']);
							}
							?><br>
						</div>
						
						<?php if ( $i % $p == 0 || $i == $last ): ?>
							</div>
						<?php endif; ?>
					<?php 
					$i++;
					endforeach; ?>
					<?php if ($pager_count['conference'] > 1): ?>
						<div class="pager-conference"></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherTeachingExperience'] ) ): ?>
				<h3>担当経験のある科目</h3>
				<div class="mb-5">
					<ul>
						<?php
						$i = 1;
						$x = 1;
						$p = 10;
						$last = count($researcher['ResearcherTeachingExperience']);
						$pager_count['teaching_experience'] = ceil( $last / $p );
						foreach( $researcher['ResearcherTeachingExperience'] as $detail )
						{
							if ( $i % $p == 1 )
							{
								echo '<div class="selection-teaching_experience" id="teaching_experience-' . $x . '">';
								$x++;
							}
							
							echo '<li>';
							echo $detail['title'];
							if ( !empty($detail['affiliation']) )
							{
								echo '（' . $detail['affiliation'] . '）';
							}
							echo '</li>';
							
							if ( $i % $p == 0 || $i == $last )
							{
								echo '</div>';
							}
							$i++;
						}
						?>
					</ul>
					<?php if ($pager_count['teaching_experience'] > 1): ?>
						<div class="pager-teaching_experience"></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherAcademicSociety'] ) ): ?>
				<h3>所属学協会</h3>
				<div class="mb-5">
					<?php
					$societies = array();
					foreach( $researcher['ResearcherAcademicSociety'] as $detail )
					{
						$societies[] = $detail['title'];
					}
					echo implode('、', $societies);
					?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherCompetitiveFund'] ) ): ?>
				<h3>競争的資金等の研究課題</h3>
				<div class="mb-5">
					<?php
					$i = 1;
					$x = 1;
					$p = 10;
					$last = count($researcher['ResearcherCompetitiveFund']);
					$pager_count['competitive_fund'] = ceil( $last / $p );
					?>
					<?php foreach( $researcher['ResearcherCompetitiveFund'] as $detail ): ?>
						<?php
						if ( $i % $p == 1 )
						{
							echo '<div class="selection-competitive_fund" id="competitive_fund-' . $x . '">';
							$x++;
						}
						?>
						<div class="mb-2">
							<hr>
							<h5>
							<?php
							if ( !empty($detail['link']) )
							{
								echo $this->Html->link($detail['title'], $detail['link'], array('target' => '_blank'));
							}
							else
							{
								echo $detail['title'];
							}
							?>
							</h5>
							<?php
							if ( !empty($detail['provider']) )
							{
								echo $detail['provider'] . '：' . $detail['system'];
							}
							if ( !empty($detail['from_date']))
							{
								echo '　研究期間：' .  $this->Display->rm_date_format($detail['from_date']) . ' - '. $this->Display->rm_date_format($detail['to_date']);
							}
							
							if( !empty($detail['author']))
							{
								echo '　代表者：' . $detail['author'];
							}
							
							if ( !empty($detail['summary']) )
							{
								echo '<p>';
								echo $this->Text->truncate($detail['summary'], 300);
								echo '</p>';
							}
							?>
						</div>
						<?php
						if ( $i % $p == 0 || $i == $last )
						{
							echo '</div>';
						}
						$i++;
						?>
					<?php endforeach; ?>
					<?php if ($pager_count['competitive_fund'] > 1): ?>
						<div class="pager-competitive_fund"></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherPatent'] ) ): ?>
				<h3>特許</h3>
				<div class="mb-5">
					<?php
					$i = 1;
					$x = 1;
					$p = 10;
					$last = count($researcher['ResearcherPatent']);
					$pager_count['patent'] = ceil( $last / $p );
					?>
					<?php foreach( $researcher['ResearcherPatent'] as $detail ): ?>
						<?php
						if ( $i % $p == 1 )
						{
							echo '<div class="selection-patent" id="patent-' . $x . '">';
							$x++;
						}
						?>
						<div class="mb-2">
							<h5>
							<?php
							if ( !empty($detail['public_id']) )
							{
								echo $this->Html->link($detail['public_id'] . '：' . $detail['title'], $detail['link'], array('target' => '_blank'));
							}
							else
							{
								echo $this->Html->link($detail['title'], $detail['link'], array('target' => '_blank'));
							}
							?>
							</h5>
							<?php
							if ( !empty($detail['author']) )
							{
								echo $detail['author'];
							}
							?>
						</div>
						<?php
						if ( $i % $p == 0 || $i == $last )
						{
							echo '</div>';
						}
						$i++;
						?>
					<?php endforeach; ?>
					<?php if ($pager_count['patent'] > 1): ?>
						<div class="pager-patent"></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherSocialContribution'] ) ): ?>
				<h3>社会貢献活動</h3>
				<div class="mb-5">
					<?php
					$i = 1;
					$x = 1;
					$p = 10;
					$last = count($researcher['ResearcherSocialContribution']);
					$pager_count['social_contribution'] = ceil( $last / $p );
					?>
					<?php foreach( $researcher['ResearcherSocialContribution'] as $detail ): ?>
						<?php
						if ( $i % $p == 1 )
						{
							echo '<div class="selection-social_contribution" id="social_contribution-' . $x . '">';
							$x++;
						}
						?>
						<div class="mb-2">
							<hr>
							<?php
							echo '<h5>' . $detail['title'] . '</h5>';
							if ( !empty($detail['summary']) )
							{
								echo $this->Text->truncate($detail['summary'], 300);
								echo '<br>';
							}
							if ( !empty($detail['role_name']) )
							{
								echo '【' . $detail['role_name'] . '】';
							}
							if ( !empty($detail['from_date']) )
							{
								echo $this->Display->rm_date_format($detail['from_date']);
								echo ' ～ ';
							}
							if ( !empty($detail['to_date']) )
							{
								echo $this->Display->rm_date_format($detail['to_date']);
							}
							?>
						</div>
						<?php
						if ( $i % $p == 0 || $i == $last )
						{
							echo '</div>';
						}
						$i++;
						?>
					<?php endforeach; ?>
					<?php if ($pager_count['social_contribution'] > 1): ?>
						<div class="pager-social_contribution"></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty($researcher['Researcher']['example']) ): ?>
				<h3>研究応用事例</h3>
				<div class="mb-5">
					<?php echo nl2br($researcher['Researcher']['example']); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( !empty( $researcher['ResearcherOther'] ) ): ?>
				<h3>その他</h3>
				<div class="mb-5">
					<?php
					$i = 1;
					$x = 1;
					$p = 10;
					$last = count($researcher['ResearcherOther']);
					$pager_count['other'] = ceil( $last / $p );
					?>
					<?php foreach( $researcher['ResearcherOther'] as $detail ): ?>
						<?php
						if ( $i % $p == 1 )
						{
							echo '<div class="selection-other" id="other-' . $x . '">';
							$x++;
						}
						?>
						<h5><?php echo $detail['title']; ?></h5>
						<?php
						if ( !empty($detail['publication_date']) )
						{
							echo $this->Display->rm_date_format($detail['publication_date']);
						}
						if ( !empty($detail['summary']) )
						{
							echo $this->Text->truncate(nl2br($detail['summary']), 300);
						}
						?>
						<?php
						if ( $i % $p == 0 || $i == $last )
						{
							echo '</div>';
						}
						$i++;
						?>
					<?php endforeach; ?>
					<?php if ($pager_count['other'] > 1): ?>
						<div class="pager-other"></div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
		</div>
	</div>
</div>
<div class="gototop">
	<a href="#"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
</div>
<?php $this->set('pager_count', $pager_count); // viewの値をlayoutへ渡す ?>