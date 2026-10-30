	@if(session()->has('msg'))
		<div class="alert alert-block alert-success" style="text-align:center;">
			<strong>{{ session()->get('msg') }}</strong>
		</div>

	@elseif(session()->has('errMsg'))
		<div class="alert alert-block alert-danger" style="text-align:center;">
			<strong>{{ session()->get('errMsg') }}</strong>
		</div>
	@elseif(session()->has('warnMsg'))
		<div class="alert alert-block alert-warning" style="text-align:center;">
			<strong>{{ session()->get('warnMsg') }}</strong>
		</div>
	@endif

	<!-- display only when there are errors -->
  @if (count($errors))
		<div class="alert alert-block alert-danger" style="background-color:#D3293E;">
			<button type="button" class="close" data-dismiss="alert">×</button>
			<strong>
				<ul>
          @foreach ($errors->all() as $error)
						<li style="color:white;">{{ $error }}</li>
          @endforeach
        </ul>
			</strong>
		</div>
	@endif

	<!-- JS Msg -->
	<div id='header_container'>
		<div id="success_msg_container" style="display:none" >
			<div class="alert alert-block alert-success" style="text-align:center;margin:0 auto">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong id="success_msg_list" style="text-align:center;margin:0 auto;">
				</strong>
			</div>
		</div>
		<div id="err_msg_container" style="display:none"><!-- Error Msg -->
			<div class="alert alert-block alert-danger" style="background-color:#D3293E;color: #FFF;">
				<button type="button" class="close" data-dismiss="alert">×</button>
				<strong>
					<ul id="err_msg_list"></ul>
				</strong>
			</div>
		</div>
	</div>

	<script>
		// Automatically hide the success message after 2000 milliseconds (2 seconds)
		setTimeout(function() {
                $('.alert-success').fadeOut('fast');
    	}, 2000);

    	// Automatically hide the success message after 2000 milliseconds (2 seconds)
    	setTimeout(function() {
                $('.alert-warning').fadeOut('fast');
    	}, 2000);

		// Automatically hide the success message after 2000 milliseconds (2 seconds)
    	setTimeout(function() {
                $('.alert-danger').fadeOut('fast');
    	}, 2000);
	</script>