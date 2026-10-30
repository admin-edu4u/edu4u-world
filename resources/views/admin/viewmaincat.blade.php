@extends('adminLayout')
@section('title')
@section('style')
<style>
    .viewsubcattable {
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
    <script>
        var c_id = sessionStorage.getItem('c_id');
    </script>
    <div class="viewsubcattable">
            <div class="card">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title ms-5">
                        <h1><strong>@if(isset($category) && $category){{ $category->c_name }}@endif</strong></h1>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addSubCatModal">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                                Add Sub Category
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                            <thead>
                                <tr>
                                    <th class="border-bottom-0" style="width:20px;"><strong>No.</strong></th>
                                    <th class=" border-bottom-0"><strong>Sub Categories</strong></th>
                                    <th class=" border-bottom-0"><strong>Character</strong></th>
                                    <th class=" border-bottom-0 text-end"><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($subcats) && count($subcats) > 0)
                                @foreach($subcats as $index => $sc)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}.</td>
                                    <td>{{$sc->sc_name}}</td>
                                    <td>
                                        @php
                                            $character = \App\Models\Characters::find($sc->sc_character);
                                            $characterName = $character ? $character->c_name : 'Character Not Found';
                                            echo $characterName;
                                        @endphp
                                    </td>
                                    <td class="text-end">
                                        <i class="bi bi-pencil edit-icon" onclick="toggleEdit('{{ $sc->sc_id }}', '{{ $sc->sc_name }}', '{{ $sc->sc_character }}')"></i>
                                        <i class="bi bi-trash delete-icon" onclick="toggleReject({{$sc->sc_id}})"></i>
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
<div class="modal fade" id="addSubCatModal" tabindex="-1" aria-labelledby="addSubCatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="addSubCatForm" method="post" action="/admin/addSubCat">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubCatModalLabel">New Sub Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input id="c_id" type="hidden" value="{{ $category->c_id }}" name="c_id" readonly>
                    <label for="subcatTitle">Sub Category:</label>
                    <input id="SubCatInput" name="subcat" class="form-control" type="text" placeholder="Enter new sub category..." required>
                        <div class="mt-3"></div>
                            <label>Character</label>
                            <select class="form-select" name="subcatchar"  data-dropdown-parent="#addSubCatModal">
                                    <option value="" selected disabled>Please select character...</option>
                                @foreach($characters as $c)
                                    <option value="{{ $c->c_id }}">{{ $c->c_name }}</option>
                                @endforeach
                            </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editSubCatModal" tabindex="-1" aria-labelledby="editSubCatModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="editSubCatForm" method="post" enctype="multipart/form-data" action="/admin/editSubCat">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubCatModalLabel">Enter Sub Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="sc_id" id="edit_id" readonly>
                    <label for="SubCatTitle">Sub Category:</label>
                    <input id="editsubcat" name="editsubcat" class="form-control" type="text" placeholder="Enter sub category..." required>
                        <div class="mt-3"></div>
                            <label>Character</label>
                            <select class="form-select" name="editsubcatchar" data-dropdown-parent="#editSubCatModal">
                                    <option value="" selected disabled>Please select character...</option>
                                @foreach($characters as $c)
                                    <option value="{{ $c->c_id }}">{{ $c->c_name }}</option>
                                @endforeach
                            </select>
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
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Job</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteSubCat" method="post">
				<input type="hidden" name="sc_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this sub category?</span>
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
            $('.form-select').select2();
    });

    function toggleEdit(id, name, char) {
        // Populate the modal fields
        $('#edit_id').val(id);
        $('#editsubcat').val(name);
        $('#editSubCatModal select[name="editsubcatchar"]').val(char).trigger('change');
        $('#editSubCatModal').modal('toggle');
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