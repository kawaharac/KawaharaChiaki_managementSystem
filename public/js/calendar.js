$(function () {
//モーダルを開く記述をする
	$(".btn-danger").click(function() {
		$(".").dialog({
			modal:true, //モーダル表示
			title:"テストダイアログ3", //タイトル
			buttons: { //ボタン
			"確認": function() {
				$(this).dialog("close");
				},
			"キャンセル": function() {
				$(this).dialog("close");
				}
			}
		});
	});
});
