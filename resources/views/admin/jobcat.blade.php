@extends('adminLayout')
@section('style')
<style>
    .jobcategoriestable {
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
    <div class="jobcategoriestable">
        <div class="card card-p-0 card-flush">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title ms-5">
                    <h1><strong>Career Categories</strong></h1>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                        Add Category
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
                        @if(isset($jobcat) && count($jobcat) > 0)
                            @foreach($jobcat as $jc)
                            @php $counter++; @endphp
                            <tr class="odd text-start align-middle">
                                <td style="width:30px;">{{ $counter }}.</td>
                                <td>{{$jc->cc_title}}</td>
                                <td class="text-end">
                                        <button class="btn btn-outline-danger" onclick="toggleReject({{$jc->cc_id}})">
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
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="addCategoryForm" method="post" action="/admin/addJobCategory">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Enter New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="categoryTitle">Category:</label>
                    <input id="categoryInput" name="title" class="form-control" type="text" placeholder="Category" required>
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
			<form action="/admin/deleteJobCategory" method="post">
				<input type="hidden" name="cc_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this career category?</span>
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