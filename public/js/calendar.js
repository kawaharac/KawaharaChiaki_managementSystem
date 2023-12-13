$(function () {
	//モーダルを開く記述をする
	$(".btn-modal-open").click(function () {
		$(".modal-container").addClass('active');
		var $modalOpenDay = $(this).attr("value");
		$(".modal-cancel-day").text($modalOpenDay);//ここのデータをcancelに利用する＝btn-modal-openの値＝$day->authReserveDate($day->everyDay())->first()->setting_reserve
		$(".cancel-get-day").val($modalOpenDay);
		var $getPart = $(".getPart").val();
		var $modalOpenTime = $(this).text();
		$(".cancel-get-part").val($getPart);
		$(".modal-cancel-time").text($modalOpenTime);
		return false;
	});
	$(".modal-close").on('click', function () {
		$(".modal-container").removeClass('active');
	});
});
