@extends('adminLayout')
@section('title', 'Jobs')
@section('style')
<style>
    .jobtable {
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

    .url-column {
        max-width: 75px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endsection

@section('content')
    @include('formMsg')
    @php $counter = 0; @endphp
<div class="jobtable">
    <div class="card card-p-0 card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title ms-5">
                <h1><strong>Job Vacancy</strong></h1>
            </div>
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                    Add Category
                </button>
                <button type="button" class="btn btn-light-primary me-8" data-bs-toggle="modal" data-bs-target="#addJobModal">
                    <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                    Add Job
                </button>
            </div>
        </div>
        <div class="card-body">
            <table class="table align-middle border rounded table-row-dashed fs-6 g-5" id="kt_datatable_example_1">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase text-center align-middle">
                        <th>No.</th>
                        <th class="min-w-100px">Title</th>
                        <th class="min-w-100px">Location</th>
                        <th class="min-w-100px">Type</th>
                        <th class="min-w-75px">Category</th>
                        <th class="w-60px">Url</th>
                    <th class="min-w-130px">Actions</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">
                @if(isset($job) && count($job) > 0)
                    @foreach($job as $j)
                    @php $counter++; @endphp
                    <tr class="odd text-center align-middle">
                        <td>{{ $counter }}.</td>
                        <td>{{$j->c_title}}</td>
                        <td>{{$j->c_state}}</td>
                        <td>{{ $j->c_type == 1 ? 'Full-Time' : ($j->c_type == 2 ? 'Part-Time' : 'N/A') }}</td>
                        <td>
                            @foreach($categories as $category)
                            @if($category->cc_id == $j->c_category)
                                {{ $category->cc_title }}
                            @endif
                            @endforeach
                        </td>
                        <td>{{ substr($j->c_url, 0, 50) }}...</td>
                        <td class="align-middle">
                                <i class="bi bi-pencil edit-icon" onclick="toggleEdit({{$j->c_id}}, '{{$j->c_title}}', '{{$j->c_category}}', '{{$j->c_type}}', '{{$j->c_state}}', {{$j->c_salary_min}}, {{$j->c_salary_max}})"></i>
                                <button class="btn btn-outline-danger" onclick="toggleReject({{$j->c_id}})">
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

<div class="modal fade" id="addJobModal" tabindex="-1" aria-labelledby="addJobModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addJobModalLabel">Add Job</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Your job form goes here -->
                    <form id="addjobForm" class="form-horizontal" method="post" action="/admin/addJob">
                    <label for="jobTitle">Job Title:</label>
                    <input type="text" id="jobTitle" name="title" class="form-control mb-3" required>

                    <label for="jobCategory">Category:</label>
                    <div class="input-group mb-3">
                    <select class="form-select" name="category" data-control="select2" data-placeholder="Select an option" data-dropdown-parent="#addJobModal" required>
                    <option></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->cc_id }}">{{ $category->cc_title }}</option>
                        @endforeach
                    </select>

                    </div>

                    <label for="jobType">Type:</label>
                    <div class="input-group mb-3">
                    <select class="form-select" name="type" data-control="select2" data-placeholder="Select an option" data-dropdown-parent="#addJobModal" required>
                    <option></option>
                    <option value="1">Full-Time</option>
                    <option value="2">Part-Time</option>
                    </select>

                    </div>

                    <label for="jobLocation">State:</label>
                    <input type="text" id="jobLocation" name="state" class="form-control mb-3" required>

                    <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="minSalary">Minimum Salary (RM):</label>
                        <input type="number" id="minSalary" name="minsalary" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="maxSalary">Maximum Salary (RM):</label>
                        <input type="number" id="maxSalary" name="maxsalary" class="form-control" required>
                    </div>
                    </div>

                    <label for="jobTitle">Job Link:</label>
                    <input type="text" id="jobUrl" name="jobUrl" class="form-control mb-3" required>

                    <!-- Add more form fields as needed -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Add Job</button>
                </div>
                </form>
            </div>
        </div>
</div>

<div class="modal fade" id="editJobModal" tabindex="-1" aria-labelledby="editJobModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editJobModalLabel">Edit Job</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Your job form goes here -->
                    <form action="/admin/editJob" method="post">
                    <input type="hidden" name="c_id" id="edit_id" value="">
                    <label for="jobTitle">Job Title:</label>
                    <input type="text" id="title" name="title" class="form-control mb-3" required>

                    <label for="jobCategory">Category:</label>
                    <div class="input-group mb-3">
                    <select id="jobCategory" name="category" class="form-select" data-control="select2" data-placeholder="Select an option" data-dropdown-parent="#editJobModal" required>
                    <option></option>
                        @foreach($categories as $category)
                            <option value="{{ $category->cc_id }}">{{ $category->cc_title }}</option>
                        @endforeach
                    </select>
                    </div>

                    <label for="jobType">Type:</label>
                    <div class="input-group mb-3">
                    <select id="jobType" name="type" class="form-select" data-control="select2" data-placeholder="Select an option" data-dropdown-parent="#editJobModal" required>
                    <option></option>
                    <option value="1">Full-Time</option>
                    <option value="2">Part-Time</option>
                    </select>
                    </div>

                    <label for="jobLocation">Location:</label>
                    <input type="text" id="state" name="state" class="form-control mb-3" required>

                    <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="minSalary">Minimum Salary (RM):</label>
                        <input type="number" id="minsalary" name="minsalary" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="maxSalary">Maximum Salary (RM):</label>
                        <input type="number" id="maxsalary" name="maxsalary" class="form-control" required>
                    </div>
                    </div>

                    <label for="jobTitle">Job Link:</label>
                    <input type="text" id="jobUrl" name="jobUrl" class="form-control mb-3" required>

                    <!-- Add more form fields as needed -->
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit Job</button>
                    </form>
                </div>
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
			<form action="/admin/deleteJob" method="post">
				<input type="hidden" name="c_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this job?</span>
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
    "use strict";

    // Class definition
    var KTDatatablesButtons = function () {
    // Shared variables
    var table;
    var datatable;

    // Private functions
    var initDatatable = function () {
        // Set date data order
        const tableRows = table.querySelectorAll('tbody tr');

        tableRows.forEach(row => {
            const dateRow = row.querySelectorAll('td');
            const realDate = moment(dateRow[3].innerHTML, "DD MMM YYYY, LT").format(); // select date from 4th column in table
            dateRow[3].setAttribute('data-order', realDate);
        });

        // Init datatable --- more info on datatables: https://datatables.net/manual/
        datatable = $(table).DataTable({
            "info": false,
            'order': [],
            'pageLength': 10,
        });
    }
    }

    var addJobModal = new bootstrap.Modal(document.getElementById('addJobModal'));

    function deleteRow(element) {
    // Implement your delete logic here
    const swalWithBootstrapButtons = Swal.mixin({
    customClass: {
    confirmButton: "btn btn-success",
    cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
    });
    swalWithBootstrapButtons.fire({
    title: "Are you sure?",
    text: "You won't be able to revert this!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonText: "Yes",
    cancelButtonText: "No",
    reverseButtons: true
    }).then((result) => {
    if (result.isConfirmed) {
    swalWithBootstrapButtons.fire({
      title: "Deleted!",
      text: "The job has been deleted.",
      icon: "success"
    });
    } else if (
    /* Read more about handling dismissals below */
    result.dismiss === Swal.DismissReason.cancel
    ) {
    swalWithBootstrapButtons.fire({
      title: "Cancelled",
      icon: "error"
    });
    }
    });
    }  

    function toggleEdit(c_id, c_title, c_category, c_type, c_state, c_salary_min, c_salary_max) {
    console.log('Received data:', c_id, c_title, c_category, c_type, c_state, c_salary_min, c_salary_max);

    // Log values before setting them
    console.log('Before setting values:', $('#edit_id').val(), $('#title').val(), $('#state').val(), $('#minsalary').val(), $('#maxSalary').val());

    // Set the values of modal fields based on the data from the table row
    $('#edit_id').val(c_id);
    $('#title').val(c_title);
    $('#jobCategory').val(c_category).trigger('change');
    $('#jobType').val(c_type).trigger('change');
    $('#state').val(c_state);
    $('#minsalary').val(c_salary_min);
    $('#maxsalary').val(c_salary_max);

    // Log values after setting them
    console.log('After setting values:', $('#edit_id').val(), $('#title').val(), $('#state').val(), $('#minsalary').val(), $('#maxSalary').val());

    // Open the modal
    $('#editJobModal').modal('show');
    }

    function toggleReject(id) {
        console.log(id);
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }
</script>
@endsection