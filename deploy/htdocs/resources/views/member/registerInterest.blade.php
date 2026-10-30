@extends('memberLayout')
@section('style')
<style>
.form-label {
  color: #1F1F1F;
  font-size: 16px;
  font-style: normal;
  font-weight: 600;
  line-height: normal;
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
  color: #ccc !important;
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
@if(isset($banner))
<img src="/images/banners/{{$banner->banner_img}}" class="d-block banner-register mx-auto">
@endif
<div class="mw-1200 mx-auto px-5 px-lg-0 pb-20">
  <div class="submission-form mt-10">
    <span class="d-block visit-campus-text">{{__('lang.registerInterest')}}</span>
    <span class="d-block uni-name text-center pt-3">{{__('lang.visitDesc1')}}</span>

    <div class="col-12 col-lg-4 mx-auto">
      <form id="submit_interest">
        <div class="mb-5 mt-10">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.fullname')}}</label>
          <input type="text" class="form-control form-control-solid" name="name" placeholder="{{__('lang.enterFullname')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.emailAddr')}}</label>
          <input type="email" class="form-control form-control-solid" name="email" placeholder="{{__('lang.enterEmailAddr')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.contactNum')}}</label>
          <input type="number" class="form-control form-control-solid" name="contact" placeholder="{{__('lang.enterContactNum')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.birthday')}}</label>
          <input id="kt_datepicker_2" class="form-control form-control-solid" name="birthday" placeholder="DD / MM / YYYY"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.secondarySchool')}}</label>
          <input type="text" class="form-control form-control-solid" name="school" placeholder="{{__('lang.enterSecondarySchool')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.stateOfResidence')}}</label>
          <select class="form-select form-select-solid" data-control="select2" name="state" data-hide-search="true" data-placeholder="{{__('lang.selectState')}}">
            <option></option>
            @if(isset($state) && count($state) > 0)
            @foreach($state as $s)
            <option value="{{$s->state_id}}">{{$s->state_name}}</option>
            @endforeach
            @endif
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.courseInterested')}}</label>
          <input type="text" class="form-control form-control-solid" name="course" placeholder="{{__('lang.enterCourseInterested')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.levelInterested')}}</label>
          <select class="form-select form-select-solid" data-control="select2" name="level" data-hide-search="true" data-placeholder="{{__('lang.selectLevelInterested')}}">
            <option></option>
            <option value="1">Advanced Diploma</option>
            <option value="2">Certificate</option>
            <option value="3">Degree</option>
            <option value="4">Diploma</option>
            <option value="5">Foundation/Pre-U</option>
            <option value="6">Postgraduate - Diploma</option>
            <option value="7">Postgraduate - Masters</option>
            <option value="8">Postgraduate - PHD</option>
            <option value="9">Professional Programmes</option>
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.uniInterested')}}</label>
          <input type="text" class="form-control form-control-solid" name="uni" placeholder="{{__('lang.enterUniInterested')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.academicResult')}}</label>
          <div class="dropzone" id="kt_dropzonejs_example_1">
            <div class="dz-message needsclick">
              <div class="ms-4">
                <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
              </div>
            </div>
          </div>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">Remarks</label>
          <textarea class="form-control form-control form-control-solid" name="remarks" data-kt-autosize="true" placeholder="{{__('lang.addRemarks')}}"></textarea>
        </div>
        <button class="w-100 mt-5 btn-submit-visit-form" id="btn" style="border: 0px;">
          <span class="indicator-label">Submit</span>
          <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
          </span>
        </button>
      </form>
    </div>
  </div>

  <div class="sucess-submission" style="display:none">
    <div class="text-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M24 48C37.2548 48 48 37.2548 48 24C48 10.7452 37.2548 0 24 0C10.7452 0 0 10.7452 0 24C0 37.2548 10.7452 48 24 48ZM33.9007 18.1221C34.6309 17.2932 34.5509 16.0294 33.7221 15.2993C32.8932 14.5691 31.6294 14.6491 30.8993 15.4779L19.7143 28.1744L17.1007 25.2077C16.3706 24.3788 15.1068 24.2989 14.2779 25.029C13.4491 25.7592 13.3691 27.023 14.0993 27.8518L18.2136 32.5221C18.5933 32.953 19.1399 33.2 19.7143 33.2C20.2887 33.2 20.8353 32.953 21.215 32.5221L33.9007 18.1221Z" fill="#29DC50"/>
      </svg>
    </div>
    <span class="pt-3 d-block visit-campus-text">Submission Successful</span>
    <span class="d-block uni-name text-center pt-3">We will contact you on WhatsApp to confirm the schedule</span>
    <span class="d-block pt-20 submission-details-text">Submission details</span>

    <div class="col-12 col-lg-6 mx-auto mt-5">
      <div class="submission-div">
        <div class="col-12 row">
          <div class="col-4">
            <span class="submission-user-label">{{__('lang.fullname')}}</span>
          </div>
          <div class="col-8 text-end">
            <span class="submission-user-details" id="fullname"></span>
          </div>
          <div class="separator my-5"></div>
        </div>
        <div class="col-12 row">
          <div class="col-4">
            <span class="submission-user-label">{{__('lang.emailAddr')}}</span>
          </div>
          <div class="col-8 text-end">
            <span class="submission-user-details" id="email"></span>
          </div>
          <div class="separator my-5"></div>
        </div>
        <div class="col-12 row">
          <div class="col-4">
            <span class="submission-user-label">{{__('lang.contactNum')}}</span>
          </div>
          <div class="col-8 text-end">
            <span class="submission-user-details" id="contact"></span>
          </div>
          <div class="separator my-5"></div>
        </div>
        <div class="col-12 row">
          <div class="col-4">
            <span class="submission-user-label">{{__('lang.courseInterested')}}</span>
          </div>
          <div class="col-8 text-end">
            <span class="submission-user-details" id="course"></span>
          </div>
          <div class="separator my-5"></div>
        </div>
        <div class="col-12 row">
          <div class="col-4">
            <span class="submission-user-label">{{__('lang.levelInterested')}}</span>
          </div>
          <div class="col-8 text-end">
            <span class="submission-user-details" id="level"></span>
          </div>
          <div class="separator my-5"></div>
        </div>
        <div class="col-12 row">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.uniInterested')}}</span>
          </div>
          <div class="col-7 text-end">
            <span class="submission-user-details" id="uni"></span>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-center"><a class="mt-20 btn-submit-visit-form" href="/">Back to home</a></div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
$("#kt_datepicker_2").flatpickr();

$(document).ready(function(){
  var myDropzone = new Dropzone("#kt_dropzonejs_example_1", {
      url: "/submitInterest", // Set the url for your upload script location
      paramName: "file", // The name that will be used to transfer the file
      maxFiles: 1,
      maxFilesize: 10, // MB
      addRemoveLinks: true,
      init: function() {
      this.on("addedfile", function() {
        if (this.files[1]!=null){
          this.removeFile(this.files[0]);
        }
      });
    }
  });

  $('#submit_interest').on('submit', function (e) {
    e.preventDefault();
    let f = new FormData($(this)[0]);

    $('#btn').prop("disabled",true);
    $('.indicator-label').hide();
    $('.indicator-progress').show();
    f.append('attachment', myDropzone.files[0]);

    $.ajax({
      url: "/submitInterest",
      method: 'post',
      data: f,
      enctype: 'multipart/form-data',
      contentType: false,
      processData: false,
      success: function(d) {

        if (d.status) {
          $('#btn').prop("disabled",false);
          $('.indicator-label').show();
          $('.indicator-progress').hide();
          $('.sucess-submission').show();
          $('.submission-form').hide();

          $('#fullname').html(d.data.ri_name);
          $('#email').html(d.data.ri_email);
          $('#contact').html(d.data.ri_contact);
          $('#course').html(d.data.ri_course);
          $('#level').html(d.data.level);
          $('#uni').html(d.data.ri_uni);

          window.scrollBy(-10000, -10000);

        }else{
          $('#btn').prop("disabled",false);
          $('.indicator-label').show();
          $('.indicator-progress').hide();
          Swal.fire({
            title: "Oops...",
            text: d.msg,
            icon: "error"
          });
        }
      }
    });
  });

});
</script>
@endsection
