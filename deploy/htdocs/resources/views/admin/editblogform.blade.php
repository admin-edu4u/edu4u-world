@extends('adminLayout')
@section('title', 'Event')
@section('style')
<style>
    .editblogform {
        max-width: 600px;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
        height:auto;
    }

    .imageFrame {
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

    .imagePreview {
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

    .delete-icon {
        font-size: 15px;
        cursor: pointer;
        margin: 0 5px;
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
        @php
            $b_id = request('id');
            $blogModel = new \App\Models\Blog();
            $blogData = $blogModel->find($b_id);
            $image = $blogData->b_img;
        @endphp
    <div class="editblogform">
        <h1><strong>Edit Blog</strong></h1>

        <form id="editblogForm" class="form-horizontal" method="post" enctype="multipart/form-data" action="/admin/editBlog">
        <div class="mb-3 row">
            <label for="blogtitle" class="col-sm-2 col-form-label">Blog Title</label>
            <input type="hidden" name="b_id" id="b_id" value="{{ $blogData->b_id }}" readonly>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" id="blogtitle" value="{{ $blogData->b_title }}" required>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Category</label>
            <div class="col-sm-10">
                <select class="form-select blogcat" name="blogcat" required>
                    <option disabled selected value="">Please select blog category...</option>
                    @foreach ($blogcat as $bc)
                    <option value="{{ $bc->bc_id }}" {{ $blogData->b_cat_id == $bc->bc_id ? 'selected' : '' }}>{{ $bc->bc_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div id="imageContainer">
            <div class="mb-3 row">
                <label for="blogimage" class="col-sm-2 col-form-label">Blog Image</label>
                <div class="col-sm-10">
                    <div class="imageFrame mt-3" onclick="openFileUploader()">
                        <span class="uploadIcon">+</span>
                        <input type="file" name="img" id="blogimage" class="d-none" multiple onchange="previewImage()">
                        <div id="imagePreview" class="d-flex flex-wrap imagePreview">
                            <img src="{{ asset('images/blogs/' . $image) }}" alt="Blog Image" style="width: 100%; height: 100%;">
                        </div>
                    </div>
                    <div id="alertMessage"></div>
                </div>
            </div>
        </div>

        <div class="mb-3 row">
                <label for="blogdescription" class="col-sm-2 col-form-label">Blog Description</label>
                <div class="col-sm-10">
                    <div id="kt_quil_1" style="height: 200px">
                    </div>
                </div>

            <div class="mt-3"></div>
            <div class="float-end">
                <button type="submit" class="btn btn-primary mt-2 float-end">Submit</button>
            </div>
        </form>
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
    const previewContainer = document.getElementById('imagePreview');
    const alertMessage = document.getElementById('alertMessage');
    const files = input.files;

    previewContainer.innerHTML = '';
    alertMessage.innerText = '';

    // Check if all files have valid extensions
    const invalidFiles = Array.from(files).filter(file => !/\.(jpg|jpeg|png)$/i.test(file.name));

    if (invalidFiles.length > 0) {
        alertMessage.innerText = 'Skipped non-JPG, JPEG, or PNG files: ' + invalidFiles.map(file => file.name).join(', ');
        // Clear input value to prevent processing invalid files
        input.value = '';
        return;
    }

    // Preview valid image files
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();

        reader.onload = function (e) {
            const imgElement = document.createElement('img');
            imgElement.src = e.target.result;
            imgElement.style.width = '100%';
            imgElement.style.height = '100%';
            previewContainer.appendChild(imgElement);
        };

        reader.readAsDataURL(file);
    }
    }
    //////////////////////////////////////////

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
    placeholder: 'Type your text here...',
    theme: 'snow' // or 'bubble'
    });

    var initialContent = {!! json_encode($blogData->b_content) !!};
        quill.clipboard.dangerouslyPasteHTML(initialContent);

    // Listen for the 'text-change' event on Quill
    quill.on('text-change', function () {
    // Get the HTML content from the Quill editor
    var content = quill.root.innerHTML;

    // Remove the <p> tags from the content
    content = content.replace(/<p>/g, '').replace(/<\/p>/g, '');

    // Dynamically create an input field with the Quill content
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'desc';
    hiddenInput.value = content;

    // Append the input field to the form
    var form = document.getElementById('editblogForm');
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
</script>
@endsection