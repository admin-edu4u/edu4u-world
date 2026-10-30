@extends('memberLayout')
@section('style')
<style>
body {
  background-color: #0D2227;
}
.darker {
  position: relative;
  top: 0;
  left: 0;
  width: 100%;
  padding-top: 50%;
  background: #473F7E;
  opacity: 0.75;
  overflow: hidden;
  border-radius: 24px;
  height: 349px;
}
.darker::after {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: #1A124E;
  opacity: 0.25;
  transform: skewY(42deg);
  transform-origin: left top;
}
.accordion-button::after {
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='10' viewBox='0 0 18 10' fill='none'><path d='M1 1L9 9L17 1' stroke='white' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>");
}

.accordion-button:not(.collapsed)::after {
  background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='18' height='10' viewBox='0 0 18 10' fill='none'><path d='M1 1L9 9L17 1' stroke='white' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/></svg>");
  transform: rotate(-180deg);
}

.separator {
  border-bottom: 1px solid #385055;
}

.accordion-button {
  align-items: baseline;
}
/* .character-percentage-second-div, .character-percentage-first-div, .character-percentage-third-div {
  position: absolute;
  right: 0px;
  left: 0px;
  bottom: 0px;
}

.character-percentage-second-div.active, .character-percentage-first-div.active, .character-percentage-third-div.active {
  bottom: 40px;
} */

.character-details {
  position: absolute;
  top: 140px;
  right: 0px;
  left: 0px;
  opacity: 0.4;
}

.view-more-btn {
  padding: 24px 36px;
  border-radius: 4px;
  border: 1px solid #FFF;
  background-color: #FFF;
  color: #1F1F1F;
  font-size: 16px;
  font-weight: 500;
}

.view-more-btn:hover {
  color: #0D2227;
}

.cant-find-btn {
  padding: 24px 36px;
  border-radius: 4px;
  border: 1px solid #FFF;
  color: #FFF;
  font-size: 16px;
  font-weight: 500;
}

.cant-find-btn:hover {
  color: #FFF;
}

@media screen and (max-width: 767px) {
  .mw-1200 {
    padding-left:0 !important;
    padding-right:0 !important;
  }
  
  .character-details {
    left: 70px;
  }

  .d-flex {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
  }

  .d-flex::-webkit-scrollbar {
    display: none;
  }
}

.character-details.active {
  /* top: 130px; */
  opacity: 1;
}

.character-details:hover {
  cursor: pointer;
}

</style>
@endsection
@section('content')
@php
use \Jenssegers\Agent\Agent;
$agent = new Agent();
@endphp
<div class="pt-80 mw-1200 pb-20 mx-auto">
  <div class="text-center mt-20">
    <span class="d-block result-character" @if($agent->isMobile()) style="font-size:24px;" @endif>3 {{__('lang.charactersUnlocked')}}</span>
    <span class="d-block result-character-desc" @if($agent->isMobile()) style="font-size:14px;" @endif>{{__('lang.resonateMost')}}</span>
    <div class="col-12 row mt-20 justify-content-center align-items-center">
      @if(isset($return_character) && count($return_character) > 0)
      @if(!$agent->isMobile())
        <div class="col-4 character-list" style="margin-right: -100px;" data-avg-salary="{{$return_character[1]['avg_salary']}}" data-character-position="2" data-character="{{$return_character[1]['id']}}" data-desc="{{$return_character[1]['desc']}}">
          <div class="position-relative">
            <div>
              <img id="character_img" src="images/assets/character-inactivate-2.png">
            </div>
            <div class="character-details">
              <div>
                <div style="height: 60px;">
                  <span class="d-block character-rank-title">{{$return_character[1]['name']}}</span>
                </div>
                <!-- <span class="d-block character-rank-title-desc">{{$return_character[1]['cat_name']}}</span> -->
                <img id="character_img" src="{{$return_character[1]['avatar']}}" style="width: 200px; height: 200px;">
              </div>
              <div class="character-percentage-second-div mt-3">
                <span>{{__('lang.matchRate')}}: {{$return_character[1]['percentage']}}%</span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-4 character-list" data-avg-salary="{{$return_character[0]['avg_salary']}}" data-character-position="1" data-character="{{$return_character[0]['id']}}" data-desc="{{$return_character[0]['desc']}}">
          <div class="position-relative">
            <div>
              <img id="character_img" src="images/assets/character-1.png">
            </div>
            <div class="character-details active">
              <div>
                <div style="height: 60px;">
                  <span class="d-block character-rank-title">{{$return_character[0]['name']}}</span>
                </div>
                <!-- <span class="d-block character-rank-title-desc">{{$return_character[0]['cat_name']}}</span> -->
                <img src="{{$return_character[0]['avatar']}}" style="width: 200px; height: 200px;">
              </div>
              <div class="character-percentage-first-div active mt-3">
                <span>{{__('lang.matchRate')}}: {{$return_character[0]['percentage']}}%</span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-4 character-list" style="margin-left: -100px;" data-avg-salary="{{$return_character[2]['avg_salary']}}" data-character-position="3" data-character="{{$return_character[2]['id']}}" data-desc="{{$return_character[2]['desc']}}">
          <div class="position-relative">
            <div>
              <img id="character_img" src="images/assets/character-inactivate-3.png">
            </div>
            <div class="character-details">
              <div>
                <div style="height: 60px;">
                  <span class="d-block character-rank-title">{{$return_character[2]['name']}}</span>
                </div>
                <!-- <span class="d-block character-rank-title-desc">{{$return_character[2]['cat_name']}}</span> -->
                <img src="{{$return_character[2]['avatar']}}" style="width: 200px; height: 200px;">
              </div>
              <div class="character-percentage-third-div mt-3">
                <span>{{__('lang.matchRate')}}: {{$return_character[2]['percentage']}}%</span>
              </div>
            </div>
          </div>
        </div>
      @endif
      @if($agent->isMobile())
      <div class="d-flex mobileCharacterContainer" style="overflow-x: auto; white-space: nowrap;" id="mobileCharacterContainer">
        <div class="col-4 character-list" style="width:285px;" data-avg-salary="{{$return_character[1]['avg_salary']}}" data-character-position="2" data-character="{{$return_character[1]['id']}}" data-desc="{{$return_character[1]['desc']}}">
          <div class="position-relative">
            <div class="padding-left:auto;">
              <img id="character_img" src="images/assets/character-inactivate-2.png">
            </div>
            <div class="character-details">
              <div>
                <span class="d-block character-rank-title">{{$return_character[1]['name']}}</span>
                <!-- <span class="d-block character-rank-title-desc">{{$return_character[1]['cat_name']}}</span> -->
                <img id="character_img" src="{{$return_character[1]['avatar']}}" style="margin-top: 20px;width: 200px; height: 200px;">
              </div>
              <div class="character-percentage-second-div mt-3">
                <span>{{__('lang.matchRate')}}: {{$return_character[1]['percentage']}}%</span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-4 character-list" style="width:285px;" data-avg-salary="{{$return_character[0]['avg_salary']}}" data-character-position="1" data-character="{{$return_character[0]['id']}}" data-desc="{{$return_character[0]['desc']}}">
          <div class="position-relative">
            <div>
              <img id="character_img" src="images/assets/character-1.png">
            </div>
            <div class="character-details active">
              <div>
                <span class="d-block character-rank-title">{{$return_character[0]['name']}}</span>
                <!-- <span class="d-block character-rank-title-desc">{{$return_character[0]['cat_name']}}</span> -->
                <img src="{{$return_character[0]['avatar']}}" style="margin-top: 20px;width: 200px; height: 200px;">
              </div>
              <div class="character-percentage-first-div active mt-3">
                <span>{{__('lang.matchRate')}}: {{$return_character[0]['percentage']}}%</span>
              </div>
            </div>
          </div>
        </div>

        <div class="col-4 character-list" style="width:285px;" data-avg-salary="{{$return_character[2]['avg_salary']}}" data-character-position="3" data-character="{{$return_character[2]['id']}}" data-desc="{{$return_character[2]['desc']}}">
          <div class="position-relative">
            <div>
              <img id="character_img" src="images/assets/character-inactivate-3.png">
            </div>
            <div class="character-details">
              <div>
                <span class="d-block character-rank-title">{{$return_character[2]['name']}}</span>
                <!-- <span class="d-block character-rank-title-desc">{{$return_character[2]['cat_name']}}</span> -->
                <img src="{{$return_character[2]['avatar']}}" style="margin-top: 20px;width: 200px; height: 200px;">
              </div>
              <div class="character-percentage-third-div mt-3">
                <span>{{__('lang.matchRate')}}: {{$return_character[2]['percentage']}}%</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endif
      @endif
    </div>
    @if($agent->isMobile()) <div class="pe-5 ps-5"> @endif
    <div class="mt-15">
      <span class="character-result-desc"></span>
      <div class="col-12 @if(!$agent->isMobile()) row @endif @if($agent->isMobile()) d-flex @endif mt-5 justify-content-center">
        <div class="col-3 col-lg-3 @if($agent->isMobile()) me-5 ps-3 @endif">
          <div class="character-result-div">
            <span class="d-block character-result-div-ttl text-center" id="ttl_course"></span>
            <span class="d-block character-result-div-ttl-desc text-center mt-3" @if($agent->isMobile()) style="font-size:12px;" @endif>{{__('lang.numOfCourse')}}</span>
          </div>
        </div>
        <div class="col-3 col-lg-3 @if($agent->isMobile()) pe-3 @endif">
          <div class="character-result-div">
            <span class="d-block character-result-div-ttl text-center" id="ttl_uni"></span>
            <span class="d-block character-result-div-ttl-desc text-center mt-3" @if($agent->isMobile()) style="font-size:12px;" @endif>{{__('lang.numOfUniversities')}}</span>
          </div>
        </div>
        <!-- <div class="col-4">
          <div class="character-result-div">
            <span class="d-block character-result-div-ttl text-center">36 Months</span>
            <span class="d-block character-result-div-ttl-desc text-center mt-3">Avg. Course duration</span>
          </div>
        </div> -->
        <div class="col-3 col-lg-3">
          <div class="character-result-div">
            <span class="d-block character-result-div-ttl text-center" id="avg_salary">MYR {{$return_character[0]['avg_salary']}}</span>
            <span class="d-block character-result-div-ttl-desc text-center mt-3">Avg. Salary</span>
          </div>
        </div>
      </div>
      <!-- <div class="col-12 d-flex mt-5 justify-content-center">
        <div class="col-6">
          <div class="character-result-div">
            <span class="d-block character-result-div-ttl text-center">MYR 13,589.00</span>
            <span class="d-block character-result-div-ttl-desc text-center mt-3">Savings through Edu4u</span>
          </div>
        </div>
        <div class="col-6 col-lg-3">
          <div class="character-result-div">
            <span class="d-block character-result-div-ttl text-center" id="avg_salary">MYR {{$return_character[0]['avg_salary']}}</span>
            <span class="d-block character-result-div-ttl-desc text-center mt-3">Avg. Salary</span>
          </div>
        </div>
      </div> -->
      <div class="mt-10 @if($agent->isMobile()) col-12 @endif">
        <button id="pathway" class="retake-test-btn me-3 @if($agent->isMobile()) col-12 @endif">{{__('lang.retakeTest')}}</button>
        <button id="counsellor" class="talk-to-counsellor-btn @if($agent->isMobile()) col-12 mt-5 @endif">{{__('lang.talkToCounsellor')}}</button>
      </div>
    </div>

    <div class="mt-20">
      <span class="personality-title-2 d-block">{{__('lang.fieldOfStudyChoice')}}</span>
      <div>
        <div id="course_list"></div>
        <!-- <div class="accordion mt-10" id="kt_accordion_1">
          <div class="accordion-item result-personality">
            <h2 class="accordion-header" id="kt_accordion_1_header_1">
              <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1" aria-expanded="true" aria-controls="kt_accordion_1_body_1">
                <div class="col-12">
                  <div class="col-12 d-flex align-items-center">
                    <div>
                      <img src="/images/assets/uni_img_3.png" class="result-course-option-img">
                    </div>
                    <div class="ms-7">
                      <span class="d-block result-course-title">BA (Hons) In Accounting & Finance</span>
                      <span class="d-block result-course-title-desc">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                          <path d="M8 2.16992C6.6744 2.17152 5.40354 2.7101 4.4662 3.66753C3.52885 4.62496 3.00157 5.92306 3.00001 7.27706C2.99842 8.38356 3.35227 9.46003 4.00728 10.3414C4.00728 10.3414 4.14364 10.5247 4.16591 10.5512L8 15.1699L11.8359 10.5489C11.8559 10.5243 11.9927 10.3414 11.9927 10.3414L11.9932 10.34C12.6479 9.45903 13.0015 8.38305 13 7.27706C12.9984 5.92306 12.4711 4.62496 11.5338 3.66753C10.5965 2.7101 9.3256 2.17152 8 2.16992ZM8 9.13421C7.6404 9.13421 7.28887 9.02529 6.98987 8.82122C6.69088 8.61716 6.45783 8.32711 6.32022 7.98776C6.18261 7.64841 6.1466 7.275 6.21676 6.91475C6.28691 6.5545 6.46008 6.22359 6.71435 5.96387C6.96863 5.70414 7.2926 5.52726 7.64529 5.45561C7.99798 5.38395 8.36356 5.42073 8.69579 5.56129C9.02802 5.70185 9.31198 5.93989 9.51176 6.24529C9.71155 6.5507 9.81818 6.90976 9.81818 7.27706C9.81758 7.76942 9.62583 8.24144 9.28498 8.58958C8.94414 8.93773 8.48203 9.13359 8 9.13421Z" fill="#999999"/>
                        </svg>
                        Sunway University
                      </span>
                    </div>
                    <div class="col-8">
                      <div class="float-end text-end me-5">
                        <span class="d-block result-course-price">MYR 9,700</span>
                        <span class="d-block result-course-price-year">/ year</span>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex mt-5">
                    <div class="me-10">
                      <span class="d-block result-course-title-desc">Duration</span>
                      <span class="d-block result-course-duration mt-3">3 Years</span>
                    </div>
                    <div class="text-start">
                      <span class="d-block result-course-title-desc mb-3">Intake</span>
                      <span class="result-course-duration me-5">Feb</span>
                      <span class="result-course-duration me-5">Apr</span>
                      <span class="result-course-duration">Sep</span>
                    </div>
                  </div>
                </div>
              </button>
            </h2>
            <div id="kt_accordion_1_body_1" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
              <div class="accordion-body">
                <div class="d-flex">
                  <div class="col-6 text-start">
                    <span class="result-course-fees">Course Fee</span>
                  </div>
                  <div class="col-6 text-end">
                    <span class="result-course-fees-amt">MYR 37,000</span>
                  </div>
                </div>
                <div class="separator my-10"></div>
                <div class="d-flex align-items-center mb-10">
                  <div class="col-6 text-start">
                    <span class="result-edu-deals">Edu4u Deals</span>
                    <span class="result-edu-deals-desc ms-5">Edu4u discount</span>
                  </div>
                  <div class="col-6 text-end">
                    <span class="result-edu-deals-amt">Save MYR 500</span>
                  </div>
                </div>
                <div class="d-flex align-items-center mb-10">
                  <div class="col-6 text-start">
                    <span class="result-edu-deals">Scholarships</span>
                    <span class="result-edu-deals-desc ms-5">Scholarships available to be selected</span>
                  </div>
                  <div class="col-6 text-end">
                    <span class="result-edu-deals-amt">Up to MYR 5,000</span>
                  </div>
                </div>
                <div class="d-flex align-items-center mb-10">
                  <div class="col-6 text-start">
                    <span class="result-edu-deals">Loan</span>
                    <span class="result-edu-deals-desc ms-5">PTPTN 75% Loan (Household Income below RM 8,000)</span>
                  </div>
                  <div class="col-6 text-end">
                    <span class="result-edu-deals-amt">- MYR 12,750</span>
                  </div>
                </div>
                <div class="separator mt-20"></div>
                <div class="text-end mt-10">
                  <span class="result-deal-total-pay-amt">You pay<span class="ms-5">MYR 19,250</span></span>
                </div>
                <div class="separator my-10"></div>
                <div class="mt-5">
                  <span class="float-start result-deal-total-save">You save MYR 500 with scholarships when you apply with Edu4u</span>
                  <span class="float-end result-deal-total-save-scholarship">Scholarships
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                      <path d="M1 13H15V14H1V13ZM12.7 4.5C13.1 4.1 13.1 3.5 12.7 3.1L10.9 1.3C10.5 0.9 9.9 0.9 9.5 1.3L2 8.8V12H5.2L12.7 4.5ZM10.2 2L12 3.8L10.5 5.3L8.7 3.5L10.2 2ZM3 11V9.2L8 4.2L9.8 6L4.8 11H3Z" fill="white"/>
                    </svg>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="accordion mt-10" id="kt_accordion_1">
          <div class="accordion-item result-personality">
            <h2 class="accordion-header" id="kt_accordion_1_header_1">
              <button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2" aria-expanded="true" aria-controls="kt_accordion_1_body_2">
                <div class="col-12">
                  <div class="col-12 d-flex align-items-center">
                    <div>
                      <img src="/images/assets/uni_img_3.png" class="result-course-option-img">
                    </div>
                    <div class="ms-7">
                      <span class="d-block result-course-title">BA (Hons) In Accounting & Finance</span>
                      <span class="d-block result-course-title-desc">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                          <path d="M8 2.16992C6.6744 2.17152 5.40354 2.7101 4.4662 3.66753C3.52885 4.62496 3.00157 5.92306 3.00001 7.27706C2.99842 8.38356 3.35227 9.46003 4.00728 10.3414C4.00728 10.3414 4.14364 10.5247 4.16591 10.5512L8 15.1699L11.8359 10.5489C11.8559 10.5243 11.9927 10.3414 11.9927 10.3414L11.9932 10.34C12.6479 9.45903 13.0015 8.38305 13 7.27706C12.9984 5.92306 12.4711 4.62496 11.5338 3.66753C10.5965 2.7101 9.3256 2.17152 8 2.16992ZM8 9.13421C7.6404 9.13421 7.28887 9.02529 6.98987 8.82122C6.69088 8.61716 6.45783 8.32711 6.32022 7.98776C6.18261 7.64841 6.1466 7.275 6.21676 6.91475C6.28691 6.5545 6.46008 6.22359 6.71435 5.96387C6.96863 5.70414 7.2926 5.52726 7.64529 5.45561C7.99798 5.38395 8.36356 5.42073 8.69579 5.56129C9.02802 5.70185 9.31198 5.93989 9.51176 6.24529C9.71155 6.5507 9.81818 6.90976 9.81818 7.27706C9.81758 7.76942 9.62583 8.24144 9.28498 8.58958C8.94414 8.93773 8.48203 9.13359 8 9.13421Z" fill="#999999"/>
                        </svg>
                        Sunway University
                      </span>
                    </div>
                    <div class="col-8">
                      <div class="float-end text-end me-5">
                        <span class="d-block result-course-price">MYR 9,700</span>
                        <span class="d-block result-course-price-year">/ year</span>
                      </div>
                    </div>
                  </div>
                  <div class="d-flex mt-5">
                    <div class="me-10">
                      <span class="d-block result-course-title-desc">Duration</span>
                      <span class="d-block result-course-duration mt-3">3 Years</span>
                    </div>
                    <div class="text-start">
                      <span class="d-block result-course-title-desc mb-3">Intake</span>
                      <span class="result-course-duration me-5">Feb</span>
                      <span class="result-course-duration me-5">Apr</span>
                      <span class="result-course-duration">Sep</span>
                    </div>
                  </div>
                </div>
              </button>
            </h2>
            <div id="kt_accordion_1_body_2" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
              <div class="accordion-body">
                <div class="d-flex">
                  <div class="col-6 text-start">
                    <span class="result-course-fees">Course Fee</span>
                  </div>
                  <div class="col-6 text-end">
                    <span class="result-course-fees-amt">MYR 37,000</span>
                  </div>
                </div>
                <div class="separator my-10"></div>
                <div class="d-flex align-items-center mb-10">
                  <div class="col-6 text-start">
                    <span class="result-edu-deals">Edu4u Deals</span>
                    <span class="result-edu-deals-desc ms-5">Edu4u discount</span>
                  </div>
                  <div class="col-6 text-end">
                    <span class="result-edu-deals-amt">Save MYR 500</span>
                  </div>
                </div>
                <div class="d-flex align-items-center mb-10">
                  <div class="col-6 text-start">
                    <span class="result-edu-deals">Scholarships</span>
                    <span class="result-edu-deals-desc ms-5">Scholarships available to be selected</span>
                  </div>
                  <div class="col-6 text-end">
                    <span class="result-edu-deals-amt">Up to MYR 5,000</span>
                  </div>
                </div>
                <div class="d-flex align-items-center mb-10">
                  <div class="col-6 text-start">
                    <span class="result-edu-deals">Loan</span>
                    <span class="result-edu-deals-desc ms-5">PTPTN 75% Loan (Household Income below RM 8,000)</span>
                  </div>
                  <div class="col-6 text-end">
                    <span class="result-edu-deals-amt">- MYR 12,750</span>
                  </div>
                </div>
                <div class="separator mt-20"></div>
                <div class="text-end mt-10">
                  <span class="result-deal-total-pay-amt">You pay<span class="ms-5">MYR 19,250</span></span>
                </div>
                <div class="separator my-10"></div>
                <div class="mt-5">
                  <span class="float-start result-deal-total-save">You save MYR 500 with scholarships when you apply with Edu4u</span>
                  <span class="float-end result-deal-total-save-scholarship">Scholarships
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                      <path d="M1 13H15V14H1V13ZM12.7 4.5C13.1 4.1 13.1 3.5 12.7 3.1L10.9 1.3C10.5 0.9 9.9 0.9 9.5 1.3L2 8.8V12H5.2L12.7 4.5ZM10.2 2L12 3.8L10.5 5.3L8.7 3.5L10.2 2ZM3 11V9.2L8 4.2L9.8 6L4.8 11H3Z" fill="white"/>
                    </svg>
                  </span>
                </div>
              </div>
            </div>
          </div>
        </div> -->
      </div>
    </div>

    <!-- <div class="mt-20">
      <span class="personality-title-2 d-block">{{__('lang.recCourse')}}</span>
      <div>
        <div id="all_course_list"></div>
      </div>
    </div> -->
</div>
@if($agent->isMobile()) </div> @endif
@section('modal')
<div class="modal fade" tabindex="-1" id="sign_up_modal" style="background: rgba(0, 0, 0, 0.50);">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content br-12" style="padding:24px;">
			<div class="modal-header p-0">
				<h3 class="modal-title login_title">{{__('lang.signUp')}}</h3>
				<div class="btn btn-icon btn-sm ms-2 btn-close-modal" data-bs-dismiss="modal" aria-label="Close">
					<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
						<path d="M1 1L9 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
						<path d="M9 1L1 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
					</svg>
				</div>
			</div>

			<div class="modal-body p-0">
				<div class="mt-3">
					<span class="mb-5 d-block sign-up-title">{{__('lang.signUpDesc')}}</span>
					<span class="d-block mt-2 sign-up-benifits">
						<svg class="me-2" width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M1 4.81818L3.13333 7L9 1" stroke="#1A124E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						{{__('lang.scholarshipMatching')}}
					</span>
					<span class="d-block mt-2 sign-up-benifits">
								<svg class="me-2" width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M1 4.81818L3.13333 7L9 1" stroke="#1A124E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
								{{__('lang.freeCounselling')}}
					</span>
					<span class="d-block mt-2 sign-up-benifits">
						<svg class="me-2" width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M1 4.81818L3.13333 7L9 1" stroke="#1A124E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						{{__('lang.onlineApplication')}}
					</span>
					<span class="d-block mt-2 sign-up-benifits">
						<svg class="me-2" width="10" height="8" viewBox="0 0 10 8" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M1 4.81818L3.13333 7L9 1" stroke="#1A124E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						{{__('lang.exclusiveDiscount')}}
					</span>
					<div class="sign-up-benifits-desc my-5">
						<span>{{__('lang.savingsDesc')}}</span>
					</div>
					<form id="signup_form">
						<div class="mb-4">
							<input type="text" class="form-control form-control-solid login-input" name="email" placeholder="Enter your email address"/>
						</div>
						<div class="mb-4">
							<input type="password" class="form-control form-control-solid login-input" name="password" placeholder="Create a password"/>
							<div class="d-flex mt-3">
								<div class="ms-3"><span class="pwd-guide">{{__('lang.newPasswordReq')}}</span></div>
							</div>
						</div>
						<div class="mb-4">
							<input type="password" class="form-control form-control-solid login-input" name="confirm_password" placeholder="Repeat password"/>
							<div class="d-flex mt-3">
								<div class="ms-3"><span class="pwd-guide">{{__('lang.passwordMatch')}}</span></div>
							</div>
						</div>
						<button class="w-100 apply-scholarship-btn" id="sign_up_btn_submit">{{__('lang.signUp')}}</button>
					</form>
				</div>
			</div>
    </div>
	</div>
</div>

<div class="modal fade" tabindex="-1" id="add_pref_cat_modal" style="background: rgba(0, 0, 0, 0.50);">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content br-12" style="padding:24px;">
			<div class="modal-header p-0">
				<h3 class="modal-title login_title">{{__('lang.cantFind')}}</h3>
				<div class="btn btn-icon btn-sm ms-2 btn-close-modal" data-bs-dismiss="modal" aria-label="Close">
					<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
						<path d="M1 1L9 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
						<path d="M9 1L1 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
					</svg>
				</div>
			</div>

			<div class="modal-body p-0">
				<div class="mt-3">
					<span class="mb-5 d-block sign-up-title">{{__('lang.cantFindDesc')}}</span>
					<form id="add_cat_form">

            <div class="mb-4">
              <select class="form-select form-control-solid coursemaincat" name="coursemaincat" id="coursemaincat" required style="background-color: #F6F6F6;">
                  <option value="" selected disabled>Select Field Of Study</option>
                  @foreach ($category as $c)
                      <option value="{{ $c->c_id }}">{{ $c->c_name }}</option>
                  @endforeach
              </select>
            </div>

            <div class="mb-4">
              <select class="form-select coursesubcat" name="coursesubcat" id="coursesubcat" required style="background-color: #F6F6F6;">
                  <option value="" selected disabled>Select Major</option>
              </select>
            </div>

						<button class="w-100 apply-scholarship-btn" id="sign_up_btn_submit">{{__('lang.submit')}}</button>
					</form>
				</div>
			</div>
    </div>
	</div>
</div>
@endsection
@endsection

@section('script')
<script>
  $( document ).ready(function() {
    upadateDesc();
    getCourses();
    getAllCourses();

    $(document).on('click', '[data-character]', function(){
      var id = $(this).data('character');
      var position = $(this).data('character-position');
      var avg_salary = $(this).data('avg-salary');

      $('.character-list').each(function(i, item){
        if($(this).data('character') == id){
          $(this).find('.character-details').addClass('active');
          $(this).find('#character_img').attr('src', 'images/assets/character-'+position+'.png')
          $('#avg_salary').html('MYR '+avg_salary)
          upadateDesc();
          getCourses();
          getAllCourses();
        } else{
          var position_2 = $(this).data('character-position');
          $(this).find('.character-details').removeClass('active');
          $(this).find('#character_img').attr('src', 'images/assets/character-inactivate-'+position_2+'.png')
        }
      });
    });

    function upadateDesc()
    {
      var desc = $('.character-details.active').parent().parent().data('desc');
      $('.character-result-desc').html(desc);
    }

    function getCourses()
    {
      var id = $('.character-details.active').parent().parent().data('character');
      // var id = 1;

      $.ajax({
        url: "/getRecommendedCourse",
        method: 'post',
        data: {
          character: id,
        },
        success: function(d) {
          if (d.status) {
            var list = d.list;
            var content = '';

            $('#ttl_course').html(d.ttl_course);
            $('#ttl_uni').html(d.ttl_uni);
            list.forEach(function(item, index) {
              content += '<div class="accordion mt-10" id="kt_accordion_1">';
              content += '<div class="accordion-item result-personality">';
              content += '<h2 class="accordion-header course-recommended-accordion-header" id="kt_accordion_1_header_1" style="border-radius: 12px;">';
              content += '<button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_'+index+'" aria-expanded="true" aria-controls="kt_accordion_1_body_'+index+'">';
              content += '<div class="col-12">';
              content += '<div class="col-12 d-flex @if($agent->isMobile()) flex-wrap @endif align-items-center justify-content-between">';
              content += '<div class="d-flex align-items-center">';
              content += '<img src="'+item.uni_img+'" class="result-course-option-img col-12">';
              content += '@if($agent->isMobile()) <br> @endif <div class="ms-7">';
              content += '<span class="d-block result-course-title @if($agent->isMobile()) col-12 @endif">'+item.name+'</span>';
              content += '<span class="d-block result-course-title-desc mt-3">';
              content += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">';
              content += '<path d="M8 2.16992C6.6744 2.17152 5.40354 2.7101 4.4662 3.66753C3.52885 4.62496 3.00157 5.92306 3.00001 7.27706C2.99842 8.38356 3.35227 9.46003 4.00728 10.3414C4.00728 10.3414 4.14364 10.5247 4.16591 10.5512L8 15.1699L11.8359 10.5489C11.8559 10.5243 11.9927 10.3414 11.9927 10.3414L11.9932 10.34C12.6479 9.45903 13.0015 8.38305 13 7.27706C12.9984 5.92306 12.4711 4.62496 11.5338 3.66753C10.5965 2.7101 9.3256 2.17152 8 2.16992ZM8 9.13421C7.6404 9.13421 7.28887 9.02529 6.98987 8.82122C6.69088 8.61716 6.45783 8.32711 6.32022 7.98776C6.18261 7.64841 6.1466 7.275 6.21676 6.91475C6.28691 6.5545 6.46008 6.22359 6.71435 5.96387C6.96863 5.70414 7.2926 5.52726 7.64529 5.45561C7.99798 5.38395 8.36356 5.42073 8.69579 5.56129C9.02802 5.70185 9.31198 5.93989 9.51176 6.24529C9.71155 6.5507 9.81818 6.90976 9.81818 7.27706C9.81758 7.76942 9.62583 8.24144 9.28498 8.58958C8.94414 8.93773 8.48203 9.13359 8 9.13421Z" fill="#999999"/>';
              content += '</svg>';
              content += ''+item.uni_name+'';
              content += '</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="col-lg-5 col-12">';
              content += '<div class="float-end text-end @if(!$agent->isMobile()) me-5 @endif @if($agent->isMobile()) col-12 @endif d-flex">';
              content += '<div>';
              content += '@if(!$agent->isMobile())<span class="d-block result-course-price">MYR '+item.price_per_year+'</span>';
              content += '<span class="d-block result-course-price-year">/ year</span>@endif';
              content += '</div>';
              content += '<div class="@if(!$agent->isMobile()) ms-5 @endif @if($agent->isMobile()) mt-3 col-12 @endif">';
              content += '<a class="apply-recommended-btn d-inline-block @if($agent->isMobile()) col-12 @endif" id="apply-course" data-navigate-url="/course-application/'+item.url_code+'" @if($agent->isMobile()) style="text-align:center;" @endif>Apply</a>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '<div class="d-flex mt-5">';
              content += '<div class="me-10">';
              content += '<span class="d-block result-course-title-desc">Duration</span>';
              content += '<span class="d-block result-course-duration mt-3">'+item.duration+'</span>';
              content += '</div>';
              content += '<div class="text-start">';
              content += '<span class="d-block result-course-title-desc mb-3">Intake</span>';
              item.intake.forEach(function(int, i) {
                content += '<span class="result-course-duration me-5">'+int+'</span>';
              });
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</button>';
              content += '</h2>';
              content += '<div id="kt_accordion_1_body_'+index+'" class="accordion-collapse collapse course-accordion-collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1" style="border-radius: 12px;">';
              content += '<div class="accordion-body">';
              content += '<div class="d-flex">';
              content += '<div class="col-6 text-start">';
              content += '<span class="result-course-fees" @if($agent->isMobile()) style="font-size:12px;" @endif>Course Fee</span>';
              content += '</div>';
              content += '<div class="col-6 text-end">';
              content += '<span class="result-course-fees-amt" @if($agent->isMobile()) style="font-size:16px;" @endif>MYR '+item.course_ttl+'</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="separator my-10"></div>';
              // content += '<div class="d-flex align-items-center mb-10">';
              // content += '<div class="col-6 text-start">';
              // content += '<span class="result-edu-deals">Edu4u Deals</span>';
              // content += '<span class="result-edu-deals-desc ms-5">Edu4u discount</span>';
              // content += '</div>';
              // content += '<div class="col-6 text-end">';
              // content += '<span class="result-edu-deals-amt">Save MYR 500</span>';
              // content += '</div>';
              // content += '</div>';
              content += '<div class="d-flex @if($agent->isMobile()) flex-wrap @endif align-items-center mb-10">';
              content += '<div class="col-12 col-lg-6 text-start">';
              content += '<span class="result-edu-deals">Scholarships</span> @if($agent->isMobile()) <br><br> @endif';
              content += '<span class="result-edu-deals-desc @if(!$agent->isMobile()) ms-5 @endif" @if($agent->isMobile()) style="font-size:12px;" @endif>Scholarships available to be selected</span> @if($agent->isMobile()) <br> @endif';
              content += '</div>';
              content += '<div class="col-12 col-lg-6 @if(!$agent->isMobile()) text-end @endif @if($agent->isMobile()) text-start @endif">';
              content += '<span class="result-edu-deals-amt" @if($agent->isMobile()) style="font-size:14px; font-weight:600;" @endif>Up to MYR '+item.ttl_scholarship+'</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="d-flex @if($agent->isMobile()) flex-wrap @endif align-items-center mb-10">';
              content += '<div class="col-12 col-lg-6 text-start">';
              content += '<span class="result-edu-deals">Loan</span> @if($agent->isMobile()) <br><br> @endif';
              content += '<span class="result-edu-deals-desc @if(!$agent->isMobile()) ms-5 @endif" @if($agent->isMobile()) style="font-size:12px;" @endif>PTPTN '+item.ptpn_percentage+' Loan (Household Income '+item.household_income+')</span>';
              content += '</div>';
              content += '<div class="col-12 col-lg-6 @if(!$agent->isMobile()) text-end @endif @if($agent->isMobile()) text-start float-start @endif">';
              content += '<span class="result-edu-deals-amt" @if($agent->isMobile()) style="font-size:14px; font-weight:600;" @endif>- MYR '+item.ptpn_ttl+'</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="separator mt-20"></div>';
              content += '<div class="text-end mt-10">';
              content += '<span class="result-deal-total-pay-amt">You pay<span class="ms-5">MYR '+item.total_pay_amt+'</span></span>';
              content += '</div>';
              // content += '<div class="separator my-10"></div>';
              // content += '<div class="mt-5">';
              // content += '<span class="float-start result-deal-total-save">You save MYR 500 with scholarships when you apply with Edu4u</span>';
              // content += '<span class="float-end result-deal-total-save-scholarship">Scholarships';
              // content += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">';
              // content += '<path d="M1 13H15V14H1V13ZM12.7 4.5C13.1 4.1 13.1 3.5 12.7 3.1L10.9 1.3C10.5 0.9 9.9 0.9 9.5 1.3L2 8.8V12H5.2L12.7 4.5ZM10.2 2L12 3.8L10.5 5.3L8.7 3.5L10.2 2ZM3 11V9.2L8 4.2L9.8 6L4.8 11H3Z" fill="white"/>';
              // content += '</svg>';
              // content += '</span>';
              // content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
            });
            if(d.slice) {
              content += '<a class="mt-20 d-inline-block cant-find-btn me-3" id="view_more_btn">Canʼt find your preferred course?</a>';
              content += '<a class="mt-20 d-inline-block view-more-btn" id="view_more_btn">View more</a>';
            } else{
              content += '<a class="mt-20 d-inline-block cant-find-btn" id="add_pref_cat">Canʼt find your preferred course?</a>';
            }
            
            $('#course_list').empty().append(content);
          } else{
            $('#course_list').empty();
            $('#ttl_course').html(0);
            $('#ttl_uni').html(0);
          }
        },
        error: function() {
          console.error('500 Server Error');
        }
      });
    }

    function getAllCourses()
    {
      var id = $('.character-details.active').parent().parent().data('character');
      // var id = 1;

      $.ajax({
        url: "/getAllRecommendedCourse",
        method: 'post',
        data: {
          character: id,
        },
        success: function(d) {
          if (d.status) {
            var list = d.list;
            var content = '';

            // $('#ttl_course').html(d.ttl_course);
            // $('#ttl_uni').html(d.ttl_uni);
            list.forEach(function(item, index) {
              content += '<div class="accordion mt-10" id="kt_accordion_1">';
              content += '<div class="accordion-item result-personality">';
              content += '<h2 class="accordion-header course-recommended-accordion-header" id="kt_accordion_1_header_1" style="border-radius: 12px;">';
              content += '<button class="accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_rec_'+index+'" aria-expanded="true" aria-controls="kt_accordion_1_body_rec_'+index+'">';
              content += '<div class="col-12">';
              content += '<div class="col-12 d-flex @if($agent->isMobile()) flex-wrap @endif align-items-center justify-content-between">';
              content += '<div class="d-flex align-items-center">';
              content += '<img src="'+item.uni_img+'" class="result-course-option-img col-12">';
              content += '@if($agent->isMobile()) <br> @endif <div class="ms-7">';
              content += '<span class="d-block result-course-title @if($agent->isMobile()) col-12 @endif">'+item.name+'</span>';
              content += '<span class="d-block result-course-title-desc mt-3">';
              content += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">';
              content += '<path d="M8 2.16992C6.6744 2.17152 5.40354 2.7101 4.4662 3.66753C3.52885 4.62496 3.00157 5.92306 3.00001 7.27706C2.99842 8.38356 3.35227 9.46003 4.00728 10.3414C4.00728 10.3414 4.14364 10.5247 4.16591 10.5512L8 15.1699L11.8359 10.5489C11.8559 10.5243 11.9927 10.3414 11.9927 10.3414L11.9932 10.34C12.6479 9.45903 13.0015 8.38305 13 7.27706C12.9984 5.92306 12.4711 4.62496 11.5338 3.66753C10.5965 2.7101 9.3256 2.17152 8 2.16992ZM8 9.13421C7.6404 9.13421 7.28887 9.02529 6.98987 8.82122C6.69088 8.61716 6.45783 8.32711 6.32022 7.98776C6.18261 7.64841 6.1466 7.275 6.21676 6.91475C6.28691 6.5545 6.46008 6.22359 6.71435 5.96387C6.96863 5.70414 7.2926 5.52726 7.64529 5.45561C7.99798 5.38395 8.36356 5.42073 8.69579 5.56129C9.02802 5.70185 9.31198 5.93989 9.51176 6.24529C9.71155 6.5507 9.81818 6.90976 9.81818 7.27706C9.81758 7.76942 9.62583 8.24144 9.28498 8.58958C8.94414 8.93773 8.48203 9.13359 8 9.13421Z" fill="#999999"/>';
              content += '</svg>';
              content += ''+item.uni_name+'';
              content += '</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="col-lg-5 col-12">';
              content += '<div class="float-end text-end @if(!$agent->isMobile()) me-5 @endif @if($agent->isMobile()) col-12 @endif d-flex">';
              content += '<div>';
              content += '@if(!$agent->isMobile())<span class="d-block result-course-price">MYR '+item.price_per_year+'</span>';
              content += '<span class="d-block result-course-price-year">/ year</span>@endif';
              content += '</div>';
              content += '<div class="@if(!$agent->isMobile()) ms-5 @endif @if($agent->isMobile()) mt-3 col-12 @endif">';
              content += '<a class="apply-recommended-btn d-inline-block @if($agent->isMobile()) col-12 @endif" id="apply-course" data-navigate-url="/course-application/'+item.url_code+'" @if($agent->isMobile()) style="text-align:center;" @endif>Apply</a>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '<div class="d-flex mt-5">';
              content += '<div class="me-10">';
              content += '<span class="d-block result-course-title-desc">Duration</span>';
              content += '<span class="d-block result-course-duration mt-3">'+item.duration+'</span>';
              content += '</div>';
              content += '<div class="text-start">';
              content += '<span class="d-block result-course-title-desc mb-3">Intake</span>';
              item.intake.forEach(function(int, i) {
                content += '<span class="result-course-duration me-5">'+int+'</span>';
              });
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</button>';
              content += '</h2>';
              content += '<div id="kt_accordion_1_body_rec_'+index+'" class="accordion-collapse collapse course-accordion-collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1" style="border-radius: 12px;">';
              content += '<div class="accordion-body">';
              content += '<div class="d-flex">';
              content += '<div class="col-6 text-start">';
              content += '<span class="result-course-fees" @if($agent->isMobile()) style="font-size:12px;" @endif>Course Fee</span>';
              content += '</div>';
              content += '<div class="col-6 text-end">';
              content += '<span class="result-course-fees-amt" @if($agent->isMobile()) style="font-size:16px;" @endif>MYR '+item.course_ttl+'</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="separator my-10"></div>';
              // content += '<div class="d-flex align-items-center mb-10">';
              // content += '<div class="col-6 text-start">';
              // content += '<span class="result-edu-deals">Edu4u Deals</span>';
              // content += '<span class="result-edu-deals-desc ms-5">Edu4u discount</span>';
              // content += '</div>';
              // content += '<div class="col-6 text-end">';
              // content += '<span class="result-edu-deals-amt">Save MYR 500</span>';
              // content += '</div>';
              // content += '</div>';
              content += '<div class="d-flex @if($agent->isMobile()) flex-wrap @endif align-items-center mb-10">';
              content += '<div class="col-12 col-lg-6 text-start">';
              content += '<span class="result-edu-deals">Scholarships</span> @if($agent->isMobile()) <br><br> @endif';
              content += '<span class="result-edu-deals-desc @if(!$agent->isMobile()) ms-5 @endif" @if($agent->isMobile()) style="font-size:12px;" @endif>Scholarships available to be selected</span> @if($agent->isMobile()) <br> @endif';
              content += '</div>';
              content += '<div class="col-12 col-lg-6 @if(!$agent->isMobile()) text-end @endif @if($agent->isMobile()) text-start @endif">';
              content += '<span class="result-edu-deals-amt" @if($agent->isMobile()) style="font-size:14px; font-weight:600;" @endif>Up to MYR '+item.ttl_scholarship+'</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="d-flex @if($agent->isMobile()) flex-wrap @endif align-items-center mb-10">';
              content += '<div class="col-12 col-lg-6 text-start">';
              content += '<span class="result-edu-deals">Loan</span> @if($agent->isMobile()) <br><br> @endif';
              content += '<span class="result-edu-deals-desc @if(!$agent->isMobile()) ms-5 @endif" @if($agent->isMobile()) style="font-size:12px;" @endif>PTPTN '+item.ptpn_percentage+' Loan (Household Income '+item.household_income+')</span>';
              content += '</div>';
              content += '<div class="col-12 col-lg-6 @if(!$agent->isMobile()) text-end @endif @if($agent->isMobile()) text-start float-start @endif">';
              content += '<span class="result-edu-deals-amt" @if($agent->isMobile()) style="font-size:14px; font-weight:600;" @endif>- MYR '+item.ptpn_ttl+'</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="separator mt-20"></div>';
              content += '<div class="text-end mt-10">';
              content += '<span class="result-deal-total-pay-amt">You pay<span class="ms-5">MYR '+item.total_pay_amt+'</span></span>';
              content += '</div>';
              // content += '<div class="separator my-10"></div>';
              // content += '<div class="mt-5">';
              // content += '<span class="float-start result-deal-total-save">You save MYR 500 with scholarships when you apply with Edu4u</span>';
              // content += '<span class="float-end result-deal-total-save-scholarship">Scholarships';
              // content += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">';
              // content += '<path d="M1 13H15V14H1V13ZM12.7 4.5C13.1 4.1 13.1 3.5 12.7 3.1L10.9 1.3C10.5 0.9 9.9 0.9 9.5 1.3L2 8.8V12H5.2L12.7 4.5ZM10.2 2L12 3.8L10.5 5.3L8.7 3.5L10.2 2ZM3 11V9.2L8 4.2L9.8 6L4.8 11H3Z" fill="white"/>';
              // content += '</svg>';
              // content += '</span>';
              // content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
            });
            if(d.slice) {
              content += '<a class="mt-20 d-inline-block cant-find-btn me-3" id="view_more_btn">Canʼt find your preferred course?</a>';
              content += '<a class="mt-20 d-inline-block view-more-btn" id="view_more_btn">View more</a>';
            } else{
              content += '<a class="mt-20 d-inline-block cant-find-btn" id="add_pref_cat">Canʼt find your preferred course?</a>';
            }
            
            $('#all_course_list').empty().append(content);
          } else{
            $('#all_course_list').empty();
            // $('#ttl_course').html(0);
            // $('#ttl_uni').html(0);
          }
        },
        error: function() {
          console.error('500 Server Error');
        }
      });
    }

    $(document).on('click', '#add_pref_cat', function(){
      $('#add_pref_cat_modal').modal('show');
    })

    $(document).on('change', '#coursemaincat', function () {
      var mainCategoryId = $(this).val();
      // Clear existing options in the subcategory select
      $('#coursesubcat').empty();
      $('#coursesubcat').append('<option value="" selected disabled>Select Sub Category</option>');

      // If a main category is selected, fetch and append subcategories
      if (mainCategoryId) {
          $.ajax({
              type: 'GET',
              url: '/get-subcategories',
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

    $('#add_cat_form').on('submit', function (e) {
			e.preventDefault();
			let f = new FormData($(this)[0]);

			$.ajax({
				url: "/add-main-sub-cat",
				method: 'post',
				data: f,
				contentType: false,
				processData: false,
				success: function(d) {

					if (d.status) {
						$('#add_pref_cat_modal').modal('hide');

						$('.toast-error').toast('hide');
						
						$('.toast-success').toast('show');
						$('.success-msg').html(d.msg);
						setTimeout(function(){
							$('.toast-success').toast('hide');
						},3000)
						getCourses();
					}else{
						$('.toast-success').toast('hide');
						$('.toast-error').toast('show');
						$('.err-msg').html(d.msg);
						setTimeout(function(){
							$('.toast-error').toast('hide');
						},3000)

					}
				}
			});

		});

    $(document).on('click', '#view_more_btn', function(){
      $('#sign_up_modal').modal('show');
    })
    
    $('#signup_form').on('submit', function (e) {
			e.preventDefault();
			let f = new FormData($(this)[0]);

			$.ajax({
				url: "/sign-up",
				method: 'post',
				data: f,
				contentType: false,
				processData: false,
				success: function(d) {

					if (d.status) {
						$('#sign_up_modal').modal('hide');

						$('.toast-error').toast('hide');
						
						$('.toast-success').toast('show');
						$('.success-msg').html(d.msg);
						setTimeout(function(){
							$('.toast-success').toast('hide');
						},3000)
						getCourses();
					}else{
						$('.toast-success').toast('hide');
						$('.toast-error').toast('show');
						$('.err-msg').html(d.msg);
						setTimeout(function(){
							$('.toast-error').toast('hide');
						},3000)

					}
				}
			});

		});

    $(document).on('click', '#apply-course', function(event){
      event.stopPropagation();
      event.preventDefault();
      $('#signup_modal').modal('show');
    });
  });

  window.addEventListener('load', function () {
        // Get the container element
        var container = document.getElementById('mobileCharacterContainer');
        
        // Get the middle character element
        var middleCharacter = document.querySelector('.character-list[data-character-position="1"]');
        
        // Scroll to the middle character
        container.scrollLeft = middleCharacter.offsetLeft - (container.offsetWidth - middleCharacter.offsetWidth) / 8;
    });
</script>
@endsection
