@extends('adminLayout')
@section('title', 'Event')
@section('style')
<style>
    .eventform {
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

    #alertMessage {
        color: red;
        margin-top: 10px;
    }

    .quill-editor-container {
        height: 160px;
        max-height: 160px; /* Adjust the height as needed */
        overflow-y: auto;
    }

    .eventtable {
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
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
<div class="eventform">
    <h1><strong>Event</strong></h1>

    <form id="eventForm" class="form-horizontal" method="post" enctype="multipart/form-data" action="/admin/addEvent">
    <div class="mb-3 row">
        <label for="eventtitle" class="col-sm-2 col-form-label">Event Title</label>
        <div class="col-sm-10">
            <input type="text" name="title" class="form-control" id="eventtitle" placeholder="Enter event title..." required>
        </div>
    </div>

    <div id="imageContainer">
        <div class="mb-3 row">
            <label for="eventimage" class="col-sm-2 col-form-label">Event Images</label>
            <div class="col-sm-10">
                <div id="imageFrame" class="imageFrame" onclick="openFileUploader(1)">
                    <span class="uploadIcon">+</span>
                    <input type="file" name="img[]" id="eventimage_1" class="d-none" multiple onchange="previewImages(1)">
                    <div id="imagePreview_1" class="d-flex flex-wrap imagePreview"></div>
                </div>
                <div id="clone_img"></div>
                <button type="button" class="btn btn-secondary mt-2" onclick="addNewImage(1)">Add New Image</button>
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

@include('formMsg')
@php $counter = 0; @endphp
<div class="eventtable">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Events</h3>
            </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th class=" border-bottom-0"><strong>Title</strong></th>
                                    <th class=" border-bottom-0"><strong>Description</strong></th>
                                    <th class=" border-bottom-0"><strong>Image</strong></th>
                                    <th class=" border-bottom-0"><strong>Action</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(isset($event) && count($event) > 0)
                                @foreach($event as $e)
                                @php
                                $img = explode(',', $e->e_img);
                                $counter++;
                                @endphp
                                <tr>
                                    <td>{{ $counter }}.</td>
                                    <td>{{$e->e_title}}</td>
                                    <td>{!! $e->e_desc !!}</td>
                                    <td><img src="/images/event/{{$img[0]}}" style="width: 100px; height:100px;"></td>
                                    <td>
                                        <i class="bi bi-pencil edit-icon" onclick="redirectToEditPage('{{ $e->e_id }}')"></i>
                                        <i class="bi bi-trash delete-icon" onclick="toggleReject({{$e->e_id}})"></i>
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
<div class="modal fade text-left" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Event</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteEvent" method="post">
				<input type="hidden" name="e_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this event?</span>
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

    
    let frameId = 1;
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
    var form = document.getElementById('eventForm');
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

    function redirectToEditPage(id) {
        window.location.href = '/admin/editeventform?id=' + id;
    }

    function toggleReject(id) {
        console.log("123");
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }
</script>
@endsection