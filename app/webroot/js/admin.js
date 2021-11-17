var abs_path = 'https://aimap.imi.kyushu-u.ac.jp/db2/';

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
	
	// 申請金額
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
	
	
	// 執行金額
	var subtotal_accept_1 = Number(0);
	var subtotal_accept_2 = Number(0);
	var subtotal_accept_3 = Number(0);
	var subtotal_accept_4 = Number(0);
	
	$accept_prices_1 = $('.form-accept_price.item-id-1');
	$.each($accept_prices_1, function(i, val){
		subtotal_accept_1 += Number($(val).val());
	});
	$('.subtotal_accept_1').html( number_format(subtotal_accept_1) );
	
	// 
	$accept_prices_2 = $('.form-accept_price.item-id-2');
	$.each($accept_prices_2, function(i, val){
		subtotal_accept_2 += Number($(val).val());
	});
	$('.subtotal_accept_2').html( number_format(subtotal_accept_2) );
	
	// 
	$accept_prices_3 = $('.form-accept_price.item-id-3');
	$.each($accept_prices_3, function(i, val){
		subtotal_accept_3 += Number($(val).val());
	});
	$('.subtotal_accept_3').html( number_format(subtotal_accept_3) );
	
	// 
	$accept_prices_4 = $('.form-accept_price.item-id-4');
	$.each($accept_prices_4, function(i, val){
		subtotal_accept_4 += Number($(val).val());
	});
	$('.subtotal_accept_4').html( number_format(subtotal_accept_4) );
	
	// ASK金額
	var subtotal_ask_1 = Number(0);
	var subtotal_ask_2 = Number(0);
	var subtotal_ask_3 = Number(0);
	var subtotal_ask_4 = Number(0);
	$ask_prices_1 = $('.form-ask_price.item-id-1');
	$.each($ask_prices_1, function(i, val){
		subtotal_ask_1 += Number($(val).val());
	});
	$('.subtotal_ask_1').html( number_format(subtotal_ask_1) );
	
	// 
	$ask_prices_2 = $('.form-ask_price.item-id-2');
	$.each($ask_prices_2, function(i, val){
		subtotal_ask_2 += Number($(val).val());
	});
	$('.subtotal_ask_2').html( number_format(subtotal_ask_2) );
	
	// 
	$ask_prices_3 = $('.form-ask_price.item-id-3');
	$.each($ask_prices_3, function(i, val){
		subtotal_ask_3 += Number($(val).val());
	});
	$('.subtotal_ask_3').html( number_format(subtotal_ask_3) );
	
	// 
	$ask_prices_4 = $('.form-ask_price.item-id-4');
	$.each($ask_prices_4, function(i, val){
		subtotal_ask_4 += Number($(val).val());
	});
	$('.subtotal_ask_4').html( number_format(subtotal_ask_4) );
	
	
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
	delay: 500,
	minLength: 2
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
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][contract_number]" class="form-control form-control-sm" maxlength="50" type="text" value="" id="Expense' + item_id + tr_number + 'ContractNumber"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][contract_branch]" class="form-control form-control-sm" maxlength="11" type="text" value="" id="Expense' + item_id + tr_number + 'ContractBranch"style="width:3rem;" ></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][affiliation]" class="form-control form-control-sm form-affiliation ui-autocomplete-input" type="text" id="Expense' + item_id + tr_number + 'AffiliationId" autocomplete="off" style="width:8rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][job]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Job" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][lastname]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Lastname" style="width:6rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][firstname]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Firstname" style="width:6rem;"></td>'
		//	+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][title]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Title"></td>'
			
			+ '<td>'
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][date_start]"      class="form-control form-control-sm datepicker d-inline col-4" type="text" id="Expense' + item_id + tr_number + 'DateStart">～ '
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][date_end]"        class="form-control form-control-sm datepicker d-inline col-4" type="text" id="Expense' + item_id + tr_number + 'DateEnd">'
			+ '</td>'
			
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][request_price]" class="form-control form-control-sm text-right form-request_price item-id-' + item_id + '" style="width:5rem;" type="text" id="Expense' + item_id + tr_number + 'RequestPrice"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][accept_price]" class="form-control form-control-sm text-right form-accept_price item-id-' + item_id + '" style="width:5rem;" type="text" value="" id="Expense' + item_id + tr_number + 'AcceptPrice"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][ask_price]" class="form-control form-control-sm text-right form-ask_price item-id-' + item_id + '" style="width:5rem;" type="text" value="" id="Expense' + item_id + tr_number + 'AskPrice"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][note]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Note"></td>'
			+ '<td><select name="data[Expense][' + item_id + '][' + tr_number + '][status]" class="form-control form-control-sm" id="Expense' + item_id  + tr_number + 'Status"><option value="0">未申請</option><option value="1">未確定</option><option value="2">確定</option></select></td>'
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
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][contract_number]"     class="form-control form-control-sm" maxlength="50"                         type="text" value="" id="Expense' + item_id + tr_number + 'ContractNumber" style="width:8rem;" ></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][contract_branch]" class="form-control form-control-sm" maxlength="11"                         type="text" value="" id="Expense' + item_id + tr_number + 'ContractBranch" style="width:3rem;" ></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][affiliation]"     class="form-control form-control-sm form-affiliation ui-autocomplete-input" type="text" id="Expense' + item_id + tr_number + 'AffiliationId" autocomplete="off" style="width:8rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][job]"             class="form-control form-control-sm"                                        type="text" id="Expense' + item_id + tr_number + 'Job" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][lastname]"        class="form-control form-control-sm"                                        type="text" id="Expense' + item_id + tr_number + 'Lastname" style="width:6rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][firstname]"       class="form-control form-control-sm"                                        type="text" id="Expense' + item_id + tr_number + 'Firstname" style="width:6rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][title]"           class="form-control form-control-sm"                                        type="text" id="Expense' + item_id + tr_number + 'Title"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][request_price]"   class="form-control form-control-sm text-right form-request_price item-id-' + item_id + '" type="text" id="Expense' + item_id + tr_number + 'RequestPrice" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][accept_price]"    class="form-control form-control-sm text-right form-accept_price item-id-' + item_id + '"  type="text" value="" id="Expense' + item_id + tr_number + 'AcceptPrice" style="width:5rem;" ></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][ask_price]"       class="form-control form-control-sm text-right form-ask_price item-id-' + item_id + '"     type="text" value="" id="Expense' + item_id + tr_number + 'AskPrice" style="width:5rem;" ></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][note]"            class="form-control form-control-sm"                                                       type="text" id="Expense' + item_id + tr_number + 'Note"></td>'
			+ '<td><select name="data[Expense][' + item_id + '][' + tr_number + '][status]"         class="form-control form-control-sm" id="Expense' + item_id  + tr_number + 'Status"><option value="0">未申請</option><option value="1">未確定</option><option value="2">確定</option></select></td>'
			+ '<td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-primary btn-minus">－</a></td>'
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
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][contract_number]"     type="text" class="form-control form-control-sm" maxlength="50" value="" id="Expense' + item_id + tr_number + 'ContractNumber"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][contract_branch]" type="text" class="form-control form-control-sm" maxlength="11"  value="" id="Expense' + item_id + tr_number + 'ContractBranch" style="width:3rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][title]"           type="text" class="form-control form-control-sm" id="Expense' + item_id + tr_number + 'Title"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][count]"           type="text" class="form-control form-control-sm" id="Expense' + item_id + tr_number + 'Count" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][price]"           type="text" class="form-control form-control-sm" id="Expense' + item_id + tr_number + 'Price" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][request_price]"   type="text" class="form-control form-control-sm text-right form-request_price item-id-' + item_id + '" type="text" id="Expense' + item_id + tr_number + 'RequestPrice" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][accept_price]"    type="text" class="form-control form-control-sm text-right form-accept_price item-id-' + item_id + '" type="text" value="" id="Expense' + item_id + tr_number + 'AcceptPrice" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][ask_price]"       type="text" class="form-control form-control-sm text-right form-ask_price item-id-' + item_id + '" type="text" value="" id="Expense' + item_id + tr_number + 'AskPrice" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][note]"            type="text" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Note"></td>'
			+ '<td><select name="data[Expense][' + item_id + '][' + tr_number + '][status]"         type="text" class="form-control form-control-sm" id="Expense' + item_id  + tr_number + 'Status"><option value="0">未申請</option><option value="1">未確定</option><option value="2">確定</option></select></td>'
			+ '<td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-primary btn-minus">－</a></td>'
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
			+ '<input name="data[Expense][' + item_id + '][' + tr_number + '][contract_number]" class="form-control form-control-sm" maxlength="50" type="text" value="" id="Expense' + item_id + tr_number + 'ContractNumber"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][contract_branch]" class="form-control form-control-sm" maxlength="11" type="text" value="" id="Expense' + item_id + tr_number + 'ContractBranch" style="width:3rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][title]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Title"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][count]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Count" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][price]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Price" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][request_price]" class="form-control form-control-sm text-right form-request_price item-id-' + item_id + '" type="text" id="Expense' + item_id + tr_number + 'RequestPrice" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][accept_price]" class="form-control form-control-sm text-right form-accept_price item-id-' + item_id + '" type="text" value="" id="Expense' + item_id + tr_number + 'AcceptPrice" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][ask_price]" class="form-control form-control-sm text-right form-ask_price item-id-' + item_id + '" type="text" value="" id="Expense' + item_id + tr_number + 'AskPrice" style="width:5rem;"></td>'
			+ '<td><input name="data[Expense][' + item_id + '][' + tr_number + '][note]" class="form-control form-control-sm" type="text" id="Expense' + item_id + tr_number + 'Note"></td>'
			+ '<td><select name="data[Expense][' + item_id + '][' + tr_number + '][status]" class="form-control form-control-sm" id="Expense' + item_id  + tr_number + 'Status"><option value="0">未申請</option><option value="1">未確定</option><option value="2">確定</option></select></td>'
			+ '<td class="text-center"><a href="javascript:void(0);" class="btn btn-sm btn-primary btn-minus">－</a></td>'
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

// 執行金額の値を入力・変更時
$(document).on('change', '.form-accept_price', function(){
	return calc_prices();
});

// ASK金額の値を入力・変更時
$(document).on('change', '.form-ask_price', function(){
	return calc_prices();
});

$.fn.extend({
	search_rm: function(param){
		$.ajax({
			url : abs_path + 'researchers/search/' + param,
			type : 'get',
			dataType : 'json',
			success : function(ret){
				var detail = '';
				$.each(ret.User, function(i, val){
					detail += '<table class="table table-bordered">'
						+ '<tr>'
						+ '<th class="bg-light rm-title" style="width:15%;">氏名</th><td style="width:35%;">' + val.data.title + '</td>'
						+ '<th class="bg-light rm-kakenid" style="width:15%;">科研費ID</th><td style="width:35%;">' + val.detail.kakenid + '</td>'
						+ '</tr>'
						+ '<tr>'
						+ '<th class="bg-light rm-affiliation">所属</th><td>' + val.detail.affiliation + '</td>'
						+ '<th class="bg-light rm-section">部署</th><td>' + val.detail.section + '</td>'
						+ '</tr>'
						+ '<tr>'
						+ '<th class="bg-light rm-job">職名</th><td>' + val.detail.job + '</td>'
						+ '<th class="bg-light rm-degree">学位</th><td>' + val.detail.degree + '</td>'
						+ '</tr>'
						+ '</table>'
						+ '<div class="text-right"><a href="javascript:void(0);" class="btn btn-info add-researcher" data-attr-id="' + val.data.id + '">研究者DBに登録</a></div>'
						+ '<hr>';
						return true;
				});
				
				if ( detail == '' )
				{
					detail += '<p>データが見つかりませんでした。</p>'
				}
				
				
				$('.results').html( detail );
				
				if (ret.opensearch.paging)
				{
					var link = '';
					$.each(ret.opensearch.paging, function(i, val){
						link += '<a href="javascrip:void(0);" class="paging-link" data-search-param="' + val + '">'
							+ i
							+ '</a">';
						return true;
					});
					$('.rm-paging').html( link );
				}
			}
		});
	},
	search_rm2: function(param){
		$.ajax({
			url : abs_path + 'researchers/search/' + param,
			type : 'get',
			dataType : 'json',
			success : function(ret){
				var detail = '';
				$.each(ret.User, function(i, val){
					detail += '<table class="table table-bordered">'
						+ '<tr>'
						+ '<th class="bg-light rm-title" style="width:15%;">氏名</th><td style="width:35%;">' + val.data.title + '</td>'
						+ '<th class="bg-light rm-kakenid" style="width:15%;">科研費ID</th><td style="width:35%;">' + val.detail.kakenid + '</td>'
						+ '</tr>'
						+ '<tr>'
						+ '<th class="bg-light rm-affiliation">所属</th><td>' + val.detail.affiliation + '</td>'
						+ '<th class="bg-light rm-section">部署</th><td>' + val.detail.section + '</td>'
						+ '</tr>'
						+ '<tr>'
						+ '<th class="bg-light rm-job">職名</th><td>' + val.detail.job + '</td>'
						+ '<th class="bg-light rm-degree">学位</th><td>' + val.detail.degree + '</td>'
						+ '</tr>'
						+ '</table>'
						+ '<div class="text-right"><a href="javascript:void(0);" class="btn btn-info upd-researcher" data-attr-id="' + val.data.id + '" data-researcher-id="' + val.Researcher.id + '">研究者DBを更新</a></div>'
						+ '<hr>';
						return true;
				});
				
				if ( detail == '' )
				{
					detail += '<p>データが見つかりませんでした。</p>'
				}
				
				$('.results').html( detail );
				
				if (ret.opensearch.paging)
				{
					var link = '';
					$.each(ret.opensearch.paging, function(i, val){
						link += '<a href="javascrip:void(0);" class="paging-link" data-search-param="' + val + '">'
							+ i
							+ '</a">';
						return true;
					});
					$('.rm-paging').html( link );
				}
			}
		});
	},
	add_researcher: function(param){
		
		$.ajax({
			url : abs_path + 'researchers/add_researcher/' + param,
			type : 'get',
			dataType : 'json',
			beforeSend : function(){
				$('.overlay').show();
			},
			success : function(ret){
				alert(ret.message);
			},
			complete : function(){
				$('.overlay').hide();
			},
			error : function( a, b, c) {
				alert(b);
			}
		});
		return;
	},
	upd_researcher: function(param){
		if ( confirm('researchmapよりデータを取得します。\r既に入力済みの項目もresearchmapのデータに上書きされます。\r本当に宜しいですか？') )
		{
			$.ajax({
				url : abs_path + 'researchers/update_researcher/' + param,
				type : 'get',
				dataType : 'json',
				beforeSend : function(){
					$('.overlay').show();
				},
				success : function(ret){
					alert(ret.message);
				},
				complete : function(){
					$('.overlay').hide();
				},
				error : function( a, b, c) {
					alert(b);
				}
			});
		}
		return;
	}
});


// researchmap検索用（経費から）
$(document).on('click', '.rm-search', function(){
	var fullname = '.fullname-' + $(this).attr('data-expense-id');
	return $('#SearchName').val($(fullname).text());
});

// researchmap検索用（研究者データベースから）
$(document).on('click', '.btn-rm-search', function(){
	var param = '?name=' + encodeURI($('#SearchName').val());
	$(document).search_rm(param);
});

// researchmap検索用（研究者データベース各行から）
var clicked_btn1;
$(document).on('click', '.rm-search-row', function(){
	var fullname = '.fullname-' + $(this).attr('data-researcher-id');
	clicked_btn1 = $(this);
	return $('#SearchName2').val($(fullname).text());
});

// researchmap検索用（研究者データベースから）
$(document).on('click', '.btn-rm-search2', function(){
	var param = '?name=' + encodeURI($('#SearchName2').val()) + '&researcher_id=' + $(clicked_btn1).attr('data-researcher-id');
	$(document).search_rm2(param);
});

// researchmap ページング
$(document).on('click', '.paging-link', function(){
	$(document).search_rm($(this).attr('data-search-param'));
});

// 研究者DBに登録
$(document).on('click', '.add-researcher', function(){
	var param = '?rm-id=' + encodeURI($(this).attr('data-attr-id'));
	$(document).add_researcher(param);
});

// 研究者DBを更新
$(document).on('click', '.upd-researcher', function(){
	var param = $(clicked_btn1).attr('data-researcher-id') + '/?rm-id=' +  encodeURI($(this).attr('data-attr-id'));
	$(document).upd_researcher(param);
});

// 研究者DBを更新（再取得リンク）
$(document).on('click', '.reget-researcher', function(){
	var param = $(this).attr('data-researcher-id') + '/?rm-id=' +  encodeURI($(this).attr('data-attr-id'));
	$(document).upd_researcher(param);
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
						+ '<input type="text" name="data[ReportProgram][' + date + '][' + program_count + '][sort]" value="' + program_count + '" class="form-control" placeholder="並び順">'
						+ '<br>'
						+ '<a href="javascript:void(0);" class="btn btn-danger btn-minus-program" data-date="' + date + '" data-program-number="' + program_count + '" data-id="" onclick="if (confirm(\'この講演課題を削除します。本当に宜しいですか？\')) { return true; } return false;">－</a>'
					+ '</th>'
					+ '<td style="width:390px;">'
						+ '<div class="form-inline">'
							+ '<select name="data[ReportProgram][' + date + '][' + program_count + '][start][hour]" value="" class="form-control" style="width:70px;">'
								+ '<option value="00">0</option><option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>'
							+ '</select>'
							+ '：'
							+ '<select name="data[ReportProgram][' + date + '][' + program_count + '][start][min]" value="" class="form-control" style="width:70px;">'
								+ '<option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>'
							+ '</select>'
							+ '～'
							+ '<select name="data[ReportProgram][' + date + '][' + program_count + '][end][hour]" value="" class="form-control" style="width:70px;">'
								+ '<option value="00">0</option><option value="01">1</option><option value="02">2</option><option value="03">3</option><option value="04">4</option><option value="05">5</option><option value="06">6</option><option value="07">7</option><option value="08">8</option><option value="09">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option>'
							+ '</select>'
							+ '：'
							+ '<select name="data[ReportProgram][' + date + '][' + program_count + '][end][min]" value="" class="form-control" style="width:70px;">'
								+ '<option value="00">00</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option><option value="32">32</option><option value="33">33</option><option value="34">34</option><option value="35">35</option><option value="36">36</option><option value="37">37</option><option value="38">38</option><option value="39">39</option><option value="40">40</option><option value="41">41</option><option value="42">42</option><option value="43">43</option><option value="44">44</option><option value="45">45</option><option value="46">46</option><option value="47">47</option><option value="48">48</option><option value="49">49</option><option value="50">50</option><option value="51">51</option><option value="52">52</option><option value="53">53</option><option value="54">54</option><option value="55">55</option><option value="56">56</option><option value="57">57</option><option value="58">58</option><option value="59">59</option>'
							+ '</select>'
						+ '</div>'
					+ '</td>'
					+ '<td>'
						+ '<input type="text" name="data[ReportProgram][' + date + '][' + program_count + '][title]" value="" class="form-control" placeholder="講演課題タイトル">'
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
			+ '<input type="text" name="data[ReportProgram][' + date + '][' + program_number + '][program][' + next + '][lastname]" class="form-control" placeholder="講演者' + next + ' 姓">'
		+ '</div>'
		+ '<div class="col-3">'
			+ '<input type="text" name="data[ReportProgram][' + date + '][' + program_number + '][program][' + next + '][firstname]" class="form-control" placeholder="講演者' + next + ' 名">'
		+ '</div>'
		+ '<div class="col-4">'
			+ '<input type="text" name="data[ReportProgram][' + date + '][' + program_number + '][program][' + next + '][organization]" class="form-control" placeholder="講演者' + next + ' 所属機関">'
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
		+ '<input name="data[EventKeyword][' + next + '][title]" class="form-control mb-2 col-3 d-inline data-keyword" maxlength="255" type="text" id="EventKeyword' + next + 'Title">&nbsp;'
	);
});


$(document).on('change', '.change-page-type', function(){
	var action;
	var options;
	var page_type = $(this).val();
	var target = $(this).attr('data-target-id');
	
	if ( page_type == 0 )
	{
		return false;
	}
	else if ( page_type == 1 )
	{
		action = 'get_migration';
	}
	else if ( page_type == 2 )
	{
		action = 'get_researcher';
	}
	else if ( page_type == 3 )
	{
		action = 'get_event';
	}
	else if ( page_type == 4 )
	{
		action = 'get_event_program';
	}
	else if ( page_type == 5 )
	{
		action = 'get_affiliation';
	}
	else if ( page_type == 6 )
	{
		action = 'get_venue';
	}
	else if ( page_type == 7 )
	{
		action = 'get_case';
	}
	
	
	$.ajax({
		url : abs_path + 'migrations/' + action,
		type : 'post',
		dataType : 'json',
		success : function ( ret )
		{
			$.each(ret, function(i, val){
				options += '<option value="' + i + '">' + val + '</option>';
			});
			
			$('#' + target + ' option').remove();
			$('#' + target).append(options);
		},
		error : function ( a, b, c )
		{
			alert(b);
		}
	});
	
});