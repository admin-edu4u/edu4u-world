@extends('adminLayout')
@section('style')
<style>
    .editk12form {
        max-width: 50%;
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
</style>
@endsection

@section('content')
    @include('formMsg')
        @php
            $int_id = request('int_id');
            $k12Model = new \App\Models\InternationalSchool();
            $k12Data = $k12Model->find($int_id);

            $k12Branch = \App\Models\InternationalSchoolBranch::where('int_id', $int_id)->where('int_b_status', 1)->get();
        @endphp
<div class="editk12form">
    <h1><strong>Edit University</strong></h1>
    <form id="editk12" class="form-horizontal" method="post" enctype="multipart/form-data" action="/admin/editK12">
    <input type="hidden" name="int_id" value="{{ $k12Data->int_id }}" readonly>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">K-12 Study</label>
        <div class="col-sm-10">
            <input type="text" name="k12name" class="form-control" id="k12name" placeholder="Enter K-12 study name..." value="{{ $k12Data->int_name }}" required>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Levels</label>
        <div class="col-sm-10">
            <select class="js-example-basic-single form-select k12level" name="k12level[]" multiple="multiple" required>
                <option value="1" {{ in_array('1', explode(',', $k12Data->int_level)) ? 'selected' : '' }}>Year 7</option>
                <option value="2" {{ in_array('2', explode(',', $k12Data->int_level)) ? 'selected' : '' }}>Year 8</option>
                <option value="3" {{ in_array('3', explode(',', $k12Data->int_level)) ? 'selected' : '' }}>Year 9</option>
                <option value="4" {{ in_array('4', explode(',', $k12Data->int_level)) ? 'selected' : '' }}>Year 10</option>
                <option value="5" {{ in_array('5', explode(',', $k12Data->int_level)) ? 'selected' : '' }}>Year 11</option>
                <option value="6" {{ in_array('6', explode(',', $k12Data->int_level)) ? 'selected' : '' }}>Year 12</option>
            </select>
        </div>
    </div>

    <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Logo</label>
            <div class="col-sm-10">
                <div class="imageFrame" onclick="openFileUploader(1)">
                    <span class="uploadIcon">+</span>
                    <input type="file" name="logo" id="universityfile_1" class="d-none" multiple onchange="previewFiles(1)">
                    <div id="imagePreview_1" class="d-flex flex-wrap imagePreview">
                        @if(!empty($k12Data->int_logo))
                            <img src="{{ asset('images/internationalschool/logo/' . $k12Data->int_logo) }}" alt="University Logo" style="width: 100%; height: 100%;">
                        @endif
                    </div>
                </div>
                <div id="alertMessage"></div>
            </div>
    </div>

    <div id="imageContainer">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Images</label>
                <div class="col-sm-10">
                    @if(!empty($k12Data->int_img))
                        @foreach(explode(',', $k12Data->int_img) as $index => $image)
                            <div class="imageFrame" onclick="openFileUploader({{ $index + 2 }})">
                                <span class="uploadIcon">+</span>
                                <input type="file" name="img[]" id="universityfile_{{ $index + 2 }}" class="d-none" multiple onchange="previewFiles({{ $index + 2 }})">
                                <div id="imagePreview_{{ $index + 2 }}" class="d-flex flex-wrap imagePreview">
                                    <img src="{{ asset('images/internationalschool/images/' . $image) }}" alt="University Image" style="width: 100%; height: 100%;">
                                </div>
                                <input type="hidden" name="saved_img[]" value="{{ $image }}">
                            </div>
                        @endforeach
                    @endif
                    <div id="clone_img"></div>
                    <button type="button" class="btn btn-secondary mt-2" onclick="addNewImage()">Add New Image</button>
                    <div id="alertMessage"></div>
                </div>
            </div>
    </div>

    <div class="mb-3 row">
        <label for="universitystudent" class="col-sm-2 col-form-label">Number of Students</label>
        <div class="col-sm-10">
            <input type="number" name="student" class="form-control" id="universitystudent" placeholder="Enter number of students..." value="{{ $k12Data->int_student }}" required>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universitystaff" class="col-sm-2 col-form-label">Number of Staffs</label>
        <div class="col-sm-10">
            <input type="number" name="staff" class="form-control" id="universitystaff" placeholder="Enter number of staffs..." value="{{ $k12Data->int_staff }}" required>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universityfoundedyear" class="col-sm-2 col-form-label">Year Founded</label>
        <div class="col-sm-10">
            <input type="number" name="year" class="form-control" id="universityfoundedyear" placeholder="Enter university founded year..." value="{{ $k12Data->int_year }}" required>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universityaddress" class="col-sm-2 col-form-label">University Address</label>
        <div class="col-sm-10">
        <textarea class="form-control" name="address" placeholder="Enter university address..." style="height: 150px; resize: none;" required>{{ $k12Data->int_addr }}</textarea>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universitystatecountry" class="col-sm-2 col-form-label"></label>
        <div class="col-sm-5">
            <select class="js-example-basic-single form-select state" name="state" required>
                <option value="" selected disabled>Select State</option>
                @foreach ($state as $s)
                    <option value="{{ $s->state_id }}" {{ $k12Data->int_state == $s->state_id ? 'selected' : '' }}>
                        {{ $s->state_name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-5">
            <select class="js-example-basic-single form-select country" name="country" required>
                <option value="" selected disabled>Select Country</option>
                @foreach ($country as $c)
                    <option value="{{ $c->country_no }}" {{ $k12Data->int_country == $c->country_no ? 'selected' : '' }}>
                        {{ $c->country_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universitybranch" class="col-sm-2 col-form-label">Has Branch</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="hasbranch" id="branch_yes" value="1" {{ $k12Data->int_has_branch == 1 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="hasbranch" id="branch_no" value="0" {{ $k12Data->int_has_branch == 0 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3">No</label>
            </div>
        </div>
    </div>

    <div id="branchContainer" class="branchContainer"style="display:none;">
        <hr>
        @php $updateBranchValue = ''; @endphp
        @if(count($k12Branch) > 0)
            @foreach ($k12Branch as $index => $kb)
            <div id="branchSet{{ $index + 1 }}" class="branchSet">
                <div class="mb-3 row">
                    <div class="col-sm-11"></div>
                    <div class="col-sm-1">@if ($index > 0)<button type="button" class="btn" onclick="removeBranch('{{ $kb->int_b_id }}')"><i class="bi bi-trash remove-branch-btn"></i></button>@endif</div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Branch {{ $index + 1 }}</label>
                    <div class="col-sm-10">
                        <textarea class="form-control" name="baddress{{ $index + 1 }}" placeholder="Enter branch {{ $index + 1 }} address..." style="height: 150px; resize: none;">{{ $kb->int_b_addr }}</textarea>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="universitystatecountry" class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-5">
                        <select class="js-example-basic-single form-select" name="bstate{{ $index + 1 }}">
                            <option value="" selected disabled>Select State</option>
                            @foreach ($state as $s)
                            <option value="{{ $s->state_id }}" {{ $kb->int_b_state == $s->state_id ? 'selected' : '' }}>{{ $s->state_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <select class="js-example-basic-single form-select" name="bcountry{{ $index + 1 }}">
                            <option value="" selected disabled>Select Country</option>
                            @foreach ($country as $c)
                            <option value="{{ $c->country_no }}" {{ $kb->int_b_country == $c->country_no ? 'selected' : '' }}>{{ $c->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr>
            </div>
            @php $updateBranchValue .= $kb->int_b_id . ','; @endphp
            @endforeach
        @endif
        @if(count($k12Branch) == 0)
        <div id="branchSet1" class="branchSet">
            <div class="mb-3 row">
                <div class="col-sm-11"></div>
                <div class="col-sm-1">@if ($index > 0)<button type="button" class="btn" onclick="premoveBranch()"><i class="bi bi-trash remove-branch-btn"></i></button>@endif</div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Branch 1</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="baddress1" placeholder="Enter branch 1 address..." style="height: 150px; resize: none;"></textarea>
                </div>
            </div>
            <div class="mb-3 row">
                <label for="universitystatecountry" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-5">
                    <select class="js-example-basic-single form-select" name="bstate1">
                        <option value="" selected disabled>Select State</option>
                        @foreach ($state as $s)
                        <option value="{{ $s->state_id }}">{{ $s->state_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-5">
                    <select class="js-example-basic-single form-select" name="bcountry1">
                        <option value="" selected disabled>Select Country</option>
                        @foreach ($country as $c)
                        <option value="{{ $c->country_no }}">{{ $c->country_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <hr>
        </div>
        @endif
    </div>

    <div id="branchbtn" class="mb-3 row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-10">
            <button class="btn btn-success float-end" type="button" id="addBranchBtn" style="display:none;">+ Branch</button>
        </div>
    </div>

    <div class="mb-3 row">
    <label for="universityhours" class="col-sm-2 col-form-label">Operating Hours</label>
    <div class="col-sm-10">
        <div class="input-group">
            @php
                $hours = explode('~', $k12Data->int_hours);
            @endphp
            <input type="time" name="hours1" class="form-control" value="{{ $hours[0] }}" required/>
            <span class="input-group-text">~</span>
            <input type="time" name="hours2" class="form-control" value="{{ $hours[1] }}" required/>
        </div>
    </div>
    </div>

    <div class="mb-3 row">
            <label for="eventdescription" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
                <div id="kt_quil_1" style="height: 200px">
            </div>
    </div>


    <div class="mt-3"></div>
        <div class="float-end">
            <button type="submit" class="btn btn-primary mt-2 float-end">Submit</button>
        </div>
        <input type="hidden" id="updateBranch" name="updateBranch" value="{{ rtrim($updateBranchValue, ',') }}">
        <input type="hidden" id="deletedBranch" name="deletedBranch" value="">
    </form>
    </div>
</div>
@endsection

@section('script')
<script>
    // Retrieve u_id value from your server-side code
    var int_id = "{{ $k12Data->int_id }}";

    function openFileUploader(id) {
        document.getElementById(`universityfile_${id}`).click();
        // Use AJAX to fetch the current values for the selected university
    $.ajax({
        url: `/getUniversityDetails/${int_id}`, // Replace with your actual route
        type: 'GET',
        success: function (data) {
            // Update the image preview with the fetched values
            updateImagePreview(id, data.logo);
        },
        error: function (error) {
            console.error(error);
        }
    });
    }

    function updateImagePreview(id, imageUrl) {
    const previewContainer = document.getElementById(`imagePreview_${id}`);

    // Clear previous previews
    previewContainer.innerHTML = '';

    // Create an img element for the initial image
    const initialImage = document.createElement('img');
    initialImage.src = imageUrl;
    initialImage.style.width = '100%';
    initialImage.style.height = '100%';

    // Append the initial image to the preview container
    previewContainer.appendChild(initialImage);

    // Open the file uploader
    document.getElementById(`universityfile_${id}`).click();
    }

    function previewFiles(id) {
        const input = document.getElementById(`universityfile_${id}`);
        const previewContainer = document.getElementById(`imagePreview_${id}`);
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

    function addNewImage() {
    // Get the number of existing images
    const existingImages = document.querySelectorAll('.imageFrame').length;

    // Determine the next available frameId
    const frameId = existingImages + 1;

    // Create a new image frame
    const newImageFrame = document.createElement('div');
    newImageFrame.innerHTML = `
        <div class="mb-3 row mt-3">
            <div class="">
                <div id="imageFrame_${frameId}" class="imageFrame" onclick="openFileUploader(${frameId})">
                    <span class="uploadIcon">+</span>
                    <input type="file" name="img[]" id="universityfile_${frameId}" class="d-none" multiple onchange="previewFiles(${frameId})">
                    <div id="imagePreview_${frameId}" class="d-flex flex-wrap imagePreview"></div>
                </div>
                <button type="button" class="btn btn-danger mt-3 d-flex float-end" onclick="removeImageFrame(${frameId})">Remove</button>
            </div>
        </div>
    `;

    // Append the new image frame
    document.getElementById('clone_img').appendChild(newImageFrame);
    }


    function removeImageFrame(frameId) {
    const frameToRemove = document.getElementById(`imageFrame_${frameId}`).closest('.mb-3.row.mt-3');
    frameToRemove.parentNode.removeChild(frameToRemove);
    }

    $(document).ready(function() {
        $('.k12level').select2();
        $('.type').select2();
        $('.state').select2();
        $('.country').select2();

        $('input[name="hasbranch"]').change(function () {
            if ($('#branch_yes').prop('checked')) {
                $('#branchContainer').show();
                $('#branchRow').show();
                $('#addBranchBtn').show();
            } else {
                $('#branchContainer').hide();
                $('#branchRow').hide();
                $('#addBranchBtn').hide();
            }
    });

        if ($('#branch_yes').prop('checked')) {
            $('#branch_yes').trigger('change');
        }
    });

    function removeBranch(branchId) {
        var deletedBranchInput = $('#deletedBranch');
        var currentValue = deletedBranchInput.val();
        if (currentValue === '') {
            deletedBranchInput.val(branchId);
        } else {
            deletedBranchInput.val(currentValue + ',' + branchId);
        }

        var updateBranchInput = $('#updateBranch');
        var updateBranchValue = updateBranchInput.val();
        if (updateBranchValue !== '') {
            var idsArray = updateBranchValue.split(',');

            var index = idsArray.indexOf(branchId);
            if (index !== -1) {
                idsArray.splice(index, 1);
            }
            updateBranchInput.val(idsArray.join(','));
        }

        var branchSet = event.target.closest('.branchSet');
        if (branchSet) {
            branchSet.remove();
        }
    }

    function premoveBranch() {
        var branchSet = event.target.closest('.branchSet');
        if (branchSet) {
            branchSet.remove();
        }
    }

    document.addEventListener("DOMContentLoaded", function() {

        document.getElementById("addBranchBtn").addEventListener("click", function() {

            var branchSet = document.getElementById("branchSet1");
            var clone = branchSet.cloneNode(true);

            var branchCounter = document.querySelectorAll('.branchSet').length + 1;
            var newBranchId = "branchSet" + branchCounter;
            clone.id = newBranchId;
            
            clone.querySelector('.col-form-label').textContent = 'Branch ' + branchCounter;
            clone.querySelector('[name="baddress1"]').value = '';
            clone.querySelector('[name="baddress1"]').setAttribute('name', 'baddress' + branchCounter);
            clone.querySelector('[name="baddress' + branchCounter + '"]').setAttribute('placeholder', 'Enter branch ' + branchCounter + ' address...');
            clone.querySelector('[name="bstate1"]').setAttribute('name', 'bstate' + branchCounter);
            clone.querySelector('[name="bcountry1"]').setAttribute('name', 'bcountry' + branchCounter);
            branchSet.parentNode.appendChild(clone);

            // Fetch countries and states for the cloned branch
            getCountries(clone.querySelector('[name="bcountry' + branchCounter + '"]'));
            getStates(clone.querySelector('[name="bstate' + branchCounter + '"]'));

            // Function to fetch countries
            function getCountries(countrySelector) {
                $.ajax({
                    type: 'GET',
                    url: '/admin/get-countries',
                    success: function (data) {
                        console.log(data);
                        // Clear existing options
                        $(countrySelector).empty().append('<option value="" selected disabled>Select Country</option>');
                        // Append countries to the select
                        $.each(data.list, function (index, country) {
                            $(countrySelector).append('<option value="' + country.country_id + '">' + country.country_name + '</option>');
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }

            // Function to fetch states
            function getStates(stateSelector) {
                $.ajax({
                    type: 'GET',
                    url: '/admin/get-states',
                    success: function (data) {
                        console.log(data);
                        // Clear existing options
                        $(stateSelector).empty().append('<option value="" selected disabled>Select State</option>');
                        // Append states to the select
                        $.each(data.list, function (index, state) {
                            $(stateSelector).append('<option value="' + state.state_id + '">' + state.state_name + '</option>');
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });
    });


    // Quilbox
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
            ['image', 'code-block']
        ]
    },
    placeholder: 'Type your text here...',
    theme: 'snow' // or 'bubble'
    });

    // Set the initial content of the Quill editor
    var initialContent = {!! json_encode($k12Data->int_desc) !!};
        quill.clipboard.dangerouslyPasteHTML(initialContent);

    // Listen for the 'text-change' event on Quill
    quill.on('text-change', function () {
    // Get the HTML content from the Quill editor
    var content = quill.root.innerHTML;

    // Remove the <p> tags from the content
    content = content.replace(/<p>/g, '').replace(/<\/p>/g, '').replace(/"/g, '').replace(/"/g, '');


    // Dynamically create an input field with the Quill content
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'desc';
    hiddenInput.value = content;

    // Check if the content has been modified
    hiddenInput.value = content !== initialContent ? JSON.stringify(content) : initialContent;

    // Append the input field to the form
    var form = document.getElementById('editk12');
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