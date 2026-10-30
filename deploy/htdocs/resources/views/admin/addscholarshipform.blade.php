@extends('adminLayout')
@section('title', 'Scholarship')
@section('style')
<style>
    .addscholarshipform {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    #imageFrame {
        max-width: 50%;
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

    .removesetbtn {
        border-radius: 5px;
        border-color: white;
    }

    .removesetbtn i {
        color:white;
    }
</style>
@endsection

@section('content')
    @include('formMsg')
<div class="addscholarshipform">
    <h1><strong>Add Scholarship</strong></h1>

    <form id="addscholarship" class="form-horizontal" method="post" enctype="multipart/form-data" action="/admin/addScholarship">
    <div id="section1">

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Is Corporate</label>
            <div class="col-sm-10">
                <div class="form-check form-check-inline">
                    <label class="form-check-label mt-3" for="corporate_yes">Yes</label>
                    <input class="form-check-input mt-3" type="radio" name="iscorporate" id="iscorporate_yes" value="1">
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label mt-3" for="corporate_no">No</label>
                    <input class="form-check-input mt-3" type="radio" name="iscorporate" id="iscorporate_no" value="0">
                </div>
            </div>
        </div>

        <div id="corporateContainer" style="display:none;">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Company Name</label>
                <div class="col-sm-10">
                    <input type="text" name="companyname" class="form-control" id="companyname" placeholder="Enter company name...">
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Company Logo</label>
                <div class="col-sm-10">
                    <div id="imageFrame" onclick="openFileUploader()">
                        <span class="uploadIcon">+</span>
                        <input type="file" id="companylogo" name="logo" class="d-none" onchange="previewImage()">
                        <img id="imagePreview" alt="">
                    </div>
                    <div id="alertMessage"></div>
                </div>
            </div>
        </div>

        <div id="universitynameContainer" style="display:none;">
            <div class="mb-3 row">
                <label for="university" class="col-sm-2 col-form-label">University</label>
                <div class="col-sm-10">
                    <select class="js-example-basic-single form-select uni" name="uni">
                        <option value="" selected disabled>Select University...</option>
                        @foreach ($university as $u)
                            <option value="{{ $u->u_id }}">{{ $u->u_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="title" class="col-sm-2 col-form-label">Title</label>
            <div class="col-sm-10">
                <input type="text" name="title" class="form-control" id="title" placeholder="Enter scholarship title..." required>
            </div>
        </div>

            <div class="mb-3 row">
                <label for="level" class="col-sm-2 col-form-label">Level</label>
                <div class="col-sm-10">
                    <select class="js-example-basic-single form-select schlv" multiple="multiple" name="schlv[]" id="schlv">
                            @foreach ($level as $value => $l)
                            <option value="{{ $value }}">{{ $l }}</option>
                            @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="studyfield" class="col-sm-2 col-form-label">Study Field</label>
                <div class="col-sm-10">
                <select class="form-select studyfield" name="studyfield[]" multiple="multiple" id="studyfield">
                    <option value="">Select All</option>
                    @foreach ($subcategory as $sc)
                    <option value="{{ $sc->sc_id }}">{{ $sc->sc_name }}</option>
                    @endforeach
                </select>
                </div>
            </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Interview</label>
            <div class="col-sm-10">
                <div class="form-check form-check-inline">
                    <label class="form-check-label mt-3" for="interview_yes">Yes</label>
                    <input class="form-check-input mt-3" type="radio" name="interview" id="interview_yes" value="1">
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label mt-3" for="interview_no">No</label>
                    <input class="form-check-input mt-3" type="radio" name="interview" id="interview_no" value="0">
                </div>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Type</label>
            <div class="col-sm-10">
            <select class="form-select schtype" name="schtype[]" multiple="multiple" id="schtype" required>
                @foreach (\App\Models\Scholarship::typeArr() as $value => $type)
                <option value="{{ $value }}">{{ $type }}</option>
                @endforeach
            </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Coverage</label>
            <div class="col-sm-10">
            <select class="form-select schcover" name="schcover[]" multiple="multiple" id="schcover" required>
                @foreach (\App\Models\Scholarship::coverageArr() as $value => $coverage)
                <option value="{{ $value }}">{{ $coverage }}</option>
                @endforeach
            </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Requirement</label>
            <div class="col-sm-10">
            <select class="form-select schreq" name="schreq[]" multiple="multiple" id="schreq" required>
                @foreach (\App\Models\Scholarship::requirementArr() as $value => $requirement)
                <option value="{{ $value }}">{{ $requirement }}</option>
                @endforeach
            </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="details" class="col-sm-2 col-form-label">Details</label>
            <div class="col-sm-10">
                <div id="kt_quil_1" style="height: 200px"></div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="criteria" class="col-sm-2 col-form-label">Criteria</label>
            <div class="col-sm-10">
                <div id="kt_quil_2" style="height: 200px"></div>
            </div>
        </div>

        <div class="mb-3 row">
            <label for="application" class="col-sm-2 col-form-label">Application</label>
            <div class="col-sm-10">
                <div id="kt_quil_3" style="height: 200px"></div>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-10">
            <div class="float-end">
                <div class="mt-3"></div>
                <button type="button" class="btn btn-primary mt-2 float-end" onclick="showSection2()">Next</button>
            </div>
            </div>
        </div>
    </div>

    <div id="section2" style="display:none;">
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Scholarship Type</label>
            <div class="col-sm-10">
                <div class="form-check form-check-inline">
                    <label class="form-check-label mt-3" for="ismanual_yes">By Grade</label>
                    <input class="form-check-input mt-3" type="radio" name="ismanual" id="ismanual_no" value="0">
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label mt-3" for="ismanual_no">Manual</label>
                    <input class="form-check-input mt-3" type="radio" name="ismanual" id="ismanual_yes" value="1">
                </div>
            </div>
        </div>

        <div id="manualContainer" style="display:none;">
            <div class="mb-3 row">
                <label for="manual" class="col-sm-2 col-form-label">Description</label>
                <div class="col-sm-10">
                    <div id="kt_quil_4" style="height: 200px"></div>
                </div>
            </div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Amount</label>
                <div class="col-sm-3">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label mt-3" for="isamount_yes">Fixed Amount</label>
                        <input class="form-check-input mt-3" type="radio" name="isamountm" id="isamountm_amt" value="1">
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label mt-3" for="isamount_no">Percentage %</label>
                        <input class="form-check-input mt-3" type="radio" name="isamountm" id="isamountm_perc" value="0">
                    </div>
                </div>
                <div id="mamount" class="col-sm-2" style="display:none;">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                            <input type="number" class="form-control" name="mamount" placeholder="Enter amount..." />
                    </div>
                </div>
                <div id="mpercentage" class="col-sm-2" style="display:none;">
                    <div class="input-group">
                        <input type="number" class="form-control" name="mpercentage" placeholder="Enter percentage..." />
                            <div class="input-group-prepend"><span class="input-group-text">%</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div id="amountContainer" style="display:none;">
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Amount</label>
                <div class="col-sm-10">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label mt-3" for="isamount_yes">Fixed Amount</label>
                        <input class="form-check-input mt-3" type="radio" name="isamount" id="isamount_yes" value="1">
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label mt-3" for="isamount_no">Percentage %</label>
                        <input class="form-check-input mt-3" type="radio" name="isamount" id="isamount_no" value="0">
                    </div>
                </div>
            </div>
        </div>

        <div id="levelContainer" style="display:none;">
            <div id="qlevelContainer1">
                <hr>
                <div class="mb-3 row">
                    <h2 class="col-sm-11">Qualification 1</h2>
                    <div class="col-sm-1 removelevelbtn"></div>
                </div>
                <div class="mb-3 row">
                    <label for="level" class="col-sm-1 col-form-label">Level</label>
                    <div class="col-sm-6">
                        <select class="js-example-basic-single form-select level" name="level1" id="level1">
                            <option value="" selected disabled>Please select level...</option>
                            @foreach ($level as $value => $l)
                            <option value="{{ $value }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row" id="sgradeRow1">
                    <label for="grade" class="col-sm-1 col-form-label">Grade Count</label>
                    <div class="col-sm-1">
                        <input class="form-control" type="number" name="count1[]" id="count1" placeholder="No...">
                    </div>
                    <div class="col-sm-1">
                        <select class="js-example-basic-single form-select grade1" name="grade1[]" id="grade1">
                            <option value="" selected disabled>Select Grade...</option>
                        </select>
                    </div>
                    <!-- <label for="isabove" class="col-sm-2 col-form-label text-center">Above?<input class="form-check-input ms-3" type="checkbox" name="isabove1[]" id="isabove1" value="1">Yes<input class="form-check-input ms-3" type="checkbox" name="isabove1[]" id="isabove1" value="0">No</label> -->
                    <div class="col-sm-2">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                            <input type="number" class="form-control" name="amount1[]" id="amount1" placeholder="Enter amount..." />
                        </div>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-2"><button type="button" class="btn btn-success" id="addgradebtn1">+ Grade</button></div>
                </div>
            </div>

            <div class="mb-3 row">
                <div class="col-sm-10"></div>
                <div class="col-sm-2"><button type="button" class="btn btn-secondary float-end" id="addqlevelbtn">+ Qualification</button></div>
            </div>
        </div>

        <div id="percentageContainer" style="display:none;">
            <div id="plevelContainer1" class="plevelContainer">
                <hr>
                <div class="mb-3 row">
                    <h2 class="col-sm-11">Qualification 1</h2>
                    <div class="col-sm-1 removelevelbtn"></div>
                </div>
                <div class="mb-3 row">
                    <label for="level" class="col-sm-1 col-form-label">Level</label>
                    <div class="col-sm-6">
                        <select class="js-example-basic-single form-select plevel" name="plevel1" id="plevel1">
                            <option value="" selected disabled>Please select level...</option>
                            @foreach ($level as $value => $l)
                            <option value="{{ $value }}">{{ $l }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-3 row" id="spgradeRow1">
                    <label for="grade" class="col-sm-1 col-form-label">Grade Count</label>
                    <div class="col-sm-1">
                        <input class="form-control" type="number" name="pcount1[]" id="pcount1" placeholder="No...">
                    </div>
                    <div class="col-sm-1">
                        <select class="js-example-basic-single form-select pgrade1" name="pgrade1[]" id="pgrade1">
                            <option value="" selected disabled>Select Grade...</option>
                        </select>
                    </div>
                    <label for="isabove" class="col-sm-2 col-form-label text-center">Above?<input class="form-check-input ms-3" type="checkbox" name="pisabove1[]" id="pisabove1" value="1">Yes<input class="form-check-input ms-3" type="checkbox" name="pisabove1[]" id="pisabove1" value="0">No</label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="number" class="form-control" name="pamount1[]" id="pamount1" placeholder="Enter percentage..." />
                            <div class="input-group-prepend"><span class="input-group-text">%</span></div>
                        </div>
                    </div>
                    <div class="col-sm-1"></div>
                </div>
                <div class="mb-3 row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-2"><button type="button" class="btn btn-success" id="paddgradebtn1">+ Grade</button></div>
                </div>
            </div>

            <div class="mb-3 row justify-content-end">
                <div class="col-sm-10"></div>
                <div class="col-sm-2"><button type="button" class="btn btn-secondary float-end" id="paddqlevelbtn">+ Qualification</button></div>
            </div>
        </div>

        <hr>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-10">
                <div class="float-end">
                    <div class="mt-3"></div>
                    <button type="button" class="btn btn-secondary mt-2 float-start me-3" onclick="showSection1()">Back</button>
                    <button type="submit" class="btn btn-primary mt-2 float-end">Submit</button>
                </div>
            </div>
        </div>
    </div>
    </form>
</div>
@endsection

@section('script')
<script>
    function openFileUploader() {
        document.getElementById('companylogo').click();
    }

    function previewImage() {
        const input = document.getElementById('companylogo');
        const preview = document.getElementById('imagePreview');
        const frame = document.getElementById('imageFrame');
        const alertMessage = document.getElementById('alertMessage');
        const file = input.files[0];
        const reader = new FileReader();

        if (file && /\.(jpg|jpeg|png)$/i.test(file.name)) {
            reader.onload = function (e) {
                preview.src = e.target.result;
                frame.classList.add('has-image');
                alertMessage.innerText = '';
            };

            reader.readAsDataURL(file);
        } else {
            frame.classList.remove('has-image');
            preview.style.display = 'none';
            input.value = '';
            alertMessage.innerText = 'Please select a valid JPG, JPEG, or PNG image file.';
        }
    }

    function showSection1() {
        document.getElementById('section1').style.display = 'block';
        document.getElementById('section2').style.display = 'none';
    }

    function showSection2() {
        document.getElementById('section1').style.display = 'none';
        document.getElementById('section2').style.display = 'block';
    }

    $(document).ready(function() {
        $('input[name="iscorporate"]').change(function () {
                if ($('#iscorporate_yes').prop('checked')) {
                    $('#universitynameContainer').hide();
                    $('#corporateContainer').show();
                } else {
                    $('#universitynameContainer').show();
                    $('#corporateContainer').hide();
                }
        });
        
        $('input[name="ismanual"]').change(function () {
                if ($('#ismanual_yes').prop('checked')) {
                    $('#amountContainer').hide();
                    $('#levelContainer').hide();
                    $('#percentageContainer').hide();
                    $('#manualContainer').show();
                } else {
                    $('#amountContainer').show();
                    $('#manualContainer').hide();
                }
        });

        $('input[name="isamount"]').change(function () {
                if ($('#isamount_yes').prop('checked')) {
                    $('#levelContainer').show();
                    $('#percentageContainer').hide();
                } else {
                    $('#levelContainer').hide();
                    $('#percentageContainer').show();
                }
        });

        $('input[name="isamountm"]').change(function () {
                if ($('#isamountm_amt').prop('checked')) {
                    $('#mamount').show();
                    $('#mpercentage').hide();
                } else {
                    $('#mamount').hide();
                    $('#mpercentage').show();
                }
        });
    });

    $(document).ready(function() {
        var qLevelCounters = {}; // Object to store loopIndex for each container

        $(document).on('click', '[id^="addgradebtn"]', function() {
            var containerIndex = $(this).closest('[id^="qlevelContainer"]').attr('id').match(/\d+/)[0];

            // Initialize loopIndex if it doesn't exist for the container
            if (!qLevelCounters[containerIndex]) {
                qLevelCounters[containerIndex] = 1;
            }

            var gradeRowCounter = $('#qlevelContainer' + containerIndex).find('[id^="sgradeRow"]').length + 1;

            var clonedRow = $('#qlevelContainer' + containerIndex).find('#sgradeRow1').clone();
            clonedRow.attr('id', 'sgradeRow' + gradeRowCounter);
            clonedRow.find('[id="count' + containerIndex + '"]').attr('name', 'count' + containerIndex + '[]').val('');
            clonedRow.find('[id="grade' + containerIndex + '"]').attr('name', 'grade' + containerIndex + '[]').attr('class', 'js-example-basic-single form-select grade' + containerIndex).val('');
            clonedRow.find('[id="isabove' + containerIndex + '"]').attr('name', 'isabove' + containerIndex + '[]').prop('checked', false);
            clonedRow.find('[id="amount' + containerIndex + '"]').attr('name', 'amount' + containerIndex + '[]').val('');

            clonedRow.find('label[for="grade"]').text('');

            var removeButton = '<button type="button" class="btn btn-danger remove-grade" onclick="removeGradeRow(\'qlevelContainer' + containerIndex + '\', ' + gradeRowCounter + ')">-</button>';
            clonedRow.find('.col-sm-1').last().append(removeButton);

            var latestGradeRow = $('#qlevelContainer' + containerIndex).find('[id^="sgradeRow"]').last();
            latestGradeRow.after(clonedRow);
            qLevelCounters[containerIndex]++; // Increment loopIndex for the container
        });    

        var qLevelCounter = 1;

        $('#addqlevelbtn').click(function() {
            qLevelCounter++;

            var clonedContainer = $('#qlevelContainer1').clone();
            clonedContainer.attr('id', 'qlevelContainer' + qLevelCounter);
            clonedContainer.find('h2').text('Qualification ' + qLevelCounter);

            var removeButton = '<button type="button" class="btn btn-danger remove-level float-end" onclick="removeLevelContainer(' + qLevelCounter + ')"><i class="bi bi-trash removesetbtn"></i></button>';
            clonedContainer.find('.removelevelbtn').html(removeButton);

            var containerIndex = qLevelCounter; // Get the index for the new container
            clonedContainer.find('[id^="level"]').attr('id', 'level' + containerIndex).attr('name', 'level' + containerIndex);
            clonedContainer.find('[id^="sgradeRow"]').attr('id', 'sgradeRow1');
            clonedContainer.find('[id^="count"]').attr('id', 'count' + containerIndex).attr('name', 'count' + containerIndex + '[]').val('');
            clonedContainer.find('[id^="grade"]').attr('id', 'grade' + containerIndex).attr('name', 'grade' + containerIndex + '[]').attr('class', 'js-example-basic-single form-select grade' + containerIndex);
            clonedContainer.find('[id^="isabove"]').attr('id', 'isabove' + containerIndex).attr('name', 'isabove' + containerIndex + '[]').prop('checked', false);
            clonedContainer.find('[id^="amount"]').attr('id', 'amount' + containerIndex).attr('name', 'amount' + containerIndex + '[]').val('');
            clonedContainer.find('[id^="addgradebtn"]').attr('id', 'addgradebtn' + containerIndex);

            clonedContainer.find('[id^="sgradeRow"]').not(':first').remove();

            $('#qlevelContainer' + (qLevelCounter - 1)).after(clonedContainer);
        });

        var pgradeRowCounter = 1;

        $(document).on('click', '[id^="paddgradebtn"]', function() {
            var containerIndex = $(this).closest('[id^="plevelContainer"]').attr('id').match(/\d+/)[0];
            var pgradeRowCounter = $('#plevelContainer' + containerIndex).find('[id^="spgradeRow"]').length + 1;

            var clonedRow = $('#plevelContainer' + containerIndex).find('#spgradeRow1').clone();
            clonedRow.attr('id', 'spgradeRow' + pgradeRowCounter);
            clonedRow.find('[id="pcount' + containerIndex + '"]').attr('name', 'pcount' + containerIndex + '[]').val('');
            clonedRow.find('[id="pgrade' + containerIndex + '"]').attr('name', 'pgrade' + containerIndex + '[]').attr('class', 'js-example-basic-single form-select pgrade' + containerIndex).val('');
            clonedRow.find('[id="pisabove' + containerIndex + '"]').attr('name', 'pisabove' + containerIndex + '[]').prop('checked', false);
            clonedRow.find('[id="pamount' + containerIndex + '"]').attr('name', 'pamount' + containerIndex + '[]').val('');

            clonedRow.find('label[for="grade"]').text('');

            var premoveButton = '<button type="button" class="btn btn-danger remove-grade" onclick="premoveGradeRow(\'plevelContainer' + containerIndex + '\', ' + pgradeRowCounter + ')">-</button>';
            clonedRow.find('.col-sm-1').last().append(premoveButton);

            var platestGradeRow = $('#plevelContainer' + containerIndex).find('[id^="spgradeRow"]').last();
            platestGradeRow.after(clonedRow);
        });    

        var pLevelCounter = 1;

        $('#paddqlevelbtn').click(function() {
            pLevelCounter++;

            var clonedContainer = $('#plevelContainer1').clone();
            clonedContainer.attr('id', 'plevelContainer' + pLevelCounter);
            clonedContainer.find('h2').text('Qualification ' + pLevelCounter);

            var premoveButton = '<button type="button" class="btn btn-danger remove-level float-end" onclick="premoveLevelContainer(' + pLevelCounter + ')"><i class="bi bi-trash removesetbtn"></i></button>';
            clonedContainer.find('.removelevelbtn').html(premoveButton);

            clonedContainer.find('[id^="plevel"]').attr('id', 'plevel' + pLevelCounter).attr('name', 'plevel' + pLevelCounter);
            clonedContainer.find('[id^="spgradeRow"]').attr('id', 'spgradeRow1');
            clonedContainer.find('[id^="pcount"]').attr('id', 'pcount' + pLevelCounter).attr('name', 'pcount' + pLevelCounter + '[]').val('');
            clonedContainer.find('[id^="pgrade"]').attr('id', 'pgrade' + pLevelCounter).attr('name', 'pgrade' + pLevelCounter + '[]').attr('class', 'js-example-basic-single form-select pgrade' + pLevelCounter);
            clonedContainer.find('[id^="pisabove"]').attr('id', 'pisabove' + pLevelCounter).attr('name', 'pisabove' + pLevelCounter + '[]').prop('checked', false);
            clonedContainer.find('[id^="pamount"]').attr('id', 'pamount' + pLevelCounter).attr('name', 'pamount' + pLevelCounter + '[]').val('');
            clonedContainer.find('[id^="paddgradebtn"]').attr('id', 'paddgradebtn' + pLevelCounter);

            clonedContainer.find('[id^="spgradeRow"]').not(':first').remove();

            $('#plevelContainer' + (pLevelCounter - 1)).after(clonedContainer);
        });
    });

    function removeGradeRow(containerId, rowNumber) {
        $('#' + containerId).find('#sgradeRow' + rowNumber).remove();
    }

    function removeLevelContainer(containerIndex) {
        $('#qlevelContainer' + containerIndex).remove();
    }

    function premoveGradeRow(containerId, rowNumber) {
        $('#' + containerId).find('#spgradeRow' + rowNumber).remove();
    }

    function premoveLevelContainer(containerIndex) {
        $('#plevelContainer' + containerIndex).remove();
    }

    document.addEventListener("DOMContentLoaded", function() {
        $('#studyfield').select2();
        $('.uni').select2();
        $('.schlv').select2();
        $('.schtype').select2();
        $('.schsponsor').select2();
        $('.schcover').select2();
        $('.schreq').select2();

        $('#studyfield').on('change', function() {
            var selectedValues = $(this).val();

            if (selectedValues.includes('')) {
                var allValuesExceptEmpty = $('#studyfield').find('option').not(':first').map(function() {
                    return $(this).val();
                }).get();
                $(this).val(allValuesExceptEmpty).trigger('change');
            }
        });
    });

    function populateGrade(qLevelId, gradeSelector, pgradeSelector) {
        console.log('11111');
        $(gradeSelector).empty().append('<option value="" selected disabled>Select Grade</option>');
        $(pgradeSelector).empty().append('<option value="" selected disabled>Select Grade</option>');

        if (qLevelId) {
            $.ajax({
                type: 'GET',
                url: '/admin/get-qlevelgrade',
                data: { qualification_level_id: qLevelId },
                success: function (data) {
                    console.log(data);

                    $.each(data.list, function (index, grade) {
                        $(gradeSelector).append('<option value="' + grade.sg_id + '">' + grade.sg_grade + '</option>');
                        $(pgradeSelector).append('<option value="' + grade.sg_id + '">' + grade.sg_grade + '</option>');
                    });
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    }
        
    $(document).on('change', '.level', function () {
        var qLevelId = $(this).val();
        var index = $(this).attr('id').match(/\d+$/);
        var gradeSelector = '.grade' + index;

        populateGrade(qLevelId, gradeSelector);
    });

    $(document).on('change', '.plevel', function () {
        var qLevelId = $(this).val();
        var index = $(this).attr('id').match(/\d+$/);
        var pgradeSelector = '.pgrade' + index;

        populateGrade(qLevelId, pgradeSelector);
    });


   var KTQuilDemos = function () {

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
        theme: 'snow'
        });

        quill.on('text-change', function () {

        var content = quill.root.innerHTML;
        content = content.replace(/<p>/g, '').replace(/<\/p>/g, '');

        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'details';
        hiddenInput.value = content;

        var form = document.getElementById('addscholarship');
        form.appendChild(hiddenInput);
        });
    }

    var demo2 = function () {
    var quill = new Quill('#kt_quil_2', {
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
        theme: 'snow'
        });

        quill.on('text-change', function () {

        var content = quill.root.innerHTML;
        content = content.replace(/<p>/g, '').replace(/<\/p>/g, '');

        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'criteria';
        hiddenInput.value = content;

        var form = document.getElementById('addscholarship');
        form.appendChild(hiddenInput);
        });
    }

    var demo3 = function () {
    var quill = new Quill('#kt_quil_3', {
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
        theme: 'snow'
        });

        quill.on('text-change', function () {

        var content = quill.root.innerHTML;
        content = content.replace(/<p>/g, '').replace(/<\/p>/g, '');

        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'application';
        hiddenInput.value = content;

        var form = document.getElementById('addscholarship');
        form.appendChild(hiddenInput);
        });
    }

    var demo4 = function () {
    var quill = new Quill('#kt_quil_4', {
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
        theme: 'snow'
        });

        quill.on('text-change', function () {

        var content = quill.root.innerHTML;
        content = content.replace(/<p>/g, '').replace(/<\/p>/g, '');

        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'desc';
        hiddenInput.value = content;

        var form = document.getElementById('addscholarship');
        form.appendChild(hiddenInput);
        });
    }

    return {
        init: function () {
            demo1();
            demo2();
            demo3();
            demo4();
        }
    };
    }();

    jQuery(document).ready(function () {
        KTQuilDemos.init();
    });
</script>
@endsection