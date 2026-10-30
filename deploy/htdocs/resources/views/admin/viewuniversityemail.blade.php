@extends('adminLayout')
@section('title', 'University')
@section('style')
<style>
    .viewuniversitytable {
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
<div class="viewuniversitytable">
        <div class="card">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title ms-5">
                    <h1><strong>University Email</strong></h1>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addEmailModal">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                        Add & Update
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th class=" border-bottom-0"><strong>University</strong></th>
                                <th class=" border-bottom-0"><strong>Branch</strong></th>
                                <th class=" border-bottom-0"><strong>Email</strong></th>
                                <th class=" border-bottom-0 text-end"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($universitybranch) && count($universitybranch) > 0)
                            @foreach($universitybranch as $u)
                                @php
                                    $counter++;
                                @endphp
                            <tr>
                                <td>{{ $counter }}.</td>
                                <td>{{ \App\Models\University::getNameById($u->ue_u_id) }}</td>
                                <td>{{ \App\Models\State::getNameById($u->ue_b_id) ?: 'Main Branch' }}</td>
                                <td>{{$u->ue_u_email}}</td>
                                <!-- <td>{{ \App\Models\Country::getNameById($u->u_country) }}</td> -->
                                <td class="text-end">
                                    <i class="bi bi-trash delete-icon" onclick="toggleReject({{$u->ue_id}})"></i>
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
<div class="modal fade" id="addEmailModal" tabindex="-1" aria-labelledby="addEmailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="addEmailForm" method="post" action="/admin/addUniEmail">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">Add & Update University Email</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="coursemaincat" class="col-sm-2 col-form-label">University</label>
                    <div class="col-sm-10">
                        <select class="form-select coursemaincat" name="uni" id="coursemaincat" required>
                            <option value="" selected disabled>Select University</option>
                            @foreach ($university as $u)
                                <option value="{{ $u->u_id }}">{{ $u->u_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label for="coursesubcat" class="col-sm-2 col-form-label mt-3">Branch</label>
                    <div class="col-sm-10">
                        <select class="form-select coursesubcat" name="unibranch" id="coursesubcat" required>
                            <option value="" selected disabled>Please Select University</option>
                        </select>
                    </div>

                    <label for="coursesubcat" class="col-sm-2 col-form-label mt-3">Email</label>
                    <input id="categoryInput" name="email" class="form-control" type="email" placeholder="Enter university email" required>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="myModalLabel140">Delete University</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteUniversityEmail" method="post">
				<input type="hidden" name="ue_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this university email?</span>
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
    $(document).ready(function() { 
        $(document).on('change', '#coursemaincat', function () {
        var mainCategoryId = $(this).val();
        // Clear existing options in the subcategory select
        $('#coursesubcat').empty();
        $('#coursesubcat').append('<option value="" selected disabled>Select University Branch</option>');
        $('#coursesubcat').append('<option value="0">Main Branch</option>');

        // If a main category is selected, fetch and append subcategories
        if (mainCategoryId) {
                $.ajax({
                    type: 'GET',
                    url: '/admin/get-uni-branches',
                    data: { main_category_id: mainCategoryId },
                    success: function (data) {
                        console.log(data)
                        // Append subcategories to the select
                        $.each(data.list, function (index, subcategory) {
                            $('#coursesubcat').append('<option value="' + subcategory.b_state + '">' + subcategory.state_name + '</option>');
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });

    function toggleReject(id) {
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }
</script>
@endsection