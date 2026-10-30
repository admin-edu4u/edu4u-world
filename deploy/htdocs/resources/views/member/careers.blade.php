@extends('memberLayout')
@section('style')
  <style>
    .event-title-3 {
        display: block;
        width: 100%;
        margin: 0 auto;
    }

    @media screen and (max-width: 767px) {
        img.w-100 {
            height: 460px;
        }
        .event-title-2,
        .event-title-3 {
            width: 340px;
            margin: 0 auto;
        }

        .mobile-font-size {
            font-size: 14px;
        }
    }

    .scrollable-tabs {
        overflow-x: auto;
        white-space: nowrap;
        -ms-overflow-style: none;
        scrollbar-width: none;
    }

    .scrollable-tabs::-webkit-scrollbar {
        display: none;
    }
  </style>
@endsection
@section('content')
@php
use \Jenssegers\Agent\Agent;
$agent = new Agent();
@endphp
<div class="pt-80 pb-20">
  <div class="position-relative">
    <img src="/images/assets/event-background.png" class="w-100">
    <div class="col-12 text-center event-overlay-text position-absolute">
      <span class="d-block event-title-1">{{__('lang.careersCap')}}</span>
      <span class="d-block event-title-2 pt-5">{{__('lang.careersSubTitle')}}</span>
      <span class="d-block event-title-3 pt-5">{{__('lang.careersDiscover')}}</span>
    </div>
  </div>
  <div class="mw-1200 mx-auto">
    <div class="pt-10"></div>

    <div class="scrollable-tabs" style="height:54px;">
      <ul class="nav nav-tabs nav-pills flex-nowrap" role="tablist" >
          @foreach(\App\Models\CareerCategory::where('cc_status', 1)->get() as $cc)
              <li class="nav-item me-5">
                  <a class="nav-link blog-nav-link{{ $loop->first ? ' active' : '' }}" data-bs-toggle="tab" href="#kt_vtab_pane_{{ $loop->iteration }}">{{ $cc->cc_title }}</a>
              </li>
          @endforeach
      </ul>
    </div>

      <div class="tab-content" id="myTabContent">
            @foreach(\App\Models\CareerCategory::where('cc_status', 1)->get() as $cc)
                <div class="tab-pane fade{{ $loop->first ? ' show active' : '' }}" id="kt_vtab_pane_{{ $loop->iteration }}" role="tabpanel">
                    <div class="col-12 row">
                        @foreach(\App\Models\Career::where('c_category', $cc->cc_id)->where('c_status', 1)->get() as $c)
                        <!-- <div class="col-12 row align-items-center mt-5">     -->
                          <div class="mt-5"></div>
                          <div class="col-lg-8 col-12">
                              <span class="d-block career-job-title">{{ $c->c_title }}</span>
                              <span class="pt-2 d-block career-job-sub-title">
                                <svg xmlns="http://www.w3.org/2000/svg" width="27" height="27" viewBox="0 0 27 27" fill="none">
                                  <path d="M5.0625 3.375V21.9375H15.1875V3.375H5.0625ZM4.21875 1.6875H16.0312C16.255 1.6875 16.4696 1.77639 16.6279 1.93463C16.7861 2.09286 16.875 2.30747 16.875 2.53125V22.7812C16.875 23.005 16.7861 23.2196 16.6279 23.3779C16.4696 23.5361 16.255 23.625 16.0312 23.625H4.21875C3.99497 23.625 3.78036 23.5361 3.62213 23.3779C3.4639 23.2196 3.375 23.005 3.375 22.7812V2.53125C3.375 2.30747 3.4639 2.09286 3.62213 1.93463C3.78036 1.77639 3.99497 1.6875 4.21875 1.6875Z" fill="black"/>
                                  <path d="M6.75 6.75H13.5V8.4375H6.75V6.75ZM6.75 11.8125H13.5V13.5H6.75V11.8125ZM6.75 16.875H13.5V18.5625H6.75V16.875ZM16.875 13.5H20.25V15.1875H16.875V13.5ZM16.875 16.875H20.25V18.5625H16.875V16.875ZM1.6875 21.9375H25.3125V23.625H1.6875V21.9375Z" fill="black"/>
                                  <path d="M16.875 10.125V21.9375H21.9375V10.125H16.875ZM16.0312 8.4375H22.7812C23.005 8.4375 23.2196 8.5264 23.3779 8.68463C23.5361 8.84286 23.625 9.05747 23.625 9.28125V22.7812C23.625 23.005 23.5361 23.2196 23.3779 23.3779C23.2196 23.5361 23.005 23.625 22.7812 23.625H16.0312C15.8075 23.625 15.5929 23.5361 15.4346 23.3779C15.2764 23.2196 15.1875 23.005 15.1875 22.7812V9.28125C15.1875 9.05747 15.2764 8.84286 15.4346 8.68463C15.5929 8.5264 15.8075 8.4375 16.0312 8.4375Z" fill="black"/>
                                </svg>
                                <span class="mx-2 mobile-font-size">Edu4u</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="4" height="5" viewBox="0 0 4 5" fill="none">
                                  <circle cx="2" cy="2.5" r="2" fill="#1F1F1F"/>
                                </svg>
                                <span class="ms-2 mobile-font-size">{{ $c->c_state }}</span>
                              </span>
                              <span class="d-block pt-2 career-job-sub-title-1">
                                <span class="me-2 mobile-font-size">{{ $c->c_type == 1 ? 'Full-Time' : ($c->c_type == 2 ? 'Part-Time' : 'N/A') }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="4" height="4" viewBox="0 0 4 4" fill="none">
                                  <circle cx="2" cy="2" r="2" fill="#999999"/>
                                </svg>
                                <span class="ms-2 mobile-font-size">MYR {{ $c->c_salary_min }} ~ MYR {{ $c->c_salary_max }}</span>
                              </span>
                            </div>
                            <div class="col-lg-4 col-12 @if(!$agent->isMobile()) mt-5 @endif mb-2">
                              <a href="{{ $c->c_url }}">
                              <button class="apply-scholarship-btn float-end mt-6 col-lg-5 col-12">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                  <path d="M10 4H6C5.46957 4 4.96086 4.21071 4.58579 4.58579C4.21071 4.96086 4 5.46957 4 6V18C4 18.5304 4.21071 19.0391 4.58579 19.4142C4.96086 19.7893 5.46957 20 6 20H18C18.5304 20 19.0391 19.7893 19.4142 19.4142C19.7893 19.0391 20 18.5304 20 18V14M12 12L20 4M20 4V9M20 4H15" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                {{__('lang.applyNow')}}
                              </button>
                              </a>
                            </div>
                        <!-- </div> -->
                        <hr class="mt-5">
                        @endforeach
                    </div>
                </div>
            @endforeach
      </div>

  </div>
</div>
@endsection

@section('script')
@endsection
