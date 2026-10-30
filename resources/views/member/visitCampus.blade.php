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

/* .btn-minus-pax,
.btn-add-pax {
  border: 1px solid;
  border-color: #1A124E;
}

.btn-minus-pax:hover,
.btn-add-pax:hover {
  background-color: white;
} */

.btn-submit-visit-form {
  border: 2px solid;
  border-color:#1A124E;
}

.btn-submit-visit-form:hover {
  color:#1A124E;
  background-color:white;
}
</style>
@endsection

@section('content')
<div class="mw-1200 mx-auto pt-140 pb-20">
  <div class="submission-form">
    <span class="d-block visit-campus-text">{{__('lang.campusVisit')}}</span>
    <span class="d-block uni-name text-center pt-3">{{__('lang.visitDesc')}}</span>
    <form id="visit_campus_form">
      <div class="col-12 col-lg-4 mx-auto">
        <div class="mt-10 mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.visitTitle')}}</label>
          <select class="form-select form-select-solid uni-dropdown" name="university[]" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectUni')}}" multiple="multiple" required>
            <option></option>
            @if(isset($university) && count($university) > 0)
            @foreach($university as $u)
            <option value="{{$u['id']}}" data-branch-id="{{$u['branch_id']}}">{{$u['name']}}</option>
            @endforeach
            @endif
          </select>
        </div>
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
          <input type="text" name="full_name" class="form-control form-control-solid" placeholder="{{__('lang.enterFullname')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.icNumber')}}</label>
          <input type="number" name="ic_num" class="form-control form-control-solid" placeholder="{{__('lang.enterICNum')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.contactNumber')}}</label>
          <input type="number" name="contact_no" class="form-control form-control-solid" placeholder="{{__('lang.enterContactNum')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.pax')}}</label>
          <div class="input-group" data-kt-dialer="true" data-kt-dialer-currency="false" data-kt-dialer-min="1" data-kt-dialer-step="1">
            <button class="btn btn-icon btn-outline btn-active-color-primary btn-minus-pax me-5" type="button" data-kt-dialer-control="decrease">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="2" viewBox="0 0 14 2" fill="none">
                <path d="M1 1H13" stroke="#999999" stroke-linecap="round"/>
              </svg>
            </button>

            <input type="text" name="pax" class="form-control text-center" readonly placeholder="Amount" value="1" data-kt-dialer-control="input" style="border: 0px;"/>

            <button class="btn btn-icon btn-outline btn-active-color-primary btn-add-pax ms-5" type="button" data-kt-dialer-control="increase">
              <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
                <path d="M1 7H13" stroke="white" stroke-linecap="round"/>
                <path d="M7 1L7 13" stroke="white" stroke-linecap="round"/>
              </svg>
            </button>
          </div>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.datePreferred')}}</label>
          <span class="d-block uni-location pb-5">{{__('lang.dateDesc')}}</span>
          <div class="col-12 row">
            <div class="col-4">
              <select class="form-select form-select-solid" name="date" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.day')}}" required>
                <option></option>
                @for($i = 1; $i <= 31; $i++)
                <option value="{{$i}}">{{$i}}</option>
                @endfor
              </select>
            </div>
            <div class="col-4">
              <select class="form-select form-select-solid" name="month" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.month')}}" required>
                <option></option>
                @for ($m=1; $m<=12; $m++)
                <option value="{{$m}}">{{date('F', mktime(0,0,0,$m, 1, date('Y')))}}</option>
                @endfor
              </select>
            </div>
            <div class="col-4">
              <select class="form-select form-select-solid" name="year" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.vcyear')}}" required>
                <option></option>
                <option value="{{date('Y') }}">{{date('Y') }}</option>
                <option value="{{date('Y', strtotime(date('Y') . " +1 year") )}}">{{date('Y', strtotime(date('Y') . " +1 year") )}}</option>
              </select>
            </div>
          </div>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.timePreferred')}}</label>
          <span class="d-block uni-location pb-5">{{__('lang.timeDesc')}}</span>
          <select class="form-select form-select-solid" name="time" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectTime')}}" required>
            <option></option>
            @if(isset($time) && count($time) > 0)
            @foreach($time as $i => $t)
            <option value="{{$i}}">{{$t}}</option>
            @endforeach
            @endif
          </select>
        </div>
        <!-- <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">Additional Universities</label>
          <span class="d-block uni-location pb-5">Please select additional university you wish to visit</span>
            <select name="other_uni[]" class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Select university" multiple="multiple">
              <option></option>
              <option value="1">Taylors</option>
              <option value="2">APU</option>
              <option value="2">MSU</option>
            </select>
          <button class="btn-add-uni mt-5">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14" fill="none">
              <path d="M7 1L7 13" stroke="#1A124E" stroke-linecap="round"/>
              <path d="M13 7L1 7" stroke="#1A124E" stroke-linecap="round"/>
            </svg>
            Add university
          </button>
        </div> -->
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.remarks')}}</label>
          <textarea class="form-control form-control form-control-solid" name="remarks" data-kt-autosize="true" placeholder="{{__('lang.enterRemarks')}}"></textarea>
        </div>
        <button type="submit" class="w-100 mt-5 btn-submit-visit-form">{{__('lang.submit')}}</button>
      </div>
    </form>
  </div>

  <div class="sucess-submission" style="display:none;">
    <div class="text-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M24 48C37.2548 48 48 37.2548 48 24C48 10.7452 37.2548 0 24 0C10.7452 0 0 10.7452 0 24C0 37.2548 10.7452 48 24 48ZM33.9007 18.1221C34.6309 17.2932 34.5509 16.0294 33.7221 15.2993C32.8932 14.5691 31.6294 14.6491 30.8993 15.4779L19.7143 28.1744L17.1007 25.2077C16.3706 24.3788 15.1068 24.2989 14.2779 25.029C13.4491 25.7592 13.3691 27.023 14.0993 27.8518L18.2136 32.5221C18.5933 32.953 19.1399 33.2 19.7143 33.2C20.2887 33.2 20.8353 32.953 21.215 32.5221L33.9007 18.1221Z" fill="#29DC50"/>
      </svg>
    </div>
    <span class="pt-3 d-block visit-campus-text">{{__('lang.submitSuccess')}}</span>
    <span class="d-block uni-name text-center pt-3">{{__('lang.submitWhatsapp')}}</span>
    <span class="d-block pt-20 submission-details-text">{{__('lang.submitDetails')}}</span>

    <div class="col-12 col-lg-6 mx-auto mt-5">
      <div class="submission-div">
        <div class="col-12 row">
          <div class="col-4">
            <span class="submission-user-label">{{__('lang.uniPrefer')}}</span>
          </div>
          <div class="col-8 text-end">
            <span class="submission-user-details" id="uni_name"></span>
          </div>
          <div class="separator my-5"></div>
        </div>
        <div class="col-12 row">
          <div class="col-4">
            <span class="submission-user-label">{{__('lang.fullName')}}</span>
          </div>
          <div class="col-8 text-end">
            <span class="submission-user-details" id="full_name"></span>
          </div>
          <div class="separator my-5"></div>
        </div>
        <div class="col-12 row">
          <div class="col-4">
            <span class="submission-user-label">{{__('lang.icNumber')}}</span>
          </div>
          <div class="col-8 text-end">
            <span class="submission-user-details" id="ic_num"></span>
          </div>
          <div class="separator my-5"></div>
        </div>
        <div class="col-12 row">
          <div class="col-4">
            <span class="submission-user-label">{{__('lang.contactNumber')}}</span>
          </div>
          <div class="col-8 text-end">
            <span class="submission-user-details" id="contact_num"></span>
          </div>
          <div class="separator my-5"></div>
        </div>
        <div class="col-12 row">
          <div class="col-4">
            <span class="submission-user-label">{{__('lang.datePreferred')}}</span>
          </div>
          <div class="col-8 text-end">
            <span class="submission-user-details" id="date"></span>
          </div>
          <div class="separator my-5"></div>
        </div>
        <div class="col-12 row">
          <div class="col-4">
            <span class="submission-user-label">{{__('lang.timePreferred')}}</span>
          </div>
          <div class="col-8 text-end">
            <span class="submission-user-details" id="time"></span>
          </div>
          <div class="separator my-5"></div>
        </div>
        <!-- <div class="col-12 row">
          <div class="col-5">
            <span class="submission-user-label">Additional Universities</span>
          </div>
          <div class="col-7 text-end">
            <span class="submission-user-details">Taylor’s University, Taylor’s University, Taylor’s University</span>
          </div>
          <div class="separator my-5"></div>
        </div> -->
        <div class="col-12 row">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.remarks')}}</span>
          </div>
          <div class="col-7 text-end">
            <span class="submission-user-details" id="remarks"></span>
          </div>
        </div>
      </div>
      <div class="d-flex justify-content-center"><a class="mt-20 btn-submit-visit-form" href="/">{{__('lang.backToHome')}}</a></div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
// $('.btn-submit-visit-form').on('click', function(){
//   $('.sucess-submission').show();
//   $('.submission-form').hide();
// })

$(document).ready(function(){
  // $(document).on('click', '.add-visit-uni-btn', function(){
  //   var myClone = $("#clone-hidden").clone();
  //   myClone.find("select").select2();
  //   myClone.show();
  //   $("#uni-list").append(myClone);
  // })

  $('#visit_campus_form').on('submit', function (e) {
    e.preventDefault();
    var branch = [];
    $(".uni-dropdown option:selected").each(function () {
      branch.push($(this).data('branch-id'))
    })
    let f = new FormData($(this)[0]);
    f.append('branch', branch);
    console.log(branch)
    $('.btn-submit-visit-form').prop("disabled",true);

    $.ajax({
      url: "/submitVisitCampus",
      method: 'post',
      data: f,
      contentType: false,
      processData: false,
      success: function(d) {

        console.log(d)
        if (d.status) {
          var data = d.data;
          $('.btn-submit-visit-form').prop("disabled",false);
          $('.sucess-submission').show();
          $('.submission-form').hide();
          $('.toast-error').toast('hide');

          $('#uni_name').html(data.uni);
          $('#full_name').html(data.vc_name);
          $('#ic_num').html(data.vc_ic_num);
          $('#contact_num').html(data.vc_contact_no);
          $('#date').html(data.date);
          $('#time').html(data.time);
          $('#remarks').html(data.vc_remarks);
          $(window).scrollTop(0);
        }else{
          $('.btn-submit-visit-form').prop("disabled",false);
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
