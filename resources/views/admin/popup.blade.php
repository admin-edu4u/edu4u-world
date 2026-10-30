@extends('adminLayout')
@section('style')
<style>
    .blogform {
        max-width: 600px;
        height: auto;
        margin: 20px;
        padding: 20px;
        padding-bottom: 80px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
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

    #alertMessage {
        color: red;
        margin-top: 10px;
    }
</style>
@endsection

@section('content')
    @include('formMsg')
<div class="blogform">
        <h1><strong>Home Pop Up</strong></h1>

        <form id="blogForm" class="form-horizontal" method="post" enctype="multipart/form-data" action="/admin/updatePopUp">
        
        <input type="hidden" value="1" name="pu_id" readonly>
        <div id="imageContainer">
            <div class="mt-5 mb-3 row">
                <label for="eventimage" class="col-sm-2 col-form-label">Pop Up</label>
                <div class="col-sm-10">
                    <div id="imageFrame" class="imageFrame" onclick="openFileUploader(1)">
                        <span class="uploadIcon">+</span>
                        <input type="file" name="img" id="eventimage_1" class="d-none" multiple onchange="previewImages(1)">
                        <div id="imagePreview_1" class="d-flex flex-wrap imagePreview"></div>
                    </div>
                    <div id="alertMessage"></div>
                </div>
            </div>
            <div class="mt-5 mb-3 row">
                <label for="eventimage" class="col-sm-2 col-form-label">Url Link</label>
                <div class="col-sm-10 input-group">
                    <div class="input-group-prepend"><span class="input-group-text">https://</span></div>
                    <input type="text" name="pu_url" class="form-control" placeholder="Enter pop up url link...">
                </div>
            </div>
        </div>
        
        <div class="mt-3"></div>
            <button type="submit" class="btn btn-primary mt-2 float-end">Submit</button>
        </form>
</div>
@endsection

@section('script')
<script>
    function openFileUploader(id) {
        document.getElementById('eventimage_' + id).click();
    }

    function previewImages(id) {
    const input = document.getElementById('eventimage_' + id);
    const previewContainer = document.getElementById('imagePreview_' + id);
    const alertMessage = document.getElementById('alertMessage');
    const files = input.files;

    // Clear previous previews and messages
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
</script>
@endsection