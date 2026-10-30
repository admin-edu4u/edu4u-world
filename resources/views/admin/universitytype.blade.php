@extends('adminLayout')
@section('style')
<style>
    .unitypetable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }
</style>
@endsection

@section('content')
@include('formMsg')
    @php $counter = 0; @endphp
<div class="unitypetable">
    <div class="card card-p-0 card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title ms-5">
                <h1><strong>University Types</strong></h1>
            </div>
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addTypeModal">
                    <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                    Add Type
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table align-middle border rounded table-row-dashed fs-6 g-5" id="kt_datatable_example_1">
            <thead>
                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">
                    <th>No.</th>
                    <th>Categories Name</th>
                    <th class="text-end">Action</th>
                </tr>
            </thead>
            <tbody class="fw-bold text-gray-600">
            @if(isset($universitytype) && count($universitytype) > 0)
                @foreach($universitytype as $ut)
                @php $counter++; @endphp
                <tr class="odd text-start align-middle">
                    <td style="width:30px;">{{ $counter }}.</td>
                    <td>{{$ut->ut_name}}</td>
                    <td class="text-end">
                            <button class="btn btn-outline-danger" onclick="toggleReject({{$ut->ut_id}})">
                            <i class="fas fa-trash"></i>
                            </button>
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
<div class="modal fade" id="addTypeModal" tabindex="-1" aria-labelledby="addTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="addTypeyForm" method="post" action="/admin/addUniversityType">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTypeModalLabel">Enter New University Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="typeTitle">University Type:</label>
                    <input id="typeInput" name="unitype" class="form-control" type="text" placeholder="Enter university type..." required>

                    <div class="mt-5 row">
                        <label>Belongs To:</label>
                        <div class="d-flex">
                            <input type="radio" class="me-1" name="utype" value="1"> Private
                            <input type="radio" class="ms-5 me-1" name="utype" value="2"> Public
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Job</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteUniversityType" method="post">
				<input type="hidden" name="ut_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this job category?</span>
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
    function toggleReject(id) {
        console.log(id);
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }
</script>
@endsection