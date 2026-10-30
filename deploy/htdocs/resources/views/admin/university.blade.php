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

    .eye-icon,
    .edit-icon,
    .delete-icon {
        font-size: 15px; /* Adjust the icon size as needed */
        cursor: pointer;
        margin: 0 5px; /* Add margin for spacing */
    }

    .eye-icon {
        color: white;
        background-color: grey;
        border: 1px solid grey;
        border-radius: 10%;
        padding: 5px;
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
                    <h1><strong>University</strong></h1>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <!-- <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addEmailModal">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                        Update Email
                    </button> -->
                    <button type="button" class="btn btn-light-primary" onclick="redirectToEmailPage()">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                            View Email
                    </button>
                    <button type="button" class="btn btn-light-primary" onclick="redirectToNewPage()">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                            Add University
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th class=" border-bottom-0"><strong>Logo</strong></th>
                                <th class=" border-bottom-0"><strong>Image</strong></th>
                                <th class=" border-bottom-0"><strong>Name</strong></th>
                                <th class=" border-bottom-0"><strong>State</strong></th>
                                <th class=" border-bottom-0"><strong>Country</strong></th>
                                <th class=" border-bottom-0"><strong>Operation Hours</strong></th>
                                <th class=" border-bottom-0"><strong>Status</strong></th>
                                <th class=" border-bottom-0 text-end"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($university) && count($university) > 0)
                            @foreach($university as $u)
                                @php
                                    $img = explode(',', $u->u_img);
                                    $counter++;
                                @endphp
                            <tr>
                                <td>{{ $counter }}.</td>
                                <td><img src="/images/university/logo/{{$u->u_logo}}" style="width: 100px; height: 100px;"></td>
                                <td><img src="/images/university/images/{{$img[0]}}" style="width: 100px; height:100px;"></td>
                                <td>{{$u->u_name}}</td>
                                <td>{{ \App\Models\State::getNameById($u->u_state) }}</td>
                                <td>{{ \App\Models\Country::getNameById($u->u_country) }}</td>
                                <td>{{$u->u_hours}}</td>
                                <td>@if($u->u_status == 1)Active @elseif($u->u_status == 98) Inactive @else Unknown Status @endif</td>
                                <td class="text-end">
                                    <i class="bi bi-eye eye-icon" onclick="toggleHide({{$u->u_id}})"></i>
                                    <i class="bi bi-pencil edit-icon" onclick="redirectToEditPage('{{ $u->u_id }}')"></i>
                                    <i class="bi bi-trash delete-icon" onclick="toggleReject({{$u->u_id}})"></i>
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
                    <h5 class="modal-title" id="addCategoryModalLabel">Add University Email</h5>
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
                            <option value="" selected disabled>Please Select Main Category</option>
                        </select>
                    </div>

                    <label for="coursesubcat" class="col-sm-2 col-form-label mt-3">Email</label>
                    <input id="categoryInput" name="email" class="form-control" type="text" placeholder="Enter university email" required>
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
				<h5 class="modal-title text-white" id="myModalLabel140">Delete University</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteUniversity" method="post">
				<input type="hidden" name="u_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this university?</span>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger" >Delete</button>
					<button type="button" class="btn" data-bs-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade text-left" id="hideModal" tabindex="-1" role="dialog" aria-labelledby="hideUniModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="hideUniModalLabel">Hide/Unhide University</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/hideUniversity" method="post">
				<input type="hidden" name="u_id" id="deactivate_ids" value="">
				<div class="modal-body">
					<span>Are you sure to hide/unhide this university?</span>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger" >Yes</button>
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
        $('#coursesubcat').append('<option value="0" selected>Main Branch</option>');

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

    function redirectToNewPage() {
        var newPageUrl = '/admin/adduniversityform';
        window.location.href = newPageUrl;
    }

    function redirectToEmailPage() {
        var newPageUrl = '/admin/viewuniversityemail';
        window.location.href = newPageUrl;
    }

    function redirectToViewPage(u_id) {
        // Log s_level_id to the browser console
        console.log('university id:', u_id);

        /// Redirect to the viewsubjects page with s_level_id as a query parameter
        window.location.href = '/admin/viewuniversity?u_id=' + u_id;
    }

    function redirectToEditPage(u_id) {
        // Log s_level_id to the browser console
        console.log('university id:', u_id);

        /// Redirect to the viewsubjects page with s_level_id as a query parameter
        window.location.href = '/admin/edituniversityform?u_id=' + u_id;
    }

    function toggleReject(id) {
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }

    function toggleHide(id) {
	    if(id != undefined && id != null) {
		    $('#deactivate_ids').val(id);
		    $('#hideModal').modal('toggle');
	    }
    }
</script>
@endsection