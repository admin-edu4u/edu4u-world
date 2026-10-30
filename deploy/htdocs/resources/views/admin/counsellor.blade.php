@extends('adminLayout')
@section('title', 'Counsellor')
@section('style')
<style>
    .counsellortable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .view-icon,
    .delete-icon {
        font-size: 15px;
        cursor: pointer;
        margin: 0 5px;
    }
</style>
@endsection

@section('content')
    @include('formMsg')
    @php $counter = 0; @endphp
<div class="counsellortable">
    <div class="card card-p-0 card-flush mt-5">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title ms-5">
                <h1>Consult Counsellor</h1>
            </div>
            <form action="{{ route('export.counsellor') }}" method="GET">
                <button type="submit" class="btn btn-primary"><i class="fas fa-file-export"></i> Export</button>
            </form>
        </div>
        <div class="card-body">
            <table class="table align-middle border rounded table-row-dashed fs-6 g-5" id="kt_datatable_example">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">
                        <th>No.</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Contact No.</th>
                        <th>Remarks</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">
                @if(count($counsellor) > 0)
                    @foreach($counsellor as $c)
                    @php $counter++; @endphp
                    <tr class="odd text-start align-middle">
                        <td style="width:30px;">{{ $counter }}.</td>
                        <td>{{ $c->cc_name }}</td>
                        <td>
                            @foreach($course as $crs)
                                @if($crs->c_id == $c->cc_c_id)
                                    {{ $crs->c_name }}
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $c->cc_contact_no }}</td>
                        <td>
                            @if($c->cc_remarks)
                                {{ $c->cc_remarks }}
                            @else
                                <i>-</i>
                            @endif
                        </td>
                        <td>
                            @foreach($course as $crs)
                                @if($crs->c_id == $c->cc_c_id)
                                <!-- <button class="form-control" style="width:100px;" onclick="redirectToViewConsult('{{ $c->cc_id }}')">View</button> -->
                                <i class="bi bi-eye view-icon" onclick="redirectToViewConsult('{{ $c->cc_id }}')"></i>
                                @endif
                            @endforeach
                            <i class="bi bi-trash delete-icon float-end" onclick="toggleReject({{$c->cc_id}})"></i>
                        </td>
                    </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade text-left" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Record</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deletecounsellor" method="post">
				<input type="hidden" name="cc_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this record?</span>
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
    function redirectToViewConsult(id) {
        window.location.href = '/admin/viewconsultcounsellor?id=' + id;
    }

    function toggleReject(id) {
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }
</script>
@endsection