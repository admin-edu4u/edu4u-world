<div class="main">
	<h2 class="title">Redirecting to Billplz...</h2>
	<form action="/submitBillplz" method="post" id="senangForm">
		<input type="hidden" name="detail" id="detail" value="{{$detail}}" />
		<input type="hidden" name="amount" id="amount" value="{{$grand_total}}" />
		<input type="hidden" name="order_id" id="order_id" value="{{$pay->pay_ref_id }}" />
		<input type="hidden" name="hash" id="hash" value="{{$hash}}" />
		<input type="hidden" name="name" id="name" value="{{$name}}" />
		<input type="hidden" name="email" id="email" value="{{$email}}" />
		<input type="hidden" name="phone" id="phone" value="{{$contact}}" />
		<button class="submitBTN addnowBtn" type="submit">
			<img src="/images/billplz.png" style="height:100px;">
		</button>
	</form>
</div>

<script>
setTimeout(function() {
	var f = document.getElementById('senangForm');
	if(f != null && f!= undefined) {
		f.submit();
	}
}, 2000);
function submitSenang() {
	var f = document.getElementById('senangForm');
	if(f != null && f!= undefined) {
		f.submit();
	}
}
</script>
<link rel="stylesheet" type="text/css" href="/css/senang.css">
