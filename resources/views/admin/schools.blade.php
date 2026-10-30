@extends('adminLayout')
@section('style')
<style>
    .viewschoolstable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .q-box {
        display: inline-block;
        padding: 5px 10px;
        margin: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f0f0f0;
    }

    .edit-icon,
    .delete-icon {
        font-size: 15px;
        cursor: pointer;
        margin: 0 5px;
    }

    .view-icon {
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
    <div class="viewschoolstable">
        <div class="card">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title ms-5">
                    <h1><strong>Schools</strong></h1>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addSchoolModal">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                            Add School
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                        <thead>
                            <tr>
                                <th><strong>No.</strong></th>
                                <th class=" border-bottom-0"><strong>School</strong></th>
                                <th class=" border-bottom-0"><strong>Qualification</strong></th>
                                <th class=" border-bottom-0"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($schools) && count($schools) > 0)
                                @foreach($schools as $s)
                                    @php $counter++; @endphp
                                <tr>
                                    <td>{{ $counter }}.</td>
                                    <td>{{$s->s_name}}</td>
                                    <td>
                                        @php
                                            $qlevelArr = \App\Models\Course::qlevelArr();
                                            $qValues = explode(',', $s->s_qualification ?? '');
                                            foreach ($qValues as $q) {
                                                $trimmedQ = trim($q);
                                                if (isset($qlevelArr[$trimmedQ])) {
                                                    echo '<div class="q-box">' . $qlevelArr[$trimmedQ] . '</div>';
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td>
                                        <i class="bi bi-pencil edit-icon" onclick="toggleEdit('{{ $s->s_id }}', '{{ $s->s_name }}', '{{ $s->s_qualification }}')"></i>
                                        <i class="bi bi-trash delete-icon" onclick="toggleReject({{$s->s_id}})"></i>
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
<div class="modal fade" id="addSchoolModal" tabindex="-1" aria-labelledby="addSchoolrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="addSchoolForm" method="post" action="/admin/addSchools">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSchoolModalLabel">Enter New School</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label class="form-label">School Name</label>
                        <div class="col-sm-12">
                            <input class="form-control" type="text" name="schname" placeholder="Enter school name..." required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">Qualifications</label>
                        <div class="col-sm-12">
                            <select class="form-select qualificationsel" multiple="multiple" name="qualification[]" required>
                                @foreach (\App\Models\Course::qlevelArr() as $value => $level)
                                    <option value="{{ $value }}">{{ $level }}</option>
                                @endforeach
                            </select>
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

<div class="modal fade" id="editSchoolModal" tabindex="-1" aria-labelledby="editSchoolrModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="editSchoolForm" method="post" action="/admin/editSchools">
                <input type="hidden" id="edit_id" name="s_id">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSchoolModalLabel">Edit School</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3 row">
                        <label class="form-label">School Name</label>
                        <div class="col-sm-12">
                            <input class="form-control" type="text" id="editschname" name="schname" placeholder="Enter school name..." required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-2">Qualifications</label>
                        <div class="col-sm-12">
                            <select class="form-select qualificationsel" multiple="multiple" id="editq" name="qualification[]" required>
                                @foreach (\App\Models\Course::qlevelArr() as $value => $level)
                                    <option value="{{ $value }}">{{ $level }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
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
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Subject</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteSchools" method="post">
				<input type="hidden" name="s_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this character?</span>
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
        $('.qualificationsel').select2();
    });

    function toggleEdit(id, name, qualification) {
        $('#edit_id').val(id);
        $('#editschname').val(name);

        var qualifications = qualification.split(',');
        var selectElement = $('#editq');
        selectElement.val(qualifications);
        selectElement.trigger('change');

        $('#editSchoolModal').modal('toggle');
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