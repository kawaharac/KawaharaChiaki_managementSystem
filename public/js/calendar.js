$(function () {
	//モーダルを開く記述をする
	$(".btn-modal-open").click(function () {
		$(".modal-container").addClass('active');
		return false;//return falseは呪文みたいなもん
	});
	$(".modal-close").on('click', function () {
		$(".modal-container").removeClass('active');
	});
});
