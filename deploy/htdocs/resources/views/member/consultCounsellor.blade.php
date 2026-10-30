@extends('memberLayout')
@section('style')
<style>
.form-label {
  color: #1F1F1F;
  font-size: 16px;
  font-style: normal;
  font-weight: 400;
  line-height: normal;
}

@media screen and (max-width: 776px){
  .visit-campus-text {
    font-size:32px;
  }
  uni-name {
    font-size:16px;
    font-weight:400;
  }
}

::placeholder {
  color: #CCC !important;
  font-size: 14px;
  font-weight: 400;
  opacity: 1; /* Firefox */
  font-family: Poppins;
}

::-ms-input-placeholder {
  color: #CCC;
  font-size: 14px;
  font-weight: 400;
  font-family: Poppins;
}

.form-control-solid, .form-select-solid, .form-control {
  padding: 18px 16px;
  border-radius: 8px !important;
  background-color: #F6F6F6 !important;
}

.select2-selection__placeholder {
  color: #1F1F1F !important;
  font-family: Poppins;
  font-size: 14px;
  font-weight: 400;
}

.select2-container--bootstrap5 .select2-selection--multiple:not(.form-select-sm):not(.form-select-lg) {
  padding-top: 18px !important;
  padding-bottom: 18px !important;
}

.separator {
  border-bottom: 1px solid #E4E4E4 !important;
}
</style>
@endsection

@section('content')
<div class="mw-1200 mx-auto pt-80 pb-20">
  <div class="submission-form mt-10">
    <span class="d-block visit-campus-text">{{__('lang.consultCounsellor')}}</span>
    <span class="d-block uni-name text-center pt-3">{{__('lang.consultDesc')}}</span>
    <!-- <div class="col-7 mx-auto course-application-div mt-5">
      <div class="col-12 row">
        <div class="col-4">
          <span class="course-application-text-1">Instituiton</span>
        </div>
        <div class="col-8">
          <span class="course-application-text-2">{{$c->u_name}}</span>
        </div>
      </div>
      <div class="col-12 row mt-2">
        <div class="col-4">
          <span class="course-application-text-1">Course</span>
        </div>
        <div class="col-8">
          <span class="course-application-text-2">{{$c->c_name}}</span>
        </div>
      </div>
    </div> -->
    <form id="course_application_form">
      <div class="col-12 col-lg-7 mx-auto mt-20">
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.salutation')}}</label>
          <select class="form-select form-select-solid" name="salutation" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectSalutation')}}" required>
            <option></option>
            <option value="Mr">{{__('lang.mr')}}</option>
            <option value="Ms">{{__('lang.ms')}}</option>
            <option value="Mrs">{{__('lang.mrs')}}</option>
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.fullName')}}</label>
          <input type="text" name="name" class="form-control form-control-solid" placeholder="{{__('lang.enterFullname')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.gender')}}</label>
          <select class="form-select form-select-solid" name="gender" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.whatsYourGender')}}" required>
            <option></option>
            <option value="1">{{__('lang.male')}}</option>
            <option value="2">{{__('lang.female')}}</option>
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.dateOfBirth')}}</label>
          <div class="col-12 row">
            <div class="col-4">
              <input type="number" name="dob_date" class="form-control form-control-solid" placeholder="{{__('lang.dd')}}" required/>
            </div>
            <div class="col-4">
              <input type="number" name="dob_month" class="form-control form-control-solid" placeholder="{{__('lang.mm')}}" required/>
            </div>
            <div class="col-4">
              <input type="number" name="dob_year" class="form-control form-control-solid" placeholder="{{__('lang.yyyy')}}" required/>
            </div>
          </div>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.secondarySchool')}}</label>
          <input type="text" name="school_name" class="form-control form-control-solid" placeholder="{{__('lang.enterSecondarySchool')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.mobileNumber')}}</label>
          <input type="number" name="contact_no" class="form-control form-control-solid" placeholder="{{__('lang.enterPhoneNum')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.homePhoneNumber')}}<span class="option-text">{{__('lang.optional')}}</span></label>
          <input type="number" name="home_contact_no" class="form-control form-control-solid" placeholder="{{__('lang.enterHomePhoneNumber')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.preferredCourse')}}</label>
          <input type="text" name="preferred_course" class="form-control form-control-solid" placeholder="{{__('lang.enterPrefCourse')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.highestQualification')}}</label>
          <select class="form-select form-select-solid" name="qualification" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectHighestQualification')}}" required>
            <option></option>
            @if(isset($qualification) && count($qualification) > 0)
            @foreach($qualification as $i => $q)
            <option value="{{$i}}">{{$q}}</option>
            @endforeach
            @endif
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.resultTranscript')}}<span class="option-text">{{__('lang.optional')}}</span></label>
          <div id="upload_docs">
            <div class="dropzone-items wm-200px pb-0" style="display:none">
                <div class="dropzone-item" style="display:none">
                  <div class="col-12 row">
                    <div class="dropzone-file col-11">
                      <div class="dropzone-filename" title="some_image_file_name.jpg">
                        <span data-dz-name>some_image_file_name.jpg</span>
                        <strong>(<span data-dz-size>340kb</span>)</strong>
                      </div>
                      <div class="dropzone-error" data-dz-errormessage></div>
                    </div>
                    <div class="dropzone-toolbar col-1">
                      <span class="dropzone-delete" data-dz-remove><i class="bi bi-x fs-1"></i></span>
                    </div>
                  </div>

                  <div class="dropzone-progress">
                    <div class="progress">
                      <div
                      class="progress-bar bg-primary"
                      role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          <div class="dropzone-panel mb-lg-0 mb-2 mt-5">
            <div class="d-flex align-items-center">
              <div><a class="dropzone-select btn upload-docs-btn">{{__('lang.uploadDocument')}}</a></div>
              <div class="col-5 col-lg-6 ms-5"><span class="upload-docs-desc">{{__('lang.uploadDoscDesc')}}</span></div>
            </div>
          </div>
          </div>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.remarks')}} <span class="option-text">{{__('lang.optional')}}</span></label>
          <textarea class="form-control form-control form-control-solid" name="remarks" data-kt-autosize="true" placeholder="{{__('lang.enterRemarks')}}"></textarea>
        </div>
        <button class="w-100 mt-5 btn-submit-visit-form" id="btn_submit_application">{{__('lang.submit')}}</button>
      </div>
    </form>
  </div>

  <div class="sucess-submission mt-10" style="display:none">
    <div class="text-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M24 48C37.2548 48 48 37.2548 48 24C48 10.7452 37.2548 0 24 0C10.7452 0 0 10.7452 0 24C0 37.2548 10.7452 48 24 48ZM33.9007 18.1221C34.6309 17.2932 34.5509 16.0294 33.7221 15.2993C32.8932 14.5691 31.6294 14.6491 30.8993 15.4779L19.7143 28.1744L17.1007 25.2077C16.3706 24.3788 15.1068 24.2989 14.2779 25.029C13.4491 25.7592 13.3691 27.023 14.0993 27.8518L18.2136 32.5221C18.5933 32.953 19.1399 33.2 19.7143 33.2C20.2887 33.2 20.8353 32.953 21.215 32.5221L33.9007 18.1221Z" fill="#29DC50"/>
      </svg>
    </div>
    <span class="pt-3 d-block visit-campus-text">{{__('lang.submitSuccess')}}</span>
    <span class="d-block uni-name text-center pt-3">{{__('lang.submitConsult')}}</span>
    <span class="d-block pt-20 submission-details-text">Submission details</span>

    <div class="col-12 col-lg-7 mx-auto mt-5">
      <div class="submission-div">
      <div class="col-12 row mb-2">
          <div class="col-12 col-lg-4">
            <span class="submission-user-label">{{__('lang.salutation')}}</span>
          </div>
          <div class="col-12 col-lg-8 text-start">
            <span class="submission-user-details" id="salutation"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-12 col-lg-4">
            <span class="submission-user-label">{{__('lang.fullName')}}</span>
          </div>
          <div class="col-12 col-lg-8 text-start">
            <span class="submission-user-details" id="full_name"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-12 col-lg-4">
            <span class="submission-user-label">{{__('lang.gender')}}</span>
          </div>
          <div class="col-12 col-lg-8 text-start">
            <span class="submission-user-details" id="gender"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-12 col-lg-4">
            <span class="submission-user-label">{{__('lang.dateOfBirth')}}</span>
          </div>
          <div class="col-12 col-lg-8 text-start">
            <span class="submission-user-details" id="dob"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-12 col-lg-4">
            <span class="submission-user-label">{{__('lang.secondarySchool')}}</span>
          </div>
          <div class="col-12 col-lg-8 text-start">
            <span class="submission-user-details" id="school_name"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-12 col-lg-4">
            <span class="submission-user-label">{{__('lang.mobileNumber')}}</span>
          </div>
          <div class="col-12 col-lg-8 text-start">
            <span class="submission-user-details" id="contact_num"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-12 col-lg-4">
            <span class="submission-user-label">{{__('lang.homePhoneNumber')}}</span>
          </div>
          <div class="col-12 col-lg-8 text-start">
            <span class="submission-user-details" id="home_contact_num"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-12 col-lg-4">
            <span class="submission-user-label">{{__('lang.prefCourse')}}</span>
          </div>
          <div class="col-12 col-lg-8 text-start">
            <span class="submission-user-details" id="preferred_course"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-12 col-lg-4">
            <span class="submission-user-label">{{__('lang.highestQ')}}</span>
          </div>
          <div class="col-12 col-lg-8 text-start">
            <span class="submission-user-details" id="qualification"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-12 col-lg-4">
            <span class="submission-user-label">{{__('lang.remarks')}}</span>
          </div>
          <div class="col-12 col-lg-8 text-start">
            <span class="submission-user-details" id="remarks"></span>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-center"><button class="mt-20 btn-submit-visit-form">{{__('lang.backToHome')}}</button></div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
const transcript_id = "#upload_docs";
const transcript_dropzone = document.querySelector(transcript_id);

var previewNode = transcript_dropzone.querySelector(".dropzone-item");
previewNode.transcript_id = "";
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);

var transcript_myDropzone = new Dropzone(transcript_id, {
    url: "/submitAddBanner",
    parallelUploads: 5,
    maxFilesize: 5,
    maxFiles: 5,
    previewTemplate: previewTemplate,
    previewsContainer: transcript_id + " .dropzone-items",
    clickable: transcript_id + " .dropzone-select"
});

transcript_myDropzone.on("addedfile", function (file) {
    // Hookup the start button
    const dropzoneItems = transcript_dropzone.querySelectorAll('.dropzone-item');
    const dropzoneItems1 = transcript_dropzone.querySelectorAll('.dropzone-items');
    dropzoneItems.forEach(dropzoneItem => {
        dropzoneItem.style.display = '';
    });

    dropzoneItems1.forEach(dropzoneItem1 => {
        dropzoneItem1.style.display = '';
    });
});

// Update the total progress bar
transcript_myDropzone.on("totaluploadprogress", function (progress) {
    const progressBars = transcript_dropzone.querySelectorAll('.progress-bar');
    progressBars.forEach(progressBar => {
        progressBar.style.width = progress + "%";
    });
});

transcript_myDropzone.on("sending", function (file) {
    // Show the total progress bar when upload starts
    const progressBars = transcript_dropzone.querySelectorAll('.progress-bar');
    progressBars.forEach(progressBar => {
        progressBar.style.opacity = "1";
    });
});

// Hide the total progress bar when nothing"s uploading anymore
transcript_myDropzone.on("complete", function (progress) {
    const progressBars = transcript_dropzone.querySelectorAll('.dz-complete');

    setTimeout(function () {
        progressBars.forEach(progressBar => {
            progressBar.querySelector('.progress-bar').style.opacity = "0";
            progressBar.querySelector('.progress').style.opacity = "0";
        });
    }, 300);
});

$(document).ready(function(){
  $('#course_application_form').on('submit', function (e) {
    e.preventDefault();
    let f = new FormData($(this)[0]);
    f.append('course_id', {{$c->c_id}});

    transcript_myDropzone.files.forEach(function(item) {
      f.append('transcript[]', item);
    })

    $('#btn_submit_application').prop("disabled",true);

    $.ajax({
      url: "/submitConsultConsellor",
      method: 'post',
      data: f,
      enctype: 'multipart/form-data',
      contentType: false,
      processData: false,
      success: function(d) {

        console.log(d)
        if (d.status) {
          var data = d.data;
          $('#btn_submit_application').prop("disabled",false);
          $('.sucess-submission').show();
          $('.submission-form').hide();
          $('.toast-error').toast('hide');

          $('#salutation').html(data.cc_salutation)
          $('#full_name').html(data.cc_name);
          $('#gender').html(data.gender);
          $('#dob').html(data.dob);
          $('#school_name').html(data.cc_school_name);
          $('#contact_num').html(data.cc_contact_no);
          $('#preferred_course').html(data.cc_course);
          $('#qualification').html(data.qualification);

          if(data.cc_remarks != null) {
            $('#remarks').html(data.cc_remarks);
          } else{
            $('#remarks').html('-');
          }

          if(data.cc_home_contact_no != null) {
            $('#home_contact_num').html(data.cc_home_contact_no);
          } else{
            $('#home_contact_num').html('-');
          }

        }else{
          $('#btn_submit_application').prop("disabled",false);
          $('.toast-error').toast('show');
          $('.err-msg').html(d.msg);
          setTimeout(function(){
            $('.toast-error').toast('hide');
          },3000)

        }
      }
    });

  });
});
</script>
@endsection
