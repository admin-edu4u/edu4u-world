@extends('memberLayout')
@section('style')
<style>
  @media screen and (max-width: 776px) {
    .mw-1200 {
      padding-top: 80px;
      padding-bottom: 114px;
    }
    #personality-counter {
      float: left !important;
      margin-bottom:10px;
      }
    }

    .dropzone-items {
      width: 50%;
    }

    .dropzone-error {
      display: none;
    }

    .progress-text.active {
      color: #1F1F1F;
      font-size: 14px !important;
      display: block;
      margin-bottom: 8px;
    }

    .progress-text {
      color: #D4D4D4;
      font-size: 14px !important;
      display: block;
      margin-bottom: 8px;
    }

    .step-indicator {
      margin-right: 80px;
    }

    .progress-dot.active{
      fill: #1A124E;
    }

    .progress-dot{
      fill: #D4D4D4;
    }

</style>
@endsection

@section('content')
@php
use \Jenssegers\Agent\Agent;
$agent = new Agent();
@endphp
<div class="position-relative" style="min-height: 100vh;">
  <div class="mw-1200 mx-auto pt-140">
    <div class="col-12 row">
      @if(!$agent->isMobile())
      <div class="col-4 mb-20" id="side_bar_steps">
        <div class="separator-right step-indicator">
            <span class="progress-step progress-text active">
              <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                <circle class="progress-dot active" cx="4" cy="4.5" r="4" fill="#1A124E"/>
              </svg>
              {{__('lang.pathwayStep1')}}
            </span>
            <span class="progress-step progress-text">
              <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                <circle class="progress-dot" cx="4" cy="4.5" r="4" fill="#D4D4D4"/>
              </svg>
              {{__('lang.pathwayStep2')}}
            </span>
            <span class="progress-step progress-text">
              <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                <circle class="progress-dot" cx="4" cy="4.5" r="4" fill="#D4D4D4"/>
              </svg>
              {{__('lang.pathwayStep3')}}
            </span>
            <span class="progress-step progress-text">
              <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                <circle class="progress-dot" cx="4" cy="4.5" r="4" fill="#D4D4D4"/>
              </svg>
              {{__('lang.pathwayStep4')}}
            </span>
            <!-- <span class="progress-step progress-text">
              <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                <circle class="progress-dot" cx="4" cy="4.5" r="4" fill="#D4D4D4"/>
              </svg>
              {{__('lang.pathwayStep5')}}
            </span> -->
            <span class="progress-step progress-text">
              <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                <circle class="progress-dot" cx="4" cy="4.5" r="4" fill="#D4D4D4"/>
              </svg>
              {{__('lang.pathwayStep5')}}
            </span>
            <span class="progress-step progress-text">
              <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                <circle class="progress-dot" cx="4" cy="4.5" r="4" fill="#D4D4D4"/>
              </svg>
              {{__('lang.pathwayStep6')}}
            </span>
            <span class="progress-step progress-text">
              <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                <circle class="progress-dot" cx="4" cy="4.5" r="4" fill="#D4D4D4"/>
              </svg>
              {{__('lang.pathwayStep7')}}
            </span>
            <span class="progress-step progress-text">
              <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                <circle class="progress-dot" cx="4" cy="4.5" r="4" fill="#D4D4D4"/>
              </svg>
              {{__('lang.pathwayStep8')}}
            </span>
            <span class="progress-step progress-text">
              <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                <circle class="progress-dot" cx="4" cy="4.5" r="4" fill="#D4D4D4"/>
              </svg>
              {{__('lang.pathwayStep9')}}
            </span>
            <span class="progress-step progress-text">
              <svg class="me-2" xmlns="http://www.w3.org/2000/svg" width="8" height="9" viewBox="0 0 8 9" fill="none">
                <circle class="progress-dot" cx="4" cy="4.5" r="4" fill="#D4D4D4"/>
              </svg>
              {{__('lang.pathwayStep10')}}
            </span>
              <!-- <div class="progressbar-wrapper">
              <ul class="progressbar">
                <li class="progress-tab active">Personality & Account</li>
                <li class="progress-tab">Academic profile</li>
                <li class="progress-tab">Achievements & Family</li>
                <li class="progress-tab">Course Preferences</li>
                <li class="progress-tab">University Preferences</li>
                <li class="progress-tab">Matched Results</li>
              </ul>
            </div> -->
        </div>
      </div>
      @endif
      <div class="col-12 col-lg-8">
        <div class="tab">
        @if($agent->isMobile())<div class="col-12" style="font-size:14px; font-weight:500;">{{__('lang.pathwayStep1')}}</div><hr>@endif
          <div class="mb-personality-15">
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whatsYourGender')}}</label>
              <div class="d-flex mb-5">
                <div class="col-3 col-lg-1 me-3 select-gender-div" data-gender="1">{{__('lang.male')}}</div>
                <div class="col-3 col-lg-1 me-3 select-gender-div" data-gender="2">{{__('lang.female')}}</div>
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whatsYourPhoneNumber')}}</label>
              <div class="input-group mb-5">
                <span class="input-group-text bg-dark-grey col-2 col-lg-1" id="basic-addon1" style="border: 0px;">+60</span>
                <input type="number" name="phone_number" class="col-10 col-lg-4 date-input custom-input-placeholder" style="border-left: 1px solid #CCCCCC;" placeholder="{{__('lang.enterPhoneNum')}}" aria-describedby="basic-addon1"/>
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whensYourBirthday')}}</label>
              <div class="row mb-5">
                <input type="number" name="dob_day" class="col-3 col-lg-1 date-input me-3 custom-input-placeholder" placeholder="{{__('lang.dd')}}"/>
                <input type="number" name="dob_month" class="col-3 col-lg-1 date-input me-3 custom-input-placeholder" placeholder="{{__('lang.mm')}}"/>
                <input type="number" name="dob_year" class="col-3 col-lg-1 date-input custom-input-placeholder" placeholder="{{__('lang.yyyy')}}"/>
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.areYouMalaysian')}}</label>
              <div class="d-flex mb-5">
                <div class="col-3 col-lg-1 me-3 select-gender-div malaysian-div" data-malaysian="1">{{__('lang.yes')}}</div>
                <div class="col-3 col-lg-1 me-3 select-gender-div malaysian-div" data-malaysian="2">{{__('lang.no')}}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab" style="display:none">
        @if($agent->isMobile())<div class="col-12" style="font-size:14px; font-weight:500;">{{__('lang.pathwayStep2')}}</div><hr>@endif
          <div class="mb-personality-15">
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="mb-3 form-label col-12" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whatsYourDescribe')}} <span class="float-end personality-counter" id="personality-counter">Select <span id="ttl_describe">0</span>/10</span></label>
              <div class="col-12 d-flex flex-row flex-wrap">
                @if(isset($desc) && count($desc) > 0)
                @foreach($desc as $d)
                <div class="me-3 select-gender-div select-describe mb-5" data-personality_1="{{$d->p_id}}" style="padding: 8px 16px;">{{$d->p_name}}</div>
                @endforeach
                @endif
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="mb-3 form-label col-12" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whatsYourInterest')}} <span class="float-end personality-counter" id="personality-counter">Select  <span id="ttl_interest">0</span>/10</span></label>
              <div class="col-12 d-flex flex-row flex-wrap">
                @if(isset($interest) && count($interest) > 0)
                @foreach($interest as $i)
                <div class="me-3 select-gender-div select-interest mb-5" data-personality_2="{{$i->p_id}}" style="padding: 8px 16px;">{{$i->p_name}}</div>
                @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>
        <div class="tab" style="display:none">
        @if($agent->isMobile())<div class="col-12" style="font-size:14px; font-weight:500;">{{__('lang.pathwayStep3')}}</div><hr>@endif
          <div class="mb-personality-15">
            <!-- <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label">Are you a Malaysian?</label>
              <div class="row  mb-5">
                <div class="col-1 me-5 select-gender-div malaysian-div" data-malaysian="1">Yes</div>
                <div class="col-1 me-5 select-gender-div malaysian-div" data-malaysian="2">No</div>
              </div>
            </div> -->
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whatsYourEducationalLevel')}}</label>
              <div class="d-flex mb-5 @if($agent->isMobile()) flex-column @endif">
                <div class="col-8 col-lg-4 me-3 select-gender-div d-flex justify-content-center align-items-center academic-div active @if($agent->isMobile()) mb-3 @endif" data-academic="1">SPM, O-Level & {{__('lang.equivalent')}}</div>
                <div class="col-12 col-lg-4 me-3 select-gender-div d-flex justify-content-center align-items-center academic-div @if($agent->isMobile()) mb-3 @endif" data-academic="2">STPM, UEC, Foundation, Diploma, A-Level & {{__('lang.equivalent')}}</div>
                <div class="col-4 col-lg-3 select-gender-div d-flex justify-content-center align-items-center academic-div" data-academic="3">{{__('lang.others')}}</div>
              </div>
            </div>
            <div class="mb-10" id="qualification-option">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whatsYourQualification')}}</label>
              <div class="col-12 d-flex flex-row flex-wrap mb-5" id="qualification_1">
                <div class="me-3 select-gender-div academic-level" data-level-id="1" data-level-name="SPM">SPM</div>
                <div class="me-3 select-gender-div academic-level" data-level-id="2" data-level-name="O-Level">O-Level</div>
                <div class="select-gender-div academic-level" data-level-id="12" data-level-name="Others">{{__('lang.others')}}</div>
              </div>
              <div class="col-12 d-none flex-row flex-wrap mb-5" id="qualification_2">
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="3" data-level-name="STPM">STPM</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="4" data-level-name="UEC">UEC</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="5" data-level-name="Foundation">Foundation</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="6" data-level-name="Diploma">Diploma</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="7" data-level-name="A-Level">A-Level</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="8" data-level-name="IB Diploma">IB Diploma</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="9" data-level-name="CPU/CIMP">CPU/CIMP</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="10" data-level-name="SACE/WACE">SACE/WACE</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="13" data-level-name="SKM/Certificate">SKM/Certificate</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="14" data-level-name="Pre-University">Pre-University</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="15" data-level-name="Monash University Foundation Year (MUFY)">Monash University Foundation Year (MUFY)</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="16" data-level-name="Master">Master</div>
                <div class="me-3 select-gender-div academic-level mb-5" data-level-id="17" data-level-name="PHD">PHD</div>
                <div class="select-gender-div academic-level mb-5" data-level-id="11" data-level-name="Others">{{__('lang.others')}}</div>
              </div>
            </div>
            <div class="mb-10" id="study-location">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whereYouStudyFor')}} <span id="level_selected">{{__('lang.thisQualification')}}</span>?</label>
              <div class="mb-5">
                <input type="text" name="school_name" class="col-12 col-lg-4 date-input me-5 custom-input-placeholder" placeholder="{{__('lang.enterSchoolName')}}" autocomplete="off"/>

                <div class="col-4 mt-3 school-option-list">
                  <div id="school_list"></div>
                  <!-- <div class="school-options selected" data-school-name="SMK Tropicana">
                    <span>SMK Tropicana</span>
                  </div>
                  <div class="school-options" data-school-name="SMK Hitam">
                    <span>SMK Hitam</span>
                  </div>
                  <div class="school-options" data-school-name="SMK Putih">
                    <span>SMK Putih</span>
                  </div> -->
                </div>
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whatStream')}}</label>
              <div class="d-flex mb-5 @if($agent->isMobile()) flex-column @endif">
                <div class="col-4 col-lg-3 me-3 select-gender-div d-flex justify-content-center align-items-center stream-div active @if($agent->isMobile()) mb-3 @endif" data-stream="1">{{__('lang.sciStream')}}</div>
                <div class="col-4 col-lg-3 me-3 select-gender-div d-flex justify-content-center align-items-center stream-div @if($agent->isMobile()) mb-3 @endif" data-stream="3">{{__('lang.subSciStream')}}</div>
                <div class="col-4 col-lg-3 me-3 select-gender-div d-flex justify-content-center align-items-center stream-div" data-stream="2">{{__('lang.artStream')}}</div>
                <div class="col-4 col-lg-3 select-gender-div d-flex justify-content-center align-items-center stream-div" data-stream="0">{{__('lang.others')}}</div>
              </div>
            </div>
            <div class="mb-10" id="upload_result" style="display:none">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.resultTranscript')}}</label>
              <div id="upload_results_docs">
                <div class="dropzone-items wm-200px pb-0" style="display:none">
                    <div class="dropzone-item" style="display:none">
                      <div class="col-12 row">
                        <div class="dropzone-file col-10 col-lg-11">
                          <div class="dropzone-filename" title="some_image_file_name.jpg">
                            <span data-dz-name>some_image_file_name.jpg</span>
                            <strong>(<span data-dz-size>340kb</span>)</strong>
                          </div>
                          <div class="dropzone-error" data-dz-errormessage></div>
                        </div>
                        <div class="dropzone-toolbar col-2 col-lg-1">
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
          </div>
        </div>
        <div class="tab" style="display:none">
        @if($agent->isMobile())<div class="col-12" style="font-size:14px; font-weight:500;">{{__('lang.pathwayStep4')}}</div><hr>@endif
          <div class="mb-personality-15">
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whatIsYour')}} <span id="level_result">SPM</span> {{__('lang.result')}}?</label>
              <div class="col-12 d-flex flex-row flex-wrap">
                <div class="me-3 select-gender-div result-type-div active @if($agent->isMobile()) col-5 mb-3 @endif" data-result-type="1">{{__('lang.finalResult')}}</div>
                <div class="me-3 select-gender-div result-type-div @if($agent->isMobile()) col-7 mb-3 @endif" data-result-type="2">{{__('lang.trialResult')}}</div>
                <div class="me-3 select-gender-div result-type-div @if($agent->isMobile()) col-8 @endif" data-result-type="3">{{__('lang.partialResult')}}<br>{{__('lang.monthlySemesterExam')}}</div>
              </div>
            </div>
            <div class="mb-10" id="select_sub_div">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:16px;" @endif><span id="level_result_1">SPM</span> {{__('lang.subjectResult')}}</label>
              <div class="mb-5">
                <div class="col-12">
                  <div id="selected_subject_list"></div>
                </div>
                <button class="select-gender-div active mt-5" id="add_subject_btn">{{__('lang.addSubjects')}}</button>
              </div>
            </div>
            <div>
              <div class="mb-10" id="grading_div" style="display:none">
                <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.selectGrading')}}</label>
                <div class="col-12 d-flex flex-row flex-wrap">
                  <div class="me-3 select-gender-div select-cgpa-div @if($agent->isMobile()) col-5 mb-3 @endif" data-grading="1">{{__('lang.cgpa')}}</div>
                  <div class="me-3 select-gender-div select-cgpa-div @if($agent->isMobile()) col-5 mb-3 @endif" data-grading="2">{{__('lang.score')}}</div>
                </div>
              </div>
              <div class="mb-10" id="cgpa_div" style="display:none">
                <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whatsYourCGPA')}}</label>
                <div class="mb-5">
                  <input type="text" name="cgpa" class="col-12 col-lg-4 date-input me-5 custom-input-placeholder" placeholder="Enter result" autocomplete="off"/>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- <div class="tab" style="display:none">
          @if($agent->isMobile())<div class="col-12" style="font-size:14px; font-weight:500;">{{__('lang.pathwayStep5')}}</div><hr>@endif
          <div class="mb-personality-15">
            <div class="mb-10 @if($agent->isMobile()) flex-wrap @endif">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.preferredCategory')}}?</label>
              <div id="main_cat"></div>
            </div>
            <div class="mb-10 @if($agent->isMobile()) flex-wrap @endif" id="sub_cat_list" style="display:none">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.preferredCourse')}}?</span></label>
              <div id="sub_cat"></div>
            </div>
          </div>
        </div> -->
        <div class="tab" style="display:none">
        @if($agent->isMobile())<div class="col-12" style="font-size:14px; font-weight:500;">{{__('lang.pathwayStep5')}}</div><hr>@endif
          <div class="mb-personality-15">
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:14px;" @endif>{{__('lang.sportsRepresent')}}?</label>
              <div class="d-flex mb-5">
                <div class="col-3 col-lg-1 me-3 select-gender-div represent-country-div" data-represent-id="1">{{__('lang.yes')}}</div>
                <div class="col-3 col-lg-1 me-3 select-gender-div represent-country-div" data-represent-id="2">{{__('lang.no')}}</div>
              </div>
              <div class="col-12" id="represent_options" style="display:none">
                <div>
                  <span class="option-title" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.iRepresentMy')}}:</span>
                  <div class="col-12 d-flex">
                    <div class="me-3 select-gender-div represent-type-div mt-3" data-represent-id="1">{{__('lang.school')}}</div>
                    <div class="me-3 select-gender-div represent-type-div mt-3" data-represent-id="2">{{__('lang.district')}}</div>
                    <div class="me-3 select-gender-div represent-type-div mt-3" data-represent-id="3">{{__('lang.state')}}</div>
                    <div class="me-3 select-gender-div represent-type-div mt-3" data-represent-id="4">{{__('lang.national')}}</div>
                  </div>
                </div>
                <div class="mt-5">
                  <span class="option-title" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.inThisSport')}}:</span>
                  <input type="text" name="sport_name" class="col-12 col-lg-5 d-block mt-3 date-input me-5 custom-input-placeholder" placeholder="{{__('lang.sportsDesc')}}"/>
                </div>
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:14px;" @endif>{{__('lang.talentsRepresent')}}?</label>
              <div class="d-flex mb-5">
                <div class="col-3 col-lg-1 me-3 select-gender-div talent-div" data-talent-id="1">{{__('lang.yes')}}</div>
                <div class="col-3 col-lg-1 me-3 select-gender-div talent-div" data-talent-id="2">{{__('lang.no')}}</div>
              </div>
              <div class="col-12" id="talent-options" style="display: none">
                <div>
                  <span class="option-title" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.completeVolunteer')}}:</span>
                  <input type="text" name="talent_name" class="col-12 col-lg-5 d-block mt-3 date-input me-5 custom-input-placeholder" placeholder="{{__('lang.talentsDesc')}}"/>
                </div>
                <div class="mt-5">
                  <span class="option-title" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.competitionCommunity')}}:</span>
                  <div class="col-12 d-flex">
                    <div class="me-3 select-gender-div talent-option-div mt-3" data-talent-options="1">{{__('lang.school')}}</div>
                    <div class="me-3 select-gender-div talent-option-div mt-3" data-talent-options="2">{{__('lang.district')}}</div>
                    <div class="me-3 select-gender-div talent-option-div mt-3" data-talent-options="3">{{__('lang.state')}}</div>
                    <div class="me-3 select-gender-div talent-option-div mt-3" data-talent-options="4">{{__('lang.national')}}</div>
                  </div>
                  <!-- <select class="form-select form-select-solid mt-3 talent-options" data-hide-search="true" data-control="select2" data-placeholder="Select your highest level">
                      <option></option>
                      <option value="1">State</option>
                      <option value="2">National</option>
                  </select> -->
                </div>
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:14px;" @endif>{{__('lang.holdLeadership')}}?</label>
              <div class="d-flex mb-5">
                <div class="col-3 col-lg-1 me-3 select-gender-div club-div" data-club-id="1">{{__('lang.yes')}}</div>
                <div class="col-3 col-lg-1 me-3 select-gender-div club-div" data-club-id="2">{{__('lang.no')}}</div>
              </div>
              <div class="col-12" id="club-options" style="display: none">
                <div>
                  <span class="option-title" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.iAmThe')}}:</span>
                  <div class="col-12 d-flex @if($agent->isMobile()) flex-wrap @endif">
                    <div class="me-3 select-gender-div role-option-div mt-3 @if($agent->isMobile()) col-5 @endif" data-role-option="1">{{__('lang.president')}}</div>
                    <div class="me-3 select-gender-div role-option-div mt-3 @if($agent->isMobile()) col-6 @endif" data-role-option="2">{{__('lang.vicePresident')}}</div>
                    <div class="me-3 select-gender-div role-option-div mt-3 @if($agent->isMobile()) col-5 @endif" data-role-option="3">{{__('lang.secretary')}}</div>
                    <div class="me-3 select-gender-div role-option-div mt-3 @if($agent->isMobile()) col-5 @endif" data-role-option="4">{{__('lang.treasurer')}}</div>
                    <div class="me-3 select-gender-div role-option-div mt-3 @if($agent->isMobile()) col-8 @endif" data-role-option="5">{{__('lang.director')}}</div>
                    <div class="me-3 select-gender-div role-option-div mt-3 @if($agent->isMobile()) col-3 @endif" data-role-option="6">{{__('lang.others')}}</div>
                  </div>
                  <!-- <select class="form-select form-select-solid mt-3 role-option" data-hide-search="true" data-control="select2" data-placeholder="Select your most impactful role">
                      <option></option>
                      <option value="1">State</option>
                      <option value="2">National</option>
                  </select> -->
                </div>
                <div class="mt-5">
                  <span class="option-title" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.ofSocietyEvent')}}</span>
                  <input type="text" name="society_name" class="col-12 col-lg-5 d-block mt-3 date-input me-5 custom-input-placeholder" placeholder="{{__('lang.eventDesc')}}"/>
                </div>
                <div class="mt-5">
                  <span class="option-title" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.atThisLevel')}}:</span>
                  <div class="col-12 d-flex">
                    <div class="me-3 select-gender-div level-option-div mt-3" data-level-option="1">{{__('lang.school')}}</div>
                    <div class="me-3 select-gender-div level-option-div mt-3" data-level-option="2">{{__('lang.district')}}</div>
                    <div class="me-3 select-gender-div level-option-div mt-3" data-level-option="3">{{__('lang.state')}}</div>
                    <div class="me-3 select-gender-div level-option-div mt-3" data-level-option="4">{{__('lang.national')}}</div>
                  </div>
                  <!-- <select class="form-select form-select-solid mt-3 level-option" data-hide-search="true" data-control="select2" data-placeholder="Select your highest level">
                      <option></option>
                      <option value="1">State</option>
                      <option value="2">National</option>
                  </select> -->
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab" style="display:none">
        @if($agent->isMobile())<div class="col-12" style="font-size:14px; font-weight:500;">{{__('lang.pathwayStep6')}}</div><hr>@endif
          <div class="mb-personality-15">
            <!-- <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label">Are you the first among your siblings to attend university?</label>
              <div class="row  mb-5">
                <div class="col-1 me-5 select-gender-div sibling-attend-uni" data-attend="1">Yes</div>
                <div class="col-1 me-5 select-gender-div sibling-attend-uni" data-attend="2">No</div>
              </div>
            </div> -->
            <!-- <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label">Is any of your parents a Primary or Secondary School Teacher?</label>
              <div class="row  mb-5">
                <div class="col-1 me-5 select-gender-div parent-div" data-parent-id="1">Yes</div>
                <div class="col-1 me-5 select-gender-div parent-div" data-parent-id="2">No</div>
              </div>
            </div> -->
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.parentIncome')}}?</label>
              <div class="col-12 d-flex flex-row flex-wrap  mb-5">
                <div class="me-3 select-gender-div income-div mb-5" data-income="1">{{__('lang.below')}} MYR 5,000</div>
                <div class="me-3 select-gender-div income-div mb-5" data-income="2">{{__('lang.below')}} MYR 8,000</div>
                <div class="me-3 select-gender-div income-div mb-5" data-income="3">{{__('lang.above')}} MYR 8,000</div>
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.courseBudget')}}?</label>
              <div class="col-12 d-flex flex-row flex-wrap mb-5">
                <div class="me-3 mb-3 select-gender-div course-div @if($agent->isMobile()) col-7 mb-3 @endif" data-course="1">{{__('lang.below')}} MYR 20,000</div>
                <div class="me-3 mb-3 select-gender-div course-div @if($agent->isMobile()) col-8 mb-3 @endif" data-course="2">MYR 20,001 - MYR 40,000</div>
                <div class="me-3 mb-3 select-gender-div course-div @if($agent->isMobile()) col-8 mb-3 @endif" data-course="3">MYR 40,001 - MYR 60,000</div>
                <div class="me-3 mb-3 select-gender-div course-div @if($agent->isMobile()) col-8 mb-3 @endif" data-course="4">MYR 60,001 - MYR 80,000</div>
                <div class="me-3 mb-3 select-gender-div course-div @if($agent->isMobile()) col-8 mb-3 @endif" data-course="5">MYR 80,001 - MYR 100,000</div>
                <div class="me-3 mb-3 select-gender-div course-div @if($agent->isMobile()) col-7 @endif" data-course="6">{{__('lang.above')}} MYR 100,000</div>
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.areYouBumi')}}?</label>
              <div class="d-flex  mb-5">
                <div class="col-3 col-lg-1 me-3 select-gender-div bumi-div" data-bumi="1">{{__('lang.yes')}}</div>
                <div class="col-3 col-lg-1 me-3 select-gender-div bumi-div" data-bumi="2">{{__('lang.no')}}</div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab" style="display:none">
        @if($agent->isMobile())<div class="col-12" style="font-size:14px; font-weight:500;">{{__('lang.pathwayStep7')}}</div><hr>@endif
          <div class="mb-personality-15">
            <div class="mb-5">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.preferredPathway')}}?</span></label>
              <div class="d-flex mb-5 m-0 @if($agent->isMobile()) flex-wrap @endif">
                <div class="mt-5 d-flex me-3">
                  <div class="col-12 select-gender-div study-level" data-study-level="1">
                    <span>Foundation</span>
                  </div>
                </div>
                <div class="mt-5 d-flex me-3">
                  <div class="col-12 select-gender-div study-level" data-study-level="2">
                    <span>International Pre-University<br>(incl. A-Level, IB Diploma, CPU, SACE)</span>
                  </div>
                </div>
                <div class="mt-5 d-flex me-3">
                  <div class="col-12 select-gender-div study-level" data-study-level="3">
                    <span>Diploma</span>
                  </div>
                </div>
                <div class="mt-5 d-flex me-3">
                  <div class="col-12 select-gender-div study-level" data-study-level="4">
                    <span>Degree</span>
                  </div>
                </div>
                <div class="mt-5 d-flex me-3">
                  <div class="col-12 select-gender-div study-level" data-study-level="5">
                    <span>Master</span>
                  </div>
                </div>
                <div class="mt-5 d-flex">
                  <div class="col-12 select-gender-div study-level" data-study-level="6">
                    <span>PHD</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="mb-10" id="level_desc">
              <div class="study-level-desc" data-level-desc="1" style="display:none;">
                <span>Foundation {{__('lang.overview')}}</span>
                <ul class="mt-3">
                  <li>{{__('lang.foundation1')}}</li>
                  <li>{{__('lang.foundation2')}}</li>
                  <li>{{__('lang.foundation3')}}</li>
                </ul>
              </div>
              <div class="study-level-desc" data-level-desc="2" style="display:none;">
                <span>International Pre-U {{__('lang.overview')}}</span>
                <ul class="mt-3">
                  <li>{{__('lang.preU1')}}</li>
                  <li>{{__('lang.preU2')}}</li>
                  <li>{{__('lang.preU3')}}</li>
                </ul>
              </div>
              <div class="study-level-desc" data-level-desc="3" style="display:none;">
                <span>Diploma {{__('lang.overview')}}</span>
                <ul class="mt-3">
                  <li>{{__('lang.diploma1')}}</li>
                  <li>{{__('lang.diploma2')}}</li>
                  <li>{{__('lang.diploma3')}}</li>
                </ul>
              </div>
              <div class="study-level-desc" data-level-desc="4" style="display:none;">
                <span>Degree {{__('lang.overview')}}</span>
                <ul class="mt-3">
                  <li>{{__('lang.degree1')}}</li>
                  <li>{{__('lang.degree2')}}</li>
                  <li>{{__('lang.degree3')}}</li>
                </ul>
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.planStart')}}?</span></label>
              <div class="row mb-5 m-0 @if($agent->isMobile()) flex-wrap @endif">
                <div class="col-5 col-lg-2 mt-5 me-3 select-gender-div joining-date" data-joining-date="1">{{__('lang.plan1')}}</div>
                <div class="col-5 col-lg-2 mt-5 me-3 select-gender-div joining-date" data-joining-date="2">{{__('lang.plan2')}}</div>
                <div class="col-5 col-lg-2 mt-5 me-3 select-gender-div joining-date" data-joining-date="3">{{__('lang.plan3')}}</div>
                <div class="col-5 col-lg-2 mt-5 me-3 select-gender-div joining-date" data-joining-date="4">{{__('lang.plan4')}}</div>
                <!-- <div class="col-6 col-lg-2 mt-5 me-3 select-gender-div joining-date" data-joining-date="5">Jan 25 Onwards</div> -->
              </div>
            </div>
          </div>
        </div>
        <div class="tab" style="display:none">
        @if($agent->isMobile())<div class="col-12" style="font-size:14px; font-weight:500;">{{__('lang.pathwayStep8')}}</div><hr>@endif
          <div class="mb-personality-15">
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.planStudyAt')}}?</span></label>
              <div class="row mb-5 m-0 @if($agent->isMobile()) flex-wrap @endif">
                <div class="col-10 col-lg-3 mt-5 me-5 select-gender-div study-plan active" data-study-plan="1">{{__('lang.planAt1')}}</div>
                <div class="col-12 col-lg-3 mt-5 me-5 select-gender-div study-plan" data-study-plan="2">{{__('lang.planAt2')}}</div>
                <div class="col-10 col-lg-3 mt-5 me-5 select-gender-div study-plan" data-study-plan="3">{{__('lang.planAt3')}}</div>
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.preferredLocation')}}?</span></label>
              <div class="row mb-5 m-0" id="state_list">
                <div class="col-3 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="1">{{__('lang.johor')}}</div>
                <div class="col-3 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="2">{{__('lang.kedah')}}</div>
                <div class="col-4 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="3">{{__('lang.kelantan')}}</div>
                <div class="col-3 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="4">{{__('lang.melaka')}}</div>
                <div class="col-6 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="5">{{__('lang.negeriSembilan')}}</div>
                <div class="col-4 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="6">{{__('lang.pahang')}}</div>
                <div class="col-3 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="7">{{__('lang.perak')}}</div>
                <div class="col-3 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="8">{{__('lang.perlis')}}</div>
                <div class="col-5 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="9">{{__('lang.pulauPinang')}}</div>
                <div class="col-5 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="10">{{__('lang.selangor')}}</div>
                <div class="col-5 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="11">{{__('lang.terengganu')}}</div>
                <div class="col-5 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="12">{{__('lang.kualaLumpur')}}</div>
                <div class="col-5 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="13">{{__('lang.putrajaya')}}</div>
                <div class="col-4 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="14">{{__('lang.sarawak')}}</div>
                <div class="col-3 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="15">{{__('lang.sabah')}}</div>
                <div class="col-4 col-lg-2 mt-5 me-5 select-gender-div state-list" data-list="16">{{__('lang.labuan')}}</div>
              </div>
              <div id="country_list" style="display:none">
                <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.studyAbroad')}}?</span></label>
                <div class="row mb-5 m-0">
                  <!-- <div class="col-4 col-lg-2 mt-5 me-5 select-gender-div data-country" data-country="129">Malaysia</div> -->
                  <div class="col-4 col-lg-2 mt-5 me-5 select-gender-div data-country" data-country="192">{{__('lang.singapore')}}</div>
                  <div class="col-4 col-lg-2 mt-5 me-5 select-gender-div data-country" data-country="13">{{__('lang.australia')}}</div>
                  <div class="col-6 col-lg-2 mt-5 me-5 select-gender-div data-country" data-country="826">{{__('lang.unitedKingdom')}}</div>
                  <div class="col-5 col-lg-2 mt-5 me-5 select-gender-div data-country" data-country="840">{{__('lang.unitedStates')}}</div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab" style="display:none">
        @if($agent->isMobile())<div class="col-12" style="font-size:14px; font-weight:500;">{{__('lang.pathwayStep9')}}</div><hr>@endif
          <div class="mb-personality-15">
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.typeOfUniversity')}}?</span></label>
              <div class="row mb-5 m-0 @if($agent->isMobile()) flex-wrap @endif">
                <div class="col-9 col-lg-3 mt-5 me-5 select-gender-div uni-type" data-uni-type="1">{{__('lang.privateUniversity')}}<br>{{__('lang.eg')}} Taylor's, APU, MMU</div>
                <div class="col-8 col-lg-3 mt-5 me-5 select-gender-div uni-type" data-uni-type="2">{{__('lang.publicUniversity')}}<br>{{__('lang.eg')}} UM, UKM, USM</div>
                <div class="col-11 col-lg-3 mt-5 me-5 select-gender-div uni-type" data-uni-type="3">{{__('lang.bothUniversity')}}</div>
              </div>
            </div>
            <div class="mb-10">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.appliedUniversity')}}?</span></label>
              <div class="row mb-5 m-0">
                <div class="col-3 col-lg-2 mt-5 me-5 select-gender-div reg-uni" data-reg-uni="1">{{__('lang.yes')}}</div>
                <div class="col-3 col-lg-2 mt-5 me-5 select-gender-div reg-uni" data-reg-uni="2">{{__('lang.no')}}</div>
              </div>
            </div>
            <div class="mb-10" id="reg_uni_name" style="display:none">
              <label for="exampleFormControlInput1" class="form-label" @if($agent->isMobile()) style="font-size:15px;" @endif>{{__('lang.whichUniversity')}}?</span></label>
              <div class="mb-5">
                <input type="text" name="uni_reg_name" class="col-12 col-lg-5 date-input me-5 custom-input-placeholder" placeholder="{{__('lang.registeredUniDesc')}}"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="tab" style="display:none">
          <div class="position-absolute" style="top: 50%;left: 50%;transform: translate(-50%, -50%);">
            <div class="text-center">
              <span class="pb-10 d-block loading-text">{{__('lang.analyseProfile')}}</span>
              <img src="/images/assets/loding-gif.gif" style="width: 60px;">
            </div>
          </div>
        </div>
  </div>
  <div class="personality-footer col-12">
    <div class="float-end flex-wrap">
      <a id="btn_cancel" class="btn-cancel me-5" href="/">{{__('lang.cancel')}}</a>
      <button id="btn_back" class="btn-cancel me-5" onclick="nextPrev(-1)" style="display:none">{{__('lang.back')}}</button>
      <button class="home-btn-get-started-2" id="btn_next">{{__('lang.next')}}</button>
    </div>
  </div>
    <form id="personality_form"></form>
</div>
@endsection

@section('modal')
<div class="modal fade" tabindex="-1" id="add_subject_modal">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content" style="padding:24px;">
      <div class="modal-header p-0">
        <h3 class="modal-title">{{__('lang.addSubjects')}}</h3>
      </div>

      <div class="modal-body p-0">
        <div id="pre_selected_subject"></div>
        <div id="subject_list"></div>
        <!-- <div class="mt-10">
          <span class="subject-heading">Science & Mathematics</span>
          <div class="col-12 d-flex flex-row flex-wrap">
            <div class="mt-3 me-5 select-gender-div">Additional Mathematics</div>
            <div class="mt-3 me-5 select-gender-div">Information & Communication Technology</div>
            <div class="mt-3 me-5 select-gender-div">Physics</div>
            <div class="mt-3 me-5 select-gender-div">Chemistry</div>
            <div class="mt-3 me-5 select-gender-div">Biology</div>
          </div>
        </div> -->
        <div class="modal-footer pb-0 pe-0" style="border:0px;">
        <button type="button" class="btn-cancel" data-bs-dismiss="modal">{{__('lang.cancel')}}</button>
        <button type="button" class="btn modal-confirm-btn" id="confirm_subject">{{__('lang.confirm')}}</button>
      </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
<script>
  var currentTab = 0;
  showTab(currentTab, true);

  function showTab(n, status) {
    let isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
    var x = document.getElementsByClassName("tab");
    var t = document.getElementsByClassName("progress-step");
    var d = document.getElementsByClassName("progress-dot");

    // if(n == 3) {
    //   x[4].style.display = "block";
    // } else{
    //   x[n].style.display = "block";
    // }
    if(!isMobile) {
      if(status) {
        t[n].classList.add("active");
        d[n].classList.add("active");
      } else{
        t[n + 1].classList.remove("active");
        d[n + 1].classList.remove("active");
      }
    }
    
    x[n].style.display = "block";
    
    // if(n < 2){
    //   t[1].classList.remove("active");
    //   t[2].classList.remove("active");
    //   t[3].classList.remove("active");
    //   t[4].classList.remove("active");
    // } else if(n == 2 || n == 3){
    //   t[1].classList.add("active");
    //   t[2].classList.remove("active");
    //   t[3].classList.remove("active");
    //   t[4].classList.remove("active");
    // } else if(n == 4 || n == 5){
    //   t[2].classList.add("active");
    //   t[3].classList.remove("active");
    //   t[4].classList.remove("active");
    // } else if(n == 6 || n == 7){
    //   t[3].classList.add("active");
    //   t[4].classList.remove("active");
    // } else if(n == 8 || n == 9){
    //   t[4].classList.add("active");
    // }
    // }

  }

  function nextPrev(n) {
    var x = $('.tab');
    var id = $('.academic-level.active').data('level-id');
    var name_1 = $('.academic-div.active').data('academic');
    var test = (n == 1) ? true : false;

    x[currentTab].style.display = "none";
    currentTab = currentTab + n;

    if(currentTab == 3 && (name_1 == 3 || id == 11 || id == 12)) {
      if(n == '-1') {
        currentTab = currentTab - 1;
      } else {
        currentTab = currentTab + 1;
      }
    } else {
      currentTab = currentTab;
    }

    // if (currentTab >= x.length) {
    //   document.getElementById("regForm").submit();
    //   return false;
    // }

    if(currentTab >= 1) {
      $('#btn_back').show();
      $('#btn_cancel').hide();
    } else {
      $('#btn_back').hide();
      $('#btn_cancel').show();
    }

    if( currentTab == 8) {
      $('#btn_next').html('{{__("lang.getResults")}}');
    } else{
      $('#btn_next').html('Next');
    }

    if( currentTab == 9 ){
      $('.personality-footer').hide();
      $('.progressbar-wrapper').hide();
      $('#side_bar_steps').hide();

      count = 0;
      wordsArray = ["{{__('lang.analyseProfile')}}", "{{__('lang.buildingCharacter')}}", "{{__('lang.findBestCourse')}}"];
      setInterval(function () {
        count++;
        if(count <= 2){
          $(".loading-text").fadeOut(400, function () {
            $(this).text(wordsArray[count % wordsArray.length]).fadeIn(400);
          });
        } else {
          window.location.href = "/characters";
        }
      }, 2000);
    }

      showTab(currentTab, test);
  }

  Dropzone.autoDiscover = false;
  const upload_result = "#upload_results_docs";
  const dropzone_1 = document.querySelector(upload_result);

  var previewNode = dropzone_1.querySelector(".dropzone-item");
  previewNode.upload_result = "";
  var previewTemplate = previewNode.parentNode.innerHTML;
  previewNode.parentNode.removeChild(previewNode);

  var transcript_input = new Dropzone(upload_result, {
    url: "/submitInterest",
    parallelUploads: 1,
    maxFilesize: 5,
    maxFiles: 1,
    previewTemplate: previewTemplate,
    previewsContainer: upload_result + " .dropzone-items",
    clickable: upload_result + " .dropzone-select"
  });

  transcript_input.on("addedfile", function (file) {
    if (transcript_input.files[1]!=null){
      transcript_input.removeFile(transcript_input.files[0]);
    }
    // Hookup the start button
    const dropzoneItems = dropzone_1.querySelectorAll('.dropzone-item');
    const dropzoneItems1 = dropzone_1.querySelectorAll('.dropzone-items');
    dropzoneItems.forEach(dropzoneItem => {
      dropzoneItem.style.display = '';
    });

    dropzoneItems1.forEach(dropzoneItem1 => {
      dropzoneItem1.style.display = '';
    });
  });

  // Update the total progress bar
  transcript_input.on("totaluploadprogress", function (progress) {
    const progressBars = dropzone_1.querySelectorAll('.progress-bar');
    progressBars.forEach(progressBar => {
      progressBar.style.width = progress + "%";
    });
  });

  transcript_input.on("sending", function (file) {
    // Show the total progress bar when upload starts
    const progressBars = dropzone_1.querySelectorAll('.progress-bar');
    progressBars.forEach(progressBar => {
      progressBar.style.opacity = "1";
    });
  });

  // Hide the total progress bar when nothing"s uploading anymore
  transcript_input.on("complete", function (progress) {
    const progressBars = dropzone_1.querySelectorAll('.dz-complete');

    setTimeout(function () {
      progressBars.forEach(progressBar => {
        progressBar.querySelector('.progress-bar').style.opacity = "0";
        progressBar.querySelector('.progress').style.opacity = "0";
      });
    }, 300);
  });

  $( document ).ready(function() {
    getMainCat();

    //for gender toggle
    $(document).on('click', '[data-gender]', function(){
      var id = $(this).data('gender');
      $('.select-gender-div').each(function(i, item){
        if($(this).data('gender') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //personality for describe
    $(document).on('click', '[data-personality_1]', function(){
      var id = $(this).data('personality_1');
      var total = $('.select-describe.active').length;
      $('.select-gender-div').each(function(i, item){
        if($(this).data('personality_1') == id){
          if($(this).hasClass('active')){
            $(this).removeClass('active');
          } else{
            if(total <= 9){
              $(this).addClass('active');
            }
          }
        }
      });
      total = $('.select-describe.active').length;
      $('#ttl_describe').html(total);
    });

    //personality for interest
    $(document).on('click', '[data-personality_2]', function(){
      var id = $(this).data('personality_2');
      var total = $('.select-interest.active').length;
      $('.select-gender-div').each(function(i, item){
        if($(this).data('personality_2') == id){
          if($(this).hasClass('active')){
            $(this).removeClass('active');
          } else{
            if(total <= 9){
              $(this).addClass('active');
            }
          }
        }
      });
      total = $('.select-interest.active').length;
      $('#ttl_interest').html(total);
    });

    //malaysia question
    $(document).on('click', '[data-malaysian]', function(){
      var id = $(this).data('malaysian');
      $('.malaysian-div').each(function(i, item){
        if($(this).data('malaysian') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //Academic level
    $(document).on('click', '[data-academic]', function(){
      var id = $(this).data('academic');
      $('input[name=school_name]').val('');
      $('.academic-div').each(function(i, item){
        if($(this).data('academic') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })

        $('.academic-level').each(function(i, item){
          if($(this).hasClass('active')){
            $(this).removeClass('active');
          }
        });

      if(id == 1){
        $('#qualification_1').removeClass('d-none');
        $('#qualification_1').addClass('d-flex');
        $('#qualification_2').addClass('d-none');
        $('#qualification_2').removeClass('d-flex');
        $('#qualification-option').show();
        $('#upload_result').hide();
      }else if(id == 2){
        $('#qualification_1').addClass('d-none');
        $('#qualification_1').removeClass('d-flex');
        $('#qualification_2').removeClass('d-none');
        $('#qualification_2').addClass('d-flex');
        $('#qualification-option').show();
        $('#upload_result').hide();
      } else if(id == 3){
        $('#qualification-option').hide();
        $('#upload_result').show();
      }

    });

    //stream question
    $(document).on('click', '[data-stream]', function(){
      var id = $(this).data('stream');
      $('.stream-div').each(function(i, item){
        if($(this).data('stream') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //cgpa level
    $(document).on('click', '[data-grading]', function(){
      var id = $(this).data('grading');
      $('.select-cgpa-div').each(function(i, item){
        if($(this).data('grading') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })

      if(id == 1){
        $('#cgpa_div').show();
        $('input[name=cgpa]').val('');
      }else if(id == 2){
        $('#cgpa_div').show();
        $('input[name=cgpa]').val('');
      }
    });

    //qualification
    $(document).on('click', '[data-level-id]', function(){
      var id = $(this).data('level-id');
      var name = $(this).data('level-name');
      $('input[name=school_name]').val('');
      console.log(id)
      console.log(name)
      $('.academic-level').each(function(i, item){
        if($(this).data('level-id') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }

        if(id == 5 || id == 6 || id == 8 || id == 9 || id == 10 || id == 13 || id == 14 || id == 15 || id == 16 || id == 17) {
          $('#select_sub_div').hide();
          $('#grading_div').show();
          // $('#cgpa_div').show();
        } else{
          $('#select_sub_div').show();
          $('#grading_div').hide();
          $('#cgpa_div').hide();
        }

        if(name != 'Others') {
          $('#level_selected').html(name);
          $('#level_result').html(name);
          $('#level_result_1').html(name);
          $('#upload_result').hide();
        } else{
          $('#level_selected').html('this qualification');
          $('#upload_result').show();
        }
      })
    });

    //result
    $(document).on('click', '[data-result-type]', function(){
      var id = $(this).data('result-type');

      $('.result-type-div').each(function(i, item){
        if($(this).data('result-type') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      });
    });

    $(document).on('click', '#add_subject_btn', function(){
      $('#add_subject_modal').modal('show');
      getClasses();
    })

    //represent sport
    $(document).on('click', '[data-represent-id]', function(){
      var id = $(this).data('represent-id');

      $('.represent-type-div').each(function(i, item){
        if($(this).data('represent-id') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      });
    });

    //talent level
    $(document).on('click', '[data-talent-options]', function(){
      var id = $(this).data('talent-options');

      $('.talent-option-div').each(function(i, item){
        if($(this).data('talent-options') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      });
    });

    //leadership role
    $(document).on('click', '[data-role-option]', function(){
      var id = $(this).data('role-option');

      $('.role-option-div').each(function(i, item){
        if($(this).data('role-option') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      });
    });

    //leadership level option
    $(document).on('click', '[data-level-option]', function(){
      var id = $(this).data('level-option');

      $('.level-option-div').each(function(i, item){
        if($(this).data('level-option') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      });
    });

    var subject_list = [];
    var grade = [];

    function getClasses()
    {
      var id = $('.academic-level.active').data('level-id');

      $.ajax({
        url: "/getSubject",
        method: 'post',
        data: {
          level: id,
        },
        success: function(d) {
          if (d.status) {
            var items = d.list;
            var pre_selected = d.preSelected
            var content = '';
            var content_pre = '';
            grade = d.grade;

            if(pre_selected.length > 0){
              content_pre += '<div class="result-pre-div mt-5 mb-5">';
              content_pre += '<span class="d-block">{{__("lang.preselectedSubjects")}}</span>';
              content_pre += '<div class="d-flex mt-1">';
              pre_selected.forEach(function(item, index) {
                content_pre += '<span class="subject-pre-selected me-5">'+item.name+'</span>';

                var data = {id: item.id, name: item.name};
                var result = subject_list.filter(function (item) {
                  return (item.name == data.name && item.id == data.id);
                });
                if(result.length == 0){
                  subject_list.push(data);
                }
              });
              content += '</div>';
              content += '</div>';
              $('#pre_selected_subject').empty().append(content_pre);
            } else{
              $('#pre_selected_subject').empty();
            }

            if(items.length > 0){
              items.forEach(function(item, index) {
                content += '<span class="subject-heading">'+item.name+'</span>';
                content += '<div class="mt-3">';
                content += '<div class="col-12 d-flex flex-row flex-wrap">';
                item.data.forEach(function(list, i) {
                  var found = subject_list.some(el => el.id === list.id);
                  if(found) {
                    content += '<div class="me-5 mb-5 select-gender-div select_subject active" data-subject-name="'+list.name+'" data-subject-id="'+list.id+'">'+list.name+'</div>';
                  } else{
                    content += '<div class="me-5 mb-5 select-gender-div select_subject" data-subject-name="'+list.name+'" data-subject-id="'+list.id+'">'+list.name+'</div>';
                  }
                });
                content += '</div>'
                content += '</div>';
              });
              $('#subject_list').empty().append(content);
            } else{
              $('#subject_list').empty();
            }
          } else{
            $('#subject_list').empty();
            $('#pre_selected_subject').empty();
          }
        }
      });
    }

    $(document).on('click', '[data-subject-id]', function(){
      var id = $(this).data('subject-id');

      $('.select_subject').each(function(i, item){
        if($(this).data('subject-id') == id){
          if($(this).hasClass('active')){
            $(this).removeClass('active');
          } else{
            $(this).addClass('active');
          }

        }
      });

    });

    // $('#confirm_subject').on('click', function(){
    //   $('.select_subject').each(function(i, item){
    //     if($(this).hasClass('active')){
    //       console.log($(this))
    //       var id = $(this).data('subject-id');
    //       var data = {id: id, name: $(this).data('subject-name')};
    //       var result = subject_list.filter(function (sl) {
    //         return (sl.name == data.name && sl.id == data.id);
    //       });

    //       if(result.length == 0){
    //         subject_list.push(data);
    //       }
    //       console.log('runnn111')
    //       console.log(result)
    //       console.log(result.length)
    //       console.log('runnn')
    //       console.log(subject_list)
    //     } else{
    //       console.log('runn 222')
    //       var data = {id: id, name: $(this).data('subject-name')};
    //       subject_list.slice(data, 1);
    //     }
    //   });
    //   selectedClass();
    //   $('#add_subject_modal').modal('hide');
    // });

    $('#confirm_subject').on('click', function(){
      $('.select_subject').each(function(i, item){
        var id = $(this).data('subject-id');
        var data = {id: id, name: $(this).data('subject-name')};
        var found = subject_list.some(el => el.id === id);

        if(found) {
          if($(this).hasClass('active')){
          } else{
            subject_list.splice(subject_list.findIndex(el => el.id === id), 1);
          }
        } else{
          if($(this).hasClass('active')){
            subject_list.push(data);
          };
        }
      });

      selectedClass();
      $('#add_subject_modal').modal('hide');
    });

    function selectedClass()
    {
      var content = '';
      if(subject_list.length > 0){
        subject_list.forEach(function(item, index) {
          content += '<div class="col-12 col-lg-6 row mt-5 align-items-center">';
          content += '<div class="col-8">';
          content += '<span @if($agent->isMobile()) style="font-size:14px;" @endif>'+item.name+'</span>';
          content += '</div>';
          content += '<div class="col-4">';
          content += '<select class="form-select form-select-solid grades" id="drop_'+item.id+'" name="grades[]" data-control="select2" data-placeholder="{{__("lang.grade")}}" data-hide-search="true">';
          content += '<option></option>';

          grade.forEach(function(g, i) {
            content += '<option value="'+g.sg_id+'">'+g.sg_grade+'</option>';
          });
          
          content += '</select>';
          content += '</div>';
          content += '</div>';
          
        });
        $('#selected_subject_list').empty().append(content);

        subject_list.forEach(function(item, index) {
          $('#drop_'+item.id+'').select2({
            minimumResultsForSearch: Infinity,
          });
        })
        // for (let index = 0; index <= subject_list.length; index++) {
        //   $('#drop_'+index+'').select2({
        //     minimumResultsForSearch: Infinity,
        //   });
          
        // }
      } else{
        $('#selected_subject_list').empty();
      }
    }

    //district
    $(document).on('click', '.represent-country-div', function(){
      var id = $(this).data('represent-id');
      if(id == 1) {
        $('#represent_options').show();
      } else {
        $('#represent_options').hide();
      }

      $('.represent-country-div').each(function(i, item){
        if($(this).data('represent-id') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //talent
    $(document).on('click', '.talent-div', function(){
      var id = $(this).data('talent-id');
      if(id == 1) {
        $('#talent-options').show();
      } else {
        $('#talent-options').hide();
      }

      $('.talent-div').each(function(i, item){
        if($(this).data('talent-id') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //club
    $(document).on('click', '.club-div', function(){
      var id = $(this).data('club-id');
      if(id == 1) {
        $('#club-options').show();
      } else {
        $('#club-options').hide();
      }

      $('.club-div').each(function(i, item){
        if($(this).data('club-id') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //attend uni
    $(document).on('click', '.sibling-attend-uni', function(){
      var id = $(this).data('attend');

      $('.sibling-attend-uni').each(function(i, item){
        if($(this).data('attend') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //teacher
    $(document).on('click', '.parent-div', function(){
      var id = $(this).data('parent-id');

      $('.parent-div').each(function(i, item){
        if($(this).data('parent-id') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //income
    $(document).on('click', '.income-div', function(){
      var id = $(this).data('income');

      $('.income-div').each(function(i, item){
        if($(this).data('income') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //course
    $(document).on('click', '.course-div', function(){
      var id = $(this).data('course');

      $('.course-div').each(function(i, item){
        if($(this).data('course') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //bumi
    $(document).on('click', '.bumi-div', function(){
      var id = $(this).data('bumi');

      $('.bumi-div').each(function(i, item){
        if($(this).data('bumi') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //course main cat
    $(document).on('click', '.course-cat', function(){
      var id = $(this).data('course-cat');
      if($('.course-cat.active').data('course-cat') != id){
        getSubCat(id);
      }

      $('.course-cat').each(function(i, item){
        if($(this).data('course-cat') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //course sub cat
    $(document).on('click', '.course-sub-cat', function(){
      var id = $(this).data('course-cat-sub');

      $('.course-sub-cat').each(function(i, item){
        if($(this).data('course-cat-sub') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //get main cat
    function getMainCat()
    {
      $.ajax({
        url: "/getMainCat",
        method: 'post',
        success: function(d) {
          if (d.status) {
            var items = d.list;
            var content = '';

            if(items.length > 0){
              content += '<div class="row mb-5 m-0">';
              items.forEach(function(list, i) {
                content += '<div class="col-5 col-lg-2 mt-5 me-3 select-gender-div course-cat" data-course-cat="'+list.c_id+'">'+list.c_name+'</div>';
              });
              content += '</div>';
              $('#main_cat').empty().append(content);
            }
          }
        }
      });
    }

    //get sub cat
    function getSubCat(id)
    {
      $.ajax({
        url: "/getSubCat",
        method: 'post',
        data: {id: id},
        success: function(d) {
          if (d.status) {
            var items = d.list;
            var content = '';

            if(items.length > 0){
              $('#sub_cat_list').show();

              content += '<div class="row mb-5 m-0">';
              items.forEach(function(list, i) {
                content += '<div class="col-5 col-lg-2 mt-5 me-3 select-gender-div course-sub-cat" data-course-cat-sub="'+list.sc_id+'">'+list.sc_name+'</div>';
              });
              content += '</div>';
              $('#sub_cat').empty().append(content);
            }
          }
        }
      });
    }

    //study plan
    $(document).on('click', '.study-plan', function(){
      var id = $(this).data('study-plan');

      $('.study-plan').each(function(i, item){
        if($(this).data('study-plan') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })

      if(id == 1){
        $('#country_list').hide();
        $('#state_list').show();
      } else if(id == 2){
        $('#country_list').show();
        $('#country_list .form-label').show();
        $('#state_list').show();
      } else if(id == 3){
        $('#country_list').show();
        $('#country_list .form-label').hide();
        $('#state_list').hide();
      }
    });

    //study level
    $(document).on('click', '.study-level', function(){
      var id = $(this).data('study-level');

      $('.study-level-desc').each(function(i, item){

        if($(this).data('level-desc') == id) {
            $(this).css('display','block');
          } else{
            $(this).css('display','none');
          }
      });

      $('.study-level').each(function(i, item){
        if($(this).data('study-level') == id){
          $(this).addClass('active');
          $('#level_desc').show();
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //study join date
    $(document).on('click', '.joining-date', function(){
      var id = $(this).data('joining-date');

      $('.joining-date').each(function(i, item){
        if($(this).data('joining-date') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //type of uni
    $(document).on('click', '.uni-type', function(){
      var id = $(this).data('uni-type');

      $('.uni-type').each(function(i, item){
        if($(this).data('uni-type') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });

    //registered uni
    $(document).on('click', '.reg-uni', function(){
      var id = $(this).data('reg-uni');

      $('.reg-uni').each(function(i, item){
        if($(this).data('reg-uni') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })

      if(id == 1){
        $('#reg_uni_name').show();
      } else{
        $('#reg_uni_name').hide();
      }
    });

    //select state
    $(document).on('click', '.state-list', function(){
      var id = $(this).data('list');

      $('.state-list').each(function(i, item){
        if($(this).data('list') == id){
          if($(this).hasClass('active')){
            $(this).removeClass('active');
          } else{
            $(this).addClass('active');
          }
        }
      })
    });

    //select study country
    $(document).on('click', '.data-country', function(){
      var id = $(this).data('country');

      $('.data-country').each(function(i, item){
        if($(this).data('country') == id){
          $(this).addClass('active');
        } else{
          $(this).removeClass('active');
        }
      })
    });
    
    $(document).on('click', '#btn_next', function(){

      if(currentTab == 0) {
        createUser();
        // nextPrev(1);
      } else if(currentTab == 1) {
        addPersonality();
        // nextPrev(1);
      } else if(currentTab == 2) {
        subject_list = [];
        selectedClass();
        addAcademic();
        addStream();
        // nextPrev(1);
      } else if(currentTab == 3) {
        addResult();
        // nextPrev(1);
      } 
      // else if(currentTab == 4) {
      //   // addAchievement();
      //   addPreferredCourse();
      //   // nextPrev(1);
      // } 
      else if(currentTab == 4) {
        // addFamily();
        addAchievement();
        // nextPrev(1);
      } else if(currentTab == 5) {
        // addPreferredCourse();
        addFamily();
        // nextPrev(1);
      } else if(currentTab == 6) {
        addPathway();
        // nextPrev(1);
      } else if(currentTab == 7) {
        addStudyLocation();
        // nextPrev(1);
      } else if(currentTab == 8) {
        addUniversityType();
        // nextPrev(1);
      }

    })

    //autofill school 
    $('input[name=school_name]').keyup(function() { 
      var value = $(this).val();
      var level = $('.academic-level.active').data('level-id');
      var academic_level = level;
      var no_content = '';

      // if(level == undefined) {
      //   $('.school-option-list').show();
      //   no_content += '<div class="school-options">';
      //   no_content += '<span>Please select qualification</span>';
      //   no_content += '</div>';
      //   $('#school_list').empty().append(no_content);
      // } else {
        if(level == 11 || level == 12) {
          academic_level = 12;
        }
      
        if(value.length >= 3) {
          $('.school-option-list').show();

          $.ajax({
            url: "/getSchool",
            method: 'post',
            data: {
              search: value,
              level: academic_level,
            },
            success: function(d) {
              var items = d.list;
              var content = '';
              if(d.status) {
                items.forEach(function(item, index) {
                  content += '<div class="school-options" data-school-name="'+item.name+'">';
                  content += '<span>'+item.name+'</span>';
                  content += '</div>';
                });
                $('#school_list').empty().append(content);
              } else {
                content += '<div class="school-options" data-school-name="'+value+'">';
                content += '<span class="d-block">'+value+'</span>';
                content += '<span class="d-block school-not-found mt-2">This school does not exist in our database, but you can still continue</span>'
                content += '</div>';
                $('#school_list').empty().append(content);
              }
            }
          });

        } else{
          $('.school-option-list').hide();
        }
      // }

    });
    
    $(document).on('click', '[data-school-name]', function(){
      var name = $(this).data('school-name');
      $('input[name=school_name]').val(name);
      $('.school-option-list').hide();
    });

    function createUser()
    {
      var gender = $('.select-gender-div.active').data('gender');
      var contact_no = $("input[name=phone_number]").val();
      var dob_day = $("input[name=dob_day]").val();
      var dob_month = $("input[name=dob_month]").val();
      var dob_year = $("input[name=dob_year]").val();
      var malaysian = $('.malaysian-div.active').data('malaysian');

      if(gender == undefined) {
        $('.toast-error').toast('show');
        $('.err-msg').html('Please select gender');

        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000);
      } else if(contact_no == null || contact_no == '') {
        $('.toast-error').toast('show');
        $('.err-msg').html('Please enter phone number');

        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000);
      } else if(dob_day == null || dob_day == '') {
        $('.toast-error').toast('show');
        $('.err-msg').html('Please enter birthday day');

        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000);
      } else if(dob_month == null || dob_month == '') {
        $('.toast-error').toast('show');
        $('.err-msg').html('Please enter birthday month');

        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000);
      } else if(dob_year == null || dob_year == '') {
        $('.toast-error').toast('show');
        $('.err-msg').html('Please enter birthday year');

        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000);
      } else if(malaysian == undefined) {
        $('.toast-error').toast('show');
        $('.err-msg').html('Please select nationality');

        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000);
      } else{
        $.ajax({
          url: "/createNewUser",
          method: 'post',
          data: { 
            gender: gender,
            contact_no: contact_no,
            dob_day: dob_day,
            dob_month: dob_month,
            dob_year: dob_year,
            is_malaysian: malaysian,
          },
          success: function(d) {
            if (d.status) {
              nextPrev(1);
              $('.toast-error').toast('hide');

            }else{
              $('.toast-error').toast('show');
              $('.err-msg').html(d.msg);

              setTimeout(function(){
                $('.toast-error').toast('hide');
              },3000)
            }
          },
          error: function() {
            console.error('500 Server Error');
          }
        });
      }
    }

    function addPersonality()
    {
      var desc = $('.select-describe.active').data('personality_1');
      var interest = $('.select-interest.active').data('personality_2');

      var desc_length = $('.select-describe.active').length;
      var interest_length = $('.select-interest.active').length;

      var error = false;
      var error_msg = '';

      var desc_list = [];
      var interest_list = [];

      if(desc_length < 5) {
        error_msg = 'Please select atleast 5 describe options';

        error = true;
      }

      if(interest_length < 5) {
        error_msg = 'Please select atleast 5 interest options';

        error = true;
      }

      if(desc == undefined) {
        error_msg = 'Please select atleast 5 describe options';

        error = true;

      } else{
        $('.select-describe').each(function(i, item){
          if($(this).hasClass('active')) {
            desc_list.push($(this).data('personality_1'));
          }
        });
      }

      if(interest == undefined) {
        error_msg = 'Please select atleast 5 interest options';

        error = true;
      } else{
        $('.select-interest').each(function(i, item){
          if($(this).hasClass('active')) {
            interest_list.push($(this).data('personality_2'));
          }
        });
      }

      if(!error) {
        $.ajax({
          url: "/addPersonality",
          method: 'post',
          data: { 
            desc: desc_list,
            interest: interest_list,
          },
          success: function(d) {
            if (d.status) {
              nextPrev(1);
              $('.toast-error').toast('hide');

            }else{
              $('.toast-error').toast('show');
              $('.err-msg').html(d.msg);

              setTimeout(function(){
                $('.toast-error').toast('hide');
              },3000)
            }
          },
          error: function() {
            console.error('500 Server Error');
          }
        });
      } else{
        $('.toast-error').toast('show');
        $('.err-msg').html(error_msg);

        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000);
      }
    }

    function addAcademic()
    {
      var academic = $('.academic-level.active').data('level-id');
      var level = $('.academic-div.active').data('academic');
      var school_name = $("input[name=school_name]").val();
      var transcript = transcript_input.files[0];

      var academic_level = academic;
      var error = false;
      var msg = '';

      if(level == undefined) {
        msg = 'Please select atleast 5 describe options';

        error = true;
      }

      if(school_name == null || school_name == '') {
        error = true;
        msg = 'Please enter school name';
      }

      if(level == 3) {
        if(transcript == undefined) {
          error = true;
          msg = 'Please enter transcript';
        }
      }

      if(academic == 11 || academic == 12) {
        if(transcript == undefined) {
          error = true;
          msg = 'Please enter transcript';
        }
        academic_level = 12;
      }

      if(error) {
        $('.toast-error').toast('show');
        $('.err-msg').html(msg);

        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000);
      } else{
        let f = new FormData($('#personality_form')[0]);
        f.append('transcript', transcript);
        f.append('school', school_name);
        f.append('qualification', academic_level);
        f.append('level', level);

        $.ajax({
          url: "/addAcademic",
          method: 'post',
          enctype: 'multipart/form-data',
          contentType: false,
          processData: false,
          data: f,
          success: function(d) {
            if (d.status) {
              nextPrev(1);
              $('.toast-error').toast('hide');

            }else{
              $('.toast-error').toast('show');
              $('.err-msg').html(d.msg);

              setTimeout(function(){
                $('.toast-error').toast('hide');
              },3000)
            }
          },
          error: function() {
            console.error('500 Server Error');
          }
        });
      }
    }

    function addStream()
    {
      var stream = $('.stream-div.active').data('stream');
      var academic = $('.academic-level.active').data('level-id');
      var level = $('.academic-div.active').data('academic');
      var school_name = $("input[name=school_name]").val();
      var transcript = transcript_input.files[0];

      var error = false;
      var msg = '';

      if(level == undefined) {
        msg = 'Please select educational level';

        error = true;
      }

      if(school_name == null || school_name == '') {
        error = true;
        msg = 'Please enter school name';
      }

      if(stream == undefined) {
        msg = 'Please select study stream';

        error = true;
      }

      if(level == 3) {
        if(transcript == undefined) {
          error = true;
          msg = 'Please enter transcript';
        }
      }

      if(academic == 11 || academic == 12) {
        if(transcript == undefined) {
          error = true;
          msg = 'Please enter transcript';
        }
        academic_level = 12;
      }

      if(error) {
        $('.toast-error').toast('show');
        $('.err-msg').html(msg);

        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000);
      } else{
        let f = new FormData($('#personality_form')[0]);
        f.append('stream', stream);

        $.ajax({
          url: "/addStream",
          method: 'post',
          enctype: 'multipart/form-data',
          contentType: false,
          processData: false,
          data: f,
          success: function(d) {
            if (d.status) {
              // nextPrev(1);
              $('.toast-error').toast('hide');

            }else{
              $('.toast-error').toast('show');
              $('.err-msg').html(d.msg);

              setTimeout(function(){
                $('.toast-error').toast('hide');
              },3000)
            }
          },
          error: function() {
            console.error('500 Server Error');
          }
        });
      }
    }

    function addResult()
    {
      var type = $('.result-type-div.active').data('result-type');
      var cgpa = $('input[name="cgpa"]').val();
      var level = $('.academic-level.active').data('level-id');
      var grade = [];
      var subject = [];
      var error = false;
      var msg = '';

      if(type == null){
        error = true;
        msg = 'Please select result type'
      }

      if(level == 5 || level == 6 || id == 8 || id == 9 || id == 10 || id == 13 || id == 14 || id == 15 || id == 16 || id == 17) {

        if(cgpa == null || cgpa == '') {
          error = true;
          msg = 'Please enter cgpa';
        }
      } else{
        $('select[name="grades[]"]').each(function (item, index){
          var val = $(this).val();
          if(val !== ' ' || val !== null) {
            grade.push($(this).val());
          }

        });

        grade.forEach((i,g) => {
          if(i == null || i == '') {
            error = true;
            msg = 'Please enter grade for all subject selected';
          }
        });

        subject_list.forEach((i,sl) => {
          subject.push(i.id)
        });
      }

      if(error) {
        $('.toast-error').toast('show');
        $('.err-msg').html(msg);

        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000);
      } else{
        $.ajax({
          url: "/addResult",
          method: 'post',
          data: {
            subject: subject,
            grades: grade,
            type: type,
            cgpa: cgpa
          },
          success: function(d) {
            if (d.status) {
              nextPrev(1);
              $('.toast-error').toast('hide');

            }else{
              $('.toast-error').toast('show');
              $('.err-msg').html(d.msg);

              setTimeout(function(){
                $('.toast-error').toast('hide');
              },3000)
            }
          },
          error: function() {
            console.error('500 Server Error');
          }
        });
      }
    }

    function addAchievement()
    {
      var district = $('.represent-country-div.active').data('represent-id');
      var represent_level = $('.represent-type-div.active').data('represent-id');
      var sport_name = $('input[name="sport_name"]').val();

      var talent = $('.talent-div.active').data('talent-id');
      var talent_option = $('.talent-option-div.active').data('talent-options');
      var talent_name = $('input[name="talent_name"]').val();

      var leadership = $('.club-div.active').data('club-id');
      var role_option = $('.role-option-div.active').data('role-option');
      var level_option = $('.level-option-div.active').data('level-option');
      var society_name = $('input[name="society_name"]').val();

      var error = false;
      var msg = '';

      if(district == undefined || talent == undefined || leadership == undefined) {
        error = true;
        msg = 'Please select all the options to continue';
      } else{
        if(district == 1) {
          if(represent_level == undefined) {
            error = true;
            msg = 'Please select highest represent level';
          } else if(sport_name == null || sport_name == '') {
            error = true;
            msg = 'Please enter the sport that you represent';
          }
        }

        if(talent == 1) {
          if(talent_option == undefined) {
            error = true;
            msg = 'Please select your highest competition / community level';
          } else if(talent_name == null || talent_name == '') {
            error = true;
            msg = 'Please enter which community service your have volunteered in';
          }
        }

        if(leadership == 1) {
          if(role_option == undefined) {
            error = true;
            msg = 'Please select your most impactful role';
          } else if(level_option == undefined) {
            error = true;
            msg = 'Please select your highest level';
          } else if(society_name == null || society_name == '') {
            error = true;
            msg = 'Please enter the name of the club, society, event, volunteer group, or community service project';
          }
        }
      }

      if(error) {
        $('.toast-error').toast('show');
        $('.err-msg').html(msg);

        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000);
      } else{
        $.ajax({
          url: "/addAchievement",
          method: 'post',
          data: {
            is_district: district,
            level_sport: represent_level,
            sport_represent: sport_name,
            is_talent: talent,
            level_talent: talent_option,
            talent_represent: talent_name,
            is_leadership: leadership,
            level_leadership: level_option,
            leadership_represent: society_name,
            role: role_option,
          },
          success: function(d) {
            if (d.status) {
              nextPrev(1);
              $('.toast-error').toast('hide');

            }else{
              $('.toast-error').toast('show');
              $('.err-msg').html(d.msg);

              setTimeout(function(){
                $('.toast-error').toast('hide');
              },3000)
            }
          },
          error: function() {
            console.error('500 Server Error');
          }
        });
      }

    }

    function addFamily()
    {
      var sibiling = $('.sibling-attend-uni.active').data('attend');
      var parent = $('.parent-div.active').data('parent-id');
      var income = $('.income-div.active').data('income');
      var budget = $('.course-div.active').data('course');
      var bumi = $('.bumi-div.active').data('bumi');

      if(income == undefined || budget == undefined || bumi == undefined) {
        $('.toast-error').toast('show');
        $('.err-msg').html('Please select all the options to continue');
          
        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000)

      } else{
        $.ajax({
          url: "/addFamily",
          method: 'post',
          data: {
            income: income,
            budget: budget,
            bumiputera: bumi,
          },
          success: function(d) {
            if (d.status) {
              nextPrev(1);
              $('.toast-error').toast('hide');

            }else{
              $('.toast-error').toast('show');
              $('.err-msg').html(d.msg);

              setTimeout(function(){
                $('.toast-error').toast('hide');
              },3000)
            }
          },
          error: function() {
            console.error('500 Server Error');
          }
        });
      }
    }

    // function addPreferredCourse()
    // {
    //   var sub_cat = $('.course-sub-cat.active').data('course-cat-sub');

    //   if(sub_cat == undefined) {
    //     $('.toast-error').toast('show');
    //     $('.err-msg').html('Please choose preferred course category and preferred course');
          
    //     setTimeout(function(){
    //       $('.toast-error').toast('hide');
    //     },3000)
    //   } else{
    //     $.ajax({
    //       url: "/updatePreferredCourse",
    //       method: 'post',
    //       data: {
    //         id: sub_cat,
    //       },
    //       success: function(d) {
    //         if (d.status) {
    //           nextPrev(1);
    //           $('.toast-error').toast('hide');

    //         }else{
    //           $('.toast-error').toast('show');
    //           $('.err-msg').html(d.msg);

    //           setTimeout(function(){
    //             $('.toast-error').toast('hide');
    //           },3000)
    //         }
    //       },
    //       error: function() {
    //         console.error('500 Server Error');
    //       }
    //     });
    //   }
    // }
    
    function addPathway()
    {
      var pathway = $('.study-level.active').data('study-level');
      var start = $('.joining-date.active').data('joining-date');

      var error = false;
      var msg = '';

      if(pathway == undefined) {
        error = true;
        msg = 'Please select preferred pathway';
      } else if(start == undefined) {
        error = true;
        msg = 'Please select preferred plan to start your studies';
      }

      if(error) {
        $('.toast-error').toast('show');
        $('.err-msg').html(msg);
          
        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000)

      } else{
        $.ajax({
          url: "/addPathway",
          method: 'post',
          data: {
            pathway: pathway,
            start: start,
          },
          success: function(d) {
            if (d.status) {
              nextPrev(1);
              $('.toast-error').toast('hide');

            }else{
              $('.toast-error').toast('show');
              $('.err-msg').html(d.msg);

              setTimeout(function(){
                $('.toast-error').toast('hide');
              },3000)
            }
          },
          error: function() {
            console.error('500 Server Error');
          }
        });
      }
    }

    function addStudyLocation()
    {
      var plan = $('.study-plan.active').data('study-plan');
      var state = $('.state-list.active').data('list');
      var country = $('.data-country.active').data('country');

      var error = false;
      var msg = '';
      var state = [];

      $('.state-list').each(function(i, item){
        if($(this).hasClass('active')) {
          state.push($(this).data('list'));
        }
      });

      if(plan == undefined) {
        error = true;
        msg = 'Please select your plan to study at';
      } else {
        if(plan == 1) {
          if(state == '[]') {
            error = true;
            msg = 'Please select your preferred study location';
          }
        } else if(plan == 2) {
          if(state == '[]') {
            error = true;
            msg = 'Please select your preferred study location';
          } else if(country == undefined) {
            error = true;
            msg = 'Please select your preferred abroad study location';
          }
        } else if(plan == 3) {
          if(country == undefined) {
            error = true;
            msg = 'Please select your preferred abroad study location';
          }
        }
      }

      if(error) {
        $('.toast-error').toast('show');
        $('.err-msg').html(msg);
          
        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000)

      } else{
        $.ajax({
          url: "/addStudyLocation",
          method: 'post',
          data: {
            location: plan,
            state: state,
            country: country
          },
          success: function(d) {
            if (d.status) {
              nextPrev(1);
              $('.toast-error').toast('hide');

            }else{
              $('.toast-error').toast('show');
              $('.err-msg').html(d.msg);

              setTimeout(function(){
                $('.toast-error').toast('hide');
              },3000)
            }
          },
          error: function() {
            console.error('500 Server Error');
          }
        });
      }

    }

    function addUniversityType()
    {
      var type = $('.uni-type.active').data('uni-type');
      var paid = $('.reg-uni.active').data('reg-uni');
      var name = $('input[name="uni_reg_name"]').val();

      var error = false;
      var msg = '';

      if(type == undefined) {
        error = true;
        msg = 'Please select the type of university your are considering';
      }

      if(paid == undefined) {
        error = true;
        msg = 'Please select a option if you have paid any registration fees for any university';
      } else {
        if(paid == 1) {
          if(name == null || name == '') {
            error = true;
            msg = 'Please enter the university which you have applied to.';
          }
        }
      }

      if(error) {
        $('.toast-error').toast('show');
        $('.err-msg').html(msg);
          
        setTimeout(function(){
          $('.toast-error').toast('hide');
        },3000)

      } else{
        $.ajax({
          url: "/addUniversityType",
          method: 'post',
          data: {
            type: type,
            paid: paid,
            uni_name: name
          },
          success: function(d) {
            if (d.status) {
              nextPrev(1);
              $('.toast-error').toast('hide');

            }else{
              $('.toast-error').toast('show');
              $('.err-msg').html(d.msg);

              setTimeout(function(){
                $('.toast-error').toast('hide');
              },3000)
            }
          },
          error: function() {
            console.error('500 Server Error');
          }
        });
      }
    }

  });

</script>
@endsection
