@extends('adminLayout')
@section('title', 'Blog')
@section('style')
<style>
    .blogform {
        max-width: 600px;
        height: 700px;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    #imageFrame {
        max-width: 100%;
        height: 200px;
        border: 2px dashed #ccc;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
    }

    #imagePreview {
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

    #alertMessage {
        color: red;
        margin-top: 10px;
    }

    .quill-editor-container {
        height: 160px;
        max-height: 160px; /* Adjust the height as needed */
        overflow-y: auto;
    }

    .blogtable {
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .description {
        width: 200px; /* Adjust the width as needed */
    }

    .alert-success{
        margin: 20px;
        padding: 20px;
        max-width: 100%;
    }

    .alert-danger{
        margin: 20px;
        padding: 20px;
        max-width: 100%;
    }

    .btn-outline-danger:hover .fas {
        color: white;
    }

    .pin-icon,
    .edit-icon,
    .delete-icon {
        font-size: 15px;
        cursor: pointer;
        margin: 0 5px;
    }

    .pin-icon {
        color: white;
        background-color: orange;
        border: 1px solid orange;
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

    tr.pinned {
        background-color: #f0f0f0;
    }
</style>
@endsection

@section('content')
    @php $counter = 0; @endphp
    <div class="blogform">
        <h1><strong>Blog</strong></h1>

        <form id="blogForm" class="form-horizontal" method="post" enctype="multipart/form-data" action="/admin/addBlog">
        <div class="mb-3 row">
            <label for="blogtitle" class="col-sm-2 col-form-label">Blog Title</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" id="blogtitle" placeholder="Enter blog title..." required>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Category</label>
            <div class="col-sm-10">
                <select class="form-select blogcat" name="blogcat" required>
                    <option disabled selected value="">Please select blog category...</option>
                    @foreach ($blogcat as $bc)
                    <option value="{{ $bc->bc_id }}">{{ $bc->bc_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="blogimage" class="col-sm-2 col-form-label">Blog Image</label>
            <div class="col-sm-10">
                <div id="imageFrame" onclick="openFileUploader()">
                    <span class="uploadIcon">+</span>
                    <input type="file" id="blogimage" name="img" class="d-none" onchange="previewImage()">
                    <img id="imagePreview" alt="">
                </div>
                <div id="alertMessage"></div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="eventdescription" class="col-sm-2 col-form-label">Blog Description</label>
            <div class="col-sm-10">
                <div id="kt_quil_1" style="height: 200px">
                </div>
            </div>
        </div>
        
        <div class="mt-3"></div>
            <button type="submit" class="btn btn-primary mt-2 float-end">Submit</button>
        </form>
    </div>

    @include('formMsg')
    <div class="blogtable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Blogs</h3>
            </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th class=" border-bottom-0"><strong>Title</strong></th>
                                    <th class=" border-bottom-0"><strong>Category</strong></th>
                                    <th class=" border-bottom-0"><strong>Description</strong></th>
                                    <th class=" border-bottom-0"><strong>Image</strong></th>
                                    <th class=" border-bottom-0"><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($blog) && count($blog) > 0)
                                @foreach($blog as $b)
                                @php $counter++; @endphp
                                <tr>
                                    <td>{{ $counter }}.</td>
                                    <td>{{$b->b_title}}</td>
                                    <td>
                                        @foreach($blogcat as $bc)
                                            @if($b->b_cat_id == $bc->bc_id)
                                                {{ $bc->bc_name }}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{!! substr($b->b_content, 0, 50) !!}...</td>
                                    <td><img src="/images/blogs/{{$b->b_img}}" style="width: 100px; height:100px;"></td>
                                    <td>
                                        @if($b->b_is_star == 1)
                                        <i class="bi bi-star-fill pin-icon" onclick="togglePin({{$b->b_id}})"></i>
                                        @else
                                        <i class="bi bi-star pin-icon" onclick="togglePin({{$b->b_id}})"></i>
                                        @endif
                                        <i class="bi bi-pencil edit-icon" onclick="redirectToEditPage('{{ $b->b_id }}')"></i>
                                        <i class="bi bi-trash delete-icon" onclick="toggleReject({{$b->b_id}})"></i>
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

    </div>
    </div>
@endsection

@section('modal')
<div class="modal fade text-left" id="pinModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-primary">
				<h5 class="modal-title text-white" id="myModalLabel140">Pin or Unpin Blog</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/pinBlog" method="post">
				<input type="hidden" name="b_id" id="pin_id" value="">
				<div class="modal-body">
					<span>Update blog status</span>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-secondary" >Yes</button>
					<button type="button" class="btn" data-bs-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade text-left" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Blog</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteBlog" method="post">
				<input type="hidden" name="b_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this blog?</span>
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
    function openFileUploader() {
        document.getElementById('blogimage').click();
    }

    function previewImage() {
        const input = document.getElementById('blogimage');
        const preview = document.getElementById('imagePreview');
        const frame = document.getElementById('imageFrame');
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

   // Class definition
   var KTQuilDemos = function () {

    // Private functions
    var demo1 = function () {
    var quill = new Quill('#kt_quil_1', {
        modules: {
            toolbar: [
                [{
                    header: [1, 2, false]
                }],
                ['bold', 'italic', 'underline'],
                [{ 'list': 'bullet' }]
            ]
        },
        placeholder: 'Enter blog description...',
        theme: 'snow' // or 'bubble'
    });

    // Listen for the 'text-change' event on Quill
    quill.on('text-change', function () {
        // Get the HTML content from the Quill editor
        var content = quill.root.innerHTML;

         // Remove the <p> tags from the content
         content = content.replace(/<p>/g, '').replace(/<\/p>/g, '');

        // Dynamically create an input field with the Quill content
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'content';
        hiddenInput.value = content;

        // Append the input field to the form
        var form = document.getElementById('blogForm');
        form.appendChild(hiddenInput);
    });
    }

    return {
    // public functions
    init: function () {
        demo1();
    }
    };
    }();

    jQuery(document).ready(function () {
    KTQuilDemos.init();
    });

    function togglePin(id) {
	    if(id != undefined && id != null) {
		    $('#pin_id').val(id);
		    $('#pinModal').modal('toggle');
	    }
    }

    function redirectToEditPage(id) {
        window.location.href = '/admin/editblogform?id=' + id;
    }

    function toggleReject(id) {
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }
</script>
@endsection