// ______________Quantity Cart Increase & Descrease
$(function () {
	$('.counter-plus').on('click',function(){
		var $qty=$(this).closest('div').find('.qty');
		var currentVal = parseInt($qty.val());
		if (!isNaN(currentVal)) {
		$qty.val(currentVal + 1);
		}
	});
	$('.counter-minus').on('click',function(){
		var $qty=$(this).closest('div').find('.qty');
		var currentVal = parseInt($qty.val());
		if (!isNaN(currentVal) && currentVal > 100) {
		$qty.val(currentVal - 1);
		}
	});

	$('.counter-plusWth').on('click',function(){
		var $qty=$(this).closest('div').find('.qty');
		var currentVal = parseInt($qty.val());
		console.log(currentVal);
		console.log($qty);
		if (!isNaN(currentVal)) {
		$qty.val(currentVal + 50);
		}
	});
	$('.counter-minusWth').on('click',function(){
		var $qty=$(this).closest('div').find('.qty');
		var currentVal = parseInt($qty.val());
		if (!isNaN(currentVal) && currentVal > 50) {
		$qty.val(currentVal - 50);
		}
	});
});
