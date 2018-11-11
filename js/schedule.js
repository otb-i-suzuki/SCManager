// ドラッグ開始時にデータの値を設定
function DragStart(event) {
	event.dataTransfer.setData("text", event.target.id);
}

// ドロップ時にドラッグIDを取得
function Drop(event) {

	// ID取得
	var id = event.dataTransfer.getData("text");

	if(id != undefined && id.indexOf('drag-ev') != -1) {

		// 対象年月
		var ym = $('#target_ym').val();
		// ID
		var d_id = $("#" + id).data("id");
		// 移動先の日付
		var date = event.currentTarget.getAttribute("data-cnt");

		var elm = document.getElementById(id);
		var ct = event.currentTarget;

		// 日付移動
		$.ajax({
			type : "POST",
			url : "/SCManager/Schedule/change_date",
			data : {
				"ym" : ym,
				"id" : d_id,
				"date" : date,
				"c_tkn" : $.cookie('c_ck')
			},
			dataType : "json"
		}).done(function(data) {

			if(data['result'] == 0) {
				// 要素を移動
				ct.appendChild(elm);
			} else {
				alert('エラーが発生しました。画面を更新してください。');
				return false;
			}

		}).fail(function(data) {
			// システムエラー
			alert('システムエラーが発生しました。');
			return false;
		});
	}

	// ブラウザ標準のドロップ動作をキャンセル
	event.preventDefault();
}

// ブラウザ標準のドロップ動作をキャンセル
function DragOver(event) {
	event.preventDefault();
}

$(function() {

	// 予定追加ボタン
	$("#new_plan_btn").click(function() {

		// 入力部分の初期化
		$('#select_date').val(1);
		$('#title').val('');
		$('#detail').val('');

		// モーダルを表示
		$('#createModal').modal('show');
	});

	// 追加ボタン
	$('#add_btn').click(function() {

		// チェック処理
		if($('#title').val().trim() == "") {
			alert("タイトルを入力してください。");
			return false;
		} else {

			// ajax処理で使用する値を取得
			var ym = $('#target_ym').val();
			var date = $('#select_date').val();
			var title = $('#title').val();
			var detail = $('#detail').val();

			$.ajax({
				type : "POST",
				url : "/SCManager/Schedule/insert_data",
				data : {
					"ym" : ym,
					"date" : date,
					"title" : title,
					"detail" : detail,
					"c_tkn" : $.cookie('c_ck')
				},
				dataType : "json"
			}).done(function(data) {

				if(data['result'] == 0) {
					// 正常に予定登録が行われた場合

					// 予定データのIDを取得
					var id = data['id'];
					// エンコードを行ったタイトルを取得
					title = data['title'];

					// 対象日付に予定を登録
					var div = $('<div class="drag" data-id="'
							+ id + '" id="drag-ev-' + id
							+ '" draggable="true" ondragstart="DragStart(event)">');
					div.append(title);
					$("#drp-area-"+date).append(div);
				} else {
					// 予定登録中に異常が発生した場合
					alert('予定の登録に失敗しました。');
					return false;
				}

			}).fail(function(data) {
				// システムエラー
				alert('システムエラーが発生しました。');
				return false;
			});
		}

	});

	// 予定クリック
	$(document).on('click', '.drag', function() {

		// ajax処理で使用する値を取得
		var ym = $('#target_ym').val();
		var id = $(this).data("id");

		$.ajax({
			type : "POST",
			url : "/SCManager/Schedule/get_plan_data",
			data : {
				"ym" : ym,
				"id" : id,
				"c_tkn" : $.cookie('c_ck')
			},
			dataType : "json"
		}).done(function(data) {

			if(data['result'] == 0) {
				// 取得した予定情報のセット
				$('#upd_title').val(data['title']);
				$('#upd_detail').val(data['detail']);

				// 対象予定のIDをセット
				$("#edit_id").val(id);

				// モーダルを表示
				$('#editModal').modal('show');

			} else {
				alert('予定情報の取得に失敗しました。');
				return false;
			}

		}).fail(function(data) {
			// システムエラー
			alert('システムエラーが発生しました。');
			return false;
		});

	});

	// 更新ボタン
	$('#upd_btn').click(function() {

		// チェック処理
		if($('#upd_title').val().trim() == "") {
			alert("タイトルを入力してください。");
			return false;
		} else {

			// 対象年月
			var ym = $('#target_ym').val();
			// 対象予定のID
			var id = $("#edit_id").val();
			// タイトル、詳細
			var title = $('#upd_title').val();
			var detail = $('#upd_detail').val();

			$.ajax({
				type : "POST",
				url : "/SCManager/Schedule/update_data",
				data : {
					"ym" : ym,
					"id" : id,
					"title" : title,
					"detail" : detail,
					"c_tkn" : $.cookie('c_ck')
				},
				dataType : "json"
			}).done(function(data) {

				if(data['result'] == 0) {
					// カレンダー内の予定のタイトルを変更
					$('#drag-ev-'+id).text(title);
				} else {
					alert('予定の更新に失敗しました。');
					return false;
				}

			}).fail(function(data) {
				// システムエラー
				alert('システムエラーが発生しました。');
				return false;
			});
		}

	});

	// 削除ボタン
	$('#del_btn').click(function() {

		// ajax処理で使用する値を取得
		var ym = $('#target_ym').val();
		var id = $("#edit_id").val();

		$.ajax({
			type : "POST",
			url : "/SCManager/Schedule/delete_data",
			data : {
				"ym" : ym,
				"id" : id,
				"c_tkn" : $.cookie('c_ck')
			},
			dataType : "json"
		}).done(function(data) {

			if(data['result'] == 0) {
				// 予定を削除
				$("#drag-ev-"+id).remove();
			} else {
				alert('予定の削除に失敗しました。');
				return false;
			}

		}).fail(function(data) {
			// システムエラー
			alert('システムエラーが発生しました。');
			return false;
		});

	});

});