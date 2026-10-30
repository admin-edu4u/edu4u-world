@extends('adminLayout')
@section('style')
<style>
    .editk12scholarshipform {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
        height:auto;
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
        @php
            $id = request('id');
            $scholarshipModel = new \App\Models\IntScholarship();
            $scholarshipData = $scholarshipModel->find($id);
            $logo = $scholarshipData->s_c_logo;

            $scholarshipDiscountData = \App\Models\IntScholarshipDiscount::where('sd_s_id', $id)->where('sd_status', 1)->first();
        @endphp
    <div class="editk12scholarshipform">
        <h1><strong>Edit Scholarship</strong></h1>

        <form id="editk12scholarship" class="form-horizontal" method="post" enctype="multipart/form-data" action="/admin/editK12Scholarship">
        <div id="section1">
            <input type="hidden" name="s_id" value="{{ $id }}" readonly>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Is Corporate</label>
                <div class="col-sm-10">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label mt-3" for="corporate_yes">Yes</label>
                        <input class="form-check-input mt-3" type="radio" name="iscorporate" id="iscorporate_yes" value="1" {{ $scholarshipData->s_is_corporate == 1 ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label mt-3" for="corporate_no">No</label>
                        <input class="form-check-input mt-3" type="radio" name="iscorporate" id="iscorporate_no" value="0" {{ $scholarshipData->s_is_corporate == 0 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>

            <div id="corporateContainer" style="display:none;">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Company Name</label>
                    <div class="col-sm-10">
                        <input type="text" name="companyname" class="form-control" id="companyname" placeholder="Enter company name..." value="{{ $scholarshipData->s_c_name }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Company Logo</label>
                    <div class="col-sm-10">
                        <div id="imageFrame" onclick="openFileUploader()">
                            <span class="uploadIcon">+</span>
                            <input type="file" id="companylogo" name="logo" class="d-none" onchange="previewImage()">
                            <div id="imagePreview" class="d-flex flex-wrap imagePreview">
                                <img src="{{ asset('images/scholarships/' . $logo) }}" alt="Corporate Logo" style="width: 100%; height: 100%;">
                            </div>
                        </div>
                        <div id="alertMessage"></div>
                    </div>
                </div>
            </div>

            <div id="universitynameContainer" style="display:none;">
                <div class="mb-3 row">
                    <label for="university" class="col-sm-2 col-form-label">K-12</label>
                    <div class="col-sm-10">
                        <select class="js-example-basic-single form-select uni" name="edituni">
                            <option value="" selected disabled>Select K-12...</option>
                            @foreach ($k12 as $k12)
                                <option value="{{ $k12->int_id }}" {{ $k12->int_id == $scholarshipData->s_int_id ? 'selected' : '' }}>
                                    {{ $k12->int_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="title" class="col-sm-2 col-form-label">Title</label>
                <div class="col-sm-10">
                    <input type="text" name="edittitle" class="form-control" id="title" value="{{ $scholarshipData->s_title }}" required>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Interview</label>
                <div class="col-sm-10">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label mt-3" for="interview_yes">Yes</label>
                        <input class="form-check-input mt-3" type="radio" name="interview" id="interview_yes" value="1" {{ $scholarshipData->s_has_interview == 1 ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label mt-3" for="interview_no">No</label>
                        <input class="form-check-input mt-3" type="radio" name="interview" id="interview_no" value="0" {{ $scholarshipData->s_has_interview == 0 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Type</label>
                <div class="col-sm-10">
                <select class="form-select schtype" name="schtype[]" multiple="multiple" id="schtype" required>
                    @foreach (\App\Models\Scholarship::typeArr() as $value => $type)
                        @php
                            $selected1 = in_array($value, explode(',', $scholarshipData->s_type)) ? 'selected' : '';
                        @endphp
                    <option value="{{ $value }}" {{ $selected1 }}>{{ $type }}</option>
                    @endforeach
                </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Coverage</label>
                <div class="col-sm-10">
                <select class="form-select schcover" name="schcover[]" multiple="multiple" id="schcover" required>
                    @foreach (\App\Models\Scholarship::coverageArr() as $value => $coverage)
                        @php
                            $selected2 = in_array($value, explode(',', $scholarshipData->s_coverage)) ? 'selected' : '';
                        @endphp
                    <option value="{{ $value }}" {{ $selected2 }}>{{ $coverage }}</option>
                    @endforeach
                </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Requirement</label>
                <div class="col-sm-10">
                <select class="form-select schreq" name="schreq[]" multiple="multiple" id="schreq" required>
                    @foreach (\App\Models\Scholarship::requirementArr() as $value => $requirement)
                        @php
                            $selected = in_array($value, explode(',', $scholarshipData->s_requirement)) ? 'selected' : '';
                        @endphp
                    <option value="{{ $value }}" {{ $selected }}>{{ $requirement }}</option>
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
                    <input class="form-check-input mt-3" type="radio" name="ismanual" id="ismanual_no" value="0" {{ $scholarshipDiscountData->sd_is_manual == 0 ? 'checked' : '' }}>
                </div>
                <div class="form-check form-check-inline">
                    <label class="form-check-label mt-3" for="ismanual_no">Manual</label>
                    <input class="form-check-input mt-3" type="radio" name="ismanual" id="ismanual_yes" value="1" {{ $scholarshipDiscountData->sd_is_manual == 1 ? 'checked' : '' }}>
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
                        <input class="form-check-input mt-3" type="radio" name="isamountm" id="isamountm_amt" value="1" {{ $scholarshipDiscountData->sd_is_amt == 1 ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label mt-3" for="isamount_no">Percentage %</label>
                        <input class="form-check-input mt-3" type="radio" name="isamountm" id="isamountm_perc" value="0" {{ $scholarshipDiscountData->sd_is_amt == 0 ? 'checked' : '' }}>
                    </div>
                </div>
                <div id="mamount" class="col-sm-2" style="display:none;">
                    <div class="input-group">
                        <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                            <input type="number" class="form-control" name="mamount" placeholder="Enter amount..." value="{{ $scholarshipDiscountData->sd_amt }}">
                    </div>
                </div>
                <div id="mpercentage" class="col-sm-2" style="display:none;">
                    <div class="input-group">
                        <input type="number" class="form-control" name="mpercentage" placeholder="Enter percentage..." value="{{ $scholarshipDiscountData->sd_percentage }}">
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
                        <input class="form-check-input mt-3" type="radio" name="isamount" id="isamount_yes" value="1" {{ $scholarshipDiscountData->sd_is_amt == 1 ? 'checked' : '' }}>
                    </div>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label mt-3" for="isamount_no">Percentage %</label>
                        <input class="form-check-input mt-3" type="radio" name="isamount" id="isamount_no" value="0" {{ $scholarshipDiscountData->sd_is_amt == 0 ? 'checked' : '' }}>
                    </div>
                </div>
            </div>
        </div>

        <div id="levelContainer" style="display:none;">
            @php
                $distinctLevels = \App\Models\IntScholarshipDiscount::select('sd_level_id')->where('sd_s_id', $id)->where('sd_status', 1)->distinct()->pluck('sd_level_id');
                $sdDataArray = \App\Models\IntScholarshipDiscount::where('sd_s_id', $id)->get();
                $updateRowValue = '';
            @endphp
            @foreach ($distinctLevels as $index => $dt)
                <div id="qlevelContainer{{ $index + 1 }}">
                    <hr>
                    <div class="mb-3 row">
                        <h2 class="col-sm-11">Qualification {{ $index + 1 }}</h2>
                        <div class="col-sm-1 removelevelbtn">
                        @if ($dt > 0)
                        <button type="button" class="btn btn-danger remove-level float-end" onclick="removeLevelContainer('qlevelContainer{{ $index + 1 }}')"><i class="bi bi-trash removesetbtn"></i></button>
                        @endif
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="level" class="col-sm-1 col-form-label">Level</label>
                        <div class="col-sm-6">
                            <select class="js-example-basic-single form-select editqlevel" name="level{{ $index + 1 }}" id="level{{ $index + 1 }}" {{ $dt ? 'disabled' : '' }}>
                                <option value="" selected disabled>Please select level...</option>
                                @foreach ($qlevel as $value => $ql)
                                    <option value="{{ $value }}" {{ $value == $dt ? 'selected' : '' }}>{{ $ql }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @php
                        $filteredData = $sdDataArray->where('sd_level_id', $dt)->where('sd_status', 1);
                        $subIndex = 0;
                    @endphp
                    @foreach ($filteredData as $data)
                    @php
                        $level= \App\Models\SubjectGrade::getGrades($dt);
                    @endphp
                    @if ($data->sd_status == 1)
                    <div class="mb-3 row" id="sgradeRow{{ $subIndex + 1 }}" data-sd-id="{{ $data->sd_id }}">
                        @if ($subIndex > 0)
                            <label class="col-sm-1 col-form-label"></label>
                        @else
                            <label for="grade" class="col-sm-1 col-form-label">Grade Count</label>
                        @endif
                        <div class="col-sm-1">
                            <input class="form-control" type="number" name="count{{ $index + 1 }}[]" id="count{{ $index + 1 }}" placeholder="No..." value="{{ $data->sd_count }}">
                        </div>
                        <div class="col-sm-1">
                            <select class="js-example-basic-single form-select grade{{ $index + 1 }}" name="grade{{ $index + 1 }}[]" id="grade{{ $index + 1 }}">
                                @foreach ($level as $l)
                                <option value="{{$l->sg_id}}" {{ $data->sd_grade == $l->sg_id ? 'selected' : '' }}>{{$l->sg_grade}}</option>
                                @endforeach
                            </select>
                        </div>
                        <label for="isabove" class="col-sm-2 col-form-label text-center">
                            Above?
                            <input class="form-check-input ms-3" type="checkbox" name="isabove{{ $index + 1 }}[]" id="isabove{{ $index + 1 }}" value="1" {{ $data->sd_is_above == 1 ? 'checked' : '' }}>Yes
                            <input class="form-check-input ms-3" type="checkbox" name="isabove{{ $index + 1 }}[]" id="isabove{{ $index + 1 }}" value="0" {{ $data->sd_is_above == 0 ? 'checked' : '' }}>No
                        </label>
                        <div class="col-sm-2">
                            <div class="input-group">
                                <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                                <input type="number" class="form-control" name="amount{{ $index + 1 }}[]" id="amount{{ $index + 1 }}" placeholder="Enter amount..." value="{{ $data->sd_amt }}">
                            </div>
                        </div>
                        <div class="col-sm-1">
                            @if ($subIndex > 0)
                            <button type="button" class="btn btn-danger remove-grade" onclick="eremoveGradeRow('{{ $data->sd_id }}', 'qlevelContainer{{ $index + 1 }}', '{{ $subIndex + 1 }}')">-</button>
                            @endif
                        </div>
                    </div>
                    @php
                        $subIndex++;
                        $updateRowValue .= $data->sd_id . ',';
                    @endphp
                    @endif
                    @endforeach
                    <div class="mb-3 row">
                        <div class="col-sm-1"></div>
                        <div class="col-sm-2"><button type="button" class="btn btn-success" id="addgradebtn{{ $index + 1 }}">+ Grade</button></div>
                    </div>
                </div>
            @endforeach

                <div class="mb-3 row">
                    <div class="col-sm-10"></div>
                    <div class="col-sm-2"><button type="button" class="btn btn-secondary float-end" id="addqlevelbtn">+ Qualification</button></div>
                </div>
        </div>

        <div id="percentageContainer" style="display:none;">
        @php
            $distinctLevels = \App\Models\IntScholarshipDiscount::select('sd_level_id')->where('sd_s_id', $id)->where('sd_status', 1)->distinct()->pluck('sd_level_id');
            $sdDataArray = \App\Models\IntScholarshipDiscount::where('sd_s_id', $id)->get();
        @endphp
        @foreach ($distinctLevels as $index => $dt)
            <div id="plevelContainer{{ $index + 1 }}">
                <hr>
                <div class="mb-3 row">
                    <h2 class="col-sm-11">Qualification {{ $index + 1 }}</h2>
                    <div class="col-sm-1 premovelevelbtn">
                    @if ($dt > 1)
                    <button type="button" class="btn btn-danger remove-level float-end" onclick="premoveLevelContainer('plevelContainer{{ $index + 1 }}')"><i class="bi bi-trash removesetbtn"></i></button>
                    @endif
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="level" class="col-sm-1 col-form-label">Level</label>
                    <div class="col-sm-6">
                        <select class="js-example-basic-single form-select editplevel" name="plevel{{ $index + 1 }}" id="plevel{{ $index + 1 }}" {{ $dt ? 'disabled' : '' }}>
                            <option value="" selected disabled>Please select level...</option>
                            @foreach ($qlevel as $value => $ql)
                                <option value="{{ $value }}" {{ $value == $dt ? 'selected' : '' }}>{{ $ql }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @php
                    $filteredData = $sdDataArray->where('sd_level_id', $dt)->where('sd_status', 1);
                    $subIndex = 0;
                @endphp
                
                @foreach ($filteredData as $data)
                @php
                    $level= \App\Models\SubjectGrade::getGrades($dt);
                @endphp
                <div class="mb-3 row" id="spgradeRow{{ $subIndex + 1 }}" data-sd-id="{{ $data->sd_id }}">
                    @if ($subIndex > 0)
                        <label class="col-sm-1 col-form-label"></label>
                    @else
                        <label for="grade" class="col-sm-1 col-form-label">Grade Count</label>
                    @endif
                    <div class="col-sm-1">
                        <input class="form-control" type="number" name="pcount{{ $index + 1 }}[]" id="pcount{{ $index + 1 }}" placeholder="No..." value="{{ $data->sd_count }}">
                    </div>
                    <div class="col-sm-1">
                        <select class="js-example-basic-single form-select pgrade{{ $index + 1 }}" name="pgrade{{ $index + 1 }}[]" id="pgrade{{ $index + 1 }}">
                            @foreach ($level as $l)
                            <option value="{{$l->sg_id}}" {{ $data->sd_grade == $l->sg_id ? 'selected' : '' }}>{{$l->sg_grade}}</option>
                            @endforeach
                        </select>
                    </div>
                    <label for="pisabove" class="col-sm-2 col-form-label text-center">
                        Above?
                        <input class="form-check-input ms-3" type="checkbox" name="pisabove{{ $index + 1 }}[]" id="pisabove{{ $index + 1 }}" value="1" {{ $data->sd_is_above == 1 ? 'checked' : '' }}>Yes
                        <input class="form-check-input ms-3" type="checkbox" name="pisabove{{ $index + 1 }}[]" id="pisabove{{ $index + 1 }}" value="0" {{ $data->sd_is_above == 0 ? 'checked' : '' }}>No
                    </label>
                    <div class="col-sm-2">
                        <div class="input-group">
                            <input type="number" class="form-control" name="pamount{{ $index + 1 }}[]" id="pamount{{ $index + 1 }}" placeholder="Enter amount..." value="{{ $data->sd_percentage }}">
                            <div class="input-group-prepend"><span class="input-group-text">%</span></div>
                        </div>
                    </div>
                    <div class="col-sm-1">
                        @if ($subIndex > 0)
                        <button type="button" class="btn btn-danger remove-grade" onclick="epremoveGradeRow('{{ $data->sd_id }}', 'plevelContainer{{ $index + 1 }}', '{{ $subIndex + 1 }}')">-</button>
                        @endif
                    </div>
                </div>
                @php
                    $subIndex++;
                @endphp
                @endforeach
                <div class="mb-3 row">
                    <div class="col-sm-1"></div>
                    <div class="col-sm-2"><button type="button" class="btn btn-success" id="addpgradebtn{{ $index + 1 }}">+ Grade</button></div>
                </div>
            </div>
        @endforeach

            <div class="mb-3 row">
                <div class="col-sm-10"></div>
                <div class="col-sm-2"><button type="button" class="btn btn-secondary float-end" id="addplevelbtn">+ Qualification</button></div>
            </div>
        </div>

            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label"></label>
                <hr>
                <div class="col-sm-12">
                    <div class="float-end">
                        <div class="mt-3"></div>
                        <button type="button" class="btn btn-secondary mt-2 float-start me-3" onclick="showSection1()">Back</button>
                        <button type="submit" class="btn btn-primary mt-2 float-end">Submit</button>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="updateRow" name="updateRow" value="{{ rtrim($updateRowValue, ',') }}">
        <input type="hidden" id="deletedRow" name="deletedRow">
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
    const previewContainer = document.getElementById('imagePreview');
    const alertMessage = document.getElementById('alertMessage');
    const files = input.files;

    previewContainer.innerHTML = '';
    alertMessage.innerText = '';

    const invalidFiles = Array.from(files).filter(file => !/\.(jpg|jpeg|png)$/i.test(file.name));

    if (invalidFiles.length > 0) {
        alertMessage.innerText = 'Skipped non-JPG, JPEG, or PNG files: ' + invalidFiles.map(file => file.name).join(', ');
        input.value = '';
        return;
    }

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
    //////////////////////

    $(document).ready(function() {
        $('.uni').select2();
        $('.studyfield').select2();
        $('.editschlv').select2();
        $('.schtype').select2();
        $('.schsponsor').select2();
        $('.schcover').select2();
        $('.schreq').select2();

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

        $('input[name="iscorporate"]').trigger('change');
        $('input[name="ismanual"]').trigger('change');
        $('input[name="isamountm"]').trigger('change');

        if ($('#ismanual_no').prop('checked')) {
            $('input[name="isamount"]').trigger('change');
        }

        $('form').submit(function() {
            $('.editqlevel').prop('disabled', false);
        });

        $('form').submit(function() {
            $('.editplevel').prop('disabled', false);
        });
        //////////////////////////////////////////////////
        var qLevelCounters = {}; 

        $(document).on('click', '[id^="addgradebtn"]', function() {
            var containerIndex = $(this).closest('[id^="qlevelContainer"]').attr('id').match(/\d+/)[0];

            if (!qLevelCounters[containerIndex]) {
                qLevelCounters[containerIndex] = 1;
            }

            var gradeRowCounter = $('#qlevelContainer' + containerIndex).find('[id^="sgradeRow"]').length + 1;
            var newgradeRow = $('#qlevelContainer' + containerIndex).find('[id^="newsgradeRow"]').length + 1;
            

            var clonedRow = $('#qlevelContainer' + containerIndex).find('#sgradeRow1').clone();
            clonedRow.attr('id', 'newsgradeRow' + newgradeRow);
            clonedRow.find('[id="count' + containerIndex + '"]').attr('name', 'newcount' + containerIndex + '[]').val('');
            clonedRow.find('[id="grade' + containerIndex + '"]').attr('name', 'newgrade' + containerIndex + '[]').attr('class', 'js-example-basic-single form-select grade' + containerIndex).val('');
            clonedRow.find('[id="isabove' + containerIndex + '"]').attr('name', 'newisabove' + containerIndex + '[]').prop('checked', false);
            clonedRow.find('[id="amount' + containerIndex + '"]').attr('name', 'newamount' + containerIndex + '[]').val('');

            clonedRow.find('label[for="grade"]').text('');
            clonedRow.removeAttr('data-sd-id');

            var removeButton = '<button type="button" class="btn btn-danger remove-grade" onclick="removeGradeRow(\'qlevelContainer' + containerIndex + '\', ' + newgradeRow + ')">-</button>';
            clonedRow.find('.col-sm-1').last().append(removeButton);

            var latestGradeRow = $('#qlevelContainer' + containerIndex).find('[id^="sgradeRow"]').last();
            latestGradeRow.after(clonedRow);
            qLevelCounters[containerIndex]++;
        });    

        var qLevelCounter = {{ $distinctLevels->count() }};

        $('#addqlevelbtn').click(function() {
            qLevelCounter++;

            var clonedContainer = $('#qlevelContainer1').clone();
            clonedContainer.attr('id', 'qlevelContainer' + qLevelCounter);
            clonedContainer.find('h2').text('Qualification ' + qLevelCounter);

            var removeButton = '<button type="button" class="btn btn-danger remove-level float-end" onclick="removeLevelContainer(\'qlevelContainer' + qLevelCounter + '\')"><i class="bi bi-trash removesetbtn"></i></button>';
            clonedContainer.find('.removelevelbtn').html(removeButton);

            var containerIndex = qLevelCounter;
            clonedContainer.find('[id^="level"]').attr('id', 'level' + containerIndex).attr('name', 'newlevel' + containerIndex).removeAttr('disabled');
            clonedContainer.find('[id^="sgradeRow"]').attr('id', 'sgradeRow1');
            clonedContainer.find('[id^="count"]').attr('id', 'count' + containerIndex).attr('name', 'newcount' + containerIndex + '[]').val('');
            clonedContainer.find('[id^="grade"]').attr('id', 'grade' + containerIndex).attr('name', 'newgrade' + containerIndex + '[]').attr('class', 'js-example-basic-single form-select grade' + containerIndex);
            clonedContainer.find('[id^="isabove"]').attr('id', 'isabove' + containerIndex).attr('name', 'newisabove' + containerIndex + '[]').prop('checked', false);
            clonedContainer.find('[id^="amount"]').attr('id', 'amount' + containerIndex).attr('name', 'newamount' + containerIndex + '[]').val('');
            clonedContainer.find('[id^="addgradebtn"]').attr('id', 'addgradebtn' + containerIndex);

            clonedContainer.find('[id^="sgradeRow"]').not(':first').remove();

            $('#qlevelContainer' + (qLevelCounter - 1)).after(clonedContainer);
        });

        ///////////////////////////////
        var pLevelCounters = {}; 

        $(document).on('click', '[id^="addpgradebtn"]', function() {
            var containerIndex = $(this).closest('[id^="plevelContainer"]').attr('id').match(/\d+/)[0];

            if (!pLevelCounters[containerIndex]) {
                pLevelCounters[containerIndex] = 1;
            }

            var gradeRowCounter = $('#plevelContainer' + containerIndex).find('[id^="spgradeRow"]').length + 1;
            var newgradeRow = $('#plevelContainer' + containerIndex).find('[id^="newspgradeRow"]').length + 1;

            var clonedRow = $('#plevelContainer' + containerIndex).find('#spgradeRow1').clone();
            clonedRow.attr('id', 'newspgradeRow' + newgradeRow);
            clonedRow.attr('id', 'spgradeRow' + gradeRowCounter);
            clonedRow.find('[id="pcount' + containerIndex + '"]').attr('name', 'newpcount' + containerIndex + '[]').val('');
            clonedRow.find('[id="pgrade' + containerIndex + '"]').attr('name', 'newpgrade' + containerIndex + '[]').attr('class', 'js-example-basic-single form-select pgrade' + containerIndex).val('');
            clonedRow.find('[id="pisabove' + containerIndex + '"]').attr('name', 'newpisabove' + containerIndex + '[]').prop('checked', false);
            clonedRow.find('[id="pamount' + containerIndex + '"]').attr('name', 'newpamount' + containerIndex + '[]').val('');

            clonedRow.find('label[for="grade"]').text('');
            clonedRow.removeAttr('data-sd-id');

            var removeButton = '<button type="button" class="btn btn-danger premove-grade" onclick="premoveGradeRow(\'plevelContainer' + containerIndex + '\', ' + gradeRowCounter + ')">-</button>';
            clonedRow.find('.col-sm-1').last().append(removeButton);

            var latestGradeRow = $('#plevelContainer' + containerIndex).find('[id^="spgradeRow"]').last();
            latestGradeRow.after(clonedRow);
            pLevelCounters[containerIndex]++;
        });    

        var pLevelCounter = {{ $distinctLevels->count() }};

        $('#addplevelbtn').click(function() {
            pLevelCounter++;

            var clonedContainer = $('#plevelContainer1').clone();
            clonedContainer.attr('id', 'plevelContainer' + pLevelCounter);
            clonedContainer.find('h2').text('Qualification ' + pLevelCounter);

            var premoveButton = '<button type="button" class="btn btn-danger remove-level float-end" onclick="premoveLevelContainer(\'plevelContainer' + pLevelCounter + '\')"><i class="bi bi-trash removesetbtn"></i></button>';
            clonedContainer.find('.premovelevelbtn').html(premoveButton);

            var containerIndex = pLevelCounter;
            clonedContainer.find('[id^="plevel"]').attr('id', 'plevel' + containerIndex).attr('name', 'newplevel' + containerIndex).removeAttr('disabled');
            clonedContainer.find('[id^="spgradeRow"]').attr('id', 'spgradeRow1');
            clonedContainer.find('[id^="pcount"]').attr('id', 'pcount' + containerIndex).attr('name', 'newpcount' + containerIndex + '[]').val('');
            clonedContainer.find('[id^="pgrade"]').attr('id', 'pgrade' + containerIndex).attr('name', 'newpgrade' + containerIndex + '[]').attr('class', 'js-example-basic-single form-select pgrade' + containerIndex);
            clonedContainer.find('[id^="pisabove"]').attr('id', 'pisabove' + containerIndex).attr('name', 'newpisabove' + containerIndex + '[]').prop('checked', false);
            clonedContainer.find('[id^="pamount"]').attr('id', 'pamount' + containerIndex).attr('name', 'newpamount' + containerIndex + '[]').val('');
            clonedContainer.find('[id^="addpgradebtn"]').attr('id', 'addpgradebtn' + containerIndex);

            clonedContainer.find('[id^="spgradeRow"]').not(':first').remove();

            $('#plevelContainer' + (pLevelCounter - 1)).after(clonedContainer);
        });


    });

    function showSection1() {
        document.getElementById('section1').style.display = 'block';
        document.getElementById('section2').style.display = 'none';
    }

    function showSection2() {
        document.getElementById('section1').style.display = 'none';
        document.getElementById('section2').style.display = 'block';
    }

    function populateGrade(qLevelId, gradeSelector, selectedGrade, pgradeSelector, selectedpGrade) {
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
                       var option = $('<option value="' + grade.sg_id + '">' + grade.sg_grade + '</option>');
                        if (selectedGrade && selectedGrade == grade.sg_id) {
                            option.prop('selected', true);
                        }
                        $(gradeSelector).append(option);

                        var option = $('<option value="' + grade.sg_id + '">' + grade.sg_grade + '</option>');
                        if (selectedpGrade && selectedpGrade == grade.sg_id) {
                            option.prop('selected', true);
                        }
                        $(pgradeSelector).append(option);
                    });
                },
                error: function (data) {
                    console.log('Error:', data);
                }
            });
        }
    }
        
    $(document).on('change', '.editqlevel', function () {
        var qLevelId = $(this).val();
        var index = $(this).attr('id').match(/\d+$/);
        var gradeSelector = '.grade' + index;

        populateGrade(qLevelId, gradeSelector);
    });

    $(document).on('change', '.editplevel', function () {
        var qLevelId = $(this).val();
        var index = $(this).attr('id').match(/\d+$/);
        var pgradeSelector = '.pgrade' + index;

        populateGrade(qLevelId, pgradeSelector);
    });

    function eremoveGradeRow(id, containerId, rowId) {
        $('#' + containerId).find('#sgradeRow' + rowId).remove();

        var deletedRowInput = $('#deletedRow');
        var currentValue = deletedRowInput.val();
        if (currentValue === '') {
            deletedRowInput.val(id);
        } else {
            deletedRowInput.val(currentValue + ',' + id);
        }

        var updateRowInput = $('#updateRow');
        var updateRowValue = updateRowInput.val();
        if (updateRowValue !== '') {
            var idsArray = updateRowValue.split(',');

            var index = idsArray.indexOf(id);
            if (index !== -1) {
                idsArray.splice(index, 1);
            }
            updateRowInput.val(idsArray.join(','));
        }
    }

    function epremoveGradeRow(id, containerId, rowId) {
        $('#' + containerId).find('#spgradeRow' + rowId).remove();

        var deletedRowInput = $('#deletedRow');
        var currentValue = deletedRowInput.val();
        if (currentValue === '') {
            deletedRowInput.val(id);
        } else {
            deletedRowInput.val(currentValue + ',' + id);
        }

        var updateRowInput = $('#updateRow');
        var updateRowValue = updateRowInput.val();
        if (updateRowValue !== '') {
            var idsArray = updateRowValue.split(',');

            var index = idsArray.indexOf(id);
            if (index !== -1) {
                idsArray.splice(index, 1);
            }
            updateRowInput.val(idsArray.join(','));
        }
    }

    function removeGradeRow(containerId, rowId) {
        $('#' + containerId).find('#newsgradeRow' + rowId).remove();
    }

    function removeLevelContainer(containerId) {
        var deletedRowInput = $('#deletedRow');
        var updateRowInput = $('#updateRow');

        $('#' + containerId + ' [data-sd-id]').each(function() {
            var sdId = $(this).data('sd-id');

            var updateRowValue = updateRowInput.val();
            if (updateRowValue.includes(sdId)) {
                updateRowValue = updateRowValue.split(',').filter(function(value) {
                    return value.trim() !== sdId.toString();
                }).join(',');
                updateRowInput.val(updateRowValue);
            }

            var deletedRowValue = deletedRowInput.val();
            if (!deletedRowValue.includes(sdId)) {
                if (deletedRowValue === '') {
                    deletedRowInput.val(sdId);
                } else {
                    deletedRowInput.val(deletedRowValue + ',' + sdId);
                }
            }
        });

        $('#' + containerId).remove();
    }

    function premoveGradeRow(containerId, rowId) {
        $('#' + containerId).find('#spgradeRow' + rowId).remove();
    }

    function premoveLevelContainer(containerId) {
        var deletedRowInput = $('#deletedRow');
        var updateRowInput = $('#updateRow');

        $('#' + containerId + ' [data-sd-id]').each(function() {
            var sdId = $(this).data('sd-id');

            var updateRowValue = updateRowInput.val();
            if (updateRowValue.includes(sdId)) {
                updateRowValue = updateRowValue.split(',').filter(function(value) {
                    return value.trim() !== sdId.toString();
                }).join(',');
                updateRowInput.val(updateRowValue);
            }

            var deletedRowValue = deletedRowInput.val();
            if (!deletedRowValue.includes(sdId)) {
                if (deletedRowValue === '') {
                    deletedRowInput.val(sdId);
                } else {
                    deletedRowInput.val(deletedRowValue + ',' + sdId);
                }
            }
        });

        $('#' + containerId).remove();
    }

    ///////Quilbox
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
    theme: 'snow' // or 'bubble'
    });

    // Set the initial content of the Quill editor
    var initialContent = {!! json_encode($scholarshipData->s_details) !!};
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
    hiddenInput.name = 'editdetails';
    hiddenInput.value = content;

    // Append the input field to the form
    var form = document.getElementById('editk12scholarship');
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
    theme: 'snow' // or 'bubble'
    });

    // Set the initial content of the Quill editor
    var initialContent = {!! json_encode($scholarshipData->s_criteria) !!};
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
    hiddenInput.name = 'editcriteria';
    hiddenInput.value = content;

    // Append the input field to the form
    var form = document.getElementById('editk12scholarship');
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
    theme: 'snow' // or 'bubble'
    });

    // Set the initial content of the Quill editor
    var initialContent = {!! json_encode($scholarshipData->s_application) !!};
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
    hiddenInput.name = 'editapplication';
    hiddenInput.value = content;

    // Append the input field to the form
    var form = document.getElementById('editk12scholarship');
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
    theme: 'snow' // or 'bubble'
    });

    // Set the initial content of the Quill editor
    var initialContent = {!! json_encode($scholarshipDiscountData->sd_desc) !!};
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
    hiddenInput.name = 'editdesc';
    hiddenInput.value = content;

    // Append the input field to the form
    var form = document.getElementById('editk12scholarship');
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