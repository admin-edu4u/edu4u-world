@extends('memberLayout')
@section('style')
<style>
  .form-check.form-check-solid .form-check-input:checked {
    background-color: #1A124E !important;
  }

  .btn-uni-filter {
    padding: 24px !important;
    border-radius: 12px !important;
    border: 1px solid #E4E4E4 !important;
    background: #FFF !important;
  }

  .close-modal-btn {
    position: absolute;
    bottom: 0px;
    left: 0;
    right: 0;
    margin-left: auto;
    margin-right: auto;
    width: fit-content;
    background: rgba(0, 0, 0, 0.50);
    padding: 0px !important;
    border-radius: 1000px;
    width: 44px;
    height: 44px;
  }

  .modal-footer {
    position: sticky;
    bottom: 0;
    left: 0;
    width: 100%;
    background-color: white;
    padding: 10px 20px;
    border-top: 1px solid #ddd;
    box-sizing: border-box;
    z-index: 9999;
  }

  [data-kt-button="true"] {
    transition: box-shadow 0.3s ease;
    border-radius: 1rem;
  }

  [data-kt-button="true"]:hover {
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.4);
  }

  #add_course1,
  #add_course2,
  #add_course3,
  #deletedcourse1,
  #deletedcourse2,
  #deletedcourse3 {
    cursor: pointer;
  }

  .apply-scholarship-btn {
    border: 2px solid !important;
    border-color: #1A124E !important;
  }

  .apply-scholarship-btn:hover {
    background-color: white !important;
    color: #1A124E !important;
  }

  @media (max-width: 767px) {
    .bg-blue{
      width:842px;
    }
  }

</style>
@endsection
@section('content')
  @php
    use \Jenssegers\Agent\Agent;
    $agent = new Agent();
  @endphp
<div class="mw-1200 mx-auto pt-80 pb-20">
  <span class="d-block visit-campus-text text-start">{{__('lang.compareCourse')}}</span>
  <div @if($agent->isMobile()) style="width:822px; padding-right:20px;" @endif>
    <div id="com_table"></div>
    <table class="mt-10 w-100" id="compare_table">
      <tr class="no-border" id="tr-col-1">
        <th class="table-sub-header table-border" @if(!$agent->isMobile()) style="width:25%;" @endif @if($agent->isMobile()) style="width:200px;" @endif>{{__('lang.basicInformation')}}</th>
        <th class="table-border pb-20 position-relative" @if(!$agent->isMobile()) style="width:25%;" @endif @if($agent->isMobile()) style="width:200px;" @endif>
          <div class="text-center" id="add_course1">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="49" viewBox="0 0 48 49" fill="none">
              <path d="M13.5 24.5H33.5" stroke="#999999" stroke-width="1.5" stroke-linecap="round"/>
              <path d="M23.5 14.5L23.5 34.5" stroke="#999999" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <span class="d-block add-course ">{{__('lang.selectCourse')}}</span>
          </div>
        </th>
        <th class="table-border pb-20 position-relative" @if(!$agent->isMobile()) style="width:25%;" @endif @if($agent->isMobile()) style="width:200px;" @endif>
          <div class="text-center" id="add_course2">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="49" viewBox="0 0 48 49" fill="none">
              <path d="M13.5 24.5H33.5" stroke="#999999" stroke-width="1.5" stroke-linecap="round"/>
              <path d="M23.5 14.5L23.5 34.5" stroke="#999999" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <span class="d-block add-course ">{{__('lang.selectCourse')}}</span>
          </div>
        </th>
        <th class="pb-20 position-relative" @if(!$agent->isMobile()) style="width:25%; border-right:0px !important;" @endif @if($agent->isMobile()) style="width:200px; border-right:0px !important;" @endif>
          <div class="text-center" id="add_course3">
            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="49" viewBox="0 0 48 49" fill="none">
              <path d="M13.5 24.5H33.5" stroke="#999999" stroke-width="1.5" stroke-linecap="round"/>
              <path d="M23.5 14.5L23.5 34.5" stroke="#999999" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <span class="d-block add-course ">{{__('lang.selectCourse')}}</span>
          </div>
        </th>
      </tr>
      <tr id="tr-col-2">
        <th class="table-sub-text table-border">{{__('lang.country')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-3">
        <th class="table-sub-text table-border">{{__('lang.state')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-5">
        <th class="table-sub-header pt-20 table-border">{{__('lang.courseInformation')}}</th>
        <th class="table-border"></th>
        <th class="table-border"></th>
        <th class="" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-6">
        <th class="table-sub-text table-border">{{__('lang.fieldOfStudy')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-7">
        <th class="table-sub-text table-border">{{__('lang.major')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-8">
        <th class="table-sub-text table-border">{{__('lang.level')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-9">
        <th class="table-sub-text table-border">{{__('lang.intakeMonth')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-30">
        <th class="table-sub-text table-border">{{__('lang.entryReq')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-10">
        <th class="table-sub-text table-border">{{__('lang.englishReq')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-11">
        <th class="table-sub-text table-border">{{__('lang.fullTimeDuration')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-12">
        <th class="table-sub-header pt-20 table-border">{{__('lang.tuitionFees')}}</th>
        <th class="table-border"></th>
        <th class="table-border"></th>
        <th class="" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-13">
        <th class="table-sub-text table-border">{{__('lang.feesBumi')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-14">
        <th class="table-sub-text table-border">{{__('lang.feesNonBumi')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-15">
        <th class="table-sub-text table-border">{{__('lang.feesInternational')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-16">
        <th class="table-sub-header pt-20 table-border">{{__('lang.awardScholarship')}}</th>
        <th class="table-border"></th>
        <th class="table-border"></th>
        <th class="" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-17">
        <th class="table-sub-text table-border">{{__('lang.awards')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <!-- <tr id="tr-col-18">
        <th class="table-sub-text table-border">Title</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-19">
        <th class="table-sub-text table-border">Interview required</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-20">
        <th class="table-sub-text table-border">Type</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr> -->
      <tr id="tr-col-21">
        <th class="table-sub-text table-border">{{__('lang.coverage')}}</th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text table-border text-center"></th>
        <th class="table-sub-text text-center" style="border-right:0px !important;"></th>
      </tr>
      <tr id="tr-col-22">
        <th class="table-border"></th>
        <th class="table-border"></th>
        <th class="table-border"></th>
        <th class="" style="border-right:0px !important;"></th>
      </tr>
    </table>
  </div>
</div>

@section('modal')
<div class="modal fade" tabindex="-1" id="add_uni_modal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content br-12 overflow-scroll" style="height:75vh;">
      <div class="modal-header">
        <span class="modal-add-uni-header">{{__('lang.selectCourseCap')}}</span>
      </div>

      <div class="modal-body">
        <div class="col-12 row">
          <div class="col-8">
            <div class="input-group input-group-solid mb-5">
              <span class="input-group-text search-course-input-icon" id="basic-addon2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <path d="M21.0002 21L16.6572 16.657M16.6572 16.657C17.4001 15.9141 17.9894 15.0321 18.3914 14.0615C18.7935 13.0909 19.0004 12.0506 19.0004 11C19.0004 9.94936 18.7935 8.90905 18.3914 7.93842C17.9894 6.96779 17.4001 6.08585 16.6572 5.34296C15.9143 4.60007 15.0324 4.01078 14.0618 3.60874C13.0911 3.20669 12.0508 2.99976 11.0002 2.99976C9.9496 2.99976 8.90929 3.20669 7.93866 3.60874C6.96803 4.01078 6.08609 4.60007 5.34321 5.34296C3.84288 6.84329 3 8.87818 3 11C3 13.1217 3.84288 15.1566 5.34321 16.657C6.84354 18.1573 8.87842 19.0002 11.0002 19.0002C13.122 19.0002 15.1569 18.1573 16.6572 16.657Z" stroke="#999999" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
              <input type="text" class="form-control search-course-input" name="search" placeholder="Search courses" aria-label="Search courses" aria-describedby="basic-addon2"/>
            </div>
          </div>
          <div class="col-4">
            <select class="form-select form-select-solid select-uni-filter" id="uni" data-control="select2" data-placeholder="Filter Universities" data-dropdown-parent="#add_uni_modal">
              <option></option>
              @if(isset($uni) && count($uni) > 0)
              @foreach($uni as $u)
              <option value="{{$u->u_id}}">{{$u->u_name}}</option>
              @endforeach
              @endif
            </select>
          </div>
            <div id="course_list"></div>
        </div>
      </div>
      <div class="modal-footer col-12" style="display:none;">
          <div class="col-4">
            <button class="btn apply-scholarship-btn float-end col-6 add-selected-course" id="add-selected-course">{{__('lang.ok')}}</button>
          </div>
        </div>
    </div>
    <button type="button" class="btn close-modal-btn" data-bs-dismiss="modal">
      <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
        <path d="M1 1L13 13" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
        <path d="M13 1L0.999999 13" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </button>
  </div>
</div>
@endsection
@endsection
@section('script')
<script>
$(document).on('click', '[id^=add_course]', function() {
  var courseId = $(this).attr('id').replace(/^\D+/g, '');
  // console.log('add_course: '+courseId);
  $('#add_uni_modal').find('.add-selected-course').attr('id', 'add-selected-course' + courseId);
  $('#add_uni_modal').modal('show');
});

$(document).ready(function(){

  $(document).on('click', '.btn-uni-filter', function() {
      $('.add-selected-course').trigger('click');
  });
  
  $(document).on('click', '[id^=add-selected-course]', function() {
    var courseId = $(this).attr('id').replace(/^\D+/g, '');
    // console.log('OK id'+courseId);

        var selectedCourseID = $('input[name="selectedcourse"]:checked').val();
        // console.log('selected course id: '+selectedCourseID);

        // if (!selectedCourseID) {
        //     alert('Please select a course.');
        //     return;
        // }

        $.ajax({
            url: "/getCourseInfo",
            method: 'post',
            data: { c_id: selectedCourseID },
            success: function(response) {
                if (response.status && response.list && response.list.length > 0) {
                    // Extract the course name from the response
                    var courseName = response.list[0].name;
                    var courseURL = response.list[0].url;
                    var uniName = response.list[0].uni_name;
                    var uniLogo = response.list[0].uni_logo;
                    var uniURL = response.list[0].uni_url;
                    var country = response.list[0].country;
                    var state = response.list[0].state;
                    var maincat = response.list[0].maincat;
                    var subcat = response.list[0].subcat;
                    var level = response.list[0].level;
                    var intake = response.list[0].intake;
                    var entryreq = response.list[0].entryreq;
                    var english = response.list[0].english;
                    var duration = response.list[0].duration;
                    var amtnonbumi = response.list[0].amtnonbumi;
                    var amtbumi = response.list[0].amtbumi;
                    var amtint = response.list[0].amtint;
                    var award = response.list[0].scholarship_award;
                    // var title = response.list[0].scholarship_title;
                    // var interview = response.list[0].scholarship_interview;
                    // var type = response.list[0].scholarship_type;
                    var coverage = response.list[0].scholarship_coverage;
                    var applynow ="{{__('lang.applyNowUncap')}}";

                    // Update the table row with the retrieved course name
                    $('#tr-col-1').find('th:eq('+courseId+')').html('<div class="position-absolute" style="right: 0; transform: translateY(-50%);" id="deletedcourse'+courseId+'"><svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="#999999" stroke-width="1" viewBox="0 0 20 20"><path d="M11.354 4.646a.5.5 0 0 0-.708 0L8 7.293 5.354 4.646a.5.5 0 0 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0 0-.708z"/></svg></div><div class="d-flex justify-content-center"><img class="course-uni-img" src="'+uniLogo+'"></div><span class="d-block uni-name text-center pt-5">'+courseName+'</span><span class="navigate-to-uni d-block text-center pt-3"><a href="/university/'+uniURL+'" style="color:#666;">'+uniName+'</a></span><a class="btn apply-scholarship-btn mx-auto mt-5" style="width: fit-content;" id="c_apply_now" href="/course-details/'+courseURL+'">'+applynow+'</a>');
                    $('#tr-col-2').find('th:eq('+courseId+')').html(country);
                    $('#tr-col-3').find('th:eq('+courseId+')').html(state);
                    $('#tr-col-6').find('th:eq('+courseId+')').html(maincat);
                    $('#tr-col-7').find('th:eq('+courseId+')').html(subcat);
                    $('#tr-col-8').find('th:eq('+courseId+')').html(level);
                    $('#tr-col-9').find('th:eq('+courseId+')').html(intake);
                    $('#tr-col-30').find('th:eq('+courseId+')').html(entryreq);
                    $('#tr-col-10').find('th:eq('+courseId+')').html(english);
                    $('#tr-col-11').find('th:eq('+courseId+')').html(duration);
                    $('#tr-col-13').find('th:eq('+courseId+')').html(amtnonbumi);
                    $('#tr-col-14').find('th:eq('+courseId+')').html(amtbumi);
                    $('#tr-col-15').find('th:eq('+courseId+')').html(amtint);
                    $('#tr-col-17').find('th:eq('+courseId+')').html(award);
                    // $('#tr-col-18').find('th:eq('+courseId+')').html(title);
                    // $('#tr-col-19').find('th:eq('+courseId+')').html(interview);
                    // $('#tr-col-20').find('th:eq('+courseId+')').html(type);
                    $('#tr-col-21').find('th:eq('+courseId+')').html(coverage);
                    $('#tr-col-22').find('th:eq('+courseId+')').html('<a class="btn apply-scholarship-btn mx-auto" style="width: fit-content;" id="c_apply_now" href="/course-details/'+courseURL+'">'+applynow+'</a>');

                    $('input[name="selectedcourse"]').prop('checked', false);

                  } else {
                    // alert('Failed to retrieve course information.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching course information:', error);
                alert('Error fetching course information.');
            }
        });

        $('input[name="search"]').val('');
        $('.select-uni-filter').val(null).trigger('change');
        $('#add_uni_modal').modal('hide');
  });

  $(document).on('click', '[id^=deletedcourse]', function() {
      var deleteId = $(this).attr('id').replace(/^\D+/g, '');
      console.log('delete id'+deleteId);
      var selectCourse ="{{__('lang.selectCourse')}}";

      $('#tr-col-1').find('th:eq('+deleteId+')').html('<div class="text-center" id="add_course'+deleteId+'"><svg xmlns="http://www.w3.org/2000/svg" width="48" height="49" viewBox="0 0 48 49" fill="none"><path d="M13.5 24.5H33.5" stroke="#999999" stroke-width="1.5" stroke-linecap="round"/><path d="M23.5 14.5L23.5 34.5" stroke="#999999" stroke-width="1.5" stroke-linecap="round"/></svg><span class="d-block add-course ">'+selectCourse+'</span></div>');
      $('#tr-col-2').find('th:eq('+deleteId+')').html('');
      $('#tr-col-3').find('th:eq('+deleteId+')').html('');
      $('#tr-col-6').find('th:eq('+deleteId+')').html('');
      $('#tr-col-7').find('th:eq('+deleteId+')').html('');
      $('#tr-col-8').find('th:eq('+deleteId+')').html('');
      $('#tr-col-9').find('th:eq('+deleteId+')').html('');
      $('#tr-col-30').find('th:eq('+deleteId+')').html('');
      $('#tr-col-10').find('th:eq('+deleteId+')').html('');
      $('#tr-col-11').find('th:eq('+deleteId+')').html('');
      $('#tr-col-13').find('th:eq('+deleteId+')').html('');
      $('#tr-col-14').find('th:eq('+deleteId+')').html('');
      $('#tr-col-15').find('th:eq('+deleteId+')').html('');
      $('#tr-col-17').find('th:eq('+deleteId+')').html('');
      // $('#tr-col-18').find('th:eq('+deleteId+')').html('');
      // $('#tr-col-19').find('th:eq('+deleteId+')').html('');
      // $('#tr-col-20').find('th:eq('+deleteId+')').html('');
      $('#tr-col-21').find('th:eq('+deleteId+')').html('');
      $('#tr-col-22').find('th:eq('+deleteId+')').html('');

  });

  getCourseList();

  $(document).on('click', '#c_apply_now', function(){
    // $('#tr-col-1').append("<td class='table-sub-text table-border text-center'>Malaysia</td>");
    // $('#tr-col-1').append("<td class='table-sub-text table-border text-center'>Malaysia</td>");
    // $('#compare_table').find('th').last().remove();
    // $('#com_table').empty().append(createRow());
  })

  function createRow()
  {
    var table = '';

    table += '<table class="mt-10 w-100" id="compare_table">';
    table += '<tr class="no-border">';
    table += '<th class="table-sub-header table-border">Basic information</th>';
    table += '<th class="table-border pb-20">';
    table += '<div class="d-flex justify-content-center">';
    table += '<img class="course-uni-img" src="/images/assets/uni_img_2.png">';
    table += '</div>';
    table += '<span class="d-block uni-name text-center pt-5">Foundation in Art</span>';
    table += '<span class="navigate-to-uni d-block text-center pt-3">Sunway University</span>';
    table += '<a class="btn apply-scholarship-btn mx-auto mt-5" style="width: fit-content;" id="c_apply_now">Apply now</a>';
    table += '</th>';
    table += '<th class="table-border pb-20">';
    table += '<div class="d-flex justify-content-center">';
    table += '<img class="course-uni-img" src="/images/assets/uni_img_2.png">';
    table += '</div>';
    table += '<span class="d-block uni-name text-center pt-5">Foundation in Art</span>';
    table += '<span class="navigate-to-uni d-block text-center pt-3">Sunway University</span>';
    table += '<a class="btn apply-scholarship-btn mx-auto mt-5" style="width: fit-content;">Apply now</a>';
    table += '</th>';
    table += '<th>';
    table += '<div class="text-center" id="add_course">';
    table += '<svg xmlns="http://www.w3.org/2000/svg" width="48" height="49" viewBox="0 0 48 49" fill="none">';
    table += '<path d="M13.5 24.5H33.5" stroke="#999999" stroke-width="1.5" stroke-linecap="round"/>';
    table += '<path d="M23.5 14.5L23.5 34.5" stroke="#999999" stroke-width="1.5" stroke-linecap="round"/>';
    table += '</svg>';
    table += '<span class="d-block add-course ">Select course</span>';
    table += '</div>';
    table += '</th>';
    table += '</tr>';
    table += '</table>';

    return table;
  }

  function getCourseList()
  {
    var search = $('input[name="search"]').val();
    var uni = $('#uni').find(":selected").val();

    $.ajax({
        url: "/getCompareCourses",
        method: 'post',
        data: {
          search: search,
          uni: uni,
        },
        success: function(d) {
          var content = '';

          if (d.status) {
            var items = d.list;

            items.forEach(function(item, index) {
              content += '<div data-kt-button="true">';
              content += '<label class="btn btn-uni-filter d-flex flex-stack text-start mb-5">';
              content += '<div class="col-12 row">';
              content += '<div class="col-8 d-flex align-items-center">';
              content += '<div>';
              content += '<div class="form-check form-check-custom form-check-solid form-check-primary me-6">';
              content += '<input class="form-check-input" type="checkbox" value="'+item.id+'" name="selectedcourse" id="flexCheckDefault" style="display:none;"/>';
              content += '<img class="course-uni-img ms-6" src="'+item.uni_logo+'">';
              content += '</div>';
              content += '</div>';
              content += '<div>';
              content += '<span class="d-block uni-name">'+item.name+'</span>';
              content += '<span class="uni-location d-block">'+item.uni_name+'</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="col-4">';
              content += '<div class="col-12 row">';
              content += '<div class="col-5">';
              content += '<span class="uni-location d-block">{{ __("lang.duration") }}</span>';
              content += '<span class="uni-total d-block pt-2">'+item.duration+'</span>';
              content += '</div>';
              content += '<div class="col-7">';
              content += '<span class="uni-location d-block">{{ __("lang.intake") }}</span>';
              content += '<span class="uni-total d-block pt-2">'+item.intake+'</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="separator my-5"></div>';
              content += '<div class="d-flex justify-content-between">';
              content += '<span class="uni-location">{{ __("lang.totalFees") }}</span>';
              content += '<span class="uni-total">'+item.ttl_amt+'</span>';
              content += '</div>';
              content += '<div class="d-flex justify-content-between">';
              content += '<span class="uni-location">{{ __("lang.deals") }}</span>';
              content += '<span class="uni-total">'+item.deals+'</span>';
              content += '</div>';
              content += '<div class="d-flex justify-content-between">';
              content += '<span class="uni-location">{{ __("lang.scholarship") }}</span>';
              content += '<span class="uni-total">'+item.scholarship+'</span>';
              content += '</div>';
              content += '<div class="separator my-5"></div>';
              content += '<span class="uni-total d-block text-end">MYR<span class="ttl-amt">'+item.ttl_amt+'</span></span>';
              content += '</div>';
              content += '</div>';
              content += '</label>';
              content += '</div>';
            });

            $('#course_list').empty().append(content);
          } else{
            $('#course_list').empty();
          }
        }
    });
  }

  $('input[name="search"]').keyup(function(){
    getCourseList();
  });

  $('#uni').change(function() {
    getCourseList();
  });

})

</script>
@endsection
