var abs_path = 'https://aimap.imi.kyushu-u.ac.jp/db2/';
$('a').smoothScroll({
    events: 'mouseover'
  });
// 関数
// 
function number_format(num)
{
	return num.toString().replace(
		/(\d+?)(?=(?:\d{3})+$)/g,
		function (x) {
			return x + ',';
		}
	);
}

// 
function calc_prices()
{
	// 
	var subtotal_1 = Number(0);
	var subtotal_2 = Number(0);
	var subtotal_3 = Number(0);
	var subtotal_4 = Number(0);
	
	$request_prices_1 = $('.form-request_price.item-id-1');
	$.each($request_prices_1, function(i, val){
		subtotal_1 += Number($(val).val());
	});
	$('.subtotal_1').html( number_format(subtotal_1) );
	
	// 
	$request_prices_2 = $('.form-request_price.item-id-2');
	$.each($request_prices_2, function(i, val){
		subtotal_2 += Number($(val).val());
	});
	$('.subtotal_2').html( number_format(subtotal_2) );
	
	// 
	$request_prices_3 = $('.form-request_price.item-id-3');
	$.each($request_prices_3, function(i, val){
		subtotal_3 += Number($(val).val());
	});
	$('.subtotal_3').html( number_format(subtotal_3) );
	
	// 
	$request_prices_4 = $('.form-request_price.item-id-4');
	$.each($request_prices_4, function(i, val){
		subtotal_4 += Number($(val).val());
	});
	$('.subtotal_4').html( number_format(subtotal_4) );
	
	var total = subtotal_1
		 + subtotal_2
		 + subtotal_3
		 + subtotal_4;
	
	$('.total').html( number_format(total) );
	
	return true;
}

// datepicker
$('.datepicker').datepicker({
	format: 'yyyy-mm-dd'
});

// 所属のオートコンプリート
$('.form-affiliation').autocomplete({
	source: function( req, res ) {
		$.ajax({
			url: abs_path + "databases/autocomplete/" + encodeURIComponent(req.term),
			dataType: "json",
			success: function( data ) {
				res(data);
			}
		});
	},
	autoFocus: true,
	delay: 200,
	minLength: 1
});

// 研究キーワードのオートコンプリート
$('#SearchKeyword').autocomplete({
	source: function( req, res ) {
		$.ajax({
			url: abs_path + "databases/autocomplete_keyword/" + encodeURIComponent(req.term),
			dataType: "json",
			success: function( data ) {
				res(data);
			}
		});
	},
	autoFocus: true,
	delay: 200,
	minLength: 1
});

// 現在の行番号
var item_id_1_count = $('#item-id-1 tbody tr').length;
var item_id_2_count = $('#item-id-2 tbody tr').length;
var item_id_3_count = $('#item-id-3 tbody tr').length;
var item_id_4_count = $('#item-id-4 tbody tr').length;

// 行追加ボタン
$(document).on('click', '.btn-plus', function(){
	// 課目ID
	var item_id = $(this).attr('data-item-id');
	
	if ( item_id == 1 )
	{
		// 追加する行番号
		item_id_1_count = item_id_1_count + 1;
		var tr_number = item_id_1_count;
		
		$('#item-id-' + item_id + ' tbody').append(
			'<tr>'
			+ '<td><input type="hidden" name="data[Expense][' + item_id + '][' + tr_number + '][id]" value="0" id="Expense' + item_id + tr_number + 'Id">'
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][affiliation]"         class="form-control form-control-sm form-affiliation ui-autocomplete-input" type="text" id="Expense' + item_id + tr_number + 'AffiliationId" autocomplete="off"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][job]"             class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Job"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][lastname]"        class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Lastname"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][firstname]"       class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Firstname"></td>'
			+ '<td>'
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][date_start]"      class="form-control form-control-sm datepicker d-inline col-4" type="text" id="Expense' + item_id + tr_number + 'DateStart">'
			+ '～'
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][date_end]"        class="form-control form-control-sm datepicker d-inline col-4" type="text" id="Expense' + item_id + tr_number + 'DateEnd">'
			+ '</td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][request_price]"   class="form-control form-control-sm text-right form-request_price item-id-' + item_id + '" type="number" id="Expense' + item_id + tr_number + 'RequestPrice"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][note]"            class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Note"></td>'
			+ '<td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-primary btn-minus">－</a></td>'
			+ '</tr>'
		);
	}
	else if( item_id == 2 )
	{
		// 追加する行番号
		item_id_2_count = item_id_2_count + 1;
		var tr_number = item_id_2_count;
		
		$('#item-id-' + item_id + ' tbody').append(
			'<tr>'
			+ '<td><input type="hidden" name="data[Expense][' + item_id + '][' + tr_number + '][id]" value="0" id="Expense' + item_id + tr_number + 'Id">'
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][affiliation]" class="form-control form-control-sm form-affiliation ui-autocomplete-input" type="text" id="Expense' + item_id + tr_number + 'AffiliationId" autocomplete="off"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][job]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Job"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][lastname]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Lastname"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][firstname]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Firstname"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][title]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Title"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][request_price]" class="form-control form-control-sm text-right form-request_price item-id-' + item_id + '" type="number" id="Expense' + item_id + tr_number + 'RequestPrice"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][note]" class="form-control form-control-sm" type="text" id="Expense21Note"></td>'
			+ '<td><a href="javascript:void(0);" class="btn btn-sm btn-primary btn-minus">－</a></td>'
			+ '</tr>'
		);
	}
	else if( item_id == 3 )
	{
		// 追加する行番号
		item_id_3_count = item_id_3_count + 1;
		var tr_number = item_id_3_count;
		
		$('#item-id-' + item_id + ' tbody').append(
			'<tr>'
			+ '<td><input type="hidden" name="data[Expense][' + item_id + '][' + tr_number + '][id]" value="0" id="Expense' + item_id + tr_number + 'Id">'
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][title]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Title"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][count]" class="form-control form-control-sm form-count" type="number" id="Expense' + item_id + tr_number + 'Count" attr-item-id="3" attr-i="' + tr_number + '"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][price]" class="form-control form-control-sm form-price" type="number" id="Expense' + item_id + tr_number + 'Price" attr-item-id="3" attr-i="' + tr_number + '"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][request_price]" class="form-control form-control-sm text-right form-request_price item-id-' + item_id + '" type="number" id="Expense' + item_id + tr_number + 'RequestPrice"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][note]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Note"></td>'
			+ '<td><a href="javascript:void(0);" class="btn btn-sm btn-primary btn-minus">－</a></td>'
			+ '</tr>'
		);
	}
	else if( item_id == 4 )
	{
		// 追加する行番号
		item_id_4_count = item_id_4_count + 1;
		var tr_number = item_id_4_count;
		
		// ドロップダウンのリストを作成
		var options;
		$.each(items, function(i, val){
			options += '<option value="' + i + '">' + val + '</option>';
		});
		
		$('#item-id-' + item_id + ' tbody').append(
			'<tr>'
			+ '<td><input type="hidden" name="data[Expense][' + item_id + '][' + tr_number + '][id]" value="0" id="Expense' + item_id + tr_number + 'Id">'
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][title]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Title"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][count]" class="form-control form-control-sm form-count" type="number" id="Expense' + item_id + tr_number + 'Count" attr-item-id="4" attr-i="' + tr_number + '"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][price]" class="form-control form-control-sm form-price" type="number" id="Expense' + item_id + tr_number + 'Price" attr-item-id="4" attr-i="' + tr_number + '"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][request_price]" class="form-control form-control-sm text-right form-request_price item-id-4" type="number" id="Expense' + item_id + tr_number + 'RequestPrice"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][note]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Note"></td>'
			+ '<td><a href="javascript:void(0);" class="btn btn-sm btn-primary btn-minus">－</a></td>'
			+ '</tr>'
		);
	}
	$('.datepicker').datepicker({
		format: 'yyyy-mm-dd'
	});
	return true;
});

// 行削除ボタン
$(document).on('click', '.btn-minus', function(){
	// 行削除
	$(this).parent().parent().remove();
	
	// 再計算
	calc_prices();
	return;
});

// ページ読み込み時、小計、合計の計算
$(window).on('load', function(){
	return calc_prices();
});

// 申請金額の値を入力・変更時
$(document).on('change', '.form-request_price', function(){
	return calc_prices();
});


// 数量の値を入力・変更時
$(document).on('change', '.form-count', function(){
	var item_id	= $(this).attr('attr-item-id');
	var row		= $(this).attr('attr-i');
	var count	= $('#Expense' + item_id + row + 'Count').val();
	var price	= $('#Expense' + item_id + row + 'Price').val();
	$('#Expense' + item_id + row + 'RequestPrice').val( count * price );
	 calc_prices();
	return true;
});

// 単価の値を入力・変更時
$(document).on('change', '.form-price', function(){
	var item_id	= $(this).attr('attr-item-id');
	var row		= $(this).attr('attr-i');
	var count	= $('#Expense' + item_id + row + 'Count').val();
	var price	= $('#Expense' + item_id + row + 'Price').val();
	$('#Expense' + item_id + row + 'RequestPrice').val( count * price );
	calc_prices();
	return true;
});

$(document).on('click', '.btn-add-program', function(){
	var date			= $(this).attr('data-date');
	var program_number	= $('.session-' + date + '.session-program').length;
	var program_count	= program_number + 1;
	
	var ret = '<div class="row session-' + date + '-' + program_count + ' session-' + date + ' session-program mb-2">'
		+ '<div class="col-12">'
			+ '<table class="table table-bordered mb-2">'
				+ '<tr>'
					+ '<th class="bg-light text-center" rowspan="2" style="width:100px;">'
						+ '並び順'
						+ '<input type="hidden" name="data[ReportProgram][' + date + '][' + program_count + '][id]" value="" id="ReportProgram' + date + program_count + 'Id">'
						+ '<input type="text" name="data[ReportProgram][' + date + '][' + program_count + '][sort]" value="' + program_count + '" class="form-control form-control-sm" placeholder="並び順">'
						+ '<br>'
						+ '<a href="javascript:void(0);" class="btn btn-danger btn-minus-program" data-date="' + date + '" data-program-number="' + program_count + '" data-id="" onclick="if (confirm(\'この講演課題を削除します。本当に宜しいですか？\')) { return true; } return false;">－</a>'
					+ '</th>'
					+ '<td style="width:390px;">'
						+ '<div class="form-inline">'
							+ '<select name="data[ReportProgram][' + date + '][' + program_count + '][start][hour]" value="" class="form-control form-control-sm" style="width:70px;">'
								+ '<option value="00">0</option><option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>'
							+ '</select>'
							+ '：'
							+ '<select name="data[ReportProgram][' + date + '][' + program_count + '][start][min]" value="" class="form-control form-control-sm" style="width:70px;">'
								+ '<option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>'
							+ '</select>'
							+ '～'
							+ '<select name="data[ReportProgram][' + date + '][' + program_count + '][end][hour]" value="" class="form-control form-control-sm" style="width:70px;">'
								+ '<option value="00">0</option><option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>'
							+ '</select>'
							+ '：'
							+ '<select name="data[ReportProgram][' + date + '][' + program_count + '][end][min]" value="" class="form-control form-control-sm" style="width:70px;">'
								+ '<option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>'
							+ '</select>'
						+ '</div>'
					+ '</td>'
					+ '<td>'
						+ '<input type="text" name="data[ReportProgram][' + date + '][' + program_count + '][title]" value="" class="form-control form-control-sm" placeholder="講演課題タイトル">'
					+ '</td>'
				+ '</tr>'
				+ '<tr>'
					+ '<td colspan="2">'
						+ '<div class="program-' + date + '-' + program_count + '"></div>'
						+ '<div class="text-right mb-2">'
							+ '<a href="javascript:void(0);" class="btn btn-warning btn-add-performer" data-date="' + date + '" data-program-number="' + program_count + '">講演者を追加</a>'
						+ '</div>'
					+ '</td>'
				+ '</tr>'
			+ '</table>'
		+ '</div>'
		+ '<hr>';
	+ '</div>'
	
	return $('.session-area-' + date).append(ret);
});

$(document).on('click', '.btn-add-performer', function(){
	var date			= $(this).attr('data-date');
	var program_number	= $(this).attr('data-program-number');
	var program_count	= $('.program-' + date + '-' + program_number + ' .program-line').length;
	var next			=  parseInt(program_count) + 1;
	
	var ret = '<div class="row mb-2 program-line program-line-' + date + '-' +  program_number + '-' + next + '">'
		+ '<div class="col-3">'
			+ '<input type="text" name="data[ReportProgram][' + date + '][' + program_number + '][program][' + next + '][lastname]" class="form-control form-control-sm" placeholder="講演者' + next + ' 姓">'
		+ '</div>'
		+ '<div class="col-3">'
			+ '<input type="text" name="data[ReportProgram][' + date + '][' + program_number + '][program][' + next + '][firstname]" class="form-control form-control-sm" placeholder="講演者' + next + ' 名">'
		+ '</div>'
		+ '<div class="col-4">'
			+ '<input type="text" name="data[ReportProgram][' + date + '][' + program_number + '][program][' + next + '][organization]" class="form-control form-control-sm" placeholder="講演者' + next + ' 所属機関">'
		+ '</div>'
		+ '<div class="col-2 text-center">'
			+ '<a href="javascript:void(0);" class="btn btn-danger btn-minus-performer" data-date="' + date + '" data-program-number="' + program_number + '" data-performer="' + next + '" data-id="" onclick="if (confirm(\'この講演者を削除します。本当に宜しいですか？\')) { return true; } return false;">－</a>'
		+ '</div>'
	+ '</div>'
	
	return $('.program-' + date + '-' + program_number).append(ret);
});

$(document).on('click', '.btn-minus-program', function(){
	var date			= $(this).attr('data-date');
	var program_number	= $(this).attr('data-program-number');
	
	// ajaxで削除する
	if ($(this).attr('data-id') != '')
	{
		$.ajax({
			url : abs_path + 'databases/delete_program/' + $(this).attr('data-id'),
			type: 'get',
			dataType: 'json',
			success: function(ret){
				alert(ret.message);
			},
			error: function(a, b, c){
				alert(b);
			},
		});
	}
	return $('.session-' + date + '-' + program_number).remove();
});

$(document).on('click', '.btn-minus-performer', function(){
	var date			= $(this).attr('data-date');
	var program_number	= $(this).attr('data-program-number');
	var performer		= $(this).attr('data-performer');
	
	// ajaxで削除する
	if ($(this).attr('data-id') != '')
	{
		$.ajax({
			url : abs_path + 'databases/delete_performer/' + $(this).attr('data-id'),
			type: 'get',
			dataType: 'json',
			success: function(ret){
				alert(ret.message);
			},
			error: function(a, b, c){
				alert(b);
			},
		});
	}
	
	return $('.program-line-' + date + '-' +  program_number + '-' + performer).remove();
});


$(document).on('click', '.btn-add-keyword', function(){
	var keyword_count = $('.keyword-area .data-keyword').length;
	var next = keyword_count + 1;
	return $('.keyword-area').append(
		'<input type="hidden" name="data[EventKeyword][' + next + '][id]" id="EventKeyword' + next + 'Id">'
		+ '<input name="data[EventKeyword][' + next + '][title]" class="form-control form-control-sm mb-2 col-3 d-inline data-keyword" maxlength="255" type="text" id="EventKeyword' + next + 'Title">&nbsp;'
	);
});


// 運営責任者追加
$(document).on('click', '.btn-add-manager', function(){
	var event_manager_count = $('.data-event-manager').length;
	var next = event_manager_count + 1;
	var ret = '<table class="table2 table-bordered">'
		+ '<tbody>'
			+ '<tr>'
				+ '<th class="bg-light" nowrap="nowrap">参加者ID<br>(メールアドレス)<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input type="hidden" name="data[EventManager][' + next + '][id]" id="EventManager' + next + 'Id">'
					+ '<input name="data[EventManager][' + next + '][email]" class="form-control form-control-sm" type="text" id="EventManager' + next + 'email">'
				+ '</td>'
				+ '<th class="bg-light">&nbsp;</th>'
				+ '<td>&nbsp;</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light" nowrap="nowrap">姓名<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<div class="container">'
						+ '<div class="row">'
							+ '<input name="data[EventManager][' + next + '][lastname]" class="form-control form-control-sm col-5" placeholder="姓" maxlength="100" type="text" id="EventManager' + next + 'Lastname" required="required">&nbsp;'
							+ '<input name="data[EventManager][' + next + '][firstname]" class="form-control form-control-sm col-5" placeholder="名" maxlength="100" type="text" id="EventManager' + next + 'Firstname" required="required">'
						+ '</div>'
					+ '</div>'
				+ '</td>'
				+ '<th class="bg-light" nowrap="nowrap">フリガナ<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<div class="container">'
						+ '<div class="row">'
							+ '<input name="data[EventManager][' + next + '][lastname_kana]" class="form-control form-control-sm col-5" placeholder="せい" maxlength="100" type="text" id="EventManager' + next + 'LastnameKana" required="required">&nbsp;'
							+ '<input name="data[EventManager][' + next + '][firstname_kana]" class="form-control form-control-sm col-5" placeholder="めい" maxlength="100" type="text" id="EventManager' + next + 'FirstnameKana" required="required">'
						+ '</div>'
					+ '</div>'
				+ '</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light">所属機関<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventManager][' + next + '][organization]" class="form-control form-control-sm" maxlength="100" type="text" id="EventManager' + next + 'Organization" required="required">'
				+ '</td>'
				+ '<th class="bg-light">所属部局<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventManager][' + next + '][department]" class="form-control form-control-sm" maxlength="100" type="text" id="EventManager' + next + 'Department">'
				+ '</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light">職名</th>'
				+ '<td>'
					+ '<input name="data[EventManager][' + next + '][job_title]" class="form-control form-control-sm" maxlength="100" type="text" id="EventManager' + next + 'JobTitle">'
				+ '</td>'
				+ '<th class="bg-light">URL</th>'
				+ '<td>'
					+ '<input name="data[EventManager][' + next + '][url]" class="form-control form-control-sm" maxlength="255" type="text" id="EventManager' + next + 'Url">'
				+ '</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light" nowrap="nowrap">郵便番号<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventManager][' + next + '][zip]" class="form-control form-control-sm" maxlength="20" type="text" id="EventManager' + next + 'Zip" required="required">'
				+ '</td>'
				+ '<th class="bg-light">都道府県<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<select name="data[EventManager][' + next + '][prefecture_id]" class="form-control form-control-sm" id="EventManager' + next + 'PrefectureId" required="required">'
						+ '<option value="0">------</option>'
						+ '<option value="1">北海道</option>'
						+ '<option value="2">青森県</option>'
						+ '<option value="3">岩手県</option>'
						+ '<option value="4">宮城県</option>'
						+ '<option value="5">秋田県</option>'
						+ '<option value="6">山形県</option>'
						+ '<option value="7">福島県</option>'
						+ '<option value="8">茨城県</option>'
						+ '<option value="9">栃木県</option>'
						+ '<option value="10">群馬県</option>'
						+ '<option value="11">埼玉県</option>'
						+ '<option value="12">千葉県</option>'
						+ '<option value="13">東京都</option>'
						+ '<option value="14">神奈川県</option>'
						+ '<option value="15">新潟県</option>'
						+ '<option value="16">富山県</option>'
						+ '<option value="17">石川県</option>'
						+ '<option value="18">福井県</option>'
						+ '<option value="19">山梨県</option>'
						+ '<option value="20">長野県</option>'
						+ '<option value="21">岐阜県</option>'
						+ '<option value="22">静岡県</option>'
						+ '<option value="23">愛知県</option>'
						+ '<option value="24">三重県</option>'
						+ '<option value="25">滋賀県</option>'
						+ '<option value="26">京都府</option>'
						+ '<option value="27">大阪府</option>'
						+ '<option value="28">兵庫県</option>'
						+ '<option value="29">奈良県</option>'
						+ '<option value="30">和歌山県</option>'
						+ '<option value="31">鳥取県</option>'
						+ '<option value="32">島根県</option>'
						+ '<option value="33">岡山県</option>'
						+ '<option value="34">広島県</option>'
						+ '<option value="35">山口県</option>'
						+ '<option value="36">徳島県</option>'
						+ '<option value="37">香川県</option>'
						+ '<option value="38">愛媛県</option>'
						+ '<option value="39">高知県</option>'
						+ '<option value="40">福岡県</option>'
						+ '<option value="41">佐賀県</option>'
						+ '<option value="42">長崎県</option>'
						+ '<option value="43">熊本県</option>'
						+ '<option value="44">大分県</option>'
						+ '<option value="45">宮崎県</option>'
						+ '<option value="46">鹿児島県</option>'
						+ '<option value="47">沖縄県</option>'
						+ '<option value="48">海外</option>'
					+ '</select>'
				+ '</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light">市区町村<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventManager][' + next + '][city]" class="form-control form-control-sm" maxlength="255" type="text" id="EventManager' + next + 'City" required="required">'
				+ '</td>'
				+ '<th class="bg-light">住所<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventManager][' + next + '][address]" class="form-control form-control-sm" maxlength="255" type="text" id="EventManager' + next + 'Address" required="required">'
				+ '</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light">TEL<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventManager][' + next + '][tel]" class="form-control form-control-sm" maxlength="50" type="text" id="EventManager' + next + 'Tel" required="required">'
				+ '</td>'
				+ '<th class="bg-light">FAX</th>'
				+ '<td>'
					+ '<input name="data[EventManager][' + next + '][fax]" class="form-control form-control-sm" maxlength="50" type="text" id="EventManager' + next + 'Fax">'
				+ '</td>'
			+ '</tr>'
		+ '</tbody>'
	+ '</table>';
	+ '<hr>';
	return $('.manager-area').append(ret);
});


// 運営責任者追加
$(document).on('click', '.btn-add-affair', function(){
	var event_affair_count = $('.data-event-affair').length;
	var next = event_affair_count + 1;
	var ret = '<table class="table2 table-bordered">'
		+ '<tbody>'
			+ '<tr>'
				+ '<th class="bg-light" nowrap="nowrap">参加者ID<br>(メールアドレス)<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input type="hidden" name="data[EventAffair][' + next + '][id]" id="EventAffair' + next + 'Id">'
					+ '<input name="data[EventAffair][' + next + '][email]" class="form-control form-control-sm" type="text" id="EventAffair' + next + 'email">'
				+ '</td>'
				+ '<th class="bg-light">&nbsp;</th>'
				+ '<td>&nbsp;</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light" nowrap="nowrap">姓名<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<div class="container">'
						+ '<div class="row">'
							+ '<input name="data[EventAffair][' + next + '][lastname]" class="form-control form-control-sm col-5" placeholder="姓" maxlength="100" type="text" id="EventAffair' + next + 'Lastname" required="required">&nbsp;'
							+ '<input name="data[EventAffair][' + next + '][firstname]" class="form-control form-control-sm col-5" placeholder="名" maxlength="100" type="text" id="EventAffair' + next + 'Firstname" required="required">'
						+ '</div>'
					+ '</div>'
				+ '</td>'
				+ '<th class="bg-light" nowrap="nowrap">フリガナ<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<div class="container">'
						+ '<div class="row">'
							+ '<input name="data[EventAffair][' + next + '][lastname_kana]" class="form-control form-control-sm col-5" placeholder="せい" maxlength="100" type="text" id="EventAffair' + next + 'LastnameKana" required="required">&nbsp;'
							+ '<input name="data[EventAffair][' + next + '][firstname_kana]" class="form-control form-control-sm col-5" placeholder="めい" maxlength="100" type="text" id="EventAffair' + next + 'FirstnameKana" required="required">'
						+ '</div>'
					+ '</div>'
				+ '</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light">所属機関<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventAffair][' + next + '][organization]" class="form-control form-control-sm" maxlength="100" type="text" id="EventAffair' + next + 'Organization" required="required">'
				+ '</td>'
				+ '<th class="bg-light">所属部局<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventAffair][' + next + '][department]" class="form-control form-control-sm" maxlength="100" type="text" id="EventAffair' + next + 'Department">'
				+ '</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light">職名</th>'
				+ '<td>'
					+ '<input name="data[EventAffair][' + next + '][job_title]" class="form-control form-control-sm" maxlength="100" type="text" id="EventAffair' + next + 'JobTitle">'
				+ '</td>'
				+ '<th class="bg-light">URL</th>'
				+ '<td>'
					+ '<input name="data[EventAffair][' + next + '][url]" class="form-control form-control-sm" maxlength="255" type="text" id="EventAffair' + next + 'Url">'
				+ '</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light" nowrap="nowrap">郵便番号<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventAffair][' + next + '][zip]" class="form-control form-control-sm" maxlength="20" type="text" id="EventAffair' + next + 'Zip" required="required">'
				+ '</td>'
				+ '<th class="bg-light">都道府県<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<select name="data[EventAffair][' + next + '][prefecture_id]" class="form-control form-control-sm" id="EventAffair' + next + 'PrefectureId" required="required">'
						+ '<option value="0">------</option>'
						+ '<option value="1">北海道</option>'
						+ '<option value="2">青森県</option>'
						+ '<option value="3">岩手県</option>'
						+ '<option value="4">宮城県</option>'
						+ '<option value="5">秋田県</option>'
						+ '<option value="6">山形県</option>'
						+ '<option value="7">福島県</option>'
						+ '<option value="8">茨城県</option>'
						+ '<option value="9">栃木県</option>'
						+ '<option value="10">群馬県</option>'
						+ '<option value="11">埼玉県</option>'
						+ '<option value="12">千葉県</option>'
						+ '<option value="13">東京都</option>'
						+ '<option value="14">神奈川県</option>'
						+ '<option value="15">新潟県</option>'
						+ '<option value="16">富山県</option>'
						+ '<option value="17">石川県</option>'
						+ '<option value="18">福井県</option>'
						+ '<option value="19">山梨県</option>'
						+ '<option value="20">長野県</option>'
						+ '<option value="21">岐阜県</option>'
						+ '<option value="22">静岡県</option>'
						+ '<option value="23">愛知県</option>'
						+ '<option value="24">三重県</option>'
						+ '<option value="25">滋賀県</option>'
						+ '<option value="26">京都府</option>'
						+ '<option value="27">大阪府</option>'
						+ '<option value="28">兵庫県</option>'
						+ '<option value="29">奈良県</option>'
						+ '<option value="30">和歌山県</option>'
						+ '<option value="31">鳥取県</option>'
						+ '<option value="32">島根県</option>'
						+ '<option value="33">岡山県</option>'
						+ '<option value="34">広島県</option>'
						+ '<option value="35">山口県</option>'
						+ '<option value="36">徳島県</option>'
						+ '<option value="37">香川県</option>'
						+ '<option value="38">愛媛県</option>'
						+ '<option value="39">高知県</option>'
						+ '<option value="40">福岡県</option>'
						+ '<option value="41">佐賀県</option>'
						+ '<option value="42">長崎県</option>'
						+ '<option value="43">熊本県</option>'
						+ '<option value="44">大分県</option>'
						+ '<option value="45">宮崎県</option>'
						+ '<option value="46">鹿児島県</option>'
						+ '<option value="47">沖縄県</option>'
						+ '<option value="48">海外</option>'
					+ '</select>'
				+ '</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light">市区町村<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventAffair][' + next + '][city]" class="form-control form-control-sm" maxlength="255" type="text" id="EventAffair' + next + 'City" required="required">'
				+ '</td>'
				+ '<th class="bg-light">住所<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventAffair][' + next + '][address]" class="form-control form-control-sm" maxlength="255" type="text" id="EventAffair' + next + 'Address" required="required">'
				+ '</td>'
			+ '</tr>'
			+ '<tr>'
				+ '<th class="bg-light">TEL<span class="text-danger">*</span></th>'
				+ '<td>'
					+ '<input name="data[EventAffair][' + next + '][tel]" class="form-control form-control-sm" maxlength="50" type="text" id="EventAffair' + next + 'Tel" required="required">'
				+ '</td>'
				+ '<th class="bg-light">FAX</th>'
				+ '<td>'
					+ '<input name="data[EventAffair][' + next + '][fax]" class="form-control form-control-sm" maxlength="50" type="text" id="EventAffair' + next + 'Fax">'
				+ '</td>'
			+ '</tr>'
		+ '</tbody>'
	+ '</table>';
	+ '<hr>';
	return $('.affair-area').append(ret);
});

$(function(){
	$("input[type=button]").click(function(){
		$("form").submit();
	});
});


var event_qualification = $('.event_qualification:checked').val();

//alert(event_qualification);

if ( event_qualification == 1 )
{
	$('#EventQualificationOther').prop('disabled', false);
}
else
{
	$('#EventQualificationOther').prop('disabled', true);
}

$(document).on('change', '.event_qualification', function(){
	
	event_qualification = $(this).val();
	
	if ( event_qualification == 1 )
	{
		$('#EventQualificationOther').prop('disabled', false);
	}
	else
	{
		$('#EventQualificationOther').prop('disabled', true);
	}
});

$(document).on('click', '.file-remove', function(){
	return $('#box-' + $(this).attr('attr-data-i')).remove();
});