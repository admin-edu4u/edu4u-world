@extends('memberLayout')
@section('style')
<style>
.select2-selection__placeholder {
  color: #141414 !important;
  font-family: Poppins;
  font-size: 14px;
  font-style: normal;
  font-weight: 400;
}
#applicable {
  overflow: hidden;
  display: -webkit-box !important;
  -webkit-line-clamp: 1;
  line-clamp: 1;
  -webkit-box-orient: vertical;
}

.nav{
  flex-wrap: nowrap;
  overflow-x: scroll;
  overflow-y: hidden;
  white-space: nowrap;
}

.nav::-webkit-scrollbar { 
    display: none;  /* Safari and Chrome */
}

.apply-scholarship-btn {
  border:1px solid #1A124E;
}

.apply-scholarship-btn:hover {
  color:#1A124E;
  background-color: white;
}
</style>
@endsection

@section('content')
@php
use \Jenssegers\Agent\Agent;
$agent = new Agent();
@endphp

<div class="mw-1200 mx-auto pt-80">
  <ul class="nav nav-pills mb-10 mt-4" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link {{$tab == 'university' ? 'active' : ''}} options-toggle" id="pills-orders-tab" data-bs-toggle="pill" data-bs-target="#pills-orders" type="button" role="tab" aria-controls="pills-orders" aria-selected="true">{{ __('lang.uni') }}</button>
    </li>
    <!-- <li class="nav-item" role="presentation">
      <button class="nav-link {{$tab == 'k12' ? 'active' : ''}} options-toggle" id="pills-subscriptions-tab" data-bs-toggle="pill" data-bs-target="#pills-k-12" type="button" role="tab" aria-controls="pills-subscriptions" aria-selected="false">{{ __('lang.k12') }}</button>
    </li> -->
    <li class="nav-item" role="presentation">
      <button class="nav-link {{$tab == 'course' ? 'active' : ''}} options-toggle" id="pills-subscriptions-tab" data-bs-toggle="pill" data-bs-target="#pills-courses" type="button" role="tab" aria-controls="pills-subscriptions" aria-selected="false">{{ __('lang.courses') }}</button>
    </li>
    <!-- <li class="nav-item" role="presentation">
      <button class="nav-link {{$tab == 'scholarship' ? 'active' : ''}} options-toggle" id="pills-subscriptions-tab" data-bs-toggle="pill" data-bs-target="#pills-scholarship" type="button" role="tab" aria-controls="pills-subscriptions" aria-selected="false">{{ __('lang.scholarship') }}</button>
    </li> -->
  </ul>

  <div class="tab-content mb-20" id="pills-tabContent">
    <div class="tab-pane fade {{$tab == 'university' ? 'show active' : ''}} text-white" id="pills-orders" role="tabpanel" aria-labelledby="pills-orders-tab">
      <div class="col-12 d-flex justify-content-between align-items-start">
        @if(!$agent->isMobile())
        <div class="col-3 explore-filter-outer">
          <span class="explore-filter-title d-block">{{ __('lang.courseInfo') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1" aria-expanded="false" aria-controls="kt_accordion_1_body_1">
                    {{ __('lang.fieldOfStudy') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_1" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($study_field) && count($study_field) > 0)
                    @foreach($study_field as $i => $sf)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="study_field" value="{{$sf->c_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$sf->c_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="accordion" id="kt_accordion_2">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_2">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2" aria-expanded="false" aria-controls="kt_accordion_1_body_2">
                    {{ __('lang.major') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_2" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_2">
                  <div class="accordion-body p-0">
                    <div id="sub_cat_list"></div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="accordion" id="kt_accordion_3">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_4" aria-expanded="false" aria-controls="kt_accordion_1_body_4">
                    {{ __('lang.level') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_4" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_3">
                  <div class="accordion-body p-0">
                    @if(isset($level) && count($level) > 0)
                    @foreach($level as $i => $l)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="level" value="{{$i}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$l}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_4">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_5" aria-expanded="false" aria-controls="kt_accordion_1_body_5">
                    {{ __('lang.scholarships') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_5" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_4">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship" value="1" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.none") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship" value="2" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 0 - MYR 5,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship" value="3" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 5,001 - MYR 10,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship" value="4" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 10,001 - MYR 20,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship" value="5" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 20,001 - MYR 30,000
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.location') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_5">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_6" aria-expanded="false" aria-controls="kt_accordion_1_body_6">
                    {{ __('lang.country') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_6" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_5">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="country" value="129" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.malaysia") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="country" value="192" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.singapore") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="country" value="13" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.australia") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="country" value="826" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.unitedKingdom") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="country" value="840" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.unitedStates") }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_6">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_7" aria-expanded="false" aria-controls="kt_accordion_1_body_7">
                    {{ __('lang.state') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_7" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_6">
                  <div class="accordion-body p-0">
                    @if(isset($state) && count($state) > 0)
                    @foreach($state as $s)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="state" value="{{$s->state_id}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$s->state_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_8" aria-expanded="false" aria-controls="kt_accordion_1_body_8">
                    {{ __('lang.environment') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_8" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        Campus Setting
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        Urban Lifestyle
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        On-Campus Accommodation
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_9" aria-expanded="false" aria-controls="kt_accordion_1_body_9">
                    {{ __('lang.publicTransport') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_9" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        By Rail
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        By Bus
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_10" aria-expanded="false" aria-controls="kt_accordion_1_body_10">
                    {{ __('lang.overseasTransfer') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_10" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        None
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        Australia
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        Canada
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        France
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
          <button class="col-12 explore-filter-btn">{{__('lang.resetFilter')}}</button>
        </div>
        @endif
        <div class="col-12 col-lg-9">
          <div class="ms-lg-10">
            <div class="col-12 row mx-auto">
              <div class="filter-width">
                <div class="input-group input-group-solid col-6">
                  <span class="input-group-text explore-search-icon" id="basic-addon2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <path d="M21.0002 21L16.6572 16.657M16.6572 16.657C17.4001 15.9141 17.9894 15.0322 18.3914 14.0615C18.7935 13.0909 19.0004 12.0506 19.0004 11C19.0004 9.94939 18.7935 8.90908 18.3914 7.93845C17.9894 6.96782 17.4001 6.08588 16.6572 5.34299C15.9143 4.6001 15.0324 4.01081 14.0618 3.60877C13.0911 3.20672 12.0508 2.99979 11.0002 2.99979C9.9496 2.99979 8.90929 3.20672 7.93866 3.60877C6.96803 4.01081 6.08609 4.6001 5.34321 5.34299C3.84288 6.84332 3 8.87821 3 11C3 13.1218 3.84288 15.1567 5.34321 16.657C6.84354 18.1573 8.87842 19.0002 11.0002 19.0002C13.122 19.0002 15.1569 18.1573 16.6572 16.657Z" stroke="#999999" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </span>
                  <input type="text" name="search_uni" class="form-control explore-search-uni" placeholder="{{__('lang.searchUni')}}" aria-label="Search universities" aria-describedby="basic-addon2"/>
                </div>
              </div>
              @if(!$agent->isMobile())
              <div style="width:20%">
                <select class="form-select form-select-solid explore-sort sort-uni" data-control="select2" data-placeholder="{{__('lang.sortBy')}}" data-hide-search="true">
                  <option></option>
                  <option value="1">{{__('lang.sortAToZ')}}</option>
                  <option value="2">{{__('lang.sortZToA')}}</option>
                </select>
              </div>
              @else
              <div class="d-flex filter-uni cursor">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin:auto;">
                  <path d="M13.3333 2H2.66667C2.48986 2 2.32029 2.07024 2.19526 2.19526C2.07024 2.32029 2 2.48986 2 2.66667V4.15133L2.00533 4.3C2.03854 4.74515 2.21971 5.16639 2.52 5.49667L5.33333 8.59067V14C5.33331 14.1056 5.35839 14.2098 5.4065 14.3038C5.45461 14.3978 5.52438 14.4791 5.61006 14.5409C5.69574 14.6027 5.79488 14.6432 5.8993 14.6592C6.00373 14.6751 6.11044 14.666 6.21067 14.6327L10.2107 13.2993L10.2827 13.2707C10.3975 13.2169 10.4946 13.1315 10.5626 13.0246C10.6306 12.9176 10.6667 12.7934 10.6667 12.6667V8.276L13.414 5.52933C13.5999 5.3435 13.7473 5.12285 13.8479 4.87999C13.9484 4.63714 14.0001 4.37685 14 4.114V2.66667C14 2.48986 13.9298 2.32029 13.8047 2.19526C13.6797 2.07024 13.5101 2 13.3333 2Z" fill="#999999"/>
                </svg>
              </div>
              @endif
            </div>
            <div id="uni_list"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-pane fade {{$tab == 'k12' ? 'show active' : ''}} text-white" id="pills-k-12" role="tabpanel" aria-labelledby="pills-orders-tab">
      <div class="col-12 d-flex justify-content-between align-items-start">
        @if(!$agent->isMobile())
        <div class="col-3 explore-filter-outer">
          <span class="explore-filter-title d-block">{{ __('lang.courseInfo') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_13" aria-expanded="false" aria-controls="kt_accordion_1_body_13">
                    {{ __('lang.level') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_13" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($k12Level) && count($k12Level) > 0)
                    @foreach($k12Level as $i => $l)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_level" value="{{$i}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$l}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_14" aria-expanded="false" aria-controls="kt_accordion_1_body_14">
                    {{ __('lang.scholarships') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_14" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="1" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.none") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="2" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 0 - MYR 5,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="3" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 5,001 - MYR 10,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="4" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 10,001 - MYR 20,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="5" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 20,001 - MYR 30,000
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.location') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_19" aria-expanded="false" aria-controls="kt_accordion_1_body_19">
                    {{ __('lang.country') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_19" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_country" value="129" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.malaysia") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_country" value="192" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.singapore") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_country" value="13" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.australia") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_country" value="826" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.unitedKingdom") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_country" value="840" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.unitedStates") }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_20" aria-expanded="false" aria-controls="kt_accordion_1_body_20">
                    {{ __('lang.state') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_20" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($state) && count($state) > 0)
                    @foreach($state as $i => $s)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_state" value="{{$s->state_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$s->state_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button class="col-12 explore-filter-btn">{{__('lang.resetFilter')}}</button>
        </div>
        @endif
        <div class="col-12 col-lg-9">
          <div class="ms-lg-10">
            <div class="col-12 row mx-auto">
              <div class="filter-width">
                <div class="input-group input-group-solid col-6">
                  <span class="input-group-text explore-search-icon" id="basic-addon2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <path d="M21.0002 21L16.6572 16.657M16.6572 16.657C17.4001 15.9141 17.9894 15.0322 18.3914 14.0615C18.7935 13.0909 19.0004 12.0506 19.0004 11C19.0004 9.94939 18.7935 8.90908 18.3914 7.93845C17.9894 6.96782 17.4001 6.08588 16.6572 5.34299C15.9143 4.6001 15.0324 4.01081 14.0618 3.60877C13.0911 3.20672 12.0508 2.99979 11.0002 2.99979C9.9496 2.99979 8.90929 3.20672 7.93866 3.60877C6.96803 4.01081 6.08609 4.6001 5.34321 5.34299C3.84288 6.84332 3 8.87821 3 11C3 13.1218 3.84288 15.1567 5.34321 16.657C6.84354 18.1573 8.87842 19.0002 11.0002 19.0002C13.122 19.0002 15.1569 18.1573 16.6572 16.657Z" stroke="#999999" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </span>
                  <input type="text" name="k_12_search" class="form-control explore-search-uni" placeholder="{{__('lang.searchK12')}}" aria-label="Search K-12 Study" aria-describedby="basic-addon2"/>
                </div>
              </div>
              @if(!$agent->isMobile())
              <div style="width:20%">
                <select class="form-select form-select-solid explore-sort sort-k12" data-control="select2" data-placeholder="{{__('lang.sortBy')}}" data-hide-search="true">
                  <option></option>
                  <option value="1">{{__('lang.sortAToZ')}}</option>
                  <option value="2">{{__('lang.sortZToA')}}</option>
                </select>
              </div>
              @else
              <div class="d-flex filter-k12 cursor">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin:auto;">
                  <path d="M13.3333 2H2.66667C2.48986 2 2.32029 2.07024 2.19526 2.19526C2.07024 2.32029 2 2.48986 2 2.66667V4.15133L2.00533 4.3C2.03854 4.74515 2.21971 5.16639 2.52 5.49667L5.33333 8.59067V14C5.33331 14.1056 5.35839 14.2098 5.4065 14.3038C5.45461 14.3978 5.52438 14.4791 5.61006 14.5409C5.69574 14.6027 5.79488 14.6432 5.8993 14.6592C6.00373 14.6751 6.11044 14.666 6.21067 14.6327L10.2107 13.2993L10.2827 13.2707C10.3975 13.2169 10.4946 13.1315 10.5626 13.0246C10.6306 12.9176 10.6667 12.7934 10.6667 12.6667V8.276L13.414 5.52933C13.5999 5.3435 13.7473 5.12285 13.8479 4.87999C13.9484 4.63714 14.0001 4.37685 14 4.114V2.66667C14 2.48986 13.9298 2.32029 13.8047 2.19526C13.6797 2.07024 13.5101 2 13.3333 2Z" fill="#999999"/>
                </svg>
              </div>
              @endif
            </div>
            <div id="k12_list"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-pane fade {{$tab == 'course' ? 'show active' : ''}} text-white" id="pills-courses" role="tabpanel" aria-labelledby="pills-orders-tab">
      <div class="col-12 d-flex justify-content-between align-items-start">
        @if(!$agent->isMobile())
        <div class="col-3 explore-filter-outer">
          <span class="explore-filter-title d-block">{{ __('lang.courseInfo') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_11" aria-expanded="false" aria-controls="kt_accordion_1_body_11">
                    {{ __('lang.fieldOfStudy') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_11" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($study_field) && count($study_field) > 0)
                    @foreach($study_field as $i => $sf)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="major_study_field" value="{{$sf->c_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$sf->c_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_12" aria-expanded="false" aria-controls="kt_accordion_1_body_12">
                    {{ __('lang.major') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_12" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div id="major_sub_cat_list"></div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_13" aria-expanded="false" aria-controls="kt_accordion_1_body_13">
                    {{ __('lang.level') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_13" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($level) && count($level) > 0)
                    @foreach($level as $i => $l)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_level" value="{{$i}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$l}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_14" aria-expanded="false" aria-controls="kt_accordion_1_body_14">
                    {{ __('lang.scholarships') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_14" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="1" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.none") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="2" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 0 - MYR 5,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="3" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 5,001 - MYR 10,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="4" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 10,001 - MYR 20,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="5" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 20,001 - MYR 30,000
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.financialsTiming') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_15" aria-expanded="false" aria-controls="kt_accordion_1_body_15">
                    {{ __('lang.feeBudget') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_15" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="1" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.below") }} MYR 20,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="2" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 20,001 - MYR 40,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="3" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 40,001 - MYR 60,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="4" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 60,001 - MYR 80,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="5" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 80,001 - MYR 100,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="6" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.above") }} MYR 100,000
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_16" aria-expanded="false" aria-controls="kt_accordion_1_body_16">
                    {{ __('lang.fullTimeDuration') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_16" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_duration" value="1" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        1 {{ __("lang.year") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_duration" value="2" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        2 {{ __("lang.year") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_duration" value="3" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        3 {{ __("lang.year") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_duration" value="4" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        4 {{ __("lang.year") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_duration" value="5" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        5 {{ __("lang.year") }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_17" aria-expanded="false" aria-controls="kt_accordion_1_body_17">
                    {{ __('lang.paymentSchemes') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_17" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        Monthly
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        Bi Monthly
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        Semester
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        Annual
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_18" aria-expanded="false" aria-controls="kt_accordion_1_body_18">
                    {{ __('lang.partTimeOption') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_18" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        Yes
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        No
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_24" aria-expanded="false" aria-controls="kt_accordion_1_body_24">
                    {{ __('lang.intakeMonth') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_24" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($month) && count($month) > 0)
                    @foreach($month as $i => $m)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_month" value="{{$i}}" id="flexCheckDefault{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault{{$i}}">
                        {{$m}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_25" aria-expanded="false" aria-controls="kt_accordion_1_body_25">
                    {{ __('lang.durationAbroad') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_25" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        None
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        0 - 1 Year
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        1 - 2 Years
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        2 - 3 Year
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="separator my-5"></div>
          </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.location') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_19" aria-expanded="false" aria-controls="kt_accordion_1_body_19">
                    {{ __('lang.country') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_19" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_country" value="129" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.malaysia") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_country" value="192" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.singapore") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_country" value="13" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.australia") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_country" value="826" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.unitedKingdom") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_country" value="840" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.unitedStates") }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_20" aria-expanded="false" aria-controls="kt_accordion_1_body_20">
                    {{ __('lang.state') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_20" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($state) && count($state) > 0)
                    @foreach($state as $i => $s)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_state" value="{{$s->state_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$s->state_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button class="col-12 explore-filter-btn">{{ __('lang.resetFilter') }}</button>
        </div>
        @endif
        <div class="col-12 col-lg-9">
          <div class="ms-lg-10">
            <div class="col-12 row mx-auto">
              <div class="filter-width">
                <div class="input-group input-group-solid col-6">
                  <span class="input-group-text explore-search-icon" id="basic-addon2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <path d="M21.0002 21L16.6572 16.657M16.6572 16.657C17.4001 15.9141 17.9894 15.0322 18.3914 14.0615C18.7935 13.0909 19.0004 12.0506 19.0004 11C19.0004 9.94939 18.7935 8.90908 18.3914 7.93845C17.9894 6.96782 17.4001 6.08588 16.6572 5.34299C15.9143 4.6001 15.0324 4.01081 14.0618 3.60877C13.0911 3.20672 12.0508 2.99979 11.0002 2.99979C9.9496 2.99979 8.90929 3.20672 7.93866 3.60877C6.96803 4.01081 6.08609 4.6001 5.34321 5.34299C3.84288 6.84332 3 8.87821 3 11C3 13.1218 3.84288 15.1567 5.34321 16.657C6.84354 18.1573 8.87842 19.0002 11.0002 19.0002C13.122 19.0002 15.1569 18.1573 16.6572 16.657Z" stroke="#999999" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </span>
                  <input type="text" name="course_search" class="form-control explore-search-uni" placeholder="{{__('lang.searchCourses')}}" aria-label="Search courses" aria-describedby="basic-addon2"/>
                </div>
              </div>
              @if(!$agent->isMobile())
              <div style="width:20%">
                <select class="form-select form-select-solid explore-sort sort-courses" data-control="select2" data-placeholder="{{__('lang.price')}}" data-hide-search="true">
                  <option></option>
                  <option value="1">{{ __('lang.sortLowestToHighest') }}</option>
                  <option value="2">{{ __('lang.sortHighestToLowest') }}</option>
                </select>
              </div>
              @else
              <div class="d-flex filter-course cursor">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin:auto;">
                  <path d="M13.3333 2H2.66667C2.48986 2 2.32029 2.07024 2.19526 2.19526C2.07024 2.32029 2 2.48986 2 2.66667V4.15133L2.00533 4.3C2.03854 4.74515 2.21971 5.16639 2.52 5.49667L5.33333 8.59067V14C5.33331 14.1056 5.35839 14.2098 5.4065 14.3038C5.45461 14.3978 5.52438 14.4791 5.61006 14.5409C5.69574 14.6027 5.79488 14.6432 5.8993 14.6592C6.00373 14.6751 6.11044 14.666 6.21067 14.6327L10.2107 13.2993L10.2827 13.2707C10.3975 13.2169 10.4946 13.1315 10.5626 13.0246C10.6306 12.9176 10.6667 12.7934 10.6667 12.6667V8.276L13.414 5.52933C13.5999 5.3435 13.7473 5.12285 13.8479 4.87999C13.9484 4.63714 14.0001 4.37685 14 4.114V2.66667C14 2.48986 13.9298 2.32029 13.8047 2.19526C13.6797 2.07024 13.5101 2 13.3333 2Z" fill="#999999"/>
                </svg>
              </div>
              @endif
            </div>
            <div id="course_list"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="tab-pane fade {{$tab == 'scholarship' ? 'show active' : ''}} text-white" id="pills-scholarship" role="tabpanel" aria-labelledby="pills-orders-tab">
      <div class="col-12 d-flex justify-content-between align-items-start">
        @if(!$agent->isMobile())
        <div class="col-3 explore-filter-outer">
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.location') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_31" aria-expanded="false" aria-controls="kt_accordion_1_body_31">
                    {{ __('lang.country') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_31" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_country" value="129" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.malaysia") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_country" value="192" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.singapore") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_country" value="13" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.australia") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_country" value="826" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.unitedKingdom") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_country" value="840" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.unitedStates") }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_32" aria-expanded="false" aria-controls="kt_accordion_1_body_32">
                    {{ __('lang.state') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_32" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($state) && count($state) > 0)
                    @foreach($state as $i => $s)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_state" value="{{$s->state_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$s->state_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_33" aria-expanded="false" aria-controls="kt_accordion_1_body_33">
                    {{ __('lang.institution') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_33" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($uni) && count($uni) > 0)
                    @foreach($uni as $i => $u)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_uni" type="checkbox" value="{{$u->u_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$u->u_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.courseInfo') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_34" aria-expanded="false" aria-controls="kt_accordion_1_body_34">
                    {{ __('lang.fieldOfStudy') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_34" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($study_field) && count($study_field) > 0)
                    @foreach($study_field as $i => $sf)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_study_field" value="{{$sf->c_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$sf->c_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_35" aria-expanded="false" aria-controls="kt_accordion_1_body_35">
                    {{ __('lang.major') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_35" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div id="scholarship_sub_cat_list"></div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_36" aria-expanded="false" aria-controls="kt_accordion_1_body_36">
                    {{ __('lang.level') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_36" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($level) && count($level) > 0)
                    @foreach($level as $i => $l)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_level" value="{{$i}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$l}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.offerDeals') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_42" aria-expanded="false" aria-controls="kt_accordion_1_body_42">
                    {{ __('lang.sponsor') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_42" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($sponser) && count($sponser) > 0)
                    @foreach($sponser as $i => $s)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_sponsor" type="checkbox" value="{{$i}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$s}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_3_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_3_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_1_body_42" aria-expanded="false" aria-controls="kt_accordion_3_1_body_42">
                    {{ __('lang.type') }}
                  </button>
                </h2>
                <div id="kt_accordion_3_1_body_42" class="accordion-collapse collapse" aria-labelledby="kt_accordion_3_1_header_1" data-bs-parent="#kt_accordion_3_1">
                  <div class="accordion-body p-0">
                    @if(isset($type) && count($type) > 0)
                    @foreach($type as $i => $t)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_type" type="checkbox" value="{{$i}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$t}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_3_3">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_3_3_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_3_body_42" aria-expanded="false" aria-controls="kt_accordion_3_3_body_42">
                    {{ __('lang.coverage') }}
                  </button>
                </h2>
                <div id="kt_accordion_3_3_body_42" class="accordion-collapse collapse" aria-labelledby="kt_accordion_3_3_header_1" data-bs-parent="#kt_accordion_3_3">
                  <div class="accordion-body p-0">
                    @if(isset($coverage) && count($coverage) > 0)
                    @foreach($coverage as $i => $c)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_coverage" type="checkbox" value="{{$i}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$c}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_3_4">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_3_4_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_4_body_42" aria-expanded="false" aria-controls="kt_accordion_3_4_body_42">
                    {{ __('lang.requirements') }}
                  </button>
                </h2>
                <div id="kt_accordion_3_4_body_42" class="accordion-collapse collapse" aria-labelledby="kt_accordion_3_4_header_1" data-bs-parent="#kt_accordion_3_4">
                  <div class="accordion-body p-0">
                    @if(isset($requirement) && count($requirement) > 0)
                    @foreach($requirement as $i => $r)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_requirement" type="checkbox" value="{{$i}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$r}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_3_2">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_3_2_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_2_body_42" aria-expanded="false" aria-controls="kt_accordion_3_2_body_42">
                    {{ __('lang.interviewRequired') }}
                  </button>
                </h2>
                <div id="kt_accordion_3_2_body_42" class="accordion-collapse collapse" aria-labelledby="kt_accordion_3_2_header_1" data-bs-parent="#kt_accordion_3_2">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_interview" type="checkbox" value="1" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.yes") }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_interview" type="checkbox" value="0" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.no") }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <button class="col-12 explore-filter-btn">{{ __('lang.resetFilter') }}</button>
        </div>
        @endif
        <div class="col-12 col-lg-9">
          <div class="ms-lg-10">
            <div class="col-12 row mx-auto">
              <div class="filter-width">
                <div class="input-group input-group-solid col-6">
                  <span class="input-group-text explore-search-icon" id="basic-addon2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                      <path d="M21.0002 21L16.6572 16.657M16.6572 16.657C17.4001 15.9141 17.9894 15.0322 18.3914 14.0615C18.7935 13.0909 19.0004 12.0506 19.0004 11C19.0004 9.94939 18.7935 8.90908 18.3914 7.93845C17.9894 6.96782 17.4001 6.08588 16.6572 5.34299C15.9143 4.6001 15.0324 4.01081 14.0618 3.60877C13.0911 3.20672 12.0508 2.99979 11.0002 2.99979C9.9496 2.99979 8.90929 3.20672 7.93866 3.60877C6.96803 4.01081 6.08609 4.6001 5.34321 5.34299C3.84288 6.84332 3 8.87821 3 11C3 13.1218 3.84288 15.1567 5.34321 16.657C6.84354 18.1573 8.87842 19.0002 11.0002 19.0002C13.122 19.0002 15.1569 18.1573 16.6572 16.657Z" stroke="#999999" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                  </span>
                  <input type="text" name="search_scholarship" class="form-control explore-search-uni" placeholder="{{__('lang.searchScholarship')}}" aria-label="Search courses" aria-describedby="basic-addon2"/>
                </div>
              </div>
              @if(!$agent->isMobile())
              <div style="width:20%">
                <select class="form-select form-select-solid explore-sort sort-scholarship" data-control="select2" data-placeholder="{{__('lang.sortBy')}}" data-hide-search="true">
                  <option></option>
                  <option value="1">{{ __('lang.scholarshipHighPer') }}</option>
                  <option value="2">{{ __('lang.scholarshipLowPer') }}</option>
                  <option value="3">{{ __('lang.scholarshipHighAmt') }}</option>
                  <option value="4">{{ __('lang.scholarshipLowAmt') }}</option>
                </select>
              </div>
              @else
              <div class="d-flex filter-scholarship cursor">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin:auto;">
                  <path d="M13.3333 2H2.66667C2.48986 2 2.32029 2.07024 2.19526 2.19526C2.07024 2.32029 2 2.48986 2 2.66667V4.15133L2.00533 4.3C2.03854 4.74515 2.21971 5.16639 2.52 5.49667L5.33333 8.59067V14C5.33331 14.1056 5.35839 14.2098 5.4065 14.3038C5.45461 14.3978 5.52438 14.4791 5.61006 14.5409C5.69574 14.6027 5.79488 14.6432 5.8993 14.6592C6.00373 14.6751 6.11044 14.666 6.21067 14.6327L10.2107 13.2993L10.2827 13.2707C10.3975 13.2169 10.4946 13.1315 10.5626 13.0246C10.6306 12.9176 10.6667 12.7934 10.6667 12.6667V8.276L13.414 5.52933C13.5999 5.3435 13.7473 5.12285 13.8479 4.87999C13.9484 4.63714 14.0001 4.37685 14 4.114V2.66667C14 2.48986 13.9298 2.32029 13.8047 2.19526C13.6797 2.07024 13.5101 2 13.3333 2Z" fill="#999999"/>
                </svg>
              </div>
              @endif
            </div>
            <div id="scholarship_list"></div>
            <!-- <div class="col-12 row justify-content-between m-0">
              <div class="col-6 mt-5">
                <div class="col-12 explore-course-outter">
                  <img class="course-uni-img" src="/images/assets/uni_img_2.png">
                  <div>
                    <span class="d-block uni-name pt-5">Sunway Jeffrey Cheah Entrance Scholarship - ACCA/ICAEW</span>
                    <span class="uni-location d-block">Sunway University</span>
                    <div class="separator my-5"></div>
                    <span class="uni-location d-block">{{ __('lang.applicableFor') }}</span>
                    <span class="uni-total d-block pt-2">Professional Programme, ACCA, ICAEW</span>
                    <div class="separator my-5"></div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.interviewRequired') }}</span>
                      <span class="uni-total">No</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.approval') }}</span>
                      <span class="uni-total">Instant</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.requirements') }}</span>
                      <span class="uni-total">Merit</span>
                    </div>
                    <div class="separator my-5"></div>
                    <span class="uni-total d-block text-end">{{ __('lang.feesWaiver') }}<span class="ttl-amt"> 100%</span></span>
                    <button class="w-100 mt-10 apply-scholarship-btn">Pathway360 - Applicable</button>
                  </div>
                </div>
              </div>
              <div class="col-6 mt-5">
                <div class="col-12 explore-course-outter">
                  <img class="course-uni-img" src="/images/assets/uni_img_2.png">
                  <div>
                    <span class="d-block uni-name pt-5">Sunway Jeffrey Cheah Entrance Scholarship - ACCA/ICAEW</span>
                    <span class="uni-location d-block">Sunway University</span>
                    <div class="separator my-5"></div>
                    <span class="uni-location d-block">{{ __('lang.applicableFor') }}</span>
                    <span class="uni-total d-block pt-2">Professional Programme, ACCA, ICAEW</span>
                    <div class="separator my-5"></div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.interviewRequired') }}</span>
                      <span class="uni-total">No</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.approval') }}</span>
                      <span class="uni-total">Instant</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.requirements') }}</span>
                      <span class="uni-total">Merit</span>
                    </div>
                    <div class="separator my-5"></div>
                    <span class="uni-total d-block text-end">{{ __('lang.feesWaiver') }}<span class="ttl-amt"> 100%</span></span>
                    <button class="w-100 mt-10 apply-scholarship-btn">Pathway360 - Applicable</button>
                  </div>
                </div>
              </div>
              <div class="col-6 mt-5">
                <div class="col-12 explore-course-outter">
                  <img class="course-uni-img" src="/images/assets/uni_img_2.png">
                  <div>
                    <span class="d-block uni-name pt-5">Sunway Jeffrey Cheah Entrance Scholarship - ACCA/ICAEW</span>
                    <span class="uni-location d-block">Sunway University</span>
                    <div class="separator my-5"></div>
                    <span class="uni-location d-block">{{ __('lang.applicableFor') }}</span>
                    <span class="uni-total d-block pt-2">Professional Programme, ACCA, ICAEW</span>
                    <div class="separator my-5"></div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.interviewRequired') }}</span>
                      <span class="uni-total">No</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.approval') }}</span>
                      <span class="uni-total">Instant</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.requirements') }}</span>
                      <span class="uni-total">Merit</span>
                    </div>
                    <div class="separator my-5"></div>
                    <span class="uni-total d-block text-end">{{ __('lang.feesWaiver') }}<span class="ttl-amt"> 100%</span></span>
                    <button class="w-100 mt-10 apply-scholarship-btn">Pathway360 - Applicable</button>
                  </div>
                </div>
              </div>
              <div class="col-6 mt-5">
                <div class="col-12 explore-course-outter">
                  <img class="course-uni-img" src="/images/assets/uni_img_2.png">
                  <div>
                    <span class="d-block uni-name pt-5">Sunway Jeffrey Cheah Entrance Scholarship - ACCA/ICAEW</span>
                    <span class="uni-location d-block">Sunway University</span>
                    <div class="separator my-5"></div>
                    <span class="uni-location d-block">{{ __('lang.applicableFor') }}</span>
                    <span class="uni-total d-block pt-2">Professional Programme, ACCA, ICAEW</span>
                    <div class="separator my-5"></div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.interviewRequired') }}</span>
                      <span class="uni-total">No</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.approval') }}</span>
                      <span class="uni-total">Instant</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.requirements') }}</span>
                      <span class="uni-total">Merit</span>
                    </div>
                    <div class="separator my-5"></div>
                    <span class="uni-total d-block text-end">{{ __('lang.feesWaiver') }}<span class="ttl-amt"> 100%</span></span>
                    <button class="w-100 mt-10 apply-scholarship-btn">Pathway360 - Applicable</button>
                  </div>
                </div>
              </div>
            </div> -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <div id="kt_drawer_example_basic" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-toggle=".filter-uni" data-kt-drawer-close="#kt_drawer_example_basic_close" data-kt-drawer-width="{default:'100%', 'md': '500px'}">

    <div class="col-12 explore-filter-outer">
    <div class="text-end me-5" data-kt-drawer-dismiss="true">
		  <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
			  <path d="M1 1L13 13" stroke="#9C9C9C" stroke-linecap="round"/>
			  <path d="M13 1L0.999999 13" stroke="#9C9C9C" stroke-linecap="round"/>
		  </svg>
	  </div>
      <span class="explore-filter-title d-block">{{ __('lang.courseInfo') }}</span>
      <div class="col-12 mb-5">
        <div class="accordion" id="kt_accordion_1">
          <div class="accordion-item">
            <h2 class="accordion-header" id="kt_accordion_1_header_1">
              <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1" aria-expanded="false" aria-controls="kt_accordion_1_body_1">
                {{ __('lang.fieldOfStudy') }}
              </button>
            </h2>
            <div id="kt_accordion_1_body_1" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
              <div class="accordion-body p-0">
                @if(isset($study_field) && count($study_field) > 0)
                @foreach($study_field as $i => $sf)
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="study_field" value="{{$sf->c_id}}" id="flexCheckDefault_{{$i}}" />
                  <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                    {{$sf->c_name}}
                  </label>
                </div>
                @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>
        <!-- <div class="accordion" id="kt_accordion_2">
          <div class="accordion-item">
            <h2 class="accordion-header" id="kt_accordion_1_header_2">
              <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2" aria-expanded="false" aria-controls="kt_accordion_1_body_2">
                  {{ __('lang.major') }}
              </button>
            </h2>
            <div id="kt_accordion_1_body_2" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_2">
              <div class="accordion-body p-0">
                <div id="sub_cat_list"></div>
              </div>
            </div>
          </div>
        </div> -->
        <div class="accordion" id="kt_accordion_3">
          <div class="accordion-item">
            <h2 class="accordion-header" id="kt_accordion_1_header_1">
              <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_4" aria-expanded="false" aria-controls="kt_accordion_1_body_4">
                    {{ __('lang.level') }}
              </button>
            </h2>
            <div id="kt_accordion_1_body_4" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_3">
              <div class="accordion-body p-0">
                    @if(isset($level) && count($level) > 0)
                    @foreach($level as $i => $l)
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="level" value="{{$i}}" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                        {{$l}}
                  </label>
                </div>
                    @endforeach
                    @endif
              </div>
            </div>
          </div>
        </div>
        <div class="accordion" id="kt_accordion_4">
          <div class="accordion-item">
            <h2 class="accordion-header" id="kt_accordion_1_header_1">
              <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_5" aria-expanded="false" aria-controls="kt_accordion_1_body_5">
                    {{ __('lang.scholarships') }}
              </button>
            </h2>
            <div id="kt_accordion_1_body_5" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_4">
              <div class="accordion-body p-0">
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="scholarship" value="1" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                    {{ __('lang.none') }}
                  </label>
                </div>
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="scholarship" value="2" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                        MYR 0 - MYR 5,000
                  </label>
                </div>
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="scholarship" value="3" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                        MYR 5,001 - MYR 10,000
                  </label>
                </div>
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="scholarship" value="4" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                        MYR 10,001 - MYR 20,000
                  </label>
                </div>
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="scholarship" value="5" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                        MYR 20,001 - MYR 30,000
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="separator my-5"></div>
      </div>
      <span class="pt-3 explore-filter-title d-block">{{ __('lang.location') }}</span>
      <div class="col-12 mb-5">
        <div class="accordion" id="kt_accordion_5">
          <div class="accordion-item">
            <h2 class="accordion-header" id="kt_accordion_1_header_1">
              <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_6" aria-expanded="false" aria-controls="kt_accordion_1_body_6">
                    {{ __('lang.country') }}
              </button>
            </h2>
            <div id="kt_accordion_1_body_6" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_5">
              <div class="accordion-body p-0">
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="country" value="129" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                    {{ __('lang.malaysia') }}
                  </label>
                </div>
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="country" value="192" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                    {{ __('lang.singapore') }}
                  </label>
                </div>
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="country" value="13" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                    {{ __('lang.australia') }}
                  </label>
                </div>
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="country" value="826" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                    {{ __('lang.unitedKingdom') }}
                  </label>
                </div>
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="country" value="840" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                    {{ __('lang.unitedStates') }}
                  </label>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="accordion" id="kt_accordion_6">
          <div class="accordion-item">
            <h2 class="accordion-header" id="kt_accordion_1_header_1">
              <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_7" aria-expanded="false" aria-controls="kt_accordion_1_body_7">
                    {{ __('lang.state') }}
              </button>
            </h2>
            <div id="kt_accordion_1_body_7" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_6">
              <div class="accordion-body p-0">
                    @if(isset($state) && count($state) > 0)
                    @foreach($state as $s)
                <div class="form-check form-check-custom  form-check-sm mb-5">
                  <input class="form-check-input" type="checkbox" name="state" value="{{$s->state_id}}" id="flexCheckDefault" />
                  <label class="form-check-label" for="flexCheckDefault">
                        {{$s->state_name}}
                  </label>
                </div>
                    @endforeach
                    @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      <button class="col-12 explore-filter-btn">{{ __('lang.resetFilter') }}</button>
      <div class="pb-5"></div>
    </div>
  </div>
  <div id="kt_drawer_example_basic" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-toggle=".filter-course" data-kt-drawer-close="#kt_drawer_example_basic_close" data-kt-drawer-width="{default:'100%', 'md': '500px'}">
    <div class="col-12 explore-filter-outer">
    <div class="text-end me-5" data-kt-drawer-dismiss="true">
		  <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
			  <path d="M1 1L13 13" stroke="#9C9C9C" stroke-linecap="round"/>
			  <path d="M13 1L0.999999 13" stroke="#9C9C9C" stroke-linecap="round"/>
		  </svg>
	  </div>
          <span class="explore-filter-title d-block">{{ __('lang.courseInfo') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_11" aria-expanded="false" aria-controls="kt_accordion_1_body_11">
                    {{ __('lang.fieldOfStudy') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_11" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($study_field) && count($study_field) > 0)
                    @foreach($study_field as $i => $sf)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="major_study_field" value="{{$sf->c_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$sf->c_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_12" aria-expanded="false" aria-controls="kt_accordion_1_body_12">
                    {{ __('lang.major') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_12" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div id="major_sub_cat_list"></div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_13" aria-expanded="false" aria-controls="kt_accordion_1_body_13">
                    {{ __('lang.level') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_13" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($level) && count($level) > 0)
                    @foreach($level as $i => $l)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_level" value="{{$i}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$l}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_14" aria-expanded="false" aria-controls="kt_accordion_1_body_14">
                    {{ __('lang.scholarships') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_14" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="1" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.none') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="2" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 0 - MYR 5,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="3" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 5,001 - MYR 10,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="4" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 10,001 - MYR 20,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_scholarship" value="5" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 20,001 - MYR 30,000
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.financialsTiming') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_15" aria-expanded="false" aria-controls="kt_accordion_1_body_15">
                    {{ __('lang.feeBudget') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_15" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="1" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __("lang.below") }} MYR 20,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="2" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 20,001 - MYR 40,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="3" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 40,001 - MYR 60,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="4" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 60,001 - MYR 80,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="5" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 80,001 - MYR 100,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_fee" value="6" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.above') }} MYR 100,000
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_16" aria-expanded="false" aria-controls="kt_accordion_1_body_16">
                    {{ __('lang.fullTimeDuration') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_16" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_duration" value="1" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        1 {{ __('lang.year') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_duration" value="2" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        2 {{ __('lang.year') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_duration" value="3" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        3 {{ __('lang.year') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_duration" value="4" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        4 {{ __('lang.year') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_duration" value="5" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        5 {{ __('lang.year') }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_24" aria-expanded="false" aria-controls="kt_accordion_1_body_24">
                    {{ __('lang.intakeMonth') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_24" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($month) && count($month) > 0)
                    @foreach($month as $i => $m)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_month" value="{{$i}}" id="flexCheckDefault{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault{{$i}}">
                        {{$m}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.location') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_19" aria-expanded="false" aria-controls="kt_accordion_1_body_19">
                    {{ __('lang.country') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_19" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_country" value="129" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.malaysia') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_country" value="192" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.singapore') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_country" value="13" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.australia') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_country" value="826" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.unitedKingdom') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_country" value="840" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.unitedStates') }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_20" aria-expanded="false" aria-controls="kt_accordion_1_body_20">
                    {{ __('lang.state') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_20" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($state) && count($state) > 0)
                    @foreach($state as $i => $s)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="course_state" value="{{$s->state_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$s->state_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button class="col-12 explore-filter-btn">{{ __('lang.resetFilter') }}</button>
          <div class="pb-5"></div>
        </div>
  </div>
  <div id="kt_drawer_example_basic" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-toggle=".filter-scholarship" data-kt-drawer-close="#kt_drawer_example_basic_close" data-kt-drawer-width="{default:'100%', 'md': '500px'}">
    <div class="col-12 explore-filter-outer">
    <div class="text-end me-5" data-kt-drawer-dismiss="true">
		  <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
			  <path d="M1 1L13 13" stroke="#9C9C9C" stroke-linecap="round"/>
			  <path d="M13 1L0.999999 13" stroke="#9C9C9C" stroke-linecap="round"/>
		  </svg>
	  </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.location') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_31" aria-expanded="false" aria-controls="kt_accordion_1_body_31">
                    {{ __('lang.country') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_31" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_country" value="129" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.malaysia') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_country" value="192" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.singapore') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_country" value="13" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.australia') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_country" value="826" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.unitedKingdom') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_country" value="840" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.unitedStates') }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_32" aria-expanded="false" aria-controls="kt_accordion_1_body_32">
                    {{ __('lang.state') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_32" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($state) && count($state) > 0)
                    @foreach($state as $i => $s)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_state" value="{{$s->state_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$s->state_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_33" aria-expanded="false" aria-controls="kt_accordion_1_body_33">
                    {{ __('lang.institution') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_33" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($uni) && count($uni) > 0)
                    @foreach($uni as $i => $u)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_uni" type="checkbox" value="{{$u->u_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$u->u_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.courseInfo') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_34" aria-expanded="false" aria-controls="kt_accordion_1_body_34">
                    {{ __('lang.fieldOfStudy') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_34" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($study_field) && count($study_field) > 0)
                    @foreach($study_field as $i => $sf)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_study_field" value="{{$sf->c_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$sf->c_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <!-- <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_35" aria-expanded="false" aria-controls="kt_accordion_1_body_35">
                    {{ __('lang.major') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_35" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div id="scholarship_sub_cat_list"></div>
                  </div>
                </div>
              </div>
            </div> -->
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_36" aria-expanded="false" aria-controls="kt_accordion_1_body_36">
                    {{ __('lang.level') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_36" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($level) && count($level) > 0)
                    @foreach($level as $i => $l)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="scholarship_level" value="{{$i}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$l}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.offerDeals') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_42" aria-expanded="false" aria-controls="kt_accordion_1_body_42">
                    {{ __('lang.sponsor') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_42" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($sponser) && count($sponser) > 0)
                    @foreach($sponser as $i => $s)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_sponsor" type="checkbox" value="{{$i}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$s}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_3_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_3_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_1_body_42" aria-expanded="false" aria-controls="kt_accordion_3_1_body_42">
                    {{ __('lang.type') }}
                  </button>
                </h2>
                <div id="kt_accordion_3_1_body_42" class="accordion-collapse collapse" aria-labelledby="kt_accordion_3_1_header_1" data-bs-parent="#kt_accordion_3_1">
                  <div class="accordion-body p-0">
                    @if(isset($type) && count($type) > 0)
                    @foreach($type as $i => $t)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_type" type="checkbox" value="{{$i}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$t}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_3_3">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_3_3_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_3_body_42" aria-expanded="false" aria-controls="kt_accordion_3_3_body_42">
                    {{ __('lang.coverage') }}
                  </button>
                </h2>
                <div id="kt_accordion_3_3_body_42" class="accordion-collapse collapse" aria-labelledby="kt_accordion_3_3_header_1" data-bs-parent="#kt_accordion_3_3">
                  <div class="accordion-body p-0">
                    @if(isset($coverage) && count($coverage) > 0)
                    @foreach($coverage as $i => $c)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_coverage" type="checkbox" value="{{$i}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$c}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_3_4">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_3_4_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_4_body_42" aria-expanded="false" aria-controls="kt_accordion_3_4_body_42">
                    {{ __('lang.requirements') }}
                  </button>
                </h2>
                <div id="kt_accordion_3_4_body_42" class="accordion-collapse collapse" aria-labelledby="kt_accordion_3_4_header_1" data-bs-parent="#kt_accordion_3_4">
                  <div class="accordion-body p-0">
                    @if(isset($requirement) && count($requirement) > 0)
                    @foreach($requirement as $i => $r)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_requirement" type="checkbox" value="{{$i}}" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{$r}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_3_2">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_3_2_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed text-capitalize" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_2_body_42" aria-expanded="false" aria-controls="kt_accordion_3_2_body_42">
                    {{ __('lang.interviewRequired') }}
                  </button>
                </h2>
                <div id="kt_accordion_3_2_body_42" class="accordion-collapse collapse" aria-labelledby="kt_accordion_3_2_header_1" data-bs-parent="#kt_accordion_3_2">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_interview" type="checkbox" value="1" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.yes') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" name="scholarship_interview" type="checkbox" value="0" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.no') }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <button class="col-12 explore-filter-btn">{{ __('lang.resetFilter') }}</button>
          <div class="pb-5"></div>
        </div>
  </div>
  <div id="kt_drawer_example_basic" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-toggle=".filter-k12" data-kt-drawer-close="#kt_drawer_example_basic_close" data-kt-drawer-width="{default:'100%', 'md': '500px'}">
    <div class="col-12 explore-filter-outer">
    <div class="text-end me-5" data-kt-drawer-dismiss="true">
		  <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
			  <path d="M1 1L13 13" stroke="#9C9C9C" stroke-linecap="round"/>
			  <path d="M13 1L0.999999 13" stroke="#9C9C9C" stroke-linecap="round"/>
		  </svg>
	  </div>
          <span class="explore-filter-title d-block">{{ __('lang.courseInfo') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_13" aria-expanded="false" aria-controls="kt_accordion_1_body_13">
                    {{ __('lang.level') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_13" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($k12Level) && count($k12Level) > 0)
                    @foreach($k12Level as $i => $l)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_level" value="{{$i}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$l}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_14" aria-expanded="false" aria-controls="kt_accordion_1_body_14">
                    {{ __('lang.scholarships') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_14" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_scholarship" value="1" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.none') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_scholarship" value="2" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 0 - MYR 5,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_scholarship" value="3" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 5,001 - MYR 10,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_scholarship" value="4" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 10,001 - MYR 20,000
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_scholarship" value="5" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        MYR 20,001 - MYR 30,000
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="separator my-5"></div>
          </div>
          <span class="pt-3 explore-filter-title d-block">{{ __('lang.location') }}</span>
          <div class="col-12 mb-5">
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_19" aria-expanded="false" aria-controls="kt_accordion_1_body_19">
                    {{ __('lang.country') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_19" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_country" value="129" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.malaysia') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_country" value="192" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.singapore') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_country" value="13" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.australia') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_country" value="826" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.unitedKingdom') }}
                      </label>
                    </div>
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_country" value="840" id="flexCheckDefault" />
                      <label class="form-check-label" for="flexCheckDefault">
                        {{ __('lang.unitedStates') }}
                      </label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="accordion" id="kt_accordion_1">
              <div class="accordion-item">
                <h2 class="accordion-header" id="kt_accordion_1_header_1">
                  <button class="ps-0 pt-3 accordion-button fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_20" aria-expanded="false" aria-controls="kt_accordion_1_body_20">
                    {{ __('lang.state') }}
                  </button>
                </h2>
                <div id="kt_accordion_1_body_20" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                  <div class="accordion-body p-0">
                    @if(isset($state) && count($state) > 0)
                    @foreach($state as $i => $s)
                    <div class="form-check form-check-custom  form-check-sm mb-5">
                      <input class="form-check-input" type="checkbox" name="k12_state" value="{{$s->state_id}}" id="flexCheckDefault_{{$i}}" />
                      <label class="form-check-label" for="flexCheckDefault_{{$i}}">
                        {{$s->state_name}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          <button class="col-12 explore-filter-btn">{{ __('lang.resetFilter') }}</button>
          <div class="pb-5"></div>
        </div>
  </div>
@endsection

@section('script')
<script>
  $( document ).ready(function() {
    getUniList();
    getCourseList();
    getScholarship();
    getK12List();

    function getUniList()
    {
      var main_cat =  $('input[name="study_field"]:checked').val();
      var sub_cat =  $('input[name="major"]:checked').val();
      var level =  $('input[name="level"]:checked').val();
      var scholarship =  $('input[name="scholarship"]:checked').val();
      var country =  $('input[name="country"]:checked').val();
      var state =  $('input[name="state"]:checked').val();
      var search = $('input[name="search_uni"]').val();
      var sort = $('.sort-uni').val();

      $.ajax({
        url: "/getUniversityJs",
        method: 'post',
        data: {
          field: main_cat,
          major: sub_cat,
          level: level,
          scholarship: scholarship,
          country: country,
          state: state,
          search: search,
          sort: sort,
        },
        success: function(d) {
          var content = '';

          if (d.status) {
            var items = d.list;

            content += '<div class="col-12 row m-0">';
            items.forEach(function(item, index) {
              content += '<div class="col-12 col-md-4 mt-5">';
              content += '<a href="/university/'+item.url+'">';
              content += '<div class="col-12 explore-uni-outter">';
              content += '<img class="uni-img" src='+item.img+'>';
              content += '<span class="d-block uni-name pt-5">'+item.name+'</span>';
              content += '<div class="mt-2">';
              if(item.has_branch) {
                content += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">';
                content += '<path d="M8.00001 1.32999C6.54184 1.33171 5.1439 1.91172 4.11282 2.9428C3.08174 3.97388 2.50173 5.37182 2.50001 6.82999C2.49826 8.0216 2.8875 9.18088 3.60801 10.13C3.60801 10.13 3.75801 10.3275 3.78251 10.356L8.00001 15.33L12.2195 10.3535C12.2415 10.327 12.392 10.13 12.392 10.13L12.3925 10.1285C13.1127 9.17979 13.5017 8.02105 13.5 6.82999C13.4983 5.37182 12.9183 3.97388 11.8872 2.9428C10.8561 1.91172 9.45817 1.33171 8.00001 1.32999ZM8.00001 8.82999C7.60444 8.82999 7.21776 8.71269 6.88887 8.49293C6.55997 8.27316 6.30362 7.96081 6.15225 7.59535C6.00087 7.2299 5.96126 6.82777 6.03844 6.43981C6.11561 6.05184 6.30609 5.69548 6.58579 5.41577C6.8655 5.13607 7.22186 4.94559 7.60983 4.86842C7.99779 4.79125 8.39992 4.83085 8.76537 4.98223C9.13082 5.1336 9.44318 5.38995 9.66294 5.71885C9.88271 6.04774 10 6.43442 10 6.82999C9.99934 7.36022 9.78842 7.86854 9.41349 8.24347C9.03856 8.6184 8.53024 8.82933 8.00001 8.82999Z" fill="#999999"/>';
                content += '</svg>';
                if(item.ttl_branch == 0) {
                  content += '<span class="uni-location">'+item.state+'</span>';
                } else if(item.ttl_branch > 0) {
                  content += '<span class="uni-location">'+item.state+' +'+item.ttl_branch+' more</span>';
                }
              } else{
                content += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">';
                content += '<path d="M8.00001 1.32999C6.54184 1.33171 5.1439 1.91172 4.11282 2.9428C3.08174 3.97388 2.50173 5.37182 2.50001 6.82999C2.49826 8.0216 2.8875 9.18088 3.60801 10.13C3.60801 10.13 3.75801 10.3275 3.78251 10.356L8.00001 15.33L12.2195 10.3535C12.2415 10.327 12.392 10.13 12.392 10.13L12.3925 10.1285C13.1127 9.17979 13.5017 8.02105 13.5 6.82999C13.4983 5.37182 12.9183 3.97388 11.8872 2.9428C10.8561 1.91172 9.45817 1.33171 8.00001 1.32999ZM8.00001 8.82999C7.60444 8.82999 7.21776 8.71269 6.88887 8.49293C6.55997 8.27316 6.30362 7.96081 6.15225 7.59535C6.00087 7.2299 5.96126 6.82777 6.03844 6.43981C6.11561 6.05184 6.30609 5.69548 6.58579 5.41577C6.8655 5.13607 7.22186 4.94559 7.60983 4.86842C7.99779 4.79125 8.39992 4.83085 8.76537 4.98223C9.13082 5.1336 9.44318 5.38995 9.66294 5.71885C9.88271 6.04774 10 6.43442 10 6.82999C9.99934 7.36022 9.78842 7.86854 9.41349 8.24347C9.03856 8.6184 8.53024 8.82933 8.00001 8.82999Z" fill="#999999"/>';
                content += '</svg>';
                content += '<span class="uni-location">'+item.state+'</span>';
              }
              content += '<div class="d-flex justify-content-center mt-7">';
              content += '<div class="col-12 bg-grey br-8 row" style="padding: 16px;">';
              content += '<div class="col-6">';
              content += '<span class="uni-location d-block">{{ __("lang.courses") }}</span>';
              content += '<span class="uni-total d-block">'+item.ttl_course+'</span>';
              content += '</div>';
              content += '<div class="col-6">';
              content += '<span class="uni-location d-block">{{ __("lang.scholarship") }}</span>';
              content += '<span class="uni-total d-block">'+item.ttl_scholarship+'</span>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</a>';
              content += '</div>';
            });
            content += '</div>';
            $('#uni_list').empty().append(content);
          } else{
            $('#uni_list').empty();
          }
        },
        error: function() {
          console.error('500 Server Error');
        }
      });
    }

    function getCourseList()
    {
      var search = $('input[name="course_search"]').val();
      var field = $('input[name="major_study_field"]:checked').val();
      var major = $('input[name="course_major"]:checked').val();
      var level = $('input[name="course_level"]:checked').val();
      var scholarship = $('input[name="course_scholarship"]:checked').val();
      var fee = $('input[name="course_fee"]:checked').val();
      var duration = $('input[name="course_duration"]:checked').val();
      var month = $('input[name="course_month"]:checked').val();
      var country = $('input[name="course_country"]:checked').val();
      var state = $('input[name="course_state"]:checked').val();
      var sort = $('.sort-courses').val();

      $.ajax({
        url: "/getCoursesJs",
        method: 'post',
        data: {
          search: search,
          field: field,
          major: major,
          level: level,
          scholarship: scholarship,
          fee: fee,
          duration: duration,
          month: month,
          country: country,
          state: state,
          sort: sort,
        },
        success: function(d) {
          var content = '';

          if (d.status) {
            var items = d.list;

            content += '<div class="col-12 row justify-content-between m-0">';
            items.forEach(function(item, index) {
              content += '<div class="col-12 col-md-6 mt-5">';
              content += '<a href="/course-details/'+item.url_code+'">';
              content += '<div class="col-12 explore-course-outter">';
              content += '<div class="d-flex justify-content-between">';
              content += '<div>';
              content += '<img class="course-uni-img" src='+item.uni_logo+'>';
              content += '</div>';
              if(item.has_rate) {
                content += '<div class="course-score-match">Match Rate: '+item.rate_amt+'%</div>';
              }
              content += '</div>';
              content += '<div>';
              var characterLimit = 75;
              var truncatedName = item.name.length > characterLimit ? item.name.substring(0, characterLimit) + '...' : item.name;
              // content += '<span class="d-block uni-name mt-5 mb-3" style="height: 48px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-overflow: ellipsis;">'+item.name+'</span>';
              // content += '<span class="d-block uni-name mt-5 mb-3" style="height: 48px; line-height: 24px; overflow: hidden; display: block; text-overflow: ellipsis;">'+item.name+'</span>';
              content += '<span class="d-block uni-name mt-5 mb-3" style="height: 48px;">'+truncatedName+'</span>';
              content += '<span class="uni-location d-block">'+item.uni_name+'</span>';
              content += '<div class="separator my-5"></div>';
              content += '<div class="col-12 row">';
              content += '<div class="col-3">';
              content += '<span class="uni-location d-block">{{ __("lang.duration") }}</span>';
              content += '<span class="uni-total d-block pt-2">'+item.duration+'</span>';
              content += '</div>';
              content += '<div class="col-9">';
              content += '<span class="uni-location d-block">{{ __("lang.intake") }}</span>';
              content += '<span class="uni-total d-block pt-2">'+item.intake+'</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="separator my-5"></div>';
              content += '<div class="d-flex justify-content-between">';
              content += '<span class="uni-location">{{ __("lang.totalFees") }}</span>';
              content += '<span class="uni-total">'+item.ttl_fees+'</span>';
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
              content += '<span class="uni-total d-block text-end">MYR<span class="ttl-amt"> '+item.ttl_amt+'</span></span>';
              content += '</div>';
              content += '</div>';
              content += '</a>';
              content += '</div>';
            });
            content += '</div>';
            $('#course_list').empty().append(content);
          } else{
            $('#course_list').empty();
          }
        },
        error: function() {
          console.error('500 Server Error');
        }
      });
    }

    function getScholarship()
    {
      var search = $('input[name="search_scholarship"]').val();
      var country = $('input[name="scholarship_country"]:checked').val();
      var state = $('input[name="scholarship_state"]:checked').val();
      var institution = $('input[name="scholarship_uni"]:checked').val();
      var field = $('input[name="scholarship_study_field"]:checked').val();
      var major = $('input[name="scholarship_major"]:checked').val();
      var level = $('input[name="scholarship_level"]:checked').val();
      var sponsor = $('input[name="scholarship_sponsor"]:checked').val();
      var type = $('input[name="scholarship_type"]:checked').val();
      var coverage = $('input[name="scholarship_coverage"]:checked').val();
      var requirement = $('input[name="scholarship_requirement"]:checked').val();
      var interview = $('input[name="scholarship_interview"]:checked').val();
      var sort = $('.sort-scholarship').val();
      
      $.ajax({
        url: "/getScholarship",
        method: 'post',
        data: {
          search: search,
          country: country,
          state: state,
          institution: institution,
          field: field,
          major: major,
          level: level,
          sponsor: sponsor,
          type: type,
          sponsor: sponsor,
          type: type,
          coverage: coverage,
          requirement: requirement,
          interview: interview,
          sort: sort,
        },
        success: function(d) {
          var content = '';
          console.log(d)
          if (d.status) {
            var items = d.list;

            content += '<div class="col-12 row justify-content-between m-0">';
            items.forEach(function(item, index) {
              content += '<div class="col-12 col-md-6 mt-5">';
              content += '<a href="/scholarship/'+item.url_code+'">';
              content += '<div class="col-12 explore-course-outter">';
              content += '<img class="course-uni-img" src="'+item.uni_logo+'">';
              content += '<div>';
              content += '<span class="d-block uni-name pt-5">'+item.name+'</span>';
              content += '<span class="uni-location d-block">'+item.uni_name+'</span>';
              content += '<div class="separator my-5"></div>';
              content += '<span class="uni-location d-block">{{ __("lang.applicableFor") }}</span>';
              if(item.has_all_cat) {
                content += '<span class="uni-total d-block pt-2" id="applicable">All Fields</span>';
              } else{
                content += '<span class="uni-total d-block pt-2" id="applicable">'+item.applicable+'</span>';
              }
              content += '<div class="separator my-5"></div>';
              content += '<div class="d-flex justify-content-between">';
              content += '<span class="uni-location">{{ __("lang.interviewRequired") }}</span>';
              content += '<span class="uni-total">'+item.has_interview+'</span>';
              content += '</div>';
              content += '<div class="d-flex justify-content-between">';
              content += '<span class="uni-location">{{ __("lang.approval") }}</span>';
              content += '<span class="uni-total">Instant</span>';
              content += '</div>';
              content += '<div class="d-flex justify-content-between">';
              content += '<div>';
              content += '<span class="uni-location">{{ __("lang.requirements") }}</span>';
              content += '</div>';
              content += '<div class="text-end">';
              content += '<span class="uni-total" id="applicable">'+item.requirement+'</span>';
              content += '</div>';
              content += '</div>';
              content += '<div class="separator my-5"></div>';
              content += '<span class="uni-total d-block text-end">{{ __("lang.feesWaiver") }}<span class="ttl-amt"> '+item.max_discount+'</span></span>';
              content += '<button class="w-100 mt-10 apply-scholarship-btn">Pathway360 - {{ __("lang.applicable") }}</button>';
              content += '</div>';
              content += '</div>';
              content += '</a>';
              content += '</div>';
            });
            content += '</div>';
            $('#scholarship_list').empty().append(content);
          } else{
            $('#scholarship_list').empty();
          }
        },
        error: function() {
          console.error('500 Server Error');
        }
      });
    }

    function getK12List()
    {
      var level =  $('input[name="k12_level"]:checked').val();
      var country =  $('input[name="k12_country"]:checked').val();
      var state =  $('input[name="k12_state"]:checked').val();
      var search = $('input[name="k_12_search"]').val();
      var sort = $('.sort-k12').val();

      $.ajax({
        url: "/getK12School",
        method: 'post',
        data: {
          level: level,
          country: country,
          state: state,
          search: search,
          sort: sort,
        },
        success: function(d) {
          var content = '';

          if (d.status) {
            var items = d.list;

            content += '<div class="col-12 row m-0">';
            items.forEach(function(item, index) {
              content += '<div class="col-12 col-md-4 mt-5">';
              content += '<a href="/k12-details/'+item.url+'">';
              content += '<div class="col-12 explore-uni-outter">';
              content += '<img class="uni-img" src='+item.img+'>';
              content += '<span class="d-block uni-name pt-5">'+item.name+'</span>';
              content += '<div class="mt-2">';
              if(item.has_branch) {
                content += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">';
                content += '<path d="M8.00001 1.32999C6.54184 1.33171 5.1439 1.91172 4.11282 2.9428C3.08174 3.97388 2.50173 5.37182 2.50001 6.82999C2.49826 8.0216 2.8875 9.18088 3.60801 10.13C3.60801 10.13 3.75801 10.3275 3.78251 10.356L8.00001 15.33L12.2195 10.3535C12.2415 10.327 12.392 10.13 12.392 10.13L12.3925 10.1285C13.1127 9.17979 13.5017 8.02105 13.5 6.82999C13.4983 5.37182 12.9183 3.97388 11.8872 2.9428C10.8561 1.91172 9.45817 1.33171 8.00001 1.32999ZM8.00001 8.82999C7.60444 8.82999 7.21776 8.71269 6.88887 8.49293C6.55997 8.27316 6.30362 7.96081 6.15225 7.59535C6.00087 7.2299 5.96126 6.82777 6.03844 6.43981C6.11561 6.05184 6.30609 5.69548 6.58579 5.41577C6.8655 5.13607 7.22186 4.94559 7.60983 4.86842C7.99779 4.79125 8.39992 4.83085 8.76537 4.98223C9.13082 5.1336 9.44318 5.38995 9.66294 5.71885C9.88271 6.04774 10 6.43442 10 6.82999C9.99934 7.36022 9.78842 7.86854 9.41349 8.24347C9.03856 8.6184 8.53024 8.82933 8.00001 8.82999Z" fill="#999999"/>';
                content += '</svg>';
                if(item.ttl_branch == 0) {
                  content += '<span class="uni-location">'+item.branch_name+'</span>';
                } else if(item.ttl_branch > 0) {
                  content += '<span class="uni-location">'+item.branch_name+' +'+item.ttl_branch+' more</span>';
                }
              } else{
                content += '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">';
                content += '<path d="M8.00001 1.32999C6.54184 1.33171 5.1439 1.91172 4.11282 2.9428C3.08174 3.97388 2.50173 5.37182 2.50001 6.82999C2.49826 8.0216 2.8875 9.18088 3.60801 10.13C3.60801 10.13 3.75801 10.3275 3.78251 10.356L8.00001 15.33L12.2195 10.3535C12.2415 10.327 12.392 10.13 12.392 10.13L12.3925 10.1285C13.1127 9.17979 13.5017 8.02105 13.5 6.82999C13.4983 5.37182 12.9183 3.97388 11.8872 2.9428C10.8561 1.91172 9.45817 1.33171 8.00001 1.32999ZM8.00001 8.82999C7.60444 8.82999 7.21776 8.71269 6.88887 8.49293C6.55997 8.27316 6.30362 7.96081 6.15225 7.59535C6.00087 7.2299 5.96126 6.82777 6.03844 6.43981C6.11561 6.05184 6.30609 5.69548 6.58579 5.41577C6.8655 5.13607 7.22186 4.94559 7.60983 4.86842C7.99779 4.79125 8.39992 4.83085 8.76537 4.98223C9.13082 5.1336 9.44318 5.38995 9.66294 5.71885C9.88271 6.04774 10 6.43442 10 6.82999C9.99934 7.36022 9.78842 7.86854 9.41349 8.24347C9.03856 8.6184 8.53024 8.82933 8.00001 8.82999Z" fill="#999999"/>';
                content += '</svg>';
                content += '<span class="uni-location">'+item.state+'</span>';
              }
              content += '<div class="d-flex justify-content-center mt-7">';
              content += '<div class="col-12 bg-grey br-8 row" style="padding: 16px;">';
              content += '<div class="col-6">';
              content += '<span class="uni-location d-block">{{ __("lang.scholarship") }}</span>';
              content += '<span class="uni-total d-block">'+item.ttl_scholarship+'</span>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</div>';
              content += '</a>';
              content += '</div>';
            });
            content += '</div>';
            $('#k12_list').empty().append(content);
          } else{
            $('#k12_list').empty();
          }
        },
        error: function() {
          console.error('500 Server Error');
        }
      });
    }

    function getSubCat()
    {
      var id = $('input[name="study_field"]:checked').val();

      $.ajax({
        url: "/getSubCatByMainCat",
        method: 'post',
        data: {
          id: id,
        },
        success: function(d) {
          var content = '';
          
          if (d.status) {
            var items = d.list;

            items.forEach(function(item, index) {
              content += '<div class="form-check form-check-custom  form-check-sm mb-5">';
              content += '<input class="form-check-input" type="checkbox" name="major" value="'+item.sc_id+'" id="flexCheckDefault1_'+index+'" />';
              content += '<label class="form-check-label" for="flexCheckDefault1_'+index+'">';
              content += item.sc_name;
              content += '</label>';
              content += '</div>';
            });

            $('#sub_cat_list').empty().append(content);
          } else{
            $('#sub_cat_list').empty();
          }
        },
        error: function() {
          console.error('500 Server Error');
        }
      });
    }

    function getMajorSubCat()
    {
      var id = $('input[name="major_study_field"]:checked').val();

      $.ajax({
        url: "/getSubCatByMainCat",
        method: 'post',
        data: {
          id: id,
        },
        success: function(d) {
          var content = '';
          
          if (d.status) {
            var items = d.list;

            items.forEach(function(item, index) {
              content += '<div class="form-check form-check-custom form-check-sm mb-5">';
              content += '<input class="form-check-input" type="checkbox" name="course_major" value="'+item.sc_id+'" id="flexCheckDefault1_'+index+'" />';
              content += '<label class="form-check-label" for="flexCheckDefault1_'+index+'">';
              content += item.sc_name;
              content += '</label>';
              content += '</div>';
            });

            $('#major_sub_cat_list').empty().append(content);
          } else{
            $('#major_sub_cat_list').empty();
          }
        },
        error: function() {
          console.error('500 Server Error');
        }
      });
    }

    function getScholarshipSubCat()
    {
      var id = $('input[name="scholarship_study_field"]:checked').val();

      $.ajax({
        url: "/getSubCatByMainCat",
        method: 'post',
        data: {
          id: id,
        },
        success: function(d) {
          var content = '';
          
          if (d.status) {
            var items = d.list;

            items.forEach(function(item, index) {
              content += '<div class="form-check form-check-custom form-check-sm mb-5">';
              content += '<input class="form-check-input" type="checkbox" name="scholarship_major" value="'+item.sc_id+'" id="flexCheckDefault1_'+index+'" />';
              content += '<label class="form-check-label" for="flexCheckDefault1_'+index+'">';
              content += item.sc_name;
              content += '</label>';
              content += '</div>';
            });

            $('#scholarship_sub_cat_list').empty().append(content);
          } else{
            $('#scholarship_sub_cat_list').empty();
          }
        },
        error: function() {
          console.error('500 Server Error');
        }
      });
    }

    //university
    $('input[name="study_field"]').on('change', function() {
      $('input[name="study_field"]').not(this).prop('checked', false);  
      getSubCat();
      getUniList()
    });

    $(document).on('change', 'input[name="major"]', function() {
      $('input[name="major"]').not(this).prop('checked', false);
      getUniList();  
    });

    $('input[name="scholarship"]').on('change', function() {
      $('input[name="scholarship"]').not(this).prop('checked', false);
      getUniList();  
    });

    $('input[name="country"]').on('change', function() {
      $('input[name="country"]').not(this).prop('checked', false);
      getUniList();  
    });

    $('input[name="state"]').on('change', function() {
      $('input[name="state"]').not(this).prop('checked', false);
      getUniList();  
    });

    $('input[name="level"]').on('change', function() {
      $('input[name="level"]').not(this).prop('checked', false);
      getUniList();  
    });

    $('input[name="search_uni"]').keyup(function(){
      getUniList();
    });

    //course
    $('input[name="major_study_field"]').on('change', function() {
      $('input[name="major_study_field"]').not(this).prop('checked', false);  
      getMajorSubCat();
      getCourseList();
    });

    $(document).on('change', 'input[name="course_major"]', function() {
      $('input[name="course_major"]').not(this).prop('checked', false);
      getCourseList();  
    });

    $('input[name="course_level"]').on('change', function() {
      $('input[name="course_level"]').not(this).prop('checked', false);
      getCourseList();  
    });

    $('input[name="course_scholarship"]').on('change', function() {
      $('input[name="course_scholarship"]').not(this).prop('checked', false);
      getCourseList();  
    });

    $('input[name="course_fee"]').on('change', function() {
      $('input[name="course_fee"]').not(this).prop('checked', false);
      getCourseList();  
    });

    $('input[name="course_duration"]').on('change', function() {
      $('input[name="course_duration"]').not(this).prop('checked', false);
      getCourseList();  
    });

    $('input[name="course_month"]').on('change', function() {
      $('input[name="course_month"]').not(this).prop('checked', false);
      getCourseList();  
    });

    $('input[name="course_country"]').on('change', function() {
      $('input[name="course_country"]').not(this).prop('checked', false);
      getCourseList();  
    });

    $('input[name="course_state"]').on('change', function() {
      $('input[name="course_state"]').not(this).prop('checked', false);
      getCourseList();  
    });

    $('input[name="course_search"]').keyup(function(){
      getCourseList();
    });

    //scholarship
    $('input[name="scholarship_study_field"]').on('change', function() {
      $('input[name="scholarship_study_field"]').not(this).prop('checked', false);  
      getScholarshipSubCat();
      getScholarship();
    });
    
    $('input[name="search_scholarship"]').keyup(function(){
      getScholarship();
    });

    $('input[name="scholarship_country"]').on('change', function() {
      $('input[name="scholarship_country"]').not(this).prop('checked', false);
      getScholarship();  
    });

    $('input[name="scholarship_state"]').on('change', function() {
      $('input[name="scholarship_state"]').not(this).prop('checked', false);
      getScholarship();  
    });

    $('input[name="scholarship_uni"]').on('change', function() {
      $('input[name="scholarship_uni"]').not(this).prop('checked', false);
      getScholarship();  
    });

    $(document).on('change', 'input[name="scholarship_major"]', function() {
      $('input[name="scholarship_major"]').not(this).prop('checked', false);
      getScholarship();  
    });

    $('input[name="scholarship_level"]').on('change', function() {
      $('input[name="scholarship_level"]').not(this).prop('checked', false);
      getScholarship();  
    });

    $('input[name="scholarship_sponsor"]').on('change', function() {
      $('input[name="scholarship_sponsor"]').not(this).prop('checked', false);
      getScholarship();  
    });

    $('input[name="scholarship_type"]').on('change', function() {
      $('input[name="scholarship_type"]').not(this).prop('checked', false);
      getScholarship();  
    });

    $('input[name="scholarship_coverage"]').on('change', function() {
      $('input[name="scholarship_coverage"]').not(this).prop('checked', false);
      getScholarship();  
    });

    $('input[name="scholarship_requirement"]').on('change', function() {
      $('input[name="scholarship_requirement"]').not(this).prop('checked', false);
      getScholarship();  
    });

    $('input[name="scholarship_interview"]').on('change', function() {
      $('input[name="scholarship_interview"]').not(this).prop('checked', false);
      getScholarship();  
    });

    $(document).on('change', 'input[name="k12_level"]', function() {
      $('input[name="k12_level"]').not(this).prop('checked', false);
      getK12List();  
    });

    $(document).on('change', 'input[name="k12_state"]', function() {
      $('input[name="k12_state"]').not(this).prop('checked', false);
      getK12List();  
    });

    $(document).on('change', 'input[name="k12_country"]', function() {
      $('input[name="k12_country"]').not(this).prop('checked', false);
      getK12List();  
    });

    $('input[name="k_12_search"]').keyup(function(){
      getK12List();
    });

    $('.sort-uni').on('change', function(){
      getUniList();  
    })

    $('.sort-k12').on('change', function(){
      getK12List();  
    })

    $('.sort-courses').on('change', function(){
      getCourseList();  
    })

    $('.sort-scholarship').on('change', function(){
      getScholarship();  
    })

  });
</script>
@endsection
