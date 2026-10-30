@extends('adminLayout')
@section('title', 'Characters')
@section('style')
<style>
    .viewcharacterstable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    #imageFrame1,
    #imageFrame2,
    #imageFrame3,
    #imageFrame4 {
        width: 150px;
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
    #imagePreview2,
    #imagePreview3,
    #imagePreview4 {
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
    @php $counter = 0; @endphp
    <div class="viewcharacterstable">
        <div class="card">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title ms-5">
                    <h1><strong>Character</strong></h1>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addCharacterModal">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                            Add Character
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                        <thead>
                            <tr>
                                <th><strong>No.</strong></th>
                                <th class=" border-bottom-0"><strong>Name</strong></th>
                                <th class=" border-bottom-0"><strong>Stream</strong></th>
                                <th class=" border-bottom-0"><strong>Average Salary(RM)</strong></th>
                                <th class=" border-bottom-0"><strong>Description</strong></th>
                                <th class=" border-bottom-0 text-center"><strong>Male</strong></th>
                                <th class=" border-bottom-0 text-center"><strong>Female</strong></th>
                                <th class=" border-bottom-0 text-center"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($characters) && count($characters) > 0)
                            @foreach($characters as $c)
                            @php $counter++; @endphp
                            <tr>
                                <td>{{ $counter }}.</td>
                                <td>{{$c->c_name}}</td>
                                <td>@if($c->c_stream == 0) All Stream @elseif($c->c_stream == 1) Science Stream @elseif($c->c_stream == 2) Art Stream @elseif($c->c_stream == 3) Sub Science @else Unknown Stream @endif</td>
                                <td>{{$c->c_aver_salary}}</td>
                                <td style="max-width: 700px; white-space: wrap; text-align: justify;">
                                {{$c->c_desc}}
                                </td>
                                <td class="text-center">
                                    <img src="/images/characters/{{$c->c_img_male}}" style="width: 100px; height:100px;">
                                </td>
                                <td class="text-center">
                                    <img src="/images/characters/{{$c->c_img_female}}" style="width: 100px; height:100px;">
                                </td>
                                <td>
                                    <i class="bi bi-pencil edit-icon" onclick="toggleEdit('{{ $c->c_id }}', '{{ $c->c_name }}', '{{ $c->c_aver_salary }}', '{{ $c->c_img_male }}', '{{ $c->c_img_female }}')"></i>
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
<div class="modal fade" id="addCharacterModal" tabindex="-1" aria-labelledby="addCharacterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="addCharacterForm" method="post" enctype="multipart/form-data" action="/admin/addCharacter">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCharacterModalLabel">Enter New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="CharacterTitle">Character Name:</label>
                    <input id="characterInput" name="title" class="form-control" type="text" placeholder="Title" required>

                    <label for="coursestream" class="col-sm-2 col-form-label">Stream</label>
                    <div class="col-sm-12">
                        <select name="coursestream" class="form-select streamselect" id="coursestream" required>
                            <option value="" selected disabled>Select Stream</option>
                            @foreach ($stream as $value => $s)
                                <option value="{{ $value }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3"></div>
                    <label for="CharacterTitle">Average Salary (RM):</label>
                    <input id="characterInput" name="aversal" class="form-control" type="number" placeholder="Salary" required>
                    <div class="mt-3"></div>
                    <div class="row">
                        <!-- Male Image -->
                        <div class="col-sm-6">
                            <label for="CharacterImageMale">Character Image (Male):</label>
                            <div id="imageFrame1" onclick="openFileUploader(1)">
                                <span class="uploadIcon">+</span>
                                <input type="file" id="characterimage1" name="img1" class="d-none" onchange="previewImage(1)">
                                <img id="imagePreview1" alt="">
                            </div>
                        </div>
                        <!-- Female Image -->
                        <div class="col-sm-6">
                            <label for="CharacterImageFemale">Character Image (Female):</label>
                            <div id="imageFrame2" onclick="openFileUploader(2)">
                                <span class="uploadIcon">+</span>
                                <input type="file" id="characterimage2" name="img2" class="d-none" onchange="previewImage(2)">
                                <img id="imagePreview2" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3"></div>
                    <label for="CharacterDescription">Description:</label>
                    <div class="col-sm-12">
                        <div id="kt_quil_1" style="height: 200px"></div>
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

<div class="modal fade" id="editCharacterModal" tabindex="-1" aria-labelledby="editCharacterModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="editCharacterForm" method="post" enctype="multipart/form-data" action="/admin/editCharacter">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCharacterModalLabel">Enter New Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="c_id" id="edit_id" readonly>
                    <label for="CharacterTitle">Character Name:</label>
                    <input name="edittitle" class="form-control" type="text" id="edittitle" placeholder="Title" required>

                    <label for="coursestream" class="col-sm-2 col-form-label">Stream</label>
                    <div class="col-sm-12">
                        <select name="editcoursestream" class="form-select editstreamselect" id="coursestream" required>
                            <option value="" selected disabled>Select Stream</option>
                            @foreach ($stream as $value => $s)
                                <option value="{{ $value }}">{{ $s }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="mt-3"></div>
                    <label for="CharacterTitle">Average Salary (RM):</label>
                    <input name="aversal" class="form-control" id="aversal" type="number" placeholder="Salary" required>
                    <div class="mt-3"></div>
                    <div class="row">
                        <!-- Male Image -->
                        <div class="col-sm-6">
                            <label for="CharacterImageMale">Character Image (Male):</label>
                            <div id="imageFrame3" onclick="openFileUploader(3)">
                                <span class="uploadIcon">+</span>
                                <input type="file" id="characterimage3" name="img3" class="d-none" onchange="previewImage(3)">
                                <img id="imagePreview3" alt="">
                            </div>
                        </div>
                        <!-- Female Image -->
                        <div class="col-sm-6">
                            <label for="CharacterImageFemale">Character Image (Female):</label>
                            <div id="imageFrame4" onclick="openFileUploader(4)">
                                <span class="uploadIcon">+</span>
                                <input type="file" id="characterimage4" name="img4" class="d-none" onchange="previewImage(4)">
                                <img id="imagePreview4" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="mt-3"></div>
                    <label for="CharacterDescription">Description:</label>
                    <div class="col-sm-12">
                        <div id="kt_quil_2" style="height: 200px">
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
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Character</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteCharacter" method="post">
				<input type="hidden" name="c_id" id="deactivate_id" value="">
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
    function openFileUploader(type) {
        document.getElementById(`characterimage${type}`).click();
    }


    function previewImage(type) {
        const input = document.getElementById(`characterimage${type}`);
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

    var quill = null;
    function toggleEdit(id, title, salary, imgMale, imgFemale) {
        // Populate the modal fields
        $('#edit_id').val(id);
        $('#edittitle').val(title);
        $('#aversal').val(salary);

        var maleImageUrl = "{{ asset('images/characters/') }}/" + imgMale;
        var femaleImageUrl = "{{ asset('images/characters/') }}/" + imgFemale;

        $('#imagePreview3').attr('src', maleImageUrl);
        $('#imagePreview4').attr('src', femaleImageUrl);

        if (!quill) {
            quill = new Quill('#kt_quil_2', {
                modules: {
                    toolbar: [
                        [{ header: [1, 2, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'bullet' }],
                        ['link']
                    ]
                },
                placeholder: 'Type your description here...',
                theme: 'snow'
            });

            quill.on('text-change', function() {
                var content = quill.root.innerHTML;

                content = content.replace(/<p>/g, '').replace(/<\/p>/g, '').replace(/"/g, '').replace(/"/g, '');

                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'editdesc';
                hiddenInput.value = content;

                var form = document.getElementById('editCharacterForm');
                form.appendChild(hiddenInput);
            });
        } else {
            // Clear existing content if any
            quill.setText('');
        }

        // Fetch description via AJAX
        $.ajax({
            url: '/admin/get-description',
            method: 'GET',
            data: { c_id: id },
            success: function(response) {
                // Set the content of the existing Quill editor
                quill.clipboard.dangerouslyPasteHTML(response);
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

        $('#editCharacterModal').modal('toggle');
    }


    function toggleReject(id) {
        console.log(id);
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }

   var KTQuilDemos = function () {

    var demo1 = function () {
    var quill = new Quill('#kt_quil_1', {
    modules: {
        toolbar: [
            [{
                header: [1, 2, false]
            }],
            ['bold', 'italic', 'underline'],
            [{ 'list': 'bullet' }],
            ['link']
        ]
    },
    placeholder: 'Type your text here...',
    theme: 'snow'
    });


    quill.on('text-change', function () {

    var content = quill.root.innerHTML;
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'desc';
    hiddenInput.value = content;

    var form = document.getElementById('addCharacterForm');
    form.appendChild(hiddenInput);
    });
    }

    return {
    init: function () {
    demo1();
    // demo2();
    }
    };
    }();

    jQuery(document).ready(function () {
    KTQuilDemos.init();
    });
</script>
@endsection
