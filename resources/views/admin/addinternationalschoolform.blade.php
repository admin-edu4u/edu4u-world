@extends('adminLayout')
@section('style')
<style>
    .addk12form {
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

    .remove-branch-btn {
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
<div class="addk12form">
    <h1><strong>Add New K-12 Study</strong></h1>

    <form id="addk12" class="form-horizontal" method="post" enctype="multipart/form-data" action="/admin/addK12">
    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">K-12 Study</label>
        <div class="col-sm-10">
            <input type="text" name="k12name" class="form-control" id="k12name" placeholder="Enter K-12 study name..." required>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Levels</label>
        <div class="col-sm-10">
            <select class="js-example-basic-single form-select k12level" name="k12level[]" multiple="multiple" required>
                <option value="1">Year 7</option>
                <option value="2">Year 8</option>
                <option value="3">Year 9</option>
                <option value="4">Year 10</option>
                <option value="5">Year 11</option>
                <option value="6">Year 12</option>
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universitylogo" class="col-sm-2 col-form-label">Logo</label>
        <div class="col-sm-10">
            <div id="imageFrame" class="imageFrame" onclick="openFileUploader(1)">
                <span class="uploadIcon">+</span>
                <input type="file" name="logo" id="universityfile_1" class="d-none" multiple onchange="previewFiles(1)">
                <div id="imagePreview_1" class="d-flex flex-wrap imagePreview"></div>
            </div>
            <div id="alertMessage"></div>
        </div>
    </div>

    <div id="imageContainer">
        <div class="mb-3 row">
            <label for="universityimage" class="col-sm-2 col-form-label">Images</label>
            <div class="col-sm-10">
                <div id="imageFrame" class="imageFrame" onclick="openFileUploader(2)">
                    <span class="uploadIcon">+</span>
                    <input type="file" name="img[]" id="universityfile_2" class="d-none" multiple onchange="previewFiles(2)">
                    <div id="imagePreview_2" class="d-flex flex-wrap imagePreview"></div>
                </div>
                <div id="clone_img"></div>
                <button type="button" class="btn btn-secondary mt-2" onclick="addNewImage(2)">Add New Image</button>
                <div id="alertMessage"></div>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universitystudent" class="col-sm-2 col-form-label">Number of Students</label>
        <div class="col-sm-10">
            <input type="number" name="student" class="form-control" id="universitystudent" placeholder="Enter number of students..." required>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universitystaff" class="col-sm-2 col-form-label">Number of Staffs</label>
        <div class="col-sm-10">
            <input type="number" name="staff" class="form-control" id="universitystaff" placeholder="Enter number of staffs..." required>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universityfoundedyear" class="col-sm-2 col-form-label">Year Founded</label>
        <div class="col-sm-10">
            <input type="number" name="year" class="form-control" id="universityfoundedyear" placeholder="Enter founded year..." required>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universityaddress" class="col-sm-2 col-form-label">Address</label>
        <div class="col-sm-10">
        <textarea class="form-control" name="address" placeholder="Enter address..." style="height: 150px; resize: none;" required></textarea>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universitystatecountry" class="col-sm-2 col-form-label"></label>
        <div class="col-sm-5">
            <select class="js-example-basic-single form-select country" name="country" id="country" required>
                <option value="" selected disabled>Select Country</option>
                @foreach ($country as $c)
                <option value="{{ $c->country_no }}">{{ $c->country_name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-5">
            <select class="js-example-basic-single form-select state" name="state" id="state" required>
                <option value="" selected disabled>Please Select Country</option>
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="universitybranch" class="col-sm-2 col-form-label">Has Branch</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="hasbranch" id="branch_yes" value="1" required>
                <label class="form-check-label mt-3">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="hasbranch" id="branch_no" value="0" required>
                <label class="form-check-label mt-3">No</label>
            </div>
        </div>
    </div>

    <div id="branchContainer" class="branchContainer" style="display:none;">
    <hr>
        <div id="branchSet">
            <div class="mb-3 row">
                <div class="col-sm-11"></div>
                <div class="col-sm-1"></div>
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
                        <select class="js-example-basic-single form-select" name="bcountry1" id="bcountry1">
                            <option value="" selected disabled>Select Country</option>
                            @foreach ($country as $c)
                            <option value="{{ $c->country_no }}">{{ $c->country_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-5">
                        <select class="js-example-basic-single form-select" name="bstate1" id="bstate1">
                            <option value="" selected disabled>Please Select Country</option>
                        </select>
                    </div>
            </div>
            <hr>
        </div>
    </div>
    <div id="branchbtn" class="mb-3 row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-10">
            <button class="btn btn-success float-end" id="addBranchBtn" type="button" style="display:none;">+ Branch</button>
        </div>
    </div>

    <div class="mb-3 row mt-3">
        <label for="universityhours" class="col-sm-2 col-form-label">Operating Hours</label>
        <div class="col-sm-10">
            <div class="input-group">
                <input type="time" name="hours1" class="form-control" required/>
                <span class="input-group-text">~</span>
                <input type="time" name="hours2" class="form-control" required/>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Description</label>
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

    let frameId = 2;
    function addNewImage() {
        // Create a new image frame
        frameId++;
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
                $('#addBranchBtn').show();
            } else {
                $('#branchContainer').hide();
                $('#addBranchBtn').hide();
            }
    });

        $(document).on('change', '#country', function () {
            var countryId = $(this).val();

            $('#state').empty();
            $('#state').append('<option value="" selected disabled>Select State</option>');

            if (countryId) {
                $.ajax({
                    type: 'GET',
                    url: '/admin/get-states-by-country',
                    data: { countryId: countryId },
                    success: function (data) {
                        console.log(data)
                        $.each(data.list, function (index, states) {
                            $('#state').append('<option value="' + states.state_id + '">' + states.state_name + '</option>');
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });

        $(document).on('change', '[id^=bcountry]', function () {
            var countryId = $(this).val();
            var branchId = $(this).attr('id').replace(/^\D+/g, '');

            $('#bstate'+branchId+'').empty();
            $('#bstate'+branchId+'').append('<option value="" selected disabled>Select State</option>');

            if (countryId) {
                $.ajax({
                    type: 'GET',
                    url: '/admin/get-states-by-country',
                    data: { countryId: countryId },
                    success: function (data) {
                        console.log(data)
                        $.each(data.list, function (index, states) {
                            $('#bstate'+branchId+'').append('<option value="' + states.state_id + '">' + states.state_name + '</option>');
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
            }
        });

    });

    document.addEventListener("DOMContentLoaded", function() {
        var branchCounter = 1;

        document.getElementById("addBranchBtn").addEventListener("click", function() {
            var branchSet = document.getElementById("branchSet");
            var clone = branchSet.cloneNode(true);

            branchCounter++;
            
            clone.querySelector('.col-form-label').textContent = 'Branch ' + branchCounter;
            clone.querySelector('[name="baddress1"]').setAttribute('name', 'baddress' + branchCounter);
            clone.querySelector('[name="baddress' + branchCounter + '"]').setAttribute('placeholder', 'Enter branch ' + branchCounter + ' address...');
            clone.querySelector('[name="bstate1"]').setAttribute('name', 'bstate' + branchCounter);
            clone.querySelector('[name="bstate' + branchCounter + '"]').setAttribute('id', 'bstate' + branchCounter);
            clone.querySelector('[name="bcountry1"]').setAttribute('name', 'bcountry' + branchCounter);
            clone.querySelector('[name="bcountry' + branchCounter + '"]').setAttribute('id', 'bcountry' + branchCounter);

            var removeBtn = document.createElement("button");
            removeBtn.innerHTML = '<i class="bi bi-trash remove-branch-btn"></i>';
            removeBtn.classList.add("btn");
            removeBtn.onclick = function() {
                // Remove the cloned branch set when the remove button is clicked
                clone.remove();
            };
            var colDiv = clone.querySelector('.col-sm-1');

            // Append remove button inside the div
            colDiv.appendChild(removeBtn);

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
    var form = document.getElementById('addk12');
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