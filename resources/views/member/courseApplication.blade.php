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
  <div class="submission-form">
    <span class="d-block visit-campus-text">{{__('lang.courseApplication')}}</span>
    <span class="d-block uni-name text-center pt-3">{{__('lang.courseAppDesc')}}</span>
    <div class="col-12 col-lg-7 mx-auto course-application-div mt-5">
      <div class="col-12 row">
        <div class="col-4">
          <span class="course-application-text-1">{{__('lang.institution')}}</span>
        </div>
        <div class="col-8">
          <span class="course-application-text-2">{{$c->u_name}}</span>
        </div>
      </div>
      <div class="col-12 row mt-2">
        <div class="col-4">
          <span class="course-application-text-1">{{__('lang.course')}}</span>
        </div>
        <div class="col-8">
          <span class="course-application-text-2">{{$c->c_name}}</span>
        </div>
      </div>
      <div class="col-12 row align-items-center mt-2">
        <div class="col-4">
          <span class="course-application-text-1">{{__('lang.savingsEdu4u')}}</span>
        </div>
        <div class="col-8">
          <span class="course-application-text-2" id="ttl_saving"></span>
        </div>
      </div>
    </div>
    <form id="course_application_form">
      <div class="col-12 col-lg-4 mx-auto mt-20">
        <span class="d-block application-title mb-5 ms-3">1. {{__('lang.personalDetails')}}</span>
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
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.dateOfBirth')}}</label>
          <div class="col-12 row">
            <div class="col-4">
              <input type="text" name="dob_date" class="form-control form-control-solid" placeholder="{{__('lang.dd')}}" required/>
            </div>
            <div class="col-4">
              <input type="text" name="dob_month" class="form-control form-control-solid" placeholder="{{__('lang.mm')}}" required/>
            </div>
            <div class="col-4">
              <input type="text" name="dob_year" class="form-control form-control-solid" placeholder="{{__('lang.yyyy')}}" required/>
            </div>
          </div>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.nricPassport')}}</label>
          <input type="number" name="ic_num" class="form-control form-control-solid" placeholder="{{__('lang.enterICNum')}}" required/>
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
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.nationality')}}</label>
          <select class="form-select form-select-solid" name="nationality" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectNationality')}}" required>
            <option></option>
            <option value="1">{{__('lang.malaysian')}}</option>
            <option value="2">{{__('lang.nonMalaysian')}}</option>
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.martialStatus')}}</label>
          <select class="form-select form-select-solid" name="martial_status" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectMartialStatus')}}" required>
            <option></option>
            <option value="1">{{__('lang.single')}}</option>
            <option value="2">{{__('lang.married')}}</option>
            <option value="3">{{__('lang.divorced')}}</option>
            <option value="4">{{__('lang.widowed')}}</option>
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.ethnicity')}}</label>
          <select class="form-select form-select-solid" name="ethnicity" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectEthnicity')}}" required>
            <option></option>
            <option value="1">{{__('lang.malay')}}</option>
            <option value="2">{{__('lang.chinese')}}</option>
            <option value="3">{{__('lang.indian')}}</option>
            <option value="4">{{__('lang.others')}}</option>
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.religion')}}</label>
          <select class="form-select form-select-solid" name="religion" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectReligion')}}" required>
            <option></option>
            <option value="1">{{__('lang.muslim')}}</option>
            <option value="2">{{__('lang.christian')}}</option>
            <option value="3">{{__('lang.hindus')}}</option>
            <option value="4">{{__('lang.others')}}</option>
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.homePhoneNumber')}} <span class="option-text">{{__('lang.optional')}}</span></label>
          <input type="number" name="home_contact_no" class="form-control form-control-solid" placeholder="{{__('lang.enterHomePhoneNumber')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.mobileNumber')}}</label>
          <input type="number" name="contact_no" class="form-control form-control-solid" placeholder="{{__('lang.whatsYourPhoneNumber')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.emailAddr')}}</label>
          <input type="text" name="email" class="form-control form-control-solid" placeholder="{{__('lang.enterEmailAddr')}}" required/>
        </div>

        <div class="separator-top mt-10 mb-10"></div>
        <span class="d-block application-title mb-5 ms-3">2. {{__('lang.emergencyContact')}}</span>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.emergencyContactName')}}</label>
          <input type="text" name="emergency_contact_name" class="form-control form-control-solid" placeholder="{{__('lang.enterEmergencyContactName')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.emergencyContactNo')}}</label>
          <input type="number" name="emergency_contact_no" class="form-control form-control-solid" placeholder="{{__('lang.enterEmergencyContactNo')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.emergencyContactEmailAddr')}}</label>
          <input type="text" name="emergency_contact_email" class="form-control form-control-solid" placeholder="{{__('lang.enterEmergencyContactEmailAddr')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.relationship')}}</label>
          <select class="form-select form-select-solid" name="relationship" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectRelationship')}}" required>
            <option></option>
            <option value="1">{{__('lang.parents')}}</option>
            <option value="2">{{__('lang.siblings')}}</option>
            <option value="3">{{__('lang.partner')}}</option>
            <option value="4">{{__('lang.others')}}</option>
          </select>
        </div>

        <div class="separator-top mt-10 mb-10"></div>
        <span class="d-block application-title mb-5 ms-3">3. {{__('lang.permanentAddr')}}</span>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.addr')}}</label>
          <input type="text" name="p_addr" class="form-control form-control-solid" placeholder="{{__('lang.enterAddr')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.cityTown')}}</label>
          <input type="text" name="p_city" class="form-control form-control-solid" placeholder="{{__('lang.enterCityTown')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.state')}}</label>
          <input type="text" name="p_state" class="form-control form-control-solid" placeholder="{{__('lang.enterState')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.country')}}</label>
          <input type="text" name="p_country" class="form-control form-control-solid" placeholder="{{__('lang.enterCountry')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.postCode')}}</label>
          <input type="text" name="p_postcode" class="form-control form-control-solid" placeholder="{{__('lang.enterPostCode')}}" required/>
        </div>

        <div class="separator-top mt-10 mb-10"></div>
        <span class="d-block application-title mb-5 ms-3">4. {{__('lang.mailingAddr')}}</span>
        <div class="form-check form-check-custom form-check-solid form-check-sm mb-5">
          <input class="form-check-input" type="checkbox" value="1" id="flexRadioLg" name="is_same_addr"/>
          <label class="form-check-label p_addr_label" for="flexRadioLg">
            {{__('lang.pleaseTick')}}
          </label>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.addr')}}</label>
          <input type="text" name="m_addr" class="form-control form-control-solid" placeholder="{{__('lang.enterAddr')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.cityTown')}}</label>
          <input type="text" name="m_city" class="form-control form-control-solid" placeholder="{{__('lang.enterCityTown')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.state')}}</label>
          <input type="text" name="m_state" class="form-control form-control-solid" placeholder="{{__('lang.enterState')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.country')}}</label>
          <input type="text" name="m_country" class="form-control form-control-solid" placeholder="{{__('lang.enterCountry')}}"/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.postCode')}}</label>
          <input type="text" name="m_postcode" class="form-control form-control-solid" placeholder="{{__('lang.enterPostCode')}}"/>
        </div>

        <div class="separator-top mt-10 mb-10"></div>
        <span class="d-block application-title mb-5 ms-3">5. {{__('lang.lastAcademic')}}</span>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.nameQualification')}}</label>
          <select class="form-select form-select-solid" name="qualification" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectQualification')}}" required>
            <option></option>
            @if(isset($qualification) && count($qualification) > 0)
            @foreach($qualification as $i => $q)
            <option value="{{$i}}">{{$q}}</option>
            @endforeach
            @endif
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.nameState')}} <br><span class="personality-counter fw-normal">({{__('lang.eg')}} SMJK PJ Katholik, Chung Hua High School)</span></label>
          <input type="text" name="school_name" class="form-control form-control-solid" placeholder="{{__('lang.enterSchoolName')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.yearCompletion')}}</label>
          <input type="text" name="year_completion" class="form-control form-control-solid" placeholder="{{__('lang.enterYearCompletion')}}" required/>
        </div>  
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.applyCreditTransfer')}}</label>
          <select class="form-select form-select-solid" name="credit_transfer" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectOption')}}" required>
            <option></option>
            <option value="1">{{__('lang.yes')}}</option>
            <option value="2">{{__('lang.no')}}</option>
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.firstFamily')}}</label>
          <select class="form-select form-select-solid" name="member_uni" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectOption')}}" required>
            <option></option>
            <option value="1">{{__('lang.yes')}}</option>
            <option value="2">{{__('lang.no')}}</option>
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.resultTranscript')}} <span class="option-text">{{__('lang.optional')}}</span></label>
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
              <div class="col-6 ms-5"><span class="upload-docs-desc">{{__('lang.uploadDoscDesc')}}</span></div>
            </div>
          </div>
          </div>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.yourResume')}} <span class="option-text">{{__('lang.optionalMaster')}}</span></label>
          <div id="upload_resume">
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
              <div><a class="dropzone-select btn upload-docs-btn">{{__('lang.uploadResume')}}</a></div>
              <div class="col-6 ms-5"><span class="upload-docs-desc">{{__('lang.uploadDoscDesc')}}</span></div>
            </div>
          </div>
          </div>
        </div>

        <div class="separator-top mt-10 mb-10"></div>
        <span class="d-block application-title mb-5 ms-3">6. {{__('lang.engQualification')}}</span>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.engQualification')}}</label>
          <select class="form-select form-select-solid" name="english_q" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectYourQualification')}}" required>
            <option></option>
            <option value="1">MUET</option>
            <option value="2">ILETS</option>
            <option value="3">TOEFL</option>
            <option value="4">GSCE/GCE 'O' Level</option>
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.score')}}</label>
          <input type="text" name="english_score" class="form-control form-control-solid" placeholder="{{__('lang.enterScore')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.yearTaken')}}</label>
          <input type="number" name="english_year" class="form-control form-control-solid" placeholder="{{__('lang.enterYearTaken')}}" required/>
        </div>

        <div class="separator-top mt-10 mb-10"></div>
        <span class="d-block application-title mb-5 ms-3">7. {{__('lang.householdIncome')}}</span>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.totalIncome')}}</label>
          <select class="form-select form-select-solid" name="income" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectTotalIncome')}}" required>
            <option></option>
            <option value="1">{{__('lang.below')}} MYR 4,000</option>
            <option value="2">{{__('lang.below')}} MYR 8,000</option>
            <option value="3">{{__('lang.above')}} MYR 8,000</option>
          </select>
        </div>

        <div class="separator-top mt-10 mb-10"></div>
        <span class="d-block application-title mb-5 ms-3">8. {{__('lang.programmePreferrences')}}</span>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.programmeName')}}</label>
          <input type="text" name="programme_name" class="form-control form-control-solid" placeholder="{{__('lang.enterProgrammeName')}}" required/>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.intakeMonth')}}</label>
          <select class="form-select form-select-solid" name="programme_month" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectIntakeMonth')}}" required>
            <option></option>
            <@for ($m=1; $m<=12; $m++)
              <option value="{{$m}}">{{date('F', mktime(0,0,0,$m, 1, date('Y')))}}</option>
            @endfor
          </select>
        </div>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.intakeYear')}}</label>
          <select class="form-select form-select-solid" name="programme_year" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectIntakeYear')}}" required>
            <option></option>
            <option value="{{date('Y') }}">{{date('Y') }}</option>
            <option value="{{date('Y', strtotime(date('Y') . " +1 year") )}}">{{date('Y', strtotime(date('Y') . " +1 year") )}}</option>
          </select>
        </div>
        <div class="separator-top mt-10 mb-10"></div>
        <span class="d-block application-title mb-5 ms-3">9. {{__('lang.disability')}}</span>
        <div class="mb-5">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.disabilityDesc')}}</label>
          <select class="form-select form-select-solid" name="disability" data-control="select2" data-hide-search="true" data-placeholder="{{__('lang.selectOption')}}" required>
            <option></option>
            <option value="1">{{__('lang.yes')}}</option>
            <option value="2">{{__('lang.no')}}</option>
          </select>
        </div>
        <div class="mb-5" id="disability_input" style="display:none">
          <label for="exampleFormControlInput1" class="form-label">{{__('lang.pleaseSpecify')}}</label>
          <input type="text" name="disbaility_name" class="form-control form-control-solid" placeholder="{{__('lang.specifyDesc')}}"/>
        </div>

        <button class="w-100 mt-5 btn-submit-visit-form" id="btn_submit_application">{{__('lang.submit')}}</button>
      </div>
    </form>
  </div>

  <div class="sucess-submission pt-10" style="display:none">
    <div class="text-center">
      <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48" fill="none">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M24 48C37.2548 48 48 37.2548 48 24C48 10.7452 37.2548 0 24 0C10.7452 0 0 10.7452 0 24C0 37.2548 10.7452 48 24 48ZM33.9007 18.1221C34.6309 17.2932 34.5509 16.0294 33.7221 15.2993C32.8932 14.5691 31.6294 14.6491 30.8993 15.4779L19.7143 28.1744L17.1007 25.2077C16.3706 24.3788 15.1068 24.2989 14.2779 25.029C13.4491 25.7592 13.3691 27.023 14.0993 27.8518L18.2136 32.5221C18.5933 32.953 19.1399 33.2 19.7143 33.2C20.2887 33.2 20.8353 32.953 21.215 32.5221L33.9007 18.1221Z" fill="#29DC50"/>
      </svg>
    </div>
    <span class="pt-3 d-block visit-campus-text">{{__('lang.submitSuccess')}}</span>
    <span class="d-block uni-name text-center pt-3">{{__('lang.submitConsult')}}</span>
    <span class="d-block pt-20 submission-details-text">{{__('lang.submitDetails')}}</span>

    <div class="col-7 mx-auto mt-5">
      <div class="submission-div">
        <div class="col-12 row mb-2">
          <span class="course-application-details-header d-block mb-5">1. {{__('lang.personalDetails')}}</span>
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.salutation')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="salutation"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.fullName')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="name"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.dateOfBirth')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="dob"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.nricPassport')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="ic_num"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.gender')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="gender"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.nationality')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="nationality"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.martialStatus')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="marital_status"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.ethnicity')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="ethnicity"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.religion')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="religion"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.homePhoneNumber')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="home_num"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.mobileNumber')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="mobile_num"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.emailAddr')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="email"></span>
          </div>
        </div>

        <div class="separator-top mt-5"></div>
        <span class="course-application-details-header d-block my-5">2. {{__('lang.emergencyContact')}}</span>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.emergencyContactName')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="e_name"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.emergencyContactNo')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="e_contact"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.emergencyContactEmailAddr')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="e_email"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.relationship')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="e_relationship"></span>
          </div>
        </div>

        <div class="separator-top mt-5"></div>
        <span class="course-application-details-header d-block my-5">3. {{__('lang.permanentAddr')}}</span>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.addr')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="addr"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.cityTown')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="city"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.state')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="state"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.country')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="country"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.postCode')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="postcode"></span>
          </div>
        </div>

        <div class="separator-top mt-5"></div>
        <span class="course-application-details-header d-block my-5">4. {{__('lang.mailingAddr')}}</span>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.addr')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="m_addr"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.cityTown')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="m_city"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.state')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="m_state"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.country')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="m_country"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.postCode')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="m_postcode"></span>
          </div>
        </div>
        
        <div class="separator-top mt-5"></div>
        <span class="course-application-details-header d-block my-5">5. {{__('lang.lastAcademic')}}</span>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.nameQualification')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="qualification"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.nameState')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="q_name"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.yearCompletion')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="q_year"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.applyCreditTransfer')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="credit_transfer"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.firstFamily')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="member_uni"></span>
          </div>
        </div>

        <div class="separator-top mt-5"></div>
        <span class="course-application-details-header d-block my-5">6. {{__('lang.engQualification')}}</span>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.engQualification')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="e_qualification"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.score')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="e_score"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.yearTaken')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="e_year"></span>
          </div>
        </div>

        <div class="separator-top mt-5"></div>
        <span class="course-application-details-header d-block my-5">7. {{__('lang.householdIncome')}}</span>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.totalIncome')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="h_ttl"></span>
          </div>
        </div>

        <div class="separator-top mt-5"></div>
        <span class="course-application-details-header d-block my-5">8. {{__('lang.programmePreferrences')}}</span>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.programmeName')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="p_name"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.intakeMonth')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="p_month"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.intakeYear')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="p_year"></span>
          </div>
        </div>

        <div class="separator-top mt-5"></div>
        <span class="course-application-details-header d-block my-5">9. {{__('lang.disability')}}</span>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.disabilityDesc')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="disability"></span>
          </div>
        </div>
        <div class="col-12 row mb-2">
          <div class="col-5">
            <span class="submission-user-label">{{__('lang.pleaseSpecify')}}</span>
          </div>
          <div class="col-7 text-start">
            <span class="submission-user-details" id="disability_desc"></span>
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

const resume_id = "#upload_resume";
const resume_dropzone = document.querySelector(resume_id);

var previewNode1 = resume_dropzone.querySelector(".dropzone-item");
previewNode1.resume_id = "";
var previewTemplate = previewNode1.parentNode.innerHTML;
previewNode1.parentNode.removeChild(previewNode1);

var resume_myDropzone = new Dropzone(resume_id, {
    url: "/submitAddBanner",
    parallelUploads: 5,
    maxFilesize: 5,
    maxFiles: 5,
    previewTemplate: previewTemplate,
    previewsContainer: resume_id + " .dropzone-items",
    clickable: resume_id + " .dropzone-select"
});

resume_myDropzone.on("addedfile", function (file) {
    // Hookup the start button
    const dropzoneItems = resume_dropzone.querySelectorAll('.dropzone-item');
    const dropzoneItems1 = resume_dropzone.querySelectorAll('.dropzone-items');
    dropzoneItems.forEach(dropzoneItem => {
        dropzoneItem.style.display = '';
    });

    dropzoneItems1.forEach(dropzoneItem1 => {
        dropzoneItem1.style.display = '';
    });
});

// Update the total progress bar
resume_myDropzone.on("totaluploadprogress", function (progress) {
    const progressBars = resume_dropzone.querySelectorAll('.progress-bar');
    progressBars.forEach(progressBar => {
        progressBar.style.width = progress + "%";
    });
});

resume_myDropzone.on("sending", function (file) {
    // Show the total progress bar when upload starts
    const progressBars = resume_dropzone.querySelectorAll('.progress-bar');
    progressBars.forEach(progressBar => {
        progressBar.style.opacity = "1";
    });
});

// Hide the total progress bar when nothing"s uploading anymore
resume_myDropzone.on("complete", function (progress) {
    const progressBars = resume_dropzone.querySelectorAll('.dz-complete');

    setTimeout(function () {
        progressBars.forEach(progressBar => {
            progressBar.querySelector('.progress-bar').style.opacity = "0";
            progressBar.querySelector('.progress').style.opacity = "0";
        });
    }, 300);
});

$(document).ready(function(){

  $('#ttl_saving').html('MYR '+parseInt(sessionStorage.getItem("course_price")).toLocaleString());

  $('select[name="disability"]').on('change', function(){
    var type = $(this).val();

    if(type == 1) {
      $('#disability_input').show();
    } else{
      $('#disability_input').hide();
    }

  })

  $('#course_application_form').on('submit', function (e) {
    e.preventDefault();
    let f = new FormData($(this)[0]);
    f.append('course_id', {{$c->c_id}});

    transcript_myDropzone.files.forEach(function(item) {
      f.append('transcript[]', item);
    })

    resume_myDropzone.files.forEach(function(item) {
      f.append('resume[]', item);
    })

    $('#btn_submit_application').prop("disabled",true);

    $.ajax({
      url: "/submitCourseApplication",
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

          $('#salutation').html(data.ca_salutation);
          $('#name').html(data.ca_name);
          $('#dob').html(data.dob);
          $('#ic_num').html(data.ca_ic_num);
          $('#gender').html(data.gender);
          $('#nationality').html(data.nationality);
          $('#marital_status').html(data.martial_status);
          $('#ethnicity').html(data.ethnicity);
          $('#religion').html(data.religion);
          if(data.ca_home_phone != null) {
            $('#home_num').html(data.ca_home_phone);
          } else{
            $('#home_num').html('-');
          }
          $('#mobile_num').html(data.ca_phone);
          $('#email').html(data.ca_email)

          $('#e_name').html(data.ca_e_name);
          $('#e_contact').html(data.ca_e_contact);
          $('#e_email').html(data.ca_e_email);
          $('#e_relationship').html(data.relationship);

          $('#addr').html(data.ca_p_addr);
          $('#city').html(data.ca_p_city);
          $('#state').html(data.ca_p_state);
          $('#country').html(data.ca_p_country);
          $('#postcode').html(data.ca_p_postcode);

          $('#m_addr').html(data.ca_m_addr);
          $('#m_city').html(data.ca_m_city);
          $('#m_state').html(data.ca_m_state);
          $('#m_country').html(data.ca_m_country);
          $('#m_postcode').html(data.ca_m_postcode);

          $('#qualification').html(data.qualification);
          $('#q_name').html(data.ca_qualification_school_name);
          $('#q_year').html(data.ca_qualification_year);
          $('#credit_transfer').html(data.credit);
          $('#member_uni').html(data.family);

          $('#e_qualification').html(data.e_qualification);
          $('#e_score').html(data.ca_e_score);
          $('#e_year').html(data.ca_e_year);

          $('#h_ttl').html(data.income);

          $('#p_name').html(data.ca_programme_name);
          $('#p_month').html(data.intake_month);
          $('#p_year').html(data.ca_programme_year);

          $('#disability').html(data.disability);
          if(data.ca_disability_desc != null) {
            $('#disability_desc').html(data.ca_disability_desc);
          } else{
            $('#disability_desc').html('-');
          }
          

          $(window).scrollTop(0);
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
