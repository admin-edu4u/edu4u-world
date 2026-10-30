@extends('adminLayout')
@section('style')
<style>
    .editcourseform {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
        height:auto;
    }

    #qualificationSet {
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
    .remove-other-btn{
        top: 5px;
        right: 5px;
        z-index: 9999; /* Adjust the value as needed */
        background-color: red; /* Red background color */
        border-radius: 5px;
        border-color: white;
    }

    .remove-core-btn i,
    .remove-elective-btn i,
    .remove-other-btn i {
        color:white;
    }
</style>
@endsection

@section('content')
    @include('formMsg')
        @php
            $c_id = request('c_id');
            $courseModel = new \App\Models\Course();
            $courseData = $courseModel->find($c_id);
            $coreCourses = json_decode($courseData->c_core) ?? [];
            $electiveCourses = json_decode($courseData->c_elective) ?? [];
            $otherCourses = json_decode($courseData->c_other) ?? [];

            $courseGrades = \App\Models\CourseGrade::where('cg_c_id', $c_id)->get();
            $courseGradesData = \App\Models\CourseGrade::where('cg_c_id', $c_id)->pluck('cg_grades');
            if ($courseGradesData->isEmpty() || $courseGradesData->contains(null)) {
                $courseGradesData = false;
            } else {
                $courseGradesData = true;
            }

            $courseFees = \App\Models\CourseFee::where('cf_c_id', $c_id)->where('cf_status', '1')->get();
            $extraFeesChecked = $courseFees->isNotEmpty() ? '1' : '0'; // Check if $courseFees has data
        @endphp
<div class="editcourseform">
<form id="editcourse" class="form-horizontal" method="post" action="/admin/editCourse">
<div id="section1">
    <h1><strong>Edit Course</strong></h1>

    <input type="hidden" name="c_id" value="{{ $courseData->c_id }}" readonly>

    <div class="mb-3 row">
        <label for="editcourseuni" class="col-sm-2 col-form-label">University</label>
        <div class="col-sm-10">
            <select name="editcourseuni" class="form-select uniselect" id="courseuni" required>
                <option value="" selected disabled>Select University</option>
                @foreach ($university as $u)
                    <option value="{{ $u->u_id }}" {{ $u->u_id == $courseData->c_uni ? 'selected' : '' }}>
                        {{ $u->u_name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="editcoursename" class="col-sm-2 col-form-label">Course Name</label>
        <div class="col-sm-10">
            <input type="text" name="editcoursename" class="form-control" id="coursename" placeholder="Enter course name..." value="{{ $courseData->c_name }}" required>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">English Required</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="engreq" value="1" {{ $courseData->c_english_required == 1 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="engreq" value="0" {{ $courseData->c_english_required == 0 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3">No</label>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="editcourselevel" class="col-sm-2 col-form-label">Level</label>
        <div class="col-sm-10">
            <select name="editcourselevel" class="form-select levelselect" id="courselevel" required>
                <option value="" selected disabled>Select Level</option>
                @foreach ($level as $value => $l)
                    <option value="{{ $value }}" {{ $value == $courseData->c_level ? 'selected' : '' }}>
                        {{ $l }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="editcoursestream" class="col-sm-2 col-form-label">Stream</label>
        <div class="col-sm-10">
            <select name="editcoursestream" class="form-select streamselect" id="coursestream" required>
                <option value="" selected disabled>Select Stream</option>
                @foreach ($stream as $value => $s)
                    <option value="{{ $value }}" {{ $value == $courseData->c_stream ? 'selected' : '' }}>
                        {{ $s }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="editcoursemonth" class="col-sm-2 col-form-label">Intake Months</label>
        <div class="col-sm-10">
            @php
                // Explode the string into an array
                $selectedMonths = explode(',', $courseData->c_intake);
            @endphp
            <select name="editcoursemonth[]" class="form-select coursemonthselect" multiple="multiple" id="coursemonth" required>
                @foreach (\App\Models\Course::monthArr() as $value => $month)
                    <option value="{{ $value }}" {{ in_array($value, $selectedMonths) ? 'selected' : '' }}>
                        {{ $month }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="editcourseduration" class="col-sm-2 col-form-label">Course Duration (Month)</label>
        <div class="col-sm-10">
            <input type="number" name="editcourseduration" class="form-control" id="courseduration" placeholder="Enter course duration..."  step="any" value="{{ $courseData->c_duration }}" required>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="editcourseaward" class="col-sm-2 col-form-label">Course Award</label>
        <div class="col-sm-10">
            <input type="text" name="editcourseaward" class="form-control" id="courseaward" placeholder="Enter course award..." value="{{ $courseData->c_award }}" required>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="editcourseinternship" class="col-sm-2 col-form-label">Internship</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="editcourseinternship" id="courseinternship_yes" value="1" {{ $courseData->c_has_internship == 1 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3" for="courseinternship_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="editcourseinternship" id="courseinternship_no" value="0" {{ $courseData->c_has_internship == 0 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3" for="courseinternship_no">No</label>
            </div>
        </div>
    </div>

    <div class="mb-3 row" id="internshipDurationRow" style="display:none;">
        <label for="editinternduration" class="col-sm-2 col-form-label">Internship Duration (Month)</label>
        <div class="col-sm-10">
            <input type="number" name="editinternduration" class="form-control" id="internduration" placeholder="Enter internship duration..." value="{{ $courseData->c_internship_duration }}"  step="any">
        </div>
    </div>

    <div class="mb-3 row">
        <label for="editcourseoutline" class="col-sm-2 col-form-label">Course Outline</label>
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
                        @php
                            $coreCourses = json_decode($courseData->c_core) ?? [];
                        @endphp
                        @forelse($coreCourses as $index => $c)
                        <div id="coreContainer{{ $index }}">
                            <div class="mt-3"></div>
                            <input class="form-control" type="text" name="title1[]" value="{{ $c->title }}" placeholder="Enter title..." required>
                            <div class="mt-3"></div>
                            <div class="kt_quil_2" id="kt_quil_2_{{ $index }}" style="height: 100px"></div>
                            <button type="button" class="btn btn-danger float-end remove-core mt-3 mb-3" data-index="{{ $index }}"><i class="bi bi-trash"></i></button>
                            <div class="mb-3"></div>
                        </div>
                        @empty
                        <div id="coreContainer0">
                            <div class="mt-3"></div>
                            <input class="form-control" type="text" name="title1[]" placeholder="Enter title..." required>
                            <div class="mt-3"></div>
                            <div class="kt_quil_2" id="kt_quil_2_0" style="height: 100px"></div>
                            <button type="button" class="btn btn-danger float-end remove-core mt-3 mb-3" data-index="0"><i class="bi bi-trash"></i></button>
                            <div class="mb-3"></div>
                        </div>
                        @endforelse
                        <div id="corebtn" class="mt-3"><button class="btn btn-secondary corebtn" type="button">+ Core</button></div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">
                        @php
                            $electiveCourses = json_decode($courseData->c_elective) ?? [];
                        @endphp
                        @forelse($electiveCourses as $index => $e)
                        <div id="electiveContainer{{ $index }}">
                            <div class="mt-3"></div>
                            <input class="form-control" type="text" name="title2[]" value="{{ $e->title }}" placeholder="Enter title...">
                            <div class="mt-3"></div>
                            <div class="kt_quil_3" id="kt_quil_3_{{ $index }}" style="height: 100px"></div>
                            <button type="button" class="btn btn-danger float-end remove-elective mt-3 mb-3" data-index="{{ $index }}"><i class="bi bi-trash"></i></button>
                            <div class="mb-3"></div>
                        </div>
                        @empty
                        <div id="electiveContainer0">
                            <div class="mt-3"></div>
                            <input class="form-control" type="text" name="title2[]" placeholder="Enter title...">
                            <div class="mt-3"></div>
                            <div class="kt_quil_3" id="kt_quil_3_0" style="height: 100px"></div>
                            <button type="button" class="btn btn-danger float-end remove-elective mt-3 mb-3" data-index="0"><i class="bi bi-trash"></i></button>
                            <div class="mb-3"></div>
                        </div>
                        @endforelse
                        <div id="electivebtn" class="mt-3"><button class="btn btn-success electivebtn" type="button">+ Elective</button></div>
                    </div>
                    <div class="tab-pane fade" id="kt_tab_pane_6" role="tabpanel">
                        @php
                            $otherCourses = json_decode($courseData->c_other) ?? [];
                        @endphp
                        @forelse($otherCourses as $index => $o)
                        <div id="otherContainer{{ $index }}">
                            <div class="mt-3"></div>
                            <input class="form-control" type="text" name="title3[]" value="{{ $o->title }}" placeholder="Enter title...">
                            <div class="mt-3"></div>
                            <div class="kt_quil_4" id="kt_quil_4_{{ $index }}" style="height: 100px"></div>
                            <button type="button" class="btn btn-danger float-end remove-other mt-3 mb-3" data-index="{{ $index }}"><i class="bi bi-trash"></i></button>
                            <div class="mb-3"></div>
                        </div>
                        @empty
                        <div id="otherContainer0">
                            <div class="mt-3"></div>
                            <input class="form-control" type="text" name="title3[]" placeholder="Enter title...">
                            <div class="mt-3"></div>
                            <div class="kt_quil_4" id="kt_quil_4_0" style="height: 100px"></div>
                            <button type="button" class="btn btn-danger float-end remove-other mt-3 mb-3" data-index="0"><i class="bi bi-trash"></i></button>
                            <div class="mb-3"></div>
                        </div>
                        @endforelse
                        <div id="otherbtn" class="mt-3"><button class="btn btn-primary otherbtn" type="button">+ Other</button></div>
                    </div>
                </div>
            </div>
    </div>

    <div class="mb-3 row">
        <label for="editcourseisfor" class="col-sm-2 col-form-label">Course Is For</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="editcourseisfor" value="1" {{ $courseData->c_is_malaysian == 1 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3">Malaysian</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="editcourseisfor" value="0" {{ $courseData->c_is_malaysian == 0 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3">Foreign</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="editcourseisfor" value="2" {{ $courseData->c_is_malaysian == 2 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3">Both</label>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="editcoursesportsrepresent" class="col-sm-2 col-form-label">Represent In Sports</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="editcoursesportsrepresent" id="coursesportsrepresent_yes" value="1" {{ $courseData->c_represent_sport == 1 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3" for="coursesportsrepresent_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="editcoursesportsrepresent" id="coursesportsrepresent_no" value="0" {{ $courseData->c_represent_sport == 0 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3" for="coursesportsrepresent_no">No</label>
            </div>
        </div>
    </div>

    <div class="mb-3 row" id="representlevel" style="display:none;">
        <label for="editrepresentlevel" class="col-sm-2 col-form-label">Represent Level</label>
            <div class="col-sm-10">
                <select name="editrepresentlevel" class="form-select representlevel" id="representlevel">
                    <option value="" selected disabled>Select Level</option>
                    @foreach (\App\Models\Course::rlevelArr() as $value => $rlevel)
                        <option value="{{ $value }}" {{ $value == $courseData->c_represent_level ? 'selected' : '' }}>
                            {{ $rlevel }}
                        </option>
                    @endforeach
                </select>
            </div>
    </div>

    <div class="mb-3 row">
        <label for="editcoursetalentrepresent" class="col-sm-2 col-form-label">Represent In Talent</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="editcoursetalentrepresent" id="coursetalentrepresent_yes" value="1" {{ $courseData->c_talent == 1 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3" for="coursetalentrepresent_yes">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="editcoursetalentrepresent" id="coursetalentrepresent_no" value="0" {{ $courseData->c_talent == 0 ? 'checked' : '' }} required>
                <label class="form-check-label mt-3" for="coursetalentrepresent_no">No</label>
            </div>
        </div>
    </div>

    <div class="mb-3 row" id="talentlevel" style="display:none;">
        <label for="edittalentlevel" class="col-sm-2 col-form-label">Represent Level</label>
            <div class="col-sm-10">
                <select name="edittalentlevel" class="form-select talentlevel" id="talentlevel">
                    <option value="" selected disabled>Select Level</option>
                    @foreach (\App\Models\Course::rlevelArr() as $value => $rlevel)
                        <option value="{{ $value }}" {{ $value == $courseData->c_talent_level ? 'selected' : '' }}>
                            {{ $rlevel }}
                        </option>
                    @endforeach
                </select>
            </div>
    </div>

    <div class="mb-3 row">
        <label for="editcourseleadership" class="col-sm-2 col-form-label">Hold Any Leadership Role</label>
            <div class="col-sm-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input mt-3" type="radio" name="editcourseleadership" id="courseleadership_yes" value="1" {{ $courseData->c_leadership == 1 ? 'checked' : '' }} required>
                    <label class="form-check-label mt-3" for="courseleadership_yes">Yes</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input mt-3" type="radio" name="editcourseleadership" id="courseleadership_no" value="0" {{ $courseData->c_leadership == 0 ? 'checked' : '' }} required>
                    <label class="form-check-label mt-3" for="courseleadership_no">No</label>
                </div>
            </div>
    </div>

    <div class="mb-3 row" id="leadershiplevel" style="display:none;">
        <label for="editleadershiplevel" class="col-sm-2 col-form-label">Leadership Level</label>
            <div class="col-sm-10">
                <select name="editleadershiplevel" class="form-select leadershiplevel" id="leadershiplevel">
                    <option value="" selected disabled>Select Level</option>
                    @foreach (\App\Models\Course::rlevelArr() as $value => $rlevel)
                        <option value="{{ $value }}" {{ $value == $courseData->c_leadership_level ? 'selected' : '' }}>
                            {{ $rlevel }}
                        </option>
                    @endforeach
                </select>
            </div>
    </div>

    <div class="mb-3 row">
        <label for="editcoursebumiputra" class="col-sm-2 col-form-label">Course Is For</label>
            <div class="col-sm-10">
                <div class="form-check form-check-inline">
                    <input class="form-check-input mt-3" type="radio" name="editcoursebumiputra" value="1" {{ $courseData->c_is_bumiputra == 1 ? 'checked' : '' }} required>
                    <label class="form-check-label mt-3">Bumiputra</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input mt-3" type="radio" name="editcoursebumiputra" value="0" {{ $courseData->c_is_bumiputra == 0 ? 'checked' : '' }} required>
                    <label class="form-check-label mt-3">Everyone</label>
                </div>
            </div>
    </div>

    <div class="mb-3 row">
        <label for="editcoursemaincat" class="col-sm-2 col-form-label">Main Category</label>
            <div class="col-sm-10">
                <select class="form-select coursemaincat" name="editcoursemaincat" id="coursemaincat" required>
                    <option value="" selected disabled>Select Main Category</option>
                    @foreach ($category as $c)
                        <option value="{{ $c->c_id }}" {{ $c->c_id == $courseData->c_main_cat ? 'selected' : '' }}>
                            {{ $c->c_name }}
                        </option>
                    @endforeach
                </select>
            </div>
    </div>

    <div class="mb-3 row">
        <label for="editcoursesubcat" class="col-sm-2 col-form-label">Sub Category</label>
            <div class="col-sm-10">
                <select class="form-select coursesubcat" name="editcoursesubcat" id="coursesubcat" required>
                    <option value="" selected disabled>Select Sub Category</option>
                    @foreach ($subcategory as $sc)
                        <option value="{{ $sc->sc_id }}" {{ $sc->sc_id == $courseData->c_sub_cat ? 'selected' : '' }}>
                            {{ $sc->sc_name }}
                        </option>
                    @endforeach
                </select>
            </div>
    </div>

    <div class="mb-3 row">
        <label for="editcoursename" class="col-sm-2 col-form-label">Amount Non Bumi</label>
        <div class="col-sm-10">
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                <input type="number" name="editcourseamount" class="form-control" id="editcourseamount" placeholder="Enter course amount..." value="{{ $courseData->c_amount }}" required>
            </div>
        </div>
    </div>
    
    <div class="mb-3 row">
        <label for="editcoursename" class="col-sm-2 col-form-label">Amount Bumiputra</label>
        <div class="col-sm-10">
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                <input type="number" name="editcourseamountbumi" class="form-control" id="editcourseamountbumi" placeholder="Enter course amount..." value="{{ $courseData->c_amount_bumi }}" required>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label for="editcoursename" class="col-sm-2 col-form-label">Amount Foreinger</label>
        <div class="col-sm-10">
            <div class="input-group">
                <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                <input type="number" name="editcourseamountfore" class="form-control" id="editcourseamountfore" placeholder="Enter course amount..." value="{{ $courseData->c_amount_international }}" required>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Extra Fees</label>
        <div class="col-sm-10">
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="extrafees" id="extrafees_yes" value="1" {{ $extraFeesChecked == '1' ? 'checked' : '' }} required>
                <label class="form-check-label mt-3">Yes</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input mt-3" type="radio" name="extrafees" id="extrafees_no" value="0" {{ $extraFeesChecked == '0' ? 'checked' : '' }} required>
                <label class="form-check-label mt-3">No</label>
            </div>
        </div>
    </div>


    <div id="extrafeesContainer" style="display:none;">
    @foreach($courseFees as $cfIndex => $fee)
        <div id="feesContainer{{ $cfIndex + 1 }}">
            <hr>
            <div class="mb-3 row"><h1 class="col-sm-2">Extra Fees {{ $cfIndex + 1 }}</h1><div class="col-sm-10"><button type="button" class="btn float-end remove-extrafees-btn"><i class="bi bi-trash"></i></button></div></div>
            <div class="mb-3 row">
                <label class="col-sm-2 col-form-label">Fees Type</label>
                <div class="col-sm-10">
                    <select name="extrafeestype{{ $cfIndex + 1 }}" class="form-select" id="extrafeestype{{ $cfIndex + 1 }}">
                        <option value="" selected disabled>Select extra fees type...</option>
                        @foreach (\App\Models\CourseFee::typeArr() as $value => $type)
                            <option value="{{ $value }}" {{ $fee->cf_type == $value ? 'selected' : '' }}>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div id="extrafeesFrequency{{ $cfIndex + 1 }}">
                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Payment Frequency</label>
                    <div class="col-sm-10">
                        <select name="extrafeesfrequency{{ $cfIndex + 1 }}" class="form-select">
                            <option value="" selected disabled>Select extra fees payment frequency...</option>
                            @foreach (\App\Models\CourseFee::frequencyArr() as $value => $frequency)
                                <option value="{{ $value }}" {{ $fee->cf_payment_frequency == $value ? 'selected' : '' }}>{{ $frequency }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">Fees Name</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="extrafeesname{{ $cfIndex + 1 }}" placeholder="Enter extra fees name..." value="{{ $fee->cf_name }}">
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">For Non Bumi</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                            <input type="number" name="extrafeesamount{{ $cfIndex + 1 }}" class="form-control" id="extrafeesamount{{ $cfIndex + 1 }}" placeholder="Enter extra fees amount for non bumiputra..." value="{{ $fee->cf_amount }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">For Bumiputra</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                            <input type="number" name="extrafeesamountbumi{{ $cfIndex + 1 }}" class="form-control" id="extrafeesamountbumi{{ $cfIndex + 1 }}" placeholder="Enter extra fees amount for bumiputra..." value="{{ $fee->cf_amount_bumi }}">
                        </div>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label">For Foreigner</label>
                    <div class="col-sm-10">
                        <div class="input-group">
                            <div class="input-group-prepend"><span class="input-group-text">RM</span></div>
                            <input type="number" name="extrafeesamountfore{{ $cfIndex + 1 }}" class="form-control" id="extrafeesamountfore{{ $cfIndex + 1 }}" placeholder="Enter extra fees amount for foreinger..." value="{{ $fee->cf_amount_international }}">
                        </div>
                    </div>
                </div>
        </div>
    @endforeach
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
    @php
        $loopIndex = 0;
    @endphp
    @if ($courseGradesData === true)
        @foreach ($courseGrades as $index => $courseGrade)
            <div id="qualificationSet">
                <div class="mb-3 row">
                    <label for="qualificationlevel" class="col-sm-2 col-form-label">Qualification Level</label>
                    <div class="col-sm-8">
                        <select class="form-select editqlevel" name="editqlevel{{ $index + 1 }}" id="editqlevel{{ $index + 1 }}" required>
                            <option value="" selected disabled>Select Qualification Level</option>
                            @foreach ($qlevel as $value => $ql)
                                <option value="{{ $value }}" {{ $courseGrade->cg_level == $value ? 'selected' : '' }}>
                                    {{ $ql }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="minsubject" class="col-sm-2 col-form-label">Minimum Subjects</label>
                    <div class="col-sm-4">
                        <input type="number" name="editminsubject{{ $index + 1 }}" class="form-control editminsubject{{ $index + 1 }}" id="editminsubject{{ $index + 1 }}" placeholder="Enter minimum subject..." value="{{ $courseGrade->cg_min }}" required>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-select editmingrade{{ $index + 1 }}" name="editmingrade{{ $index + 1 }}" id="editmingrade{{ $index + 1 }}" required>
                            <option value="" selected disabled>Please Select Qualification Level</option>
                        </select>
                    </div>
                </div>

                @foreach(json_decode($courseGrade->cg_grades) as $grade)
                    <div class="mb-3 row subject-row" id="subjectRow{{ $index + 1 }}">
                        <label for="subject" class="col-sm-2 col-form-label"></label>
                        <div class="col-sm-4">
                            <select class="form-select editsubject{{ $index + 1 }}" name="editsubject{{ $index + 1 }}[]" id="editsubject{{ $index + 1 }}_{{ $loopIndex }}" required>
                                <option value="" selected disabled>Please Select Subject</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <select class="form-select editgrade{{ $index + 1 }}" name="editgrade{{ $index + 1 }}[]" id="editgrade{{ $index + 1 }}_{{ $loopIndex }}" required>
                                <option value="" selected disabled>Please Select Grade</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <button type="button" class="btn btn-danger deleteSubjectRow"><i class="bi bi-dash"></i></button>
                        </div>
                    </div>
                    @php
                        $loopIndex++;
                    @endphp
                @endforeach

                <div class="mb-3 row">
                    <label class="col-sm-2 col-form-label"></label>
                    <div class="col-sm-10">
                        <button type="button" class="btn btn-success" id="addSubject{{ $index + 1 }}">
                            <i class="bi bi-plus"></i>Subject
                        </button>
                    </div>
                    <div id="deletebtn"></div>
                </div>
                <hr>
            </div>
        @endforeach
    @else
        <!-- Default qualification set HTML -->
        <div id="qualificationSet">
            <div class="mb-3 row">
                <label for="qualificationlevel" class="col-sm-2 col-form-label">Qualification Level</label>
                <div class="col-sm-8">
                    <select class="form-select editqlevel" name="editqlevel1" id="editqlevel1" required>
                        <option value="" selected disabled>Select Qualification Level</option>
                        @foreach (array_slice($qlevel, 0, -3, true) as $value => $ql)
                            <option value="{{ $value }}">
                                {{ $ql }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mb-3 row">
                <label for="minsubject" class="col-sm-2 col-form-label">Minimum Subjects</label>
                <div class="col-sm-4">
                    <input type="number" name="editminsubject1" class="form-control editminsubject1" id="editminsubject1" placeholder="Enter minimum subject..." required>
                </div>
                <div class="col-sm-4">
                    <select class="form-select editmingrade1" name="editmingrade1" id="editmingrade1" required>
                        <option value="" selected disabled>Please Select Qualification Level</option>
                    </select>
                </div>
            </div>

            <div class="mb-3 row subject-row" id="subjectRow1">
                <label for="subject" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-4">
                    <select class="form-select editsubject1" name="editsubject1[]" id="editsubject1_1" required>
                        <option value="" selected disabled>Please Select Subject</option>
                    </select>
                </div>
                <div class="col-sm-4">
                    <select class="form-select editgrade1" name="editgrade1[]" id="editgrade1_1" required>
                        <option value="" selected disabled>Please Select Grade</option>
                    </select>
                </div>
                <div class="col-sm-2">
                    <button type="button" class="btn btn-danger deleteSubjectRow"><i class="bi bi-dash"></i></button>
                </div>
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
    @endif
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
</form>
</div>

@endsection

@section('script')
<script>
    var qualificationSetCounter = {{ $courseGrades->isEmpty() ? 1 : count($courseGrades) }};

    var courseGrades = <?php echo json_encode($courseGrades); ?>;
    console.log(courseGrades);
    
    function showSection2() {
        document.getElementById('section1').style.display = 'none';

        document.getElementById('section2').style.display = 'block';
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('.corebtn').addEventListener('click', function() {
            var coreContainer = document.getElementById('coreContainer0');
            var clone = coreContainer.cloneNode(true);

            var removeBtn = clone.querySelector('.remove-core');
            if (removeBtn) {
                removeBtn.remove();
            }

            clone.querySelector('[name="title1[]"]').value = '';

            var clonedQuill = clone.querySelector('#kt_quil_2_0 .ql-editor');
            clonedQuill.innerHTML = '';

            var firstToolbar = clone.querySelector('.ql-toolbar');
            if (firstToolbar) {
                firstToolbar.parentNode.removeChild(firstToolbar);
            }

            coreContainer.parentNode.insertBefore(clone, document.getElementById('corebtn'));

            var clonedQuillContainer = clone.querySelector('.ql-container');
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
            hiddenInput.name = 'editcoremodule[]';

            clonedQuillEditor.on('text-change', function () {

            var content = clonedQuillEditor.root.innerHTML;
            hiddenInput.value = content;

            var form = document.getElementById('editcourse');
            form.appendChild(hiddenInput);
            });

            var removeBtn = document.createElement("button");
            removeBtn.innerHTML = '<i class="bi bi-trash remove-core-btn"></i>';
            removeBtn.classList.add("btn","remove-core-btn", "float-end");
            removeBtn.onclick = function() {
                clone.remove();
            };
            clone.appendChild(removeBtn);
        });

        document.querySelector('.electivebtn').addEventListener('click', function() {
            var electiveContainer = document.getElementById('electiveContainer0');
            var clone = electiveContainer.cloneNode(true);

            var removeBtn = clone.querySelector('.remove-elective');
            if (removeBtn) {
                removeBtn.remove();
            }

            clone.querySelector('[name="title2[]"]').value = '';

            var clonedQuill = clone.querySelector('#kt_quil_3_0 .ql-editor');
            clonedQuill.innerHTML = '';

            var firstToolbar = clone.querySelector('.ql-toolbar');
            if (firstToolbar) {
                firstToolbar.parentNode.removeChild(firstToolbar);
            }

            electiveContainer.parentNode.insertBefore(clone, document.getElementById('electivebtn'));

            var clonedQuillContainer = clone.querySelector('.ql-container');
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
            hiddenInput.name = 'editelectivemodule[]';

            clonedQuillEditor.on('text-change', function () {

            var content = clonedQuillEditor.root.innerHTML;
            hiddenInput.value = content;

            var form = document.getElementById('editcourse');
            form.appendChild(hiddenInput);
            });

            var removeBtn = document.createElement("button");
            removeBtn.innerHTML = '<i class="bi bi-trash remove-elective-btn"></i>';
            removeBtn.classList.add("btn","remove-elective-btn", "float-end");
            removeBtn.onclick = function() {
                clone.remove();
            };
            clone.appendChild(removeBtn);
        });

        document.querySelector('.otherbtn').addEventListener('click', function() {
            var otherContainer = document.getElementById('otherContainer0');
            var clone = otherContainer.cloneNode(true);

            var removeBtn = clone.querySelector('.remove-other');
            if (removeBtn) {
                removeBtn.remove();
            }

            clone.querySelector('[name="title3[]"]').value = '';

            var clonedQuill = clone.querySelector('#kt_quil_4_0 .ql-editor');
            clonedQuill.innerHTML = '';

            var firstToolbar = clone.querySelector('.ql-toolbar');
            if (firstToolbar) {
                firstToolbar.parentNode.removeChild(firstToolbar);
            }

            otherContainer.parentNode.insertBefore(clone, document.getElementById('otherbtn'));

            var clonedQuillContainer = clone.querySelector('.ql-container');
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
            hiddenInput.name = 'editothermodule[]';

            clonedQuillEditor.on('text-change', function () {

            var content = clonedQuillEditor.root.innerHTML;
            hiddenInput.value = content;

            var form = document.getElementById('editcourse');
            form.appendChild(hiddenInput);
            });

            var removeBtn = document.createElement("button");
            removeBtn.innerHTML = '<i class="bi bi-trash remove-other-btn"></i>';
            removeBtn.classList.add("btn","remove-other-btn", "float-end");
            removeBtn.onclick = function() {
                clone.remove();
            };
            clone.appendChild(removeBtn);
        });
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

    $(document).ready(function() {

    $('.uniselect').select2();
    $('.levelselect').select2();
    $('.coursemonthselect').select2();
    $('.representlevel').select2();
    $('.talentlevel').select2();
    $('.leadershiplevel').select2();
    $('.coursemaincat').select2();
    $('.coursesubcat').select2();

    $('input[name="editcourseinternship"]').change(function () {
    console.log('Internship radio button changed');
    if ($('#courseinternship_yes').prop('checked')) {
        console.log('Showing #internshipDurationRow');
        $('#internshipDurationRow').show();
    } else {
        console.log('Hiding #internshipDurationRow');
        $('#internshipDurationRow').hide();
    }
    }).change()


    $('input[name="editcoursesportsrepresent"]').change(function () {
            if ($('#coursesportsrepresent_yes').prop('checked')) {
                $('#representlevel').show();
            } else {
                $('#representlevel').hide();
            }
    }).change()

    $('input[name="editcoursetalentrepresent"]').change(function () {
            if ($('#coursetalentrepresent_yes').prop('checked')) {
                $('#talentlevel').show();
            } else {
                $('#talentlevel').hide();
            }
    }).change()

    $('input[name="editcourseleadership"]').change(function () {
            if ($('#courseleadership_yes').prop('checked')) {
                $('#leadershiplevel').show();
            } else {
                $('#leadershiplevel').hide();
            }
    }).change()

    $('input[name="extrafees"]').change(function () {
            if ($('#extrafees_yes').prop('checked')) {
                $('#extrafeesContainer').show();
            } else {
                $('#extrafeesContainer').hide();
            }
    }).change()

    // $('[id^="extrafeestype"]').each(function() {
    //     var selectedType = $(this).val();
    //     var frequencyId = $(this).attr('id').replace('extrafeestype', 'extrafeesFrequency');

    //     if (selectedType == 2) {
    //         $('#' + frequencyId).show();
    //     } else {
    //         $('#' + frequencyId).hide();
    //     }
    // });

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

    $('.remove-core').click(function() {
        var index = $(this).data('index');
        $('#coreContainer' + index).remove();
        $('input[name="editcoremodule[]"]').eq(index).remove();
    });

    $('.remove-elective').click(function() {
        var index = $(this).data('index');
        $('#electiveContainer' + index).remove();
        $('input[name="editelectivemodule[]"]').eq(index).remove();
    });

    $('.remove-other').click(function() {
        var index = $(this).data('index');
        $('#otherContainer' + index).remove();
        $('input[name="editothermodule[]"]').eq(index).remove();
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

    var setCounter = 1;
    var gsetCounter = 1;
    
    // Function to handle subject and grade population
    function populateSubjectAndGrade(qLevelId, mingradeSelector, subjectSelector, gradeSelector) {
            // Clear existing options
            $(mingradeSelector).empty().append('<option value="" selected disabled>Select Minimum Grade</option>');
            $(subjectSelector).empty().append('<option value="" selected disabled>Select Subject</option>');
            $(gradeSelector).empty().append('<option value="" selected disabled>Select Grade</option>');

            

            if (qLevelId) {

                var minGrades = [];

                courseGrades.forEach(function(courseGrade) {
                    var minGrade = courseGrade.cg_min_grades;
                    minGrades.push(minGrade);
                });

                $.ajax({
                    type: 'GET',
                    url: '/admin/get-mingrade',
                    data: { qualification_level_id: qLevelId },
                    success: function (data) {
                        console.log(data);

                        $.each(data.list, function (index, mingrade) {
                            var isSelected = ''; 
                            $(mingradeSelector).append('<option value="' + mingrade.sg_id + '" ' + isSelected + '>' + mingrade.sg_grade + '</option>');
                        });
                        
                        var mgindexCounter = 1; 
                        for (var i = 0; i < minGrades.length; i++) {
                            var minGradesId = minGrades[i];
                            var minGradeId = '#editmingrade' + mgindexCounter;
                            
                            $(minGradeId).val(minGradesId);

                            if (minGradeId === '#editmingrade' + mgindexCounter + (minGrades.length - 1)) {
                                mgindexCounter++;
                            } else {
                                mgindexCounter++;
                            }
                        }

                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                }); 

                var selectedSubjectIds = [];
                var selectedGradeIds = [];

                courseGrades.forEach(function(courseGrade) {
                    var grades = JSON.parse(courseGrade.cg_grades); 

                    grades.forEach(function(grade) {
                        var subjects = grade.subject.split(','); 
                        var gradeValues = grade.grades.split(','); 

                        subjects.forEach(function(subject) {
                            selectedSubjectIds.push(subject);
                        });

                        gradeValues.forEach(function(gradeValue) {
                            selectedGradeIds.push(gradeValue);
                        });
                    });
                });

                $.ajax({
                    type: 'GET',
                    url: '/admin/get-qlevel',
                    data: { qualification_level_id: qLevelId },
                    success: function (data) {
                        console.log(data);

                        //Append subjects to the subject select
                        $.each(data.list, function (index, subject) {
                            var isSelected = '';
                            $(subjectSelector).append('<option value="' + subject.s_id + '" ' + isSelected + '>' + subject.s_name + '</option>');
                        });

                        var indexCounter = 0; 

                        for (var i = 0; i < selectedSubjectIds.length; i++) {
                            var subjectId = selectedSubjectIds[i];
                            var subjectSelectorId = '#editsubject' + setCounter + '_' + i;

                            $(subjectSelectorId).val(subjectId);

                            if (subjectSelectorId === '#editsubject' + setCounter + '_' + (selectedSubjectIds.length - 1)) {
                                indexCounter = 0;
                                setCounter++;
                            } else {
                                indexCounter++;
                            }
                        }
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

                        $.each(data.list, function (index, grade) {
                            var isSelected = '';
                            $(gradeSelector).append('<option value="' + grade.sg_id + '" ' + isSelected + '>' + grade.sg_grade + '</option>');
                        });

                        var gindexCounter = 0;

                        for (var i = 0; i < selectedGradeIds.length; i++) {
                            var gradeId = selectedGradeIds[i];
                            var gradeSelectorId = '#editgrade' + gsetCounter + '_' + i;

                            $(gradeSelectorId).val(gradeId);

                            if (gradeSelectorId === '#editgrade' + gsetCounter + '_' + (selectedGradeIds.length - 1)) {
                                gindexCounter = 0;
                                gsetCounter++;
                            } else {
                                gindexCounter++;
                            }
                        }
                    },
                    error: function (data) {
                        console.log('Error:', data);
                    }
                });

                }
    }

        // Initial change event handler
        $(document).on('change', '[id^=editqlevel]', function () {
            var qLevelId = $(this).val();
            var index = $(this).attr('id').match(/\d+$/);
            var mingradeSelector = '.editmingrade'  + index;
            var subjectSelector = '.editsubject'  + index;
            var gradeSelector = '.editgrade'  + index;
            populateSubjectAndGrade(qLevelId, mingradeSelector, subjectSelector, gradeSelector);
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


            // Insert the new row immediately after the current row
            $('#subjectRow' + setId).after(newRow);

            // Call the function to populate subject and grade for the new row
            // populateSubjectAndGrade('', '.editsubject' + setId, '.editgrade' + setId);
        });

        $(document).on('click', '.deleteSubjectRow', function () {
            // Remove the subject row when the delete button is clicked
            $(this).closest('.mb-3.row').remove();
        });

        $('[id^=editqlevel]').each(function () {
            $(this).trigger('change');
        });
});

    function addQualification() {
        // Clone the entire wrapper
        // var cloneWrapper = $("#qualificationWrapper").clone();
        var cloneWrapper = $("#qualificationSet").clone();

        qualificationSetCounter++;

        // Update IDs to make them unique
        /* var newIndex = $(".editqlevel").length + 1; */
        var newIndex = qualificationSetCounter;
        cloneWrapper.find("#qualificationSet").attr("id", "qualificationSet" +newIndex);
        cloneWrapper.find(".editqlevel").attr("id", "editqlevel" + newIndex).attr("name", "editqlevel" + newIndex).attr("class", "form-select editqlevel");
        cloneWrapper.find(".editminsubject1").attr("id", "editminsubject" + newIndex).attr("name", "editminsubject" + newIndex).attr("class", "form-select editminsubject" + newIndex);
        cloneWrapper.find(".editmingrade1").attr("id", "editmingrade" + newIndex).attr("name", "editmingrade" + newIndex).attr("class", "form-select editmingrade" + newIndex);
        cloneWrapper.find("#subjectRow1:not(:first)").remove();
        cloneWrapper.find("#subjectRow1").attr("id", "subjectRow" + newIndex);
        cloneWrapper.find(".editsubject1").attr("id", "editsubject" + newIndex).attr("class", "form-select editsubject" + newIndex).attr("name", "editsubject" + newIndex +"[]");
        cloneWrapper.find(".editgrade1").attr("id", "editgrade" + newIndex).attr("class", "form-select editgrade" + newIndex).attr("name", "editgrade" + newIndex + "[]");
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

        // $("#footer").appendTo(cloneWrapper);
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

    // Set the initial content of the Quill editor
    var initialContent = {!! json_encode($courseData->c_outline) !!};
        quill.clipboard.dangerouslyPasteHTML(initialContent);

    // Listen for the 'text-change' event on Quill
    quill.on('text-change', function () {
    // Get the HTML content from the Quill editor
    var content = quill.root.innerHTML;

    // Dynamically create an input field with the Quill content
    var hiddenInput = document.createElement('input');
    hiddenInput.type = 'hidden';
    hiddenInput.name = 'editcourseoutline';
    hiddenInput.value = content;

    // Append the input field to the form
    var form = document.getElementById('editcourse');
    form.appendChild(hiddenInput);
    });
    }
    /////
    var demo2 = function () {
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

    // Set the initial content of the Quill editor
    var initialContent = {!! json_encode($courseData->c_entry_req) !!};
        quill.clipboard.dangerouslyPasteHTML(initialContent);

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
    var form = document.getElementById('editcourse');
    form.appendChild(hiddenInput);
    });
    }

    // Private function to initialize a Quill editor
        var initializeQuill = function (containerId, initialContent, inputName) {
        var quill = new Quill('#' + containerId, {
            modules: {
                toolbar: [
                    [{ header: [1, 2, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'bullet' }],
                    ['link']
                ]
            },
            placeholder: 'Type your text here...',
            theme: 'snow' // or 'bubble'
        });
        var form = document.getElementById('editcourse');
        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = inputName;
        hiddenInput.value = initialContent;
        form.appendChild(hiddenInput);
        // Set the initial content of the Quill editor
        quill.clipboard.dangerouslyPasteHTML(initialContent);

        // Listen for the 'text-change' event on Quill
        quill.on('text-change', function () {
            // Get the HTML content from the Quill editor
            var content = quill.root.innerHTML;
            hiddenInput.value = content;

            // Append the input field to the form
            form.appendChild(hiddenInput);
        });
    };

    var coreCourses = <?php echo json_encode($coreCourses); ?>;
    var electiveCourses = <?php echo json_encode($electiveCourses); ?>;
    var otherCourses = <?php echo json_encode($otherCourses); ?>;

    return {
    // public functions
    init: function () {
        demo1();
        demo2();

        if (coreCourses.length === 0) {
            initializeQuill('kt_quil_2_0', '', 'editcoremodule[]');
        } else {
            coreCourses.forEach((course, index) => {
                initializeQuill(`kt_quil_2_${index}`, course.desc, 'editcoremodule[]');
            });
        }

        if (electiveCourses.length === 0) {
            initializeQuill('kt_quil_3_0', '', 'editelectivemodule[]');
        } else {
            electiveCourses.forEach((course, index) => {
                initializeQuill(`kt_quil_3_${index}`, course.desc, 'editelectivemodule[]');
            });
        }

        if (otherCourses.length === 0) {
            initializeQuill('kt_quil_4_0', '', 'editothermodule[]');
        } else {
            otherCourses.forEach((course, index) => {
                initializeQuill(`kt_quil_4_${index}`, course.desc, 'editothermodule[]');
            });
        }
    }
    };
    }();

    jQuery(document).ready(function () {
    KTQuilDemos.init();
    });
</script>
@endsection