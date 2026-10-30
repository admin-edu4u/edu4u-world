@if(session()->has('msg'))
<div class="alert alert-block alert-success" style="background-color:#10A300; text-align:center; padding: 5px 10px; border-radius: 10px;">
	{{-- <button type="button" class="close" data-dismiss="alert">×</button> --}}
	<strong style="font-size:14px; color:white">{{ session()->get('msg') }}</strong>
</div>

@elseif(session()->has('errMsg'))
<div class="alert alert-block alert-danger" style="background-color:#D3293E; text-align:center; padding: 5px 10px; border-radius: 10px;">
	{{-- <button type="button" class="close" data-dismiss="alert">×</button> --}}
	<strong style="font-size:14px; color:white">{{ session()->get('errMsg') }}</strong>
</div>
@elseif(session()->has('warnMsg'))
<div class="alert alert-block alert-warning" style="background-color:#D9AD00; text-align:center; padding: 5px 10px; border-radius: 10px;">
	{{-- <button type="button" class="close" data-dismiss="alert">×</button> --}}
	<strong style="font-size:14px; color:white">{{ session()->get('warnMsg') }}</strong>
</div>
@endif

<!-- display only when there are errors -->
@if (count($errors))
<div class="alert alert-block alert-danger" style="background-color:#D3293E; padding: 5px 10px; border-radius: 10px;">
	{{-- <button type="button" class="close" data-dismiss="alert">×</button> --}}
	<strong>
		<ul style="padding:0px;margin:0px;">
			@foreach ($errors->all() as $error)
			<li style="padding:0px;margin:0px;color:white;font-size:14px;">{{ $error }}</li>
			@endforeach
		</ul>
	</strong>
</div>
@endif