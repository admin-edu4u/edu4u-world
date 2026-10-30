@extends('adminLayout')
@section('title', 'Enquiry')
@section('style')
<style>
    .enquirytable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }
    .delete-icon {
        font-size: 15px;
        cursor: pointer;
        margin: 0 5px;
        color:white;
        background-color: red;
        padding: 5px;
        border-radius: 3px;
    }
</style>
@endsection

@section('content')
    @include('formMsg')
<div class="enquirytable">
    <div class="card card-p-0 card-flush mt-5">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title ms-5">
                <h1>Enquiries</h1>
            </div>
            <form action="{{ route('export.enquiries') }}" method="GET">
                <button type="submit" class="btn btn-primary float-end"><i class="fas fa-file-export"></i> Export</button>
            </form>
        </div>
        <div class="card-body">
            <table class="table align-middle border rounded table-row-dashed fs-6 g-5" id="kt_datatable_example">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">
                        <th>No.</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone No.</th>
                        <th>Message</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">
                @php $counter=1; @endphp
                @if(count($enquiries) > 0)
                    @foreach($enquiries as $eq)
                    <tr class="odd text-start align-middle">
                        <td style="width:30px;">{{ $counter }}.</td>
                        <td>{{ $eq->e_name }}</td>
                        <td>{{ $eq->e_email }}</td>
                        <td>{{ $eq->e_phone_num }}</td>
                        <td>{{ $eq->e_message }}</td>
                        <td>
                            <i class="bi bi-trash delete-icon" onclick="toggleReject({{$eq->e_id}})"></i>
                        </td>
                    </tr>
                    @php $counter++; @endphp
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
			<form action="/admin/deleteenquiries" method="post">
				<input type="hidden" name="e_id" id="deactivate_id" value="">
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
    function toggleReject(id) {
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }
</script>
@endsection