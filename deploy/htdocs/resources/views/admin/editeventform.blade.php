@extends('adminLayout')
@section('title', 'Event')
@section('style')
<style>
    .editeventform {
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
            $e_id = request('id');
            $eventModel = new \App\Models\Event();
            $eventData = $eventModel->find($e_id);
        @endphp
    <div class="editeventform">
        <h1><strong>Edit Event</strong></h1>

        <form id="editeventForm" class="form-horizontal" method="post" enctype="multipart/form-data" action="/admin/editEvent">
        <div class="mb-3 row">
            <label for="eventtitle" class="col-sm-2 col-form-label">Event Title</label>
            <input type="hidden" name="e_id" id="e_id" value="{{ $eventData->e_id }}" readonly>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" id="eventtitle" value="{{ $eventData->e_title }}" required>
            </div>
        </div>

        <div id="imageContainer">
            <div class="mb-3 row">
                <label for="eventimage" class="col-sm-2 col-form-label">Event Images</label>
                <div class="col-sm-10">
                    @if(!empty($eventData->e_img))
                            @foreach(explode(',', $eventData->e_img) as $index => $image)
                                <div class="imageFrame mt-3" onclick="openFileUploader({{ $index + 1 }})">
                                    <span class="uploadIcon">+</span>
                                    <input type="file" name="img[]" id="eventimage_{{ $index + 1 }}" class="d-none" multiple onchange="previewImages({{ $index + 1 }})">
                                    <div id="imagePreview_{{ $index + 1 }}" class="d-flex flex-wrap imagePreview">
                                        <input type="hidden" name="existing_images[]" value="{{ $image }}">
                                        <img src="{{ asset('images/event/' . $image) }}" alt="Event Image" style="width: 100%; height: 100%;">
                                    </div>
                                </div>
                        @endforeach
                    @endif
                    <div id="clone_img"></div>
                    <button type="button" class="btn btn-secondary mt-2" onclick="addNewImage(1)">+ New Image</button>
                    <div id="alertMessage"></div>
                </div>
            </div>
        </div>

        <div class="mb-3 row">
                <label for="eventdescription" class="col-sm-2 col-form-label">Event Description</label>
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
    function openFileUploader(id) {
        document.getElementById('eventimage_' + id).click();
    }

    function previewImages(id) {
    const input = document.getElementById('eventimage_' + id);
    const previewContainer = document.getElementById('imagePreview_' + id);
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
    
    let frameId = {{ count(explode(',', $eventData->e_img)) }};
    function addNewImage() {
        // Create a new image frame
        frameId++;
        const newImageFrame = document.createElement('div');
        newImageFrame.innerHTML = `
        <div class="mb-3 row mt-3">
        <div class="">
            <div id="imageFrame_${frameId}" class="imageFrame" onclick="openFileUploader(${frameId})">
                <span class="uploadIcon">+</span>
                <input type="file" name="img[]" id="eventimage_${frameId}" class="d-none" multiple onchange="previewImages(${frameId})">
                <div id="imagePreview_${frameId}" class="d-flex flex-wrap imagePreview"></div>
            </div>
            <button type="button" class="btn mt-3 d-flex float-end" onclick="removeImageFrame(${frameId})"><i class="bi bi-trash delete-icon"></i></button>
        </div>
        </div>
    `;

    document.getElementById('clone_img').appendChild(newImageFrame);
    }

    function removeImageFrame(frameId) {
    const frameToRemove = document.getElementById(`imageFrame_${frameId}`).closest('.mb-3.row.mt-3');
    frameToRemove.parentNode.removeChild(frameToRemove);
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

    var initialContent = {!! json_encode($eventData->e_desc) !!};
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
    var form = document.getElementById('editeventForm');
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