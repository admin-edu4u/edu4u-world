@php
	$site_key = Config::get('app.site_key');
@endphp
<!-- <script src="https://www.google.com/recaptcha/api.js?render={{$site_key}}"></script> -->
<script type="text/javascript">
	$().ready(function() {
		//recaptch v3
		grecaptcha.ready(function () {
      grecaptcha.execute('{{$site_key}}', { action: 'contact' }).then(function (token) {
				console.log("Loaded");
				var recaptchaResponse = document.getElementById('recaptchaResponse');
        recaptchaResponse.value = token;
      });
    });

	})
</script>
