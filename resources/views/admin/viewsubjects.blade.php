@extends('adminLayout')
@section('title', '')

@section('style')
<style>
    .viewsubjectstable {
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
<div class="viewsubjectstable">
<div class="card card-p-0 card-flush">
 <div class="card-header align-items-center py-5 gap-2 gap-md-5">
  <div class="card-title ms-5">
    <h1><strong>Subject</strong></h1>
  </div>
  <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
   <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
   <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
   Add Category
   </button>
   <button type="button" class="btn btn-light-primary me-8" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
        Add Subject
    </button>
  </div>
 </div>
 <div class="card-body">
  <table class="table align-middle border rounded table-row-dashed fs-6 g-5" id="kt_datatable_example_1">
   <thead>
    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">
     <th class="min-w-100px">Subject</th>
     <th class="min-w-100px text-center">Category</th>
     <th class="text-center">is Preselected</th>
     <th class="text-end min-w-130px">Actions</th>
    </tr>
   </thead>
   <tbody class="fw-bold text-gray-600">
        @foreach($subjects as $subject)
        <tr>
            <td>{{ $subject->s_name }}</td>
            <td class="text-center">
            @php
                $category = \App\Models\SubjectCategory::find($subject->s_subject_cat_id);
                echo $category ? $category->sc_name : 'N/A';
            @endphp
            </td>
            <td class="text-center">{{ $subject->s_is_preselect == 1 ? 'Yes' : 'No' }}</td>
            <td class="text-end">
                <i class="bi bi-pencil edit-icon" onclick="toggleEdit('{{$subject->s_id}}', '{{$subject->s_name}}', '{{$subject->s_is_preselect}}', '{{$subject->s_subject_cat_id}}')"></i>
                <i class="bi bi-trash delete-icon" onclick="toggleReject({{ $subject->s_id }})"></i>
            </td>
        </tr>
        @endforeach
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
            <form id="addCategoryForm" method="post" action="/admin/addsubjectCategory">
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

<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="addSubjectForm" method="post" action="/admin/addSubject">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Add Subject</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="subjectId" name="subjectId" class="form-control mb-3 bg-secondary" value="{{ $value }}" readonly>
                <label for="subjectLevel">Level:</label>
                <input type="text" id="subjectLevel" name="subjectLevel" class="form-control mb-3 bg-secondary" value="{{ $level }}" readonly>

                <label for="subjectName">Subject Name:</label>
                <input type="text" id="subjectTitle" name="subjectTitle" class="form-control mb-3">

                <label for="subjectCategory">Category:</label>
                <div class="input-group mb-3">
                    <select id="subjectCategory" name="subjectCategory" class="form-select" data-dropdown-parent="#addSubjectModal">
                            <option value="" selected disabled>Select category</option>
                        @foreach($subjectcategories as $category)
                            <option value="{{ $category->sc_id }}">{{ $category->sc_name }}</option>
                        @endforeach
                    </select>

                </div>
                <div class="mt-5"></div>
                <label for="subjectType">Preselected:</label>
                    <div class="form-check form-check-inline mt-3">
                    <input class="form-check-input" type="radio" name="preselected" id="inlineRadio1" value="1">
                    <label class="form-check-label" for="inlineRadio1">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="preselected" id="inlineRadio2" value="0">
                    <label class="form-check-label" for="inlineRadio2">No</label>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Add Subject</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="editSubjectForm" method="post" action="/admin/editSubject">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubjectModalLabel">Edit Subject</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editSubjectId" name="s_id" class="form-control mb-3 bg-secondary" readonly>
                    <label for="editSubjectName">Subject Name:</label>
                    <input type="text" id="editSubjectTitle" name="editSubjectTitle" class="form-control mb-3">

                    <label for="editSubjectCategory">Category:</label>
                    <div class="input-group mb-3">
                        <select id="editSubjectCategory" name="editSubjectCategory" class="form-select" data-control="select2" data-placeholder="Select an option" data-dropdown-parent="#editSubjectModal">
                            <option></option>
                            @foreach($subjectcategories as $category)
                                <option value="{{ $category->sc_id }}">{{ $category->sc_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mt-5"></div>
                    <label for="editSubjectType">Preselected:</label>
                    <div class="form-check form-check-inline mt-3">
                        <input class="form-check-input" type="radio" name="editPreselected" id="editInlineRadio1" value="1">
                        <label class="form-check-label" for="editInlineRadio1">Yes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="editPreselected" id="editInlineRadio2" value="0">
                        <label class="form-check-label" for="editInlineRadio2">No</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Subject</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteSubject" method="post">
				<input type="hidden" name="s_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this subject?</span>
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
    // Get s_level_id from the query parameters
    const urlParams = new URLSearchParams(window.location.search);
    const id = urlParams.get('id');

    function toggleEdit(s_level_id, s_name, s_subject_cat_id, s_is_preselect) {
    console.log('Received data:', s_level_id, s_name, s_subject_cat_id, s_is_preselect);

    // Set the values of modal fields based on the data from the table row
    $('#editSubjectId').val(s_level_id);
    $('#editSubjectTitle').val(s_name);

    // Update the Select2 dropdown with the selected category
    $('#editSubjectCategory').val(s_subject_cat_id).trigger('change.select2');

    // Check the radio button based on s_is_preselect
    if (s_is_preselect == 1) {
        $('input[name="editPreselected"][value="1"]').prop('checked', true);
    } else {
        $('input[name="editPreselected"][value="0"]').prop('checked', true);
    }

    // Open the modal
    $('#editSubjectModal').modal('show');
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