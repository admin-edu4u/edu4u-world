@extends('adminLayout')
@section('title', 'K-12 Scholarship')
@section('style')
<style>
    .viewscholarshiptable {
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
        font-size: 15px; /* Adjust the icon size as needed */
        cursor: pointer;
        margin: 0 5px; /* Add margin for spacing */
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
</style>
@endsection

@section('content')
    @include('formMsg')
    @php $counter = 0; @endphp
<div class="viewscholarshiptable">
        <div class="card">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title ms-5">
                    <h1><strong>K-12 Scholarship</strong></h1>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button type="button" class="btn btn-light-primary" onclick="redirectToNewPage()">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                            Add Scholarship
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                        <thead>
                            <tr>
                                <th style="width:30px;"><strong>No.</strong></th>
                                <th class=" border-bottom-0"><strong>K-12</strong></th>
                                <th class=" border-bottom-0"><strong>Title</strong></th>
                                <th class=" border-bottom-0 text-end"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($k12s) && count($k12s) > 0)
                            @foreach($k12s as $ks)
                            @php $counter++; @endphp
                            <tr>
                                <td>{{ $counter }}.</td>
                                <td>{{ App\Models\InternationalSchool::find($ks->s_int_id)->int_name ?? 'N/A' }}</td>
                                <td>{{$ks->s_title}}</td>
                                <td class="text-end">
                                <i class="bi bi-pencil edit-icon" onclick="redirectToEditPage('{{ $ks->s_id }}')"></i>
                                    <i class="bi bi-trash delete-icon" onclick="toggleReject({{$ks->s_id}})"></i>
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
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Event</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteK12Scholarship" method="post">
				<input type="hidden" name="s_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this scholarship?</span>
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
        var newPageUrl = '/admin/addk12scholarshipform';
        window.location.href = newPageUrl;
    }

    function redirectToEditPage(s_id) {
        window.location.href = '/admin/editk12scholarshipform?id=' + s_id;
    }


    function toggleReject(id) {
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }
</script>
@endsection