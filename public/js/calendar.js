$(function () {
	//モーダルを開く記述をする
	$(".btn-modal-open").click(function () {
		$(".modal-container").addClass('active');
		var $modalOpenDay = $(this).attr("value");
		$(".modal-cancel-day").text($modalOpenDay);
		var $modalOpenTime = $(this).text();
		$(".modal-cancel-time").text($modalOpenTime);
		return false;
	});
	$(".modal-close").on('click', function () {
		$(".modal-container").removeClass('active');
	});
});
