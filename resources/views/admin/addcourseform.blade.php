@extends('adminLayout')
@section('style')
<style>
    .addcourseform {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
        height:auto;
    }

    #qualificationWrapper {
        position: relative;
    }

    .deleteSetButton {
        position: absolute;
        top: 5px;
        right: 5px;
        z-index: 9999; /* Adjust the value as needed */
        background-color: red; /* Red background color */
        border-radius: 5px;
        border-color: white;
    }

    .deleteSetButton i {
        color: white; /* White color for the icon */
    }

    .remove-core-btn,
    .remove-elective-btn,
    .remove-other-btn,
    .remove-extrafees-btn {
        top: 5px;
        right: 5px;
        z-index: 9999; /* Adjust the value as needed */
        background-color: red; /* Red background color */
        border-radius: 5px;
        border-color: white;
    }

    .remove-core-btn i,
    .remove-elective-btn i,
    .remove-other-btn i,
    .remove-extrafees-btn i {
        color:white;
    }

</style>
@endsection

@section('content')
    @include('formMsg')
<div class="addcourseform">
<form id="addcourse" class="form-horizontal" method="post" action="/admin/addCourse">
    <div id="section1">
    <h1><strong>Add Course</strong></h1>


    <div class="mb-3 row">
        <label for="courseuni" class="col-sm-2 col-form-label">University</label>
        <div class="col-sm-10">
            <select name="courseuni" class="form-select uniselect" id="courseuni" required>
                <option value="" selected disabled>Select University</option>
                @foreach ($university as $u)
                    <option value="{{ $u->u_id }}">{{ $u->u_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="coursename" class="col-sm-2 col-form-label">Course Name</label>
        <div class="col-sm-10">
            <input type="text" name="coursename" class="form-control" id="coursename" placeholder="Enter course name..." required>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">English Required</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="engreq" value="1" required>
                <label class="form-check-label mt-3">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="engreq" value="0" required>
                <label class="form-check-label mt-3">No</label>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
    <label for="courselevel" class="col-sm-2 col-form-label">Level</label>
    <div class="col-sm-10">
        <select name="courselevel" class="form-select levelselect" id="courselevel" required>
            <option value="" selected disabled>Select Level</option>
            @foreach ($level as $value => $l)
                <option value="{{ $value }}">{{ $l }}</option>
            @endforeach
        </select>
    </div>
    </div>

    <div class="mb-3 row">
    <label for="coursestream" class="col-sm-2 col-form-label">Stream</label>
    <div class="col-sm-10">
        <select name="coursestream" class="form-select streamselect" id="coursestream" required>
            <option value="" selected disabled>Select Stream</option>
            @foreach ($stream as $value => $s)
                <option value="{{ $value }}">{{ $s }}</option>
            @endforeach
        </select>
    </div>
    </div>

    <div class="mb-3 row">
    <label for="coursemonth" class="col-sm-2 col-form-label">Intake Months</label>
    <div class="col-sm-10">
        <select name="coursemonth[]" class="form-select coursemonthselect" multiple="multiple" id="coursemonth" required>
            @foreach (\App\Models\Course::monthArr() as $value => $month)
                <option value="{{ $value }}">{{ $month }}</option>
            @endforeach
        </select>
    </div>
    </div>

    <div class="mb-3 row">
        <label for="courseduration" class="col-sm-2 col-form-label">Course Duration (Month)</label>
        <div class="col-sm-10">
            <input type="number" name="courseduration" class="form-control" id="courseduration" placeholder="Enter course duration..."  step="any" required>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="courseaward" class="col-sm-2 col-form-label">Course Award</label>
        <div class="col-sm-10">
            <input type="text" name="courseaward" class="form-control" id="courseaward" placeholder="Enter course award..." required>
        </div>
    </div>

    <div class="mb-3 row">
    <label for="courseinternship" class="col-sm-2 col-form-label">Internship</label>
    <div class="col-sm-10">
        <div class="form-check form-check-inline">
            <input class="form-check-input mt-3" type="radio" name="courseinternship" id="courseinternship_yes" value="1" required>
            <label class="form-check-label mt-3" for="courseinternship_yes">Yes</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input mt-3" type="radio" name="courseinternship" id="courseinternship_no" value="0" required>
            <label class="form-check-label mt-3" for="courseinternship_no">No</label>
        </div>
    </div>
    </div>

    <div class="mb-3 row" id="internshipDurationRow" style="display:none;">
        <label for="internduration" class="col-sm-2 col-form-label">Internship Duration (Month)</label>
        <div class="col-sm-10">
            <input type="number" name="internduration" class="form-control" id="internduration" placeholder="Enter internship duration..."  step="any">
        </div>
    </div>

    <div class="mb-3 row">
        <label for="courseoutline" class="col-sm-2 col-form-label">Course Outline</label>
        <div class="col-sm-10">
            <div id="kt_quil_1" style="height: 200px"></div>
        </div>
    </div>

    <div class="mb-3 row mt-3">
        <label for="coursemodule" class="col-sm-2 col-form-label">Modules</label>
            <div class="col-sm-10">
                <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                    <li class="nav-item">
                        <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_4">Cores</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5">Electives</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_6">Others</a>
                    </li>
                </ul>

                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="kt_tab_pane_4" role="tabpanel">
                        <div id="coreContainer">
                            <div class="mt-3"></div>
                            <input class="form-control" type="text" name="title1[]" placeholder="Enter title..." required>
                            <div class="mt-3"></div>
                            <div class="ql-container" id="kt_quil_2_0" style="height: 100px"></div>
                            <div class="mb-3"></div>
                        </div>
                        <div id="corebtn" class="mt-3"><button class="btn btn-secondary corebtn" type="button">+ Core</button></div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
                        <div id="electiveContainer">
                            <div class="mt-3"></div>
                            <input class="form-control" type="text" name="title2[]" placeholder="Enter title...">
                            <div class="mt-3"></div>
                            <div id="kt_quil_3_0" style="height: 100px"></div>
                            <div class="mb-3"></div>
                        </div>
                        <div id="electivebtn" class="mt-3"><button class="btn btn-success electivebtn" type="button">+ Elective</button></div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_6" role="tabpanel">
                        <div id="otherContainer">
                            <div class="mt-3"></div>
                            <input class="form-control" type="text" name="title3[]" placeholder="Enter title...">
                            <div class="mt-3"></div>
                            <div id="kt_quil_4_0" style="height: 100px"></div>
                            <div class="mb-3"></div>
                        </div>
                        <div id="otherbtn" class="mt-3"><button class="btn btn-primary otherbtn" type="button">+ Other</button></div>
                    </div>
                </div>
            </div>
    </div>

    

    <div class="mb-3 row">
        <label for="courseisfor" class="col-sm-2 col-form-label">Course Is For</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="courseisfor" value="1" required>
                <label class="form-check-label mt-3">Malaysian</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="courseisfor" value="0" required>
                <label class="form-check-label mt-3">Foreign</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="courseisfor" value="2" required>
                <label class="form-check-label mt-3">Both</label>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="coursesportsrepresent" class="col-sm-2 col-form-label">Represent In Sports</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="coursesportsrepresent" id="coursesportsrepresent_yes" value="1" required>
                <label class="form-check-label mt-3" for="coursesportsrepresent_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="coursesportsrepresent" id="coursesportsrepresent_no" value="0" required>
                <label class="form-check-label mt-3" for="coursesportsrepresent_no">No</label>
            </div>
        </div>
    </div>

    <div class="mb-3 row" id="representlevel" style="display:none;">
            <label for="representlevel" class="col-sm-2 col-form-label">Represent Level</label>
        <div class="col-sm-10">
            <select name="representlevel" class="form-select representlevel" id="representlevel">
                <option value="" selected disabled>Select Level</option>
                @foreach (\App\Models\Course::rlevelArr() as $value => $rlevel)
                    <option value="{{ $value }}">{{ $rlevel }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="coursetalentrepresent" class="col-sm-2 col-form-label">Represent In Talent</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="coursetalentrepresent" id="coursetalentrepresent_yes" value="1" required>
                <label class="form-check-label mt-3" for="coursetalentrepresent_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="coursetalentrepresent" id="coursetalentrepresent_no" value="0" required>
                <label class="form-check-label mt-3" for="coursetalentrepresent_no">No</label>
            </div>
        </div>
    </div>

    <div class="mb-3 row" id="talentlevel" style="display:none;">
            <label for="talentlevel" class="col-sm-2 col-form-label">Represent Level</label>
        <div class="col-sm-10">
            <select name="talentlevel" class="form-select talentlevel" id="talentlevel">
                <option value="" selected disabled>Select Level</option>
                @foreach (\App\Models\Course::rlevelArr() as $value => $rlevel)
                    <option value="{{ $value }}">{{ $rlevel }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="courseleadership" class="col-sm-2 col-form-label">Hold Any Leadership Role</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="courseleadership" id="courseleadership_yes" value="1" required>
                <label class="form-check-label mt-3" for="courseleadership_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="courseleadership" id="courseleadership_no" value="0" required>
                <label class="form-check-label mt-3" for="courseleadership_no">No</label>
            </div>
        </div>
    </div>

    <div class="mb-3 row" id="leadershiplevel" style="display:none;">
            <label for="leadershiplevel" class="col-sm-2 col-form-label">Leadership Level</label>
        <div class="col-sm-10">
            <select name="leadershiplevel" class="form-select leadershiplevel" id="leadershiplevel">
                <option value="" selected disabled>Select Level</option>
                @foreach (\App\Models\Course::rlevelArr() as $value => $rlevel)
                    <option value="{{ $value }}">{{ $rlevel }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="coursebumiputra" class="col-sm-2 col-form-label">Course Is For</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="coursebumiputra" value="1" required>
                <label class="form-check-label mt-3">Bumiputra</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="coursebumiputra" value="0" required>
                <label class="form-check-label mt-3">Everyone</label>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="coursemaincat" class="col-sm-2 col-form-label">Main Category</label>
        <div class="col-sm-10">
            <select class="form-select coursemaincat" name="coursemaincat" id="coursemaincat" required>
                <option value="" selected disabled>Select Main Category</option>
                @foreach ($category as $c)
                    <option value="{{ $c->c_id }}">{{ $c->c_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="coursesubcat" class="col-sm-2 col-form-label">Sub Category</label>
        <div class="col-sm-10">
            <select class="form-select coursesubcat" name="coursesubcat" id="coursesubcat" required>
                <option value="" selected disabled>Please Select Main Category</option>
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="coursename" class="col-sm-2 col-form-label">Amount Non Bumi</label>
        <div class="col-sm-10">
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                <input type="number" name="courseamount" class="form-control" id="courseamount" placeholder="Enter course amount..." required>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="coursename" class="col-sm-2 col-form-label">Amount Bumiputra</label>
        <div class="col-sm-10">
            <div class="input-group">
                    <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                <input type="number" name="courseamountbumi" class="form-control" id="courseamountbumi" placeholder="Enter course amount..." required>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="coursename" class="col-sm-2 col-form-label">Amount Foreigner</label>
        <div class="col-sm-10">
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                <input type="number" name="courseamountfore" class="form-control" id="courseamountfore" placeholder="Enter course amount..." required>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Extra Fees</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="extrafees" id="extrafees_yes" value="1" required>
                <label class="form-check-label mt-3">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="extrafees" id="extrafees_no" value="0" required>
                <label class="form-check-label mt-3">No</label>
            </div>
        </div>
    </div>

    <div id="extrafeesContainer" style="display:none;">
        <div id="feesContainer1">
            <hr>
            <div class="mb-3 row"><h1 class="col-sm-2">Extra Fees 1</h1><div class="col-sm-10"><button type="button" class="btn float-end remove-extrafees-btn"><i class="bi bi-trash"></i></button></div></div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Fees Type</label>
                <div class="col-sm-10">
                    <select name="extrafeestype1" class="form-select" id="extrafeestype1">
                        <option value="" selected disabled>Select extra fees type...</option>
                        @foreach (\App\Models\CourseFee::typeArr() as $value => $type)
                            <option value="{{ $value }}">{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="extrafeesFrequency1">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Payment Frequency</label>
                    <div class="col-sm-10">
                        <select name="extrafeesfrequency1" class="form-select">
                            <option value="" selected disabled>Select extra fees payment frequency...</option>
                            @foreach (\App\Models\CourseFee::frequencyArr() as $value => $frequency)
                                <option value="{{ $value }}">{{ $frequency }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Fees Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="extrafeesname1" placeholder="Enter extra fees name...">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">For Non Bumi</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                            <input type="number" name="extrafeesamount1" class="form-control" id="extrafeesamount1" placeholder="Enter extra fees amount for non bumiputra...">
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">For Bumiputra</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                            <input type="number" name="extrafeesamountbumi1" class="form-control" id="extrafeesamountbumi1" placeholder="Enter extra fees amount for bumiputra...">
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">For Foreigner</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                            <input type="number" name="extrafeesamountfore1" class="form-control" id="extrafeesamountfore1" placeholder="Enter extra fees amount for foreinger...">
                        </div>
                    </div>
                </div>
        </div>
            <div class="mb-3 row">
                <div class="col-sm-12">
                    <button type="button" class="btn btn-secondary" id="addextrafeesbtn" onclick="addExtraFees()">+ Fees</button>
                </div>
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

    <div id="section2" style="display: none;">
    <h1><strong>Qualifications</strong></h1>
<div id="qualificationWrapper">
    <div class="mb-3 row">
        <label for="qualificationlevel" class="col-sm-2 col-form-label">Qualification Level</label>
        <div class="col-sm-8">
            <select class="form-select qlevel" name="qlevel1" id="qlevel1" required>
                <option value="" selected disabled>Select Qualification Level</option>
                @foreach ($qlevel as $value => $ql)
                    <option value="{{ $value }}">{{ $ql }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="minsubject" class="col-sm-2 col-form-label">Minimum Subjects</label>
        <div class="col-sm-4">
            <input type="number" name="minsubject1" class="form-control minsubject1" id="minsubject1" placeholder="Enter minimum subject..." required>
        </div>
        <div class="col-sm-4">
            <select class="form-select mingrade1" name="mingrade1" id="mingrade1" required>
                <option value="" selected disabled>Please Select Qualification Level</option>
            </select>
        </div>
    </div>

    <div class="mb-3 row subject-row" id="subjectRow1">
        <label for="subject" class="col-sm-2 col-form-label">Subjects</label>
        <div class="col-sm-4">
            <select class="form-select subject1" name="subject1[]" id="subject1" required>
                <option value="" selected disabled>Please Select Qualification Level</option>
            </select>
        </div>
        <div class="col-sm-4">
            <select class="form-select grade1" name="grade1[]" id="grade1" required>
                <option value="" selected disabled>Please Select Qualification Level</option>
            </select>
        </div>
        <div class="col-sm-2"></div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-10">
            <button type="button" class="btn btn-success" id="addSubject1">
                <i class="bi bi-plus"></i>Subject
            </button>
        </div>
        <div id="deletebtn"></div>
    </div>

    <hr>
</div>

<div id="footer">
    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-10">
            <button type="button" class="btn btn-success" onclick="addQualification()">
                <i class="bi bi-plus"></i> Add Qualification
            </button>
        </div>
    </div>

    <div class="mt-5 mb-3 row">
        <label class="col-sm-2 col-form-label">Entry Requirements</label>
        <div class="col-sm-10">
            <div id="kt_quil_5" style="height: 200px"></div>
        </div>
    </div>

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

    </div>
</div>
</form>
@endsection

@section('script')
<script>
    function showSection2() {
        document.getElementById('section1').style.display = 'none';
        document.getElementById('section2').style.display = 'block';
    }

    var loopIndex = 1;
    document.querySelector('.corebtn').addEventListener('click', function() {
        var coreContainer = document.getElementById('coreContainer');
        var clone = coreContainer.cloneNode(true);

        clone.querySelector('[name="title1[]"]').value = '';

        var clonedQuillContainer = clone.querySelector('#kt_quil_2_0');
        clonedQuillContainer.id = 'kt_quil_2_' + loopIndex;

        var clonedQuill = clone.querySelector('.ql-editor');
        clonedQuill.innerHTML = '';

        var firstToolbar = clone.querySelector('.ql-toolbar');
        if (firstToolbar) {
            firstToolbar.parentNode.removeChild(firstToolbar);
        }

        coreContainer.parentNode.insertBefore(clone, document.getElementById('corebtn'));

        var clonedQuillContainer = clone.querySelector('#kt_quil_2_' + loopIndex);
        var clonedQuillEditor = new Quill(clonedQuillContainer, {
            modules: {
                toolbar: [
                    [{ header: [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'bullet' }],
                    ['link']
                ]
            },
            theme: 'snow'
        });
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'coremodule[]';

        // Listen for the 'text-change' event on Quill
        clonedQuillEditor.on('text-change', function () {

        // Get the HTML content from the Quill editor
        var content = clonedQuillEditor.root.innerHTML;
        hiddenInput.value = content;

        var form = document.getElementById('addcourse');
        form.appendChild(hiddenInput);

        });

        loopIndex ++;

        var removeBtn = document.createElement("button");
        removeBtn.innerHTML = '<i class="bi bi-trash remove-core-btn"></i>';
        removeBtn.classList.add("btn","remove-core-btn","float-end","mb-3");
        removeBtn.onclick = function() {
            clone.remove();
        };
        clone.appendChild(removeBtn);
    });

    document.querySelector('.electivebtn').addEventListener('click', function() {
        var electiveContainer = document.getElementById('electiveContainer');
        var clone = electiveContainer.cloneNode(true);

        clone.querySelector('[name="title2[]"]').value = '';

        var clonedQuillContainer = clone.querySelector('#kt_quil_3_0');
        clonedQuillContainer.id = 'kt_quil_3_' + loopIndex;

        var clonedQuill = clone.querySelector('.ql-editor');
        clonedQuill.innerHTML = '';

        var firstToolbar = clone.querySelector('.ql-toolbar');
        if (firstToolbar) {
            firstToolbar.parentNode.removeChild(firstToolbar);
        }

        electiveContainer.parentNode.insertBefore(clone, document.getElementById('electivebtn'));

        var clonedQuillContainer = clone.querySelector('#kt_quil_3_' + loopIndex);
        var clonedQuillEditor = new Quill(clonedQuillContainer, {
            modules: {
                toolbar: [
                    [{ header: [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'bullet' }],
                    ['link']
                ]
            },
            theme: 'snow'
        });
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'electivemodule[]';

        // Listen for the 'text-change' event on Quill
        clonedQuillEditor.on('text-change', function () {

        // Get the HTML content from the Quill editor
        var content = clonedQuillEditor.root.innerHTML;
        hiddenInput.value = content;

        var form = document.getElementById('addcourse');
        form.appendChild(hiddenInput);

        });

        loopIndex ++;

        var removeBtn = document.createElement("button");
        removeBtn.innerHTML = '<i class="bi bi-trash remove-elective-btn"></i>';
        removeBtn.classList.add("btn","remove-elective-btn","float-end","mb-3");
        removeBtn.onclick = function() {
            clone.remove();
        };
        clone.appendChild(removeBtn);
    });

    document.querySelector('.otherbtn').addEventListener('click', function() {
        var otherContainer = document.getElementById('otherContainer');
        var clone = otherContainer.cloneNode(true);

        clone.querySelector('[name="title3[]"]').value = '';

        var clonedQuillContainer = clone.querySelector('#kt_quil_4_0');
        clonedQuillContainer.id = 'kt_quil_4_' + loopIndex;

        var clonedQuill = clone.querySelector('.ql-editor');
        clonedQuill.innerHTML = '';

        var firstToolbar = clone.querySelector('.ql-toolbar');
        if (firstToolbar) {
            firstToolbar.parentNode.removeChild(firstToolbar);
        }

        otherContainer.parentNode.insertBefore(clone, document.getElementById('otherbtn'));

        var clonedQuillContainer = clone.querySelector('#kt_quil_4_' + loopIndex);
        var clonedQuillEditor = new Quill(clonedQuillContainer, {
            modules: {
                toolbar: [
                    [{ header: [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'bullet' }],
                    ['link']
                ]
            },
            theme: 'snow'
        });
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'othermodule[]';

        // Listen for the 'text-change' event on Quill
        clonedQuillEditor.on('text-change', function () {

        // Get the HTML content from the Quill editor
        var content = clonedQuillEditor.root.innerHTML;
        hiddenInput.value = content;

        var form = document.getElementById('addcourse');
        form.appendChild(hiddenInput);

        });

        loopIndex ++;

        var removeBtn = document.createElement("button");
        removeBtn.innerHTML = '<i class="bi bi-trash remove-other-btn"></i>';
        removeBtn.classList.add("btn","remove-other-btn","float-end","mb-3");
        removeBtn.onclick = function() {
            clone.remove();
        };
        clone.appendChild(removeBtn);
    });

    function addExtraFees() {
        var feesContainer1 = document.getElementById('feesContainer1');
        var clonedfeesContainer = feesContainer1.cloneNode(true);

        var count = document.querySelectorAll('[id^="feesContainer"]').length + 1;
        clonedfeesContainer.id = 'feesContainer' + count;

        clonedfeesContainer.querySelector('h1').textContent = 'Extra Fees ' + count;

        var selectElements = clonedfeesContainer.querySelectorAll('select');
        selectElements.forEach(function(select, index) {
            if (select.name === 'extrafeestype1') {
                select.id = 'extrafeestype' + count;
                select.name = 'extrafeestype' + count;
            } else if (select.name === 'extrafeesfrequency1') {
                select.id = 'extrafeesfrequency' + count;
                select.name = 'extrafeesfrequency' + count;
            }
            select.selectedIndex = 0;
        });

        var inputElements = clonedfeesContainer.querySelectorAll('input[type="number"]');
        inputElements.forEach(function(input, index) {
            input.id = input.id.replace(/[0-9]+/, count);
            input.name = input.name.replace(/[0-9]+/, count);
            input.value = '';
        });

        var inputElements = clonedfeesContainer.querySelectorAll('input[type="text"]');
        inputElements.forEach(function(input, index) {
            input.id = input.id.replace(/[0-9]+/, count);
            input.name = input.name.replace(/[0-9]+/, count);
            input.value = '';
        });

        var extrafeesFrequency = clonedfeesContainer.querySelector('#extrafeesFrequency1');
        extrafeesFrequency.id = 'extrafeesFrequency' + count;

        // Append the cloned feesContainer2 to the extrafeesContainer
        var extrafeesContainer = document.getElementById('extrafeesContainer');
        extrafeesContainer.appendChild(clonedfeesContainer);

        var clonedContainers = document.querySelectorAll('[id^="feesContainer"]');
        var lastClonedContainer = clonedContainers[clonedContainers.length - 1];
        var addExtraFeesBtn = document.getElementById('addextrafeesbtn');
        lastClonedContainer.insertAdjacentElement('afterend', addExtraFeesBtn);

        // Check the count and hide the remove button if there's only one feesContainer
        if (clonedContainers.length === 1) {
            $('#feesContainer1 .remove-extrafees-btn').hide();
        } else {
            $('.remove-extrafees-btn').show(); // Ensure the button is shown for other containers
        }

    }

    function showSection1() {
        // Show Sub Category Section
        document.getElementById('section1').style.display = 'block';

        // Hide Qualifications Section
        document.getElementById('section2').style.display = 'none';
    }

    var subjectRowCounter = 1;

    $(document).ready(function() {
    $('.uniselect').select2();
    $('.levelselect').select2();
    $('.coursemonthselect').select2();
    $('.representlevel').select2();
    $('.talentlevel').select2();
    $('.leadershiplevel').select2();
    $('.coursemaincat').select2();
    $('.coursesubcat').select2();
    /* $('.qlevel').select2();
    $('.subject').select2();
    $('.grade').select2(); */


    $('input[name="courseinternship"]').change(function () {
            if ($('#courseinternship_yes').prop('checked')) {
                $('#internshipDurationRow').show();
            } else {
                $('#internshipDurationRow').hide();
            }
    });

    $('input[name="coursesportsrepresent"]').change(function () {
            if ($('#coursesportsrepresent_yes').prop('checked')) {
                $('#representlevel').show();
            } else {
                $('#representlevel').hide();
            }
    });

    $('input[name="coursetalentrepresent"]').change(function () {
            if ($('#coursetalentrepresent_yes').prop('checked')) {
                $('#talentlevel').show();
            } else {
                $('#talentlevel').hide();
            }
    });

    $('input[name="courseleadership"]').change(function () {
            if ($('#courseleadership_yes').prop('checked')) {
                $('#leadershiplevel').show();
            } else {
                $('#leadershiplevel').hide();
            }
    });

    $('input[name="extrafees"]').change(function () {
            if ($('#extrafees_yes').prop('checked')) {
                $('#extrafeesContainer').show();
            } else {
                $('#extrafeesContainer').hide();
            }
    });

    // $(document).on('change', '[id^="extrafeestype"]', function() {
    //     var selectedType = $(this).val();
    //     var frequencyId = $(this).attr('id').replace('extrafeestype', 'extrafeesFrequency');

    //     if (selectedType == 2) {
    //         $('#' + frequencyId).show();
    //     } else {
    //         $('#' + frequencyId).hide();
    //     }
    // });

    $(document).on('click', '.remove-extrafees-btn', function() {
        $(this).closest('[id^="feesContainer"]').remove();
    });

    $(document).on('change', '#coursemaincat', function () {
    var mainCategoryId = $(this).val();
    // Clear existing options in the subcategory select
    $('#coursesubcat').empty();
    $('#coursesubcat').append('<option value="" selected disabled>Select Sub Category</option>');

    // If a main category is selected, fetch and append subcategories
    if (mainCategoryId) {
        $.ajax({
            type: 'GET',
            url: '/admin/get-subcategories',
            data: { main_category_id: mainCategoryId },
            success: function (data) {
                console.log(data)
                // Append subcategories to the select
                $.each(data.list, function (index, subcategory) {
                    $('#coursesubcat').append('<option value="' + subcategory.sc_id + '">' + subcategory.sc_name + '</option>');
                });
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    }
    });

        // Function to handle subject and grade population
        function populateSubjectAndGrade(qLevelId, mingradeSelector, subjectSelector, gradeSelector) {
            // Clear existing options
            $(mingradeSelector).empty().append('<option value="" selected disabled>Select Minimum Grade</option>');
            $(subjectSelector).empty().append('<option value="" selected disabled>Select Subject</option>');
            $(gradeSelector).empty().append('<option value="" selected disabled>Select Grade</option>');

            if (qLevelId) {
                $.ajax({
                    type: 'GET',
                    url: '/admin/get-mingrade',
                    data: { qualification_level_id: qLevelId },
                    success: function (data) {
                        console.log(data);

                        // Append grades to the grade select
                        $.each(data.list, function (index, mingrade) {
                            $(mingradeSelector).append('<option value="' + mingrade.sg_id + '">' + mingrade.sg_grade + '</option>');
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: '/admin/get-qlevel',
                    data: { qualification_level_id: qLevelId },
                    success: function (data) {
                        console.log(data);

                        // Append subjects to the subject select
                        $.each(data.list, function (index, subject) {
                            $(subjectSelector).append('<option value="' + subject.s_id + '">' + subject.s_name + '</option>');
                        });

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

                $.ajax({
                    type: 'GET',
                    url: '/admin/get-qlevelgrade',
                    data: { qualification_level_id: qLevelId },
                    success: function (data) {
                        console.log(data);

                        // Append grades to the grade select
                        $.each(data.list, function (index, grade) {
                            $(gradeSelector).append('<option value="' + grade.sg_id + '">' + grade.sg_grade + '</option>');
                        });
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });
                }
            }
        

        // Initial change event handler
        $(document).on('change', '[id^=qlevel]', function () {
            var qLevelId = $(this).val();
            var index = $(this).attr('id').match(/\d+$/);
            var mingradeSelector = $(this).closest('.mb-3.row').find('#mingrade'  + index);
            var subjectSelector = $(this).closest('.mb-3.row').find('#subject'  + index);
            var gradeSelector = $(this).closest('.mb-3.row').find('#grade'  + index);
            populateSubjectAndGrade(qLevelId, '.mingrade'  + index, '.subject'  + index, '.grade'  + index);
        });

        // Add Subject Button Click Event
        $(document).on('click', '[id^=addSubject]', function () {

            // Extract the set ID from the button's ID
            var setId = $(this).attr("id").replace("addSubject", "");
            console.log(setId);
            // Clone the existing subject and grade row
            var newRow = $("#subjectRow" + setId).clone();

            // Remove the text from the cloned label
            newRow.find('label').text('');

            var removeButton = '<button type="button" class="btn btn-danger deleteSubjectRow"><i class="bi bi-dash"></i></button>';
            newRow.find('.col-sm-2').last().append(removeButton);

            // Insert the new row immediately after the current row
            $('#subjectRow' + setId).after(newRow);
            
        });

        // $('.subject-row:first .deleteSubjectRow').hide(); 

        $(document).on('click', '.deleteSubjectRow', function () {
            // Remove the subject row when the delete button is clicked
            $(this).closest('.mb-3.row').remove();
        });
    });

    function addQualification() {
        // Clone the entire wrapper
        var cloneWrapper = $("#qualificationWrapper").clone();

        // Update IDs to make them unique
        var newIndex = $(".qlevel").length + 1;
        cloneWrapper.find("#qualificationWrapper").attr("id", "qualificationWrapper" + newIndex);
        cloneWrapper.find(".qlevel").attr("id", "qlevel" + newIndex).attr("name", "qlevel" + newIndex).attr("class", "form-select qlevel");
        cloneWrapper.find(".minsubject1").attr("id", "minsubject" + newIndex).attr("name", "minsubject" + newIndex);
        cloneWrapper.find(".mingrade1").attr("id", "mingrade" + newIndex).attr("name", "mingrade" + newIndex).attr("class", "form-select mingrade" + newIndex);
        cloneWrapper.find("#subjectRow1:not(:first)").remove();
        cloneWrapper.find("#subjectRow1").attr("id", "subjectRow" + newIndex);
        cloneWrapper.find(".subject1").attr("id", "subject" + newIndex).attr("class", "form-select subject" + newIndex).attr("name", "subject" + newIndex +"[]");
        cloneWrapper.find(".grade1").attr("id", "grade" + newIndex).attr("class", "form-select grade" + newIndex).attr("name", "grade" + newIndex + "[]");
        cloneWrapper.find("#addSubject1").attr("id", "addSubject" + newIndex);

        // Update the 'for' attribute of labels to match the new IDs
        cloneWrapper.find("label[for='qlevel']").attr("for", "qlevel" + newIndex);
        cloneWrapper.find("label[for='minsubject']").attr("for", "minsubject" + newIndex);
        cloneWrapper.find("label[for='subject']").attr("for", "subject" + newIndex);

        // Add the class 'qualification-set' to the cloned wrapper
         cloneWrapper.addClass("qualification-set");
        
        // Append the cloned wrapper to the form
        $("#qualificationWrapper").parent().append(cloneWrapper);

        // Clear the selected values in the cloned wrapper
        cloneWrapper.find("select").val("");

        // Update the labels for the new set
        cloneWrapper.find("label[for='qlevel']").text("Qualification Level (Set " + newIndex + ")");
        cloneWrapper.find("label[for='minsubject']").text("Minimum Subjects (Set " + newIndex + ")");
        cloneWrapper.find("label[for='subject']").text("Subjects (Set " + newIndex + ")");

        cloneWrapper.find('#deletebtn').append('<button type="button" class="deleteSetButton float-end"><i class="bi bi-trash"></i></button>');

        // $("#footer").appendTo("#qualificationWrapper:last");
        // $("#footer").appendTo("#footer1");
        $("#footer").appendTo($("#qualificationWrapper").parent());
    }

    $(document).on("click", ".deleteSetButton", function() {
        $(this).closest(".qualification-set").remove();
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
            [{ 'list': 'bullet' }],
            ['link']
        ]
    },
    placeholder: 'Type your text here...',
    theme: 'snow' // or 'bubble'
    });

    // Listen for the 'text-change' event on Quill
    quill.on('text-change', function () {
    // Get the HTML content from the Quill editor
    var content = quill.root.innerHTML;

    // Dynamically create an input field with the Quill content
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'courseoutline';
    hiddenInput.value = content;

    // Append the input field to the form
    var form = document.getElementById('addcourse');
    form.appendChild(hiddenInput);
    });
    }

    ///////
    var demo2 = function () {
    var quill = new Quill('#kt_quil_2_0', {
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
    placeholder: 'Enter core modules...',
    theme: 'snow' // or 'bubble'
    });
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'coremodule[]';

    // Listen for the 'text-change' event on Quill
    quill.on('text-change', function () {

    // Get the HTML content from the Quill editor
    var content = quill.root.innerHTML;
    hiddenInput.value = content;

    var form = document.getElementById('addcourse');
    form.appendChild(hiddenInput);

    });

    }

    //////////////////////////
    var demo3 = function () {
    var quill = new Quill('#kt_quil_3_0', {
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
    placeholder: 'Enter elective modules...',
    theme: 'snow' // or 'bubble'
    });
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'electivemodule[]';

    // Listen for the 'text-change' event on Quill
    quill.on('text-change', function () {

    // Get the HTML content from the Quill editor
    var content = quill.root.innerHTML;
    hiddenInput.value = content;

    var form = document.getElementById('addcourse');
    form.appendChild(hiddenInput);

    });
    }
    //////

    var demo4 = function () {
    var quill = new Quill('#kt_quil_4_0', {
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
    placeholder: 'Enter other modules...',
    theme: 'snow' // or 'bubble'
    });
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'othermodule[]';

    // Listen for the 'text-change' event on Quill
    quill.on('text-change', function () {

    // Get the HTML content from the Quill editor
    var content = quill.root.innerHTML;
    hiddenInput.value = content;

    var form = document.getElementById('addcourse');
    form.appendChild(hiddenInput);

    });
    }
    //////
    var demo5 = function () {
    var quill = new Quill('#kt_quil_5', {
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
    theme: 'snow' // or 'bubble'
    });

    // Listen for the 'text-change' event on Quill
    quill.on('text-change', function () {
    // Get the HTML content from the Quill editor
    var content = quill.root.innerHTML;

    // Dynamically create an input field with the Quill content
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'entryreq';
    hiddenInput.value = content;

    // Append the input field to the form
    var form = document.getElementById('addcourse');
    form.appendChild(hiddenInput);
    });
    }
    //////
    

    return {
    // public functions
    init: function () {
    demo1();
    demo2();
    demo3();
    demo4();
    demo5();
    // demo2_1();
    }
    };
    }();

    jQuery(document).ready(function () {
    KTQuilDemos.init();
    });
</script>
@endsection