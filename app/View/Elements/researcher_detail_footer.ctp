<script>
$(function(){
	$('.pager-career').pagination({
		items: <?php echo $pager_count['career']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#career-" + pageNumber;
			$('.selection-career').hide();
			$(page).show();
		}
	});
	$('.pager-academic_background').pagination({
		items: <?php echo $pager_count['academic_background']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#academic_background-" + pageNumber;
			$('.selection-academic_background').hide();
			$(page).show();
		}
	});
	$('.pager-committee_career').pagination({
		items: <?php echo $pager_count['committee_career']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#committee_career-" + pageNumber;
			$('.selection-committee_career').hide();
			$(page).show();
		}
	});
	$('.pager-prize').pagination({
		items: <?php echo $pager_count['prize']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#prize-" + pageNumber;
			$('.selection-prize').hide();
			$(page).show();
		}
	});
	$('.pager-paper').pagination({
		items: <?php echo $pager_count['paper']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#paper-" + pageNumber;
			$('.selection-paper').hide();
			$(page).show();
		}
	});
	$('.pager-biblio').pagination({
		items: <?php echo $pager_count['biblio']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#biblio-" + pageNumber;
			$('.selection-biblio').hide();
			$(page).show();
		}
	});
	$('.pager-conference').pagination({
		items: <?php echo $pager_count['conference']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#conference-" + pageNumber;
			$('.selection-conference').hide();
			$(page).show();
		}
	});
	$('.pager-teaching_experience').pagination({
		items: <?php echo $pager_count['teaching_experience']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#teaching_experience-" + pageNumber;
			$('.selection-teaching_experience').hide();
			$(page).show();
		}
	});
	$('.pager-competitive_fund').pagination({
		items: <?php echo $pager_count['competitive_fund']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#competitive_fund-" + pageNumber;
			$('.selection-competitive_fund').hide();
			$(page).show();
		}
	});
	$('.pager-patent').pagination({
		items: <?php echo $pager_count['patent']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#patent-" + pageNumber;
			$('.selection-patent').hide();
			$(page).show();
		}
	});
	$('.pager-social_contribution').pagination({
		items: <?php echo $pager_count['social_contribution']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#social_contribution-" + pageNumber;
			$('.selection-social_contribution').hide();
			$(page).show();
		}
	});
	$('.pager-other').pagination({
		items: <?php echo $pager_count['other']; ?>,
		displayPages: 10,
		cssStyle: 'light-theme',
		prevText: '<<',
		nextText: '>>',
		onPageClick: function(pageNumber)
		{
			var page = "#other-" + pageNumber;
			$('.selection-other').hide();
			$(page).show();
		}
	});
});
</script>
<style>
.selection-career {display: none;}
#career-1 { display: block;}

.selection-academic_background {display: none;}
#academic_background-1 { display: block;}

.selection-committee_career {display: none;}
#committee_career-1 { display: block;}

.selection-prize {display: none;}
#prize-1 { display: block;}

.selection-paper {display: none;}
#paper-1 { display: block;}

.selection-biblio {display: none;}
#biblio-1 { display: block;}

.selection-conference {display: none;}
#conference-1 { display: block;}

.selection-teaching_experience {display: none;}
#teaching_experience-1 { display: block;}

.selection-competitive_fund {display: none;}
#competitive_fund-1 { display: block;}

.selection-patent {display: none;}
#patent-1 { display: block;}

.selection-social_contribution {display: none;}
#social_contribution-1 { display: block;}

.selection-other {display: none;}
#other-1 { display: block;}
</style>
