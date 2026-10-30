@extends('adminLayout')
@section('title', 'Main Category')
@section('style')
<style>
    .viewcategorytable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    #imageFrame1,
    #imageFrame2 {
        width: 300px;
        height: 150px;
        border: 2px dashed #ccc;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        margin-right: 20px; /* Add margin to create space between the frames */
    }

    #imagePreview1,
    #imagePreview2 {
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
    }

    .uploadIcon {
        font-size: 40px;
        cursor: pointer;
    }

    .edit-icon,
    .view-icon,
    .delete-icon {
        font-size: 15px; /* Adjust the icon size as needed */
        cursor: pointer;
        margin: 0 5px; /* Add margin for spacing */
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
    <div class="viewcategorytable">
            <div class="card">
                <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                    <div class="card-title ms-5">
                        <h1><strong>Main Category</strong></h1>
                    </div>
                    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                        <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                                Add Main Category
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th class=" border-bottom-0"><strong>Name</strong></th>
                                    <th class=" border-bottom-0"><strong>Image</strong></th>
                                    <th class=" border-bottom-0 text-end"><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                            @if(isset($category) && count($category) > 0)
                                @foreach($category as $c)
                                @php $counter++; @endphp
                                <tr>
                                    <td>{{ $counter }}.</td>
                                    <td>{{$c->c_name}}</td>
                                    <td><img src="/images/category/{{$c->c_img}}" style="width: 100px; height: 100px;"></td>
                                    <td class="text-end">
                                        <i class="bi bi-eye view-icon" onclick="redirectToViewPage('{{ $c->c_id }}', '{{ $c->c_name }}')"></i>
                                        <i class="bi bi-pencil edit-icon" onclick="toggleEdit('{{ $c->c_id }}', '{{ $c->c_name }}', '{{ $c->c_img }}')"></i>
                                        <i class="bi bi-trash delete-icon" onclick="toggleReject({{$c->c_id}})"></i>
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
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="addCategoryForm" method="post" enctype="multipart/form-data" action="/admin/addCategory">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCategoryModalLabel">New Main Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="categoryTitle">Category:</label>
                    <input id="categoryInput" name="maincat" class="form-control" type="text" placeholder="Enter new main category..." required>
                    <div class="mt-3"></div>
                    <div class="col-sm-6">
                        <label for="CategoryImage">Image:</label>
                        <div id="imageFrame1" onclick="openFileUploader(1)">
                            <span class="uploadIcon">+</span>
                            <input type="file" id="categoryimg1" name="img" class="d-none" onchange="previewImage(1)">
                            <img id="imagePreview1" alt="">
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

<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="editCategoryForm" method="post" enctype="multipart/form-data" action="/admin/editCategory">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCategoryModalLabel">Main Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="c_id" id="edit_id" readonly>
                    <label for="categoryTitle">Category:</label>
                    <input id="editmaincat" name="editmaincat" class="form-control" type="text" placeholder="Enter main category..." required>
                    <div class="mt-3"></div>
                        <div class="col-sm-6">
                            <label for="Category Image">Image:</label>
                            <div id="imageFrame2" onclick="openFileUploader(2)">
                                <span class="uploadIcon">+</span>
                                <input type="file" id="categoryimg2" name="img2" class="d-none" onchange="previewImage(2)">
                                <img id="imagePreview2" alt="">
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
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Job</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteCategory" method="post">
				<input type="hidden" name="c_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this main category?</span>
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
    function openFileUploader(type) {
        document.getElementById(`categoryimg${type}`).click();
    }


    function previewImage(type) {
        const input = document.getElementById(`categoryimg${type}`);
        const preview = document.getElementById(`imagePreview${type}`);
        const frame = document.getElementById(`imageFrame${type}`);
        const alertMessage = document.getElementById('alertMessage');
        const file = input.files[0];
        const reader = new FileReader();

        // Validate that the file is a JPG, JPEG, or PNG image
        if (file && /\.(jpg|jpeg|png)$/i.test(file.name)) {
            reader.onload = function (e) {
                preview.src = e.target.result;
                frame.classList.add('has-image');
                alertMessage.innerText = ''; // Clear any previous error message
            };

            reader.readAsDataURL(file);
        } else {
            // Clear preview and hide frame if not a valid image
            frame.classList.remove('has-image');
            preview.style.display = 'none';
            input.value = ''; // Clear the file input value
            alertMessage.innerText = 'Please select a valid JPG, JPEG, or PNG image file.';
        }
    }

    function redirectToViewPage(c_id, name) {
        console.log(c_id);
        window.location.href = '/admin/viewmaincat?c_id=' + c_id + '&name=' + encodeURIComponent(name);
    }

    function toggleEdit(id, name, img) {
        // Populate the modal fields
        $('#edit_id').val(id);
        $('#editmaincat').val(name);

        var categoryImageUrl = "{{ asset('images/category/') }}/" + img;

        $('#imagePreview2').attr('src', categoryImageUrl);

        $('#editCategoryModal').modal('toggle');
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