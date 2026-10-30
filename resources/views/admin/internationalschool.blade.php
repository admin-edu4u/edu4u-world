@extends('adminLayout')
@section('title', 'K-12')
@section('style')
<style>
    .viewk12table {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .edit-icon,
    .delete-icon {
        font-size: 15px; 
        cursor: pointer;
        margin: 0 5px; 
    }

    .edit-icon {
        color: white;
        background-color: lightskyblue;
        border: 1px solid lightskyblue;
        border-radius: 10%;
        padding: 5px;
    }

    .delete-icon {
        color: white;
        background-color: red;
        border: 1px solid red;
        border-radius: 10%;
        padding: 5px;
    }

    .level-box {
        display: inline-block;
        padding: 5px 10px;
        margin: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f0f0f0;
    }
</style>
@endsection

@section('content')
    @include('formMsg')
    @php $counter = 0; @endphp
<div class="viewk12table">
        <div class="card">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title ms-5">
                    <h1><strong>K-12 Study</strong></h1>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button type="button" class="btn btn-light-primary" onclick="redirectToNewPage()">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                            Add K-12 School
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                        <thead>
                            <tr>
                                <th><strong>No.</strong></th>
                                <th class=" border-bottom-0"><strong>Name</strong></th>
                                <th class=" border-bottom-0"><strong>Logo</strong></th>
                                <th class=" border-bottom-0"><strong>Image</strong></th>
                                <th class=" border-bottom-0"><strong>Levels</strong></th>
                                <th class=" border-bottom-0"><strong>State</strong></th>
                                <th class=" border-bottom-0"><strong>Country</strong></th>
                                <th class=" border-bottom-0 text-end"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($k12) && count($k12) > 0)
                            @foreach($k12 as $k)
                                @php
                                    $img = explode(',', $k->int_img);
                                    $counter++;
                                @endphp
                                <tr>
                                    <td>{{ $counter }}.</td>
                                    <td>{{$k->int_name}}</td>
                                    <td><img src="/images/internationalschool/logo/{{$k->int_logo}}" style="width: 100px; height: 100px;"></td>
                                    <td><img src="/images/internationalschool/images/{{$img[0]}}" style="width: 100px; height:100px;"></td>
                                    <td>
                                        @php
                                            $yearLabels = [
                                                '1' => 'Year 7',
                                                '2' => 'Year 8',
                                                '3' => 'Year 9',
                                                '4' => 'Year 10',
                                                '5' => 'Year 11',
                                                '6' => 'Year 12'
                                            ];
                                            $levelValues = explode(',', $k->int_level ?? '');
                                            foreach ($levelValues as $levelValue) {
                                                if (isset($yearLabels[$levelValue])) {
                                                    echo '<div class="level-box">' . $yearLabels[$levelValue] . '</div>';
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td>{{ \App\Models\State::getNameById($k->int_state) }}</td>
                                    <td>{{ \App\Models\Country::getNameById($k->int_country) }}</td>
                                    <td class="text-end">
                                        <i class="bi bi-pencil edit-icon" onclick="redirectToEditPage('{{ $k->int_id }}')"></i>
                                        <i class="bi bi-trash delete-icon" onclick="toggleReject({{$k->int_id}})"></i>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>
@endsection

@section('modal')
<div class="modal fade text-left" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Study</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteK12" method="post">
				<input type="hidden" name="int_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this K-12 study?</span>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger" >Delete</button>
					<button type="button" class="btn" data-bs-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
    function redirectToNewPage() {
        var newPageUrl = '/admin/addk12form';
        window.location.href = newPageUrl;
    }

    function redirectToEditPage(int_id) {
        console.log('k-12 id:', int_id);
        window.location.href = '/admin/editk12form?int_id=' + int_id;
    }

    function toggleReject(id) {
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }
</script>
@endsection