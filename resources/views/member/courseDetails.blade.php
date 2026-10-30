@extends('memberLayout')
@section('style')
<style>
  button:disabled,
  button[disabled]{
    opacity: 0.5;
  }

  .do-next-btn-1 {
    border: 2px solid;
    border-color: #1A124E;
  }

  .do-next-btn-2 {
    border: 2px solid;
    border-color:white;
  }

  .do-next-btn-1:hover {
    color:#1A124E;
    background-color: white;
  }

  .do-next-btn-2:hover {
    color:white;
    background-color: #1A124E;
  }

  @media screen and (max-width: 768px) {
    .details-div-border-right {
      border-right: 0px;
    }
    .mt-400 {
      margin-top: 550px;
    }
  }

  .image-container {
      background-image: url({{$course->background_img}});
      background-size:cover;
      /* position: absolute; */
      top: 0;
      left: 0;
      height: 460px;
      z-index: -1;
  }

  @media (min-width: 768px) {
        .programme-outline {
            margin-top: 50px;
        }
    }

    @media (max-width: 767px) {
        .programme-outline {
            margin-top: 40px;
        }
    }
</style>
@endsection

@section('content')
@php
use \Jenssegers\Agent\Agent;
$agent = new Agent();
@endphp

<div class="pt-80 pb-20">
  <div>
  <div class="image-container">
    @if(!$agent->isMobile())
    <div class="mw-1200 mx-auto" style="padding-top:200px;">
    @else 
    <div class="mw-1200 mx-auto" style="padding-top:60px;">
    @endif
      @if(!$agent->isMobile())
      <div class="col-12 m-0 row course-outer-div">
        <div class="col-2">
          <img src="{{$course->uni_logo}}" class="uni-logo">
        </div>
        <div class="col-10 mt-10">
          <div class="ms-5">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <span class="d-block visit-campus-text text-start">{{$course->c_name}}</span>
              </div>
            </div>
            <span class="d-block course-details-uni-name text-start pt-3"><a href="/university/{{ $course->u_url_code }}" style="color:#666;">{{$course->u_name}}</a></span>
            <div class="col-12 row mt-10">
              <div class="col-3 px-1">
                <div class="course-details-div">
                  <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                    <path d="M3 18C3 12.3435 3 9.5145 4.758 7.758C6.5145 6 9.3435 6 15 6H21C26.6565 6 29.4855 6 31.242 7.758C33 9.5145 33 12.3435 33 18V21C33 26.6565 33 29.4855 31.242 31.242C29.4855 33 26.6565 33 21 33H15C9.3435 33 6.5145 33 4.758 31.242C3 29.4855 3 26.6565 3 21V18Z" stroke="#1F1F1F" stroke-width="2"/>
                    <path d="M10.5 6V3.75M25.5 6V3.75M3.75 13.5H32.25" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round"/>
                    <path d="M27 25.5C27 25.8978 26.842 26.2794 26.5607 26.5607C26.2794 26.842 25.8978 27 25.5 27C25.1022 27 24.7206 26.842 24.4393 26.5607C24.158 26.2794 24 25.8978 24 25.5C24 25.1022 24.158 24.7206 24.4393 24.4393C24.7206 24.158 25.1022 24 25.5 24C25.8978 24 26.2794 24.158 26.5607 24.4393C26.842 24.7206 27 25.1022 27 25.5ZM27 19.5C27 19.8978 26.842 20.2794 26.5607 20.5607C26.2794 20.842 25.8978 21 25.5 21C25.1022 21 24.7206 20.842 24.4393 20.5607C24.158 20.2794 24 19.8978 24 19.5C24 19.1022 24.158 18.7206 24.4393 18.4393C24.7206 18.158 25.1022 18 25.5 18C25.8978 18 26.2794 18.158 26.5607 18.4393C26.842 18.7206 27 19.1022 27 19.5ZM19.5 25.5C19.5 25.8978 19.342 26.2794 19.0607 26.5607C18.7794 26.842 18.3978 27 18 27C17.6022 27 17.2206 26.842 16.9393 26.5607C16.658 26.2794 16.5 25.8978 16.5 25.5C16.5 25.1022 16.658 24.7206 16.9393 24.4393C17.2206 24.158 17.6022 24 18 24C18.3978 24 18.7794 24.158 19.0607 24.4393C19.342 24.7206 19.5 25.1022 19.5 25.5ZM19.5 19.5C19.5 19.8978 19.342 20.2794 19.0607 20.5607C18.7794 20.842 18.3978 21 18 21C17.6022 21 17.2206 20.842 16.9393 20.5607C16.658 20.2794 16.5 19.8978 16.5 19.5C16.5 19.1022 16.658 18.7206 16.9393 18.4393C17.2206 18.158 17.6022 18 18 18C18.3978 18 18.7794 18.158 19.0607 18.4393C19.342 18.7206 19.5 19.1022 19.5 19.5ZM12 25.5C12 25.8978 11.842 26.2794 11.5607 26.5607C11.2794 26.842 10.8978 27 10.5 27C10.1022 27 9.72064 26.842 9.43934 26.5607C9.15804 26.2794 9 25.8978 9 25.5C9 25.1022 9.15804 24.7206 9.43934 24.4393C9.72064 24.158 10.1022 24 10.5 24C10.8978 24 11.2794 24.158 11.5607 24.4393C11.842 24.7206 12 25.1022 12 25.5ZM12 19.5C12 19.8978 11.842 20.2794 11.5607 20.5607C11.2794 20.842 10.8978 21 10.5 21C10.1022 21 9.72064 20.842 9.43934 20.5607C9.15804 20.2794 9 19.8978 9 19.5C9 19.1022 9.15804 18.7206 9.43934 18.4393C9.72064 18.158 10.1022 18 10.5 18C10.8978 18 11.2794 18.158 11.5607 18.4393C11.842 18.7206 12 19.1022 12 19.5Z" fill="#1F1F1F"/>
                  </svg>
                  <span class="course-details-course-intake d-block pt-2">{{$course->intake}}</span>
                  <span class="course-details-course-intake-desc pt-1 d-block">{{__('lang.intakeFor')}}{{date('Y') }} / {{date('Y', strtotime(date('Y') . " +1 year") )}}</span>
                </div>
              </div>
              <div class="col-3 px-1">
                <div class="course-details-div">
                  <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                    <path d="M4.5 18C4.5 19.7728 4.84919 21.5283 5.52763 23.1662C6.20606 24.8041 7.20047 26.2923 8.45406 27.5459C9.70765 28.7995 11.1959 29.7939 12.8338 30.4724C14.4717 31.1508 16.2272 31.5 18 31.5C19.7728 31.5 21.5283 31.1508 23.1662 30.4724C24.8041 29.7939 26.2923 28.7995 27.5459 27.5459C28.7995 26.2923 29.7939 24.8041 30.4724 23.1662C31.1508 21.5283 31.5 19.7728 31.5 18C31.5 14.4196 30.0777 10.9858 27.5459 8.45406C25.0142 5.92232 21.5804 4.5 18 4.5C14.4196 4.5 10.9858 5.92232 8.45406 8.45406C5.92232 10.9858 4.5 14.4196 4.5 18Z" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18 10.5V18L22.5 22.5" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span class="course-details-course-intake d-block pt-2">{{$course->duration}}</span>
                  <span class="course-details-course-intake-desc pt-1 d-block">{{__('lang.durationOfStudies')}}</span>
                </div>
              </div>
              <div class="col-3 px-1">
                <div class="course-details-div">
                  <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                    <path d="M18 24C23.799 24 28.5 19.299 28.5 13.5C28.5 7.70101 23.799 3 18 3C12.201 3 7.5 7.70101 7.5 13.5C7.5 19.299 12.201 24 18 24Z" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18 18C20.4853 18 22.5 15.9853 22.5 13.5C22.5 11.0147 20.4853 9 18 9C15.5147 9 13.5 11.0147 13.5 13.5C13.5 15.9853 15.5147 18 18 18Z" fill="#1F1F1F"/>
                    <path d="M10.5 21V30.351C10.5002 30.6163 10.5708 30.8767 10.7045 31.1059C10.8382 31.335 11.0302 31.5245 11.2611 31.6552C11.4919 31.7859 11.7533 31.853 12.0186 31.8497C12.2838 31.8464 12.5435 31.7728 12.771 31.6365L17.229 28.9635C17.462 28.8239 17.7284 28.7502 18 28.7502C18.2716 28.7502 18.538 28.8239 18.771 28.9635L23.229 31.6365C23.4565 31.7728 23.7162 31.8464 23.9814 31.8497C24.2467 31.853 24.5081 31.7859 24.7389 31.6552C24.9698 31.5245 25.1618 31.335 25.2955 31.1059C25.4292 30.8767 25.4998 30.6163 25.5 30.351V21" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>
                  <span class="course-details-course-intake d-block pt-2">{{$course->c_award}}</span>
                  <span class="course-details-course-intake-desc pt-1 d-block">{{__('lang.awards')}}</span>
                </div>
              </div>
              <div class="col-3 px-1">
                <div class="course-details-div">
                  <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                    <path d="M12 10.5V7.5C12 6.70435 12.3161 5.94129 12.8787 5.37868C13.4413 4.81607 14.2044 4.5 15 4.5H21C21.7956 4.5 22.5587 4.81607 23.1213 5.37868C23.6839 5.94129 24 6.70435 24 7.5V10.5M18 18V18.015M4.5 13.5C4.5 12.7044 4.81607 11.9413 5.37868 11.3787C5.94129 10.8161 6.70435 10.5 7.5 10.5H28.5C29.2956 10.5 30.0587 10.8161 30.6213 11.3787C31.1839 11.9413 31.5 12.7044 31.5 13.5V27C31.5 27.7956 31.1839 28.5587 30.6213 29.1213C30.0587 29.6839 29.2956 30 28.5 30H7.5C6.70435 30 5.94129 29.6839 5.37868 29.1213C4.81607 28.5587 4.5 27.7956 4.5 27V13.5Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M4.5 19.5C8.68737 21.61 13.311 22.7091 18 22.7091C22.689 22.7091 27.3126 21.61 31.5 19.5" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg>  
                  <span class="course-details-course-intake d-block pt-2">{{$course->intership}}</span>
                  <span class="course-details-course-intake-desc pt-1 d-block">{{__('lang.internship')}}</span>
                </div>
              </div>
            </div>
            <span class="course-details-desc d-block pt-10">
              {{$course->u_desc}}
            </span>
          </div>
        </div>
      </div>
      @else 
      <div class="col-12 mx-auto course-outer-div row align-items-center">
        <div class="col-3">
          <img src="{{$course->uni_logo}}" class="uni-logo">
        </div>
        <div class="col-9 ps-8">
          <span class="visit-campus-text text-start">{{$course->c_name}}</span>
          <span class="d-block course-details-uni-name text-start"><a href="/university/{{ $course->u_url_code }}" style="color:#666;">{{$course->u_name}}</a></span>
        </div>
        <div class="col-12 m-0 row mt-10">
          <div class="col-12 px-1 mt-5">
            <div class="course-details-div d-flex align-items-center">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                  <path d="M3 18C3 12.3435 3 9.5145 4.758 7.758C6.5145 6 9.3435 6 15 6H21C26.6565 6 29.4855 6 31.242 7.758C33 9.5145 33 12.3435 33 18V21C33 26.6565 33 29.4855 31.242 31.242C29.4855 33 26.6565 33 21 33H15C9.3435 33 6.5145 33 4.758 31.242C3 29.4855 3 26.6565 3 21V18Z" stroke="#1F1F1F" stroke-width="2"/>
                  <path d="M10.5 6V3.75M25.5 6V3.75M3.75 13.5H32.25" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round"/>
                  <path d="M27 25.5C27 25.8978 26.842 26.2794 26.5607 26.5607C26.2794 26.842 25.8978 27 25.5 27C25.1022 27 24.7206 26.842 24.4393 26.5607C24.158 26.2794 24 25.8978 24 25.5C24 25.1022 24.158 24.7206 24.4393 24.4393C24.7206 24.158 25.1022 24 25.5 24C25.8978 24 26.2794 24.158 26.5607 24.4393C26.842 24.7206 27 25.1022 27 25.5ZM27 19.5C27 19.8978 26.842 20.2794 26.5607 20.5607C26.2794 20.842 25.8978 21 25.5 21C25.1022 21 24.7206 20.842 24.4393 20.5607C24.158 20.2794 24 19.8978 24 19.5C24 19.1022 24.158 18.7206 24.4393 18.4393C24.7206 18.158 25.1022 18 25.5 18C25.8978 18 26.2794 18.158 26.5607 18.4393C26.842 18.7206 27 19.1022 27 19.5ZM19.5 25.5C19.5 25.8978 19.342 26.2794 19.0607 26.5607C18.7794 26.842 18.3978 27 18 27C17.6022 27 17.2206 26.842 16.9393 26.5607C16.658 26.2794 16.5 25.8978 16.5 25.5C16.5 25.1022 16.658 24.7206 16.9393 24.4393C17.2206 24.158 17.6022 24 18 24C18.3978 24 18.7794 24.158 19.0607 24.4393C19.342 24.7206 19.5 25.1022 19.5 25.5ZM19.5 19.5C19.5 19.8978 19.342 20.2794 19.0607 20.5607C18.7794 20.842 18.3978 21 18 21C17.6022 21 17.2206 20.842 16.9393 20.5607C16.658 20.2794 16.5 19.8978 16.5 19.5C16.5 19.1022 16.658 18.7206 16.9393 18.4393C17.2206 18.158 17.6022 18 18 18C18.3978 18 18.7794 18.158 19.0607 18.4393C19.342 18.7206 19.5 19.1022 19.5 19.5ZM12 25.5C12 25.8978 11.842 26.2794 11.5607 26.5607C11.2794 26.842 10.8978 27 10.5 27C10.1022 27 9.72064 26.842 9.43934 26.5607C9.15804 26.2794 9 25.8978 9 25.5C9 25.1022 9.15804 24.7206 9.43934 24.4393C9.72064 24.158 10.1022 24 10.5 24C10.8978 24 11.2794 24.158 11.5607 24.4393C11.842 24.7206 12 25.1022 12 25.5ZM12 19.5C12 19.8978 11.842 20.2794 11.5607 20.5607C11.2794 20.842 10.8978 21 10.5 21C10.1022 21 9.72064 20.842 9.43934 20.5607C9.15804 20.2794 9 19.8978 9 19.5C9 19.1022 9.15804 18.7206 9.43934 18.4393C9.72064 18.158 10.1022 18 10.5 18C10.8978 18 11.2794 18.158 11.5607 18.4393C11.842 18.7206 12 19.1022 12 19.5Z" fill="#1F1F1F"/>
                </svg>
              </div>
              <div class="ms-5">
                <span class="course-details-course-intake d-block pt-2">{{$course->intake}}</span>
                <span class="course-details-course-intake-desc d-block">{{__('lang.intakeFor')}}{{date('Y') }} / {{date('Y', strtotime(date('Y') . " +1 year") )}}</span>
              </div>
            </div>
          </div>
          <div class="col-12 px-1 mt-5">
            <div class="course-details-div d-flex align-items-center">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                  <path d="M4.5 18C4.5 19.7728 4.84919 21.5283 5.52763 23.1662C6.20606 24.8041 7.20047 26.2923 8.45406 27.5459C9.70765 28.7995 11.1959 29.7939 12.8338 30.4724C14.4717 31.1508 16.2272 31.5 18 31.5C19.7728 31.5 21.5283 31.1508 23.1662 30.4724C24.8041 29.7939 26.2923 28.7995 27.5459 27.5459C28.7995 26.2923 29.7939 24.8041 30.4724 23.1662C31.1508 21.5283 31.5 19.7728 31.5 18C31.5 14.4196 30.0777 10.9858 27.5459 8.45406C25.0142 5.92232 21.5804 4.5 18 4.5C14.4196 4.5 10.9858 5.92232 8.45406 8.45406C5.92232 10.9858 4.5 14.4196 4.5 18Z" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M18 10.5V18L22.5 22.5" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <div class="ms-5">
                <span class="course-details-course-intake d-block pt-2">{{$course->duration}}</span>
                <span class="course-details-course-intake-desc pt-1 d-block">{{__('lang.durationOfStudies')}}</span>
              </div>
            </div>
          </div>
          <div class="col-12 px-1 mt-5">
            <div class="course-details-div d-flex align-items-center">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                  <path d="M18 24C23.799 24 28.5 19.299 28.5 13.5C28.5 7.70101 23.799 3 18 3C12.201 3 7.5 7.70101 7.5 13.5C7.5 19.299 12.201 24 18 24Z" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M18 18C20.4853 18 22.5 15.9853 22.5 13.5C22.5 11.0147 20.4853 9 18 9C15.5147 9 13.5 11.0147 13.5 13.5C13.5 15.9853 15.5147 18 18 18Z" fill="#1F1F1F"/>
                  <path d="M10.5 21V30.351C10.5002 30.6163 10.5708 30.8767 10.7045 31.1059C10.8382 31.335 11.0302 31.5245 11.2611 31.6552C11.4919 31.7859 11.7533 31.853 12.0186 31.8497C12.2838 31.8464 12.5435 31.7728 12.771 31.6365L17.229 28.9635C17.462 28.8239 17.7284 28.7502 18 28.7502C18.2716 28.7502 18.538 28.8239 18.771 28.9635L23.229 31.6365C23.4565 31.7728 23.7162 31.8464 23.9814 31.8497C24.2467 31.853 24.5081 31.7859 24.7389 31.6552C24.9698 31.5245 25.1618 31.335 25.2955 31.1059C25.4292 30.8767 25.4998 30.6163 25.5 30.351V21" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </div>
              <div class="ms-5">
                <span class="course-details-course-intake d-block pt-2">{{$course->c_award}}</span>
                <span class="course-details-course-intake-desc pt-1 d-block">{{__('lang.awards')}}</span>
              </div>
            </div>
          </div>
          <div class="col-12 px-1 mt-5">
            <div class="course-details-div d-flex align-items-center">
              <div>
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
                  <path d="M12 10.5V7.5C12 6.70435 12.3161 5.94129 12.8787 5.37868C13.4413 4.81607 14.2044 4.5 15 4.5H21C21.7956 4.5 22.5587 4.81607 23.1213 5.37868C23.6839 5.94129 24 6.70435 24 7.5V10.5M18 18V18.015M4.5 13.5C4.5 12.7044 4.81607 11.9413 5.37868 11.3787C5.94129 10.8161 6.70435 10.5 7.5 10.5H28.5C29.2956 10.5 30.0587 10.8161 30.6213 11.3787C31.1839 11.9413 31.5 12.7044 31.5 13.5V27C31.5 27.7956 31.1839 28.5587 30.6213 29.1213C30.0587 29.6839 29.2956 30 28.5 30H7.5C6.70435 30 5.94129 29.6839 5.37868 29.1213C4.81607 28.5587 4.5 27.7956 4.5 27V13.5Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M4.5 19.5C8.68737 21.61 13.311 22.7091 18 22.7091C22.689 22.7091 27.3126 21.61 31.5 19.5" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>  
              </div>
              <div class="ms-5">
                <span class="course-details-course-intake d-block pt-2">{{$course->intership}}</span>
                <span class="course-details-course-intake-desc pt-1 d-block">{{__('lang.internship')}}</span>
              </div>
            </div>
          </div>
        </div>
        <span class="course-details-desc d-block pt-10">
          {{$course->u_desc}}
        </span>
      </div>
      @endif 
    </div>
    <div class="programme-outline mw-1200 mx-auto">
    <div class="col-12 row m-0">
      <div class="col-12 col-lg-8 details-div-border-right">
        <div>
          <span class="criteria d-block">{{__('lang.programmeOutline')}}</span>
          {!! $course->c_outline !!}
        </div>
        <div class="mt-15">
          <ul class="nav nav-pills mt-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="p-0 me-5 nav-link active options-toggle" id="pills-orders-tab" data-bs-toggle="pill" data-bs-target="#pills-core" type="button" role="tab" aria-controls="pills-core" aria-selected="true">{{ __('lang.core') }}</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="p-0 me-5 nav-link options-toggle" id="pills-subscriptions-tab" data-bs-toggle="pill" data-bs-target="#pills-elective" type="button" role="tab" aria-controls="pills-subscriptions" aria-selected="false">{{ __('lang.elective') }}</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="p-0 nav-link options-toggle" id="pills-subscriptions-tab" data-bs-toggle="pill" data-bs-target="#pills-others" type="button" role="tab" aria-controls="pills-subscriptions" aria-selected="false">{{ __('lang.others') }}</button>
            </li>
          </ul>

          <div class="tab-content mt-5 mb-10" id="pills-tabContent">
            <div class="tab-pane fade show active text-white" id="pills-core" role="tabpanel" aria-labelledby="pills-orders-tab">
              <div class="col-12 mb-5" style="color: black;">
              {!! $course->c_core !!}
              </div>
            </div>
            <div class="tab-pane fade show active text-white" id="pills-elective" role="tabpanel" aria-labelledby="pills-orders-tab">
              <div class="col-12 mb-5" style="color: black;">
              {!! $course->c_elective !!}
              </div>
            </div>
            <div class="tab-pane fade show active text-white" id="pills-others" role="tabpanel" aria-labelledby="pills-orders-tab">
              <div class="col-12 mb-5" style="color: black;">
              {!! $course->c_other !!}
              </div>
            </div>
            <!-- <div class="tab-pane fade text-white" id="pills-elective" role="tabpanel" aria-labelledby="pills-orders-tab">
              <div class="col-12 mb-5">
                @if(isset($course->elective) && count($course->elective) > 0)
                  @foreach($course->elective as $i => $c)
                  <div class="accordion" id="kt_accordion_1">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="kt_accordion_1_header_1">
                        <button class="pb-0 pt-3 ps-0 accordion-button details-btn-accordion fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_body_{{$i}}" aria-expanded="false" aria-controls="kt_accordion_body_{{$i}}">
                          {{$c->title}}
                        </button>
                      </h2>
                      <div id="kt_accordion_body_{{$i}}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                        <div class="accordion-body p-0 mt-5">
                          <span class="text-black">{!! $c->desc !!}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                @endif
              </div>
            </div>
            <div class="tab-pane fade text-white" id="pills-others" role="tabpanel" aria-labelledby="pills-orders-tab">
              <div class="col-12 mb-5">
                @if(isset($course->other) && count($course->other) > 0)
                  @foreach($course->other as $i => $c)
                  <div class="accordion" id="kt_accordion_1">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="kt_accordion_1_header_1">
                        <button class="pb-0 pt-3 ps-0 accordion-button details-btn-accordion fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_body_{{$i}}" aria-expanded="false" aria-controls="kt_accordion_body_{{$i}}">
                          {{$c->title}}
                        </button>
                      </h2>
                      <div id="kt_accordion_body_{{$i}}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                        <div class="accordion-body p-0 mt-5">
                          <span class="text-black">{!! $c->desc !!}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                @endif
              </div>
            </div> -->
          </div>
        </div>

        <div class="mt-15">
          <span class="criteria d-block">{{__('lang.whatCanYouDo')}}</span>
          @if(!$agent->isMobile())
          <div class="col-12 row mt-8">
          @else
          <div class="col-12 mt-8">
          @endif
            <div class="col-12 col-lg-6" style="height:200px;">
              <div class=" do-next-div-1" style="height:100%;">
                <span class="d-block do-next-text-1">{{__('lang.consultOurCounsellor')}}</span>
                <span class="d-block do-next-text-2 pt-1">{{__('lang.consultOurCounsellorDesc')}}</span>
                <a class="do-next-btn-1 d-inline-block mt-3" href="/consult-counsellor/{{$course->c_url_code}}">{{__('lang.consultForFree')}}</a>
              </div>
            </div>
            <div class="col-12 col-lg-6 mt-5 mt-lg-0" style="height:200px;">
              <div class="do-next-div-2" style="height:100%;">
                <span class="d-block do-next-text-1 text-white">{{__('lang.compareCourse')}}</span>
                <span class="d-block do-next-text-2 pt-1 text-white">{{__('lang.compareCourseDesc')}}</span>
                <a class="do-next-btn-2 d-inline-block mt-9" href="/compare-course">{{__('lang.compareNow')}}</a>
              </div>
            </div>
          </div>
        </div>

        @if(isset($recommendation) && count($recommendation) > 0)
        <div class="mt-15">
          <span class="criteria d-block">{{__('lang.otherYouMayLike')}}</span>
          @if(!$agent->isMobile())
          <div class="col-12 row mt-8">
          @else
          <div class="d-flex overflow-scroll mt-8">
          @endif
            @foreach($recommendation as $r)
            <div class="col-10 col-lg-6 mb-5 me-5 me-lg-0">
              <a href="/course-details/{{$r->c_url_code}}">
                <div class="col-12 explore-course-outter">
                  <img class="course-uni-img" src="{{$r->uni_logo}}">
                  <div>
                    <span class="d-block uni-name pt-5">{{$r->c_name}}</span>
                    <span class="uni-location d-block">{{$r->u_name}}</span>
                    <div class="separator my-5"></div>
                    <div class="col-12 row">
                      <div class="col-4 col-lg-3">
                        <span class="uni-location d-block">{{ __('lang.duration') }}</span>
                        <span class="uni-total d-block pt-2">{{$r->duration}}</span>
                      </div>
                      <div class="col-8 col-lg-9">
                        <span class="uni-location d-block">{{ __('lang.intake') }}</span>
                        <span class="uni-total d-block pt-2">{{$r->intake}}</span>
                      </div>
                    </div>
                    <div class="separator my-5"></div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.totalFees') }}</span>
                      <span class="uni-total">{{$r->ttl_fees}}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.deals') }}</span>
                      <span class="uni-total">{{$r->deals}}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.scholarship') }}</span>
                      <span class="uni-total">{{$r->scholarship}}</span>
                    </div>
                    <div class="separator my-5"></div>
                    <span class="uni-total d-block text-end">MYR<span class="ttl-amt"> {{$r->ttl_amt}}</span></span>
                  </div>
                </div>
              </a>
            </div>
            @endforeach
          </div>
        </div>
        @endif

      </div>
      <div class="col-12 col-lg-4">
        <div class="ms-lg-5 mt-10 mt-lg-0">
          <span class="criteria d-block">{{ __('lang.applyCourseNow') }}</span>
          <div class="mt-5">
            <span>{!! $course->c_entry_req !!}</span>
          </div>
          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.step1')}}</span>
            <span class="admission-text d-block mb-10">{{__('lang.yourAdmissionQualifi')}}</span>
            <!-- @if(isset($return_grade) && count($return_grade) > 0)
            @foreach($return_grade as $i => $g)
            <div class="mb-5">
              <input type="radio" class="btn-check" name="qualification" value="{{$i + 1}}" id="kt_qualification_option_{{$i}}"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_qualification_option_{{$i}}">
                <div class="col-12 row align-items-center">
                  <div class="col-8">
                    <span class="d-block fw-semibold text-start">
                      <span class="do-next-text-1 d-block">{{$g['grade']}}</span>
                      <span class="do-next-text-2 d-block">
                        @if (!empty($g['subject']))
                            {{ $g['subject'] }}
                        @else
                            {{__('lang.anySub')}}
                        @endif
                      </span>
                    </span>
                  </div>
                  <div class="col-4 p-0 text-end">
                    <span class="submission-user-label">{{$g['level']}}</span>
                  </div>
                </div>
              </label>
            </div>
            @endforeach
            <div class="mb-5">
              <input type="radio" class="btn-check" name="qualification" value="99" id="kt_qualification_option_99"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_qualification_option_99">
                <div class="col-12 row align-items-center">
                  <div class="col-8">
                    <span class="d-block fw-semibold text-start">
                      <span class="do-next-text-1 d-block">Others</span>
                      <span class="do-next-text-2 d-block"></span>
                    </span>
                  </div>
                  <div class="col-4 p-0 text-end">
                    <span class="submission-user-label"></span>
                  </div>
                </div>
              </label>
            </div>
            @endif -->

            @if(isset($return_gradeDesc) && count($return_gradeDesc) > 0)
            @foreach($return_gradeDesc as $i => $g)
            <div class="mb-5">
              <input type="radio" class="btn-check" name="qualification" value="{{$i + 1}}" id="kt_qualification_option_{{$i}}"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_qualification_option_{{$i}}">
                <div class="col-12 row align-items-center">
                  <div class="col-6">
                    <span class="d-block fw-semibold text-start">
                      <span class="do-next-text-1 d-block">{{$g['level']}}</span>
                    </span>
                  </div>
                  <div class="col-6 p-0 text-end">
                    <span class="submission-user-label" style="font-size:12px; text-decoration:underline;" id="viewreq_{{$i}}">View Requirements</span>
                  </div>
                </div>
              </label>
            </div>

            <div class="modal fade" tabindex="-1" id="viewreq_modal_{{$i}}" style="background: rgba(0, 0, 0, 0.50);">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="padding:36px;">
                  <div class="modal-body p-0">
                    <span class="modal-pathway-title d-block">{{$g['level']}}</span>
                    <span class="d-block modal-pathway-desc" style="padding-top:36px;">{!! $g['desc'] !!}</span>
                    <div style="padding-top:36px;">
                      <button class="w-100 apply-scholarship-btn" data-bs-dismiss="modal">Okay</button>
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-close-btn cursor" data-bs-dismiss="modal">
                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M1 1L13 13" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                  <path d="M13 1L0.999999 13" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                </svg>
              </div>
            </div>

            @endforeach
            <div class="mb-5">
              <input type="radio" class="btn-check" name="qualification" value="99" id="kt_qualification_option_99"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_qualification_option_99">
                <div class="col-12 row align-items-center">
                  <div class="col-8">
                    <span class="d-block fw-semibold text-start">
                      <span class="do-next-text-1 d-block">Others</span>
                      <span class="do-next-text-2 d-block"></span>
                    </span>
                  </div>
                  <div class="col-4 p-0 text-end">
                    <span class="submission-user-label"></span>
                  </div>
                </div>
              </label>
            </div>
            @endif

          </div>
          <div class="text-center">
            <span class="other-qualifications-text d-block">{{__('lang.otherQualifi')}}</span>
            <a class="talk-to-consult" href="/consult-counsellor/{{$course->c_url_code}}" style="color:#1A124E;">{{__('lang.talkToOurCounsellor')}}</a>
          </div>

          <div class="mt-15 separator-top"></div>

          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.step2')}}</span>
            <span class="admission-text d-block">{{__('lang.typeOfStudent')}}</span>
            <div class="mt-10 mb-5">
              <input type="radio" class="btn-check" name="student_type" value="1" id="kt_student_type_option_2"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_student_type_option_2">
                <div class="text-start">
                  <span class="do-next-text-1 d-block">{{__('lang.malaysiaStudent')}}</span>
                </div>
              </label>
            </div>
            <div class="mb-5">
              <input type="radio" class="btn-check" name="student_type" value="2" id="kt_student_type_option_1"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_student_type_option_1">
                <div class="text-start">
                  <span class="do-next-text-1 d-block">{{__('lang.internationalStudent')}}</span>
                </div>
              </label>
            </div>
          </div>

          <div class="mt-15 separator-top"></div>

          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.step3')}}</span>
            <span class="admission-text d-block">{{__('lang.areYouBumi')}}</span>
            <div class="mt-10 mb-5">
              <input type="radio" class="btn-check" name="bumiputera" value="1" id="kt_bumiputera_option_2"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_bumiputera_option_2">
                <div class="text-start">
                  <span class="do-next-text-1 d-block">{{__('lang.iAmBumi')}}</span>
                </div>
              </label>
            </div>
            <div class="mb-5">
              <input type="radio" class="btn-check" name="bumiputera" value="2" id="kt_bumiputera_option_1"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_bumiputera_option_1">
                <div class="text-start">
                  <span class="do-next-text-1 d-block">{{__('lang.iAmNonBumi')}}</span>
                </div>
              </label>
            </div>
          </div>

          <div class="mt-15 separator-top"></div>
          @if($course->c_english_required == 1)
          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.importantCap')}}</span>
            <span class="admission-text d-block">{{__('lang.englishRequirement')}}</span>
            <div class="mt-10 mb-5">
              <input type="radio" class="btn-check" name="radio_buttons_4" value="1" id="kt_radio_buttons_4_option_1"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_radio_buttons_4_option_1">
                <div class="text-start">
                  <span class="do-next-text-1 d-block">{{__('lang.englishResults')}}</span>
                  <ul class="mt-5">
                    <li class="requirement-list">MUET - Band 3</li>
                    <li class="requirement-list">IELTS - 5.5</li>
                    <li class="requirement-list">TOEFL - IBT (65)</li>
                    <li class="requirement-list">GSCE/GCE ‘O’ Level - C</li>
                  </ul>
                </div>
              </label>
            </div>
          </div>

          <div class="mt-15 separator-top"></div>
          @endif

          <!-- <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.step4')}}</span>
            <span class="admission-text d-block">{{__('lang.awardsAndScholarships')}}</span>
            <div class="mt-10 mb-5">
              <input type="radio" class="btn-check" name="is_scholarship" value="1" id="kt_is_scholarship_option_1"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_is_scholarship_option_1">
                <span class="do-next-text-1">{{__('lang.iWantScholarship')}}</span>
              </label>
            </div>
            <div class="mb-5">
              <input type="radio" class="btn-check" name="is_scholarship" value="2" id="kt_is_scholarship_option_2"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_is_scholarship_option_2">
                <span class="do-next-text-1">{{__('lang.iDontWantScholarship')}}</span>
              </label>
            </div>
          </div> -->

          <!-- <div class="step-five-select-scholarship">
          <div class="mt-15 separator-top"></div>

          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.step5')}}</span>
            <span class="mb-10 admission-text d-block">{{__('lang.selectScholarship')}}</span>
            <div id="scholarship_list"></div>
          </div>
          </div>

          <div class="mt-15 separator-top"></div> -->

          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.final')}}</span>
            <span class="admission-text d-block">{{__('lang.feesAndBursary')}}</span>
            <div class="btn-admission mt-7">
              <div id="fees_list"></div>
              <div class="mb-5 col-12 m-0 p-0 align-items-center" id="scholarship_discount" style="display:none">
                <div class="col-6">
                  <span class="d-block fw-semibold text-start">
                    <span class="requiremnt-fees-text-1 d-block">{{__('lang.awardScholarship')}}</span>
                  </span>
                </div>
                <div class="col-6 p-0 d-flex justify-content-end align-items-center">
                  <span class="requiremnt-fees-text-2">-MYR <span id="scholarship_amt"></span></span>
                </div>
              </div>
              <div class="mt-5 mb-5 separator-top"></div>
              <div class="col-12 d-flex m-0 p-0 align-items-center">
                <div class="col-7">
                  <span class="d-block fw-semibold text-start">
                    <span class="do-next-text-1 d-block">{{__('lang.estimateTotalFees')}}</span>
                  </span>
                </div>
                <div class="col-5 p-0 d-flex justify-content-end align-items-center">
                  <span class="requiremnt-fees-ttl-amt" id="ttl_course_fee"></span>
                  <input type="hidden" value="" id="ttl_course_hidden">
                  <input type="hidden" value="" id="new_ttl_course_hidden">
                </div>
              </div>
            </div>
          </div>

          <div class="mt-10">
            <div id="deposit_list"></div>
          </div>

          <div class="mt-10">
            <button class="w-100 select-offer-btn d-block text-center" data-navigate-url="/course-application/{{$course->c_url_code}}" disabled>{{__('lang.secureYourOffer')}}</button>
          </div>

          <div class="mt-5 mb-10 d-flex justify-content-center align-items-center">
            <span class="submission-user-label" style="font-size:12px; text-decoration:underline; cursor:pointer;" id="checkfees">Check the tuition fees</span>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
  </div>
  <!-- <div class="programme-outline mw-1200 mx-auto">
    <div class="col-12 row m-0">
      <div class="col-12 col-lg-8 details-div-border-right">
        <div>
          <span class="criteria d-block">{{__('lang.programmeOutline')}}</span>
          {!! $course->c_outline !!}
        </div>
        <div class="mt-15">
          <ul class="nav nav-pills mt-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="p-0 me-5 nav-link active options-toggle" id="pills-orders-tab" data-bs-toggle="pill" data-bs-target="#pills-core" type="button" role="tab" aria-controls="pills-core" aria-selected="true">{{ __('lang.core') }}</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="p-0 me-5 nav-link options-toggle" id="pills-subscriptions-tab" data-bs-toggle="pill" data-bs-target="#pills-elective" type="button" role="tab" aria-controls="pills-subscriptions" aria-selected="false">{{ __('lang.elective') }}</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="p-0 nav-link options-toggle" id="pills-subscriptions-tab" data-bs-toggle="pill" data-bs-target="#pills-others" type="button" role="tab" aria-controls="pills-subscriptions" aria-selected="false">{{ __('lang.others') }}</button>
            </li>
          </ul>

          <div class="tab-content mt-5 mb-10" id="pills-tabContent">
            <div class="tab-pane fade show active text-white" id="pills-core" role="tabpanel" aria-labelledby="pills-orders-tab">
              <div class="col-12 mb-5">
                @if(isset($course->core) && count($course->core) > 0)
                @foreach($course->core as $i => $c)
                <div class="accordion" id="kt_accordion_1">
                  <div class="accordion-item">
                    <h2 class="accordion-header" id="kt_accordion_1_header_1">
                      <button class="pb-0 pt-3 ps-0 accordion-button details-btn-accordion fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_body_{{$i}}" aria-expanded="false" aria-controls="kt_accordion_body_{{$i}}">
                        {{$c->title}}
                      </button>
                    </h2>
                    <div id="kt_accordion_body_{{$i}}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                      <div class="accordion-body p-0 mt-5">
                        <span class="text-black">{!! $c->desc !!}</span>
                      </div>
                    </div>
                  </div>
                </div>
                @endforeach
                @endif
              </div>
            </div>
            <div class="tab-pane fade text-white" id="pills-elective" role="tabpanel" aria-labelledby="pills-orders-tab">
              <div class="col-12 mb-5">
                @if(isset($course->elective) && count($course->elective) > 0)
                  @foreach($course->elective as $i => $c)
                  <div class="accordion" id="kt_accordion_1">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="kt_accordion_1_header_1">
                        <button class="pb-0 pt-3 ps-0 accordion-button details-btn-accordion fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_body_{{$i}}" aria-expanded="false" aria-controls="kt_accordion_body_{{$i}}">
                          {{$c->title}}
                        </button>
                      </h2>
                      <div id="kt_accordion_body_{{$i}}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                        <div class="accordion-body p-0 mt-5">
                          <span class="text-black">{!! $c->desc !!}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                @endif
              </div>
            </div>
            <div class="tab-pane fade text-white" id="pills-others" role="tabpanel" aria-labelledby="pills-orders-tab">
              <div class="col-12 mb-5">
                @if(isset($course->other) && count($course->other) > 0)
                  @foreach($course->other as $i => $c)
                  <div class="accordion" id="kt_accordion_1">
                    <div class="accordion-item">
                      <h2 class="accordion-header" id="kt_accordion_1_header_1">
                        <button class="pb-0 pt-3 ps-0 accordion-button details-btn-accordion fs-4 fw-semibold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#kt_accordion_body_{{$i}}" aria-expanded="false" aria-controls="kt_accordion_body_{{$i}}">
                          {{$c->title}}
                        </button>
                      </h2>
                      <div id="kt_accordion_body_{{$i}}" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                        <div class="accordion-body p-0 mt-5">
                          <span class="text-black">{!! $c->desc !!}</span>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endforeach
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="mt-15">
          <span class="criteria d-block">{{__('lang.whatCanYouDo')}}</span>
          @if(!$agent->isMobile())
          <div class="col-12 row mt-8">
          @else
          <div class="col-12 mt-8">
          @endif
            <div class="col-12 col-lg-6" style="height:200px;">
              <div class=" do-next-div-1" style="height:100%;">
                <span class="d-block do-next-text-1">{{__('lang.consultOurCounsellor')}}</span>
                <span class="d-block do-next-text-2 pt-1">{{__('lang.consultOurCounsellorDesc')}}</span>
                <a class="do-next-btn-1 d-inline-block mt-3" href="/consult-counsellor/{{$course->c_url_code}}">{{__('lang.consultForFree')}}</a>
              </div>
            </div>
            <div class="col-12 col-lg-6 mt-5 mt-lg-0" style="height:200px;">
              <div class="do-next-div-2" style="height:100%;">
                <span class="d-block do-next-text-1 text-white">{{__('lang.compareCourse')}}</span>
                <span class="d-block do-next-text-2 pt-1 text-white">{{__('lang.compareCourseDesc')}}</span>
                <a class="do-next-btn-2 d-inline-block mt-9" href="/compare-course">{{__('lang.compareNow')}}</a>
              </div>
            </div>
          </div>
        </div>

        @if(isset($recommendation) && count($recommendation) > 0)
        <div class="mt-15">
          <span class="criteria d-block">{{__('lang.otherYouMayLike')}}</span>
          @if(!$agent->isMobile())
          <div class="col-12 row mt-8">
          @else
          <div class="d-flex overflow-scroll mt-8">
          @endif
            @foreach($recommendation as $r)
            <div class="col-10 col-lg-6 mb-5 me-5 me-lg-0">
              <a href="/course-details/{{$r->c_url_code}}">
                <div class="col-12 explore-course-outter">
                  <img class="course-uni-img" src="{{$r->uni_logo}}">
                  <div>
                    <span class="d-block uni-name pt-5">{{$r->c_name}}</span>
                    <span class="uni-location d-block">{{$r->u_name}}</span>
                    <div class="separator my-5"></div>
                    <div class="col-12 row">
                      <div class="col-4 col-lg-3">
                        <span class="uni-location d-block">{{ __('lang.duration') }}</span>
                        <span class="uni-total d-block pt-2">{{$r->duration}}</span>
                      </div>
                      <div class="col-8 col-lg-9">
                        <span class="uni-location d-block">{{ __('lang.intake') }}</span>
                        <span class="uni-total d-block pt-2">{{$r->intake}}</span>
                      </div>
                    </div>
                    <div class="separator my-5"></div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.totalFees') }}</span>
                      <span class="uni-total">{{$r->ttl_fees}}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.deals') }}</span>
                      <span class="uni-total">{{$r->deals}}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                      <span class="uni-location">{{ __('lang.scholarship') }}</span>
                      <span class="uni-total">{{$r->scholarship}}</span>
                    </div>
                    <div class="separator my-5"></div>
                    <span class="uni-total d-block text-end">MYR<span class="ttl-amt"> {{$r->ttl_amt}}</span></span>
                  </div>
                </div>
              </a>
            </div>
            @endforeach
          </div>
        </div>
        @endif

      </div>
      <div class="col-12 col-lg-4">
        <div class="ms-lg-5 mt-10 mt-lg-0">
          <span class="criteria d-block">{{ __('lang.applyCourseNow') }}</span>
          <div class="mt-5">
            <span>{!! $course->c_entry_req !!}</span>
          </div>
          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.step1')}}</span>
            <span class="admission-text d-block mb-10">{{__('lang.yourAdmissionQualifi')}}</span>
            @if(isset($return_grade) && count($return_grade) > 0)
            @foreach($return_grade as $i => $g)
            <div class="mb-5">
              <input type="radio" class="btn-check" name="qualification" value="{{$i + 1}}" id="kt_qualification_option_{{$i}}"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_qualification_option_{{$i}}">
                <div class="col-12 row align-items-center">
                  <div class="col-8">
                    <span class="d-block fw-semibold text-start">
                      <span class="do-next-text-1 d-block">{{$g['grade']}}</span>
                      <span class="do-next-text-2 d-block">{{$g['subject']}}</span>
                    </span>
                  </div>
                  <div class="col-4 p-0 text-end">
                    <span class="submission-user-label">{{$g['level']}}</span>
                  </div>
                </div>
              </label>
            </div>
            @endforeach
            <div class="mb-5">
              <input type="radio" class="btn-check" name="qualification" value="99" id="kt_qualification_option_99"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_qualification_option_99">
                <div class="col-12 row align-items-center">
                  <div class="col-8">
                    <span class="d-block fw-semibold text-start">
                      <span class="do-next-text-1 d-block">Others</span>
                      <span class="do-next-text-2 d-block"></span>
                    </span>
                  </div>
                  <div class="col-4 p-0 text-end">
                    <span class="submission-user-label"></span>
                  </div>
                </div>
              </label>
            </div>
            @endif
          </div>
          <div class="text-center">
            <span class="other-qualifications-text d-block">{{__('lang.otherQualifi')}}</span>
            <a class="talk-to-consult" href="/consult-counsellor/{{$course->c_url_code}}" style="color:#1A124E;">{{__('lang.talkToOurCounsellor')}}</a>
          </div>

          <div class="mt-15 separator-top"></div>

          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.step2')}}</span>
            <span class="admission-text d-block">{{__('lang.typeOfStudent')}}</span>
            <div class="mt-10 mb-5">
              <input type="radio" class="btn-check" name="student_type" value="1" id="kt_student_type_option_2"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_student_type_option_2">
                <div class="text-start">
                  <span class="do-next-text-1 d-block">{{__('lang.malaysiaStudent')}}</span>
                </div>
              </label>
            </div>
            <div class="mb-5">
              <input type="radio" class="btn-check" name="student_type" value="2" id="kt_student_type_option_1"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_student_type_option_1">
                <div class="text-start">
                  <span class="do-next-text-1 d-block">{{__('lang.internationalStudent')}}</span>
                </div>
              </label>
            </div>
          </div>

          <div class="mt-15 separator-top"></div>

          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.step3')}}</span>
            <span class="admission-text d-block">{{__('lang.areYouBumi')}}</span>
            <div class="mt-10 mb-5">
              <input type="radio" class="btn-check" name="bumiputera" value="1" id="kt_bumiputera_option_2"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_bumiputera_option_2">
                <div class="text-start">
                  <span class="do-next-text-1 d-block">{{__('lang.iAmBumi')}}</span>
                </div>
              </label>
            </div>
            <div class="mb-5">
              <input type="radio" class="btn-check" name="bumiputera" value="2" id="kt_bumiputera_option_1"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_bumiputera_option_1">
                <div class="text-start">
                  <span class="do-next-text-1 d-block">{{__('lang.iAmNonBumi')}}</span>
                </div>
              </label>
            </div>
          </div>

          <div class="mt-15 separator-top"></div>
          @if($course->c_english_required == 1)
          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.importantCap')}}</span>
            <span class="admission-text d-block">{{__('lang.englishRequirement')}}</span>
            <div class="mt-10 mb-5">
              <input type="radio" class="btn-check" name="radio_buttons_4" value="1" id="kt_radio_buttons_4_option_1"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_radio_buttons_4_option_1">
                <div class="text-start">
                  <span class="do-next-text-1 d-block">{{__('lang.englishResults')}}</span>
                  <ul class="mt-5">
                    <li class="requirement-list">MUET - Band 3</li>
                    <li class="requirement-list">IELTS - 5.5</li>
                    <li class="requirement-list">TOEFL - IBT (65)</li>
                    <li class="requirement-list">GSCE/GCE ‘O’ Level - C</li>
                  </ul>
                </div>
              </label>
            </div>
          </div>

          <div class="mt-15 separator-top"></div>
          @endif

          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.step4')}}</span>
            <span class="admission-text d-block">{{__('lang.awardsAndScholarships')}}</span>
            <div class="mt-10 mb-5">
              <input type="radio" class="btn-check" name="is_scholarship" value="1" id="kt_is_scholarship_option_1"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_is_scholarship_option_1">
                <span class="do-next-text-1">{{__('lang.iWantScholarship')}}</span>
              </label>
            </div>
            <div class="mb-5">
              <input type="radio" class="btn-check" name="is_scholarship" value="2" id="kt_is_scholarship_option_2"/>
              <label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_is_scholarship_option_2">
                <span class="do-next-text-1">{{__('lang.iDontWantScholarship')}}</span>
              </label>
            </div>
          </div>

          <div class="mt-15 separator-top"></div>

          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.step5')}}</span>
            <span class="mb-10 admission-text d-block">{{__('lang.selectScholarship')}}</span>
            <div id="scholarship_list"></div>
          </div>

          <div class="mt-15 separator-top"></div>

          <div class="mt-10">
            <span class="explore-filter-title d-block">{{__('lang.final')}}</span>
            <span class="admission-text d-block">{{__('lang.feesAndBursary')}}</span>
            <div class="btn-admission mt-7">
              <div id="fees_list"></div>
              <div class="mb-5 col-12 m-0 p-0 align-items-center" id="scholarship_discount" style="display:none">
                <div class="col-6">
                  <span class="d-block fw-semibold text-start">
                    <span class="requiremnt-fees-text-1 d-block">{{__('lang.awardScholarship')}}</span>
                  </span>
                </div>
                <div class="col-6 p-0 d-flex justify-content-end align-items-center">
                  <span class="requiremnt-fees-text-2">-MYR <span id="scholarship_amt"></span></span>
                </div>
              </div>
              <div class="mt-5 mb-5 separator-top"></div>
              <div class="col-12 d-flex m-0 p-0 align-items-center">
                <div class="col-7">
                  <span class="d-block fw-semibold text-start">
                    <span class="do-next-text-1 d-block">{{__('lang.estimateTotalFees')}}</span>
                  </span>
                </div>
                <div class="col-5 p-0 d-flex justify-content-end align-items-center">
                  <span class="requiremnt-fees-ttl-amt" id="ttl_course_fee"></span>
                  <input type="hidden" value="" id="ttl_course_hidden">
                  <input type="hidden" value="" id="new_ttl_course_hidden">
                </div>
              </div>
            </div>
          </div>

          <div class="mt-10">
            <div id="deposit_list"></div>
          </div>

          <div class="mt-10">
            <button class="w-100 select-offer-btn d-block text-center" data-navigate-url="/course-application/{{$course->c_url_code}}" disabled>{{__('lang.secureYourOffer')}}</button>
          </div>
        </div>
      </div>
    </div>
  </div> -->
</div>
@endsection

@section('modal')
<div class="modal fade" tabindex="-1" id="checkfeesModal" style="background: rgba(0, 0, 0, 0.50);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="padding:36px;">
            <div class="modal-body p-0">
                <span class="modal-pathway-title d-block">Total Fees</span>
                <div style="padding-top:36px;">
                    <!-- Table with 3 columns -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th class="text-center"><strong>Bumiputra</strong></th>
                                <th class="text-center"><strong>Non Bumiputra</strong></th>
                                <th class="text-center"><strong>International</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalBumiputra = 0;
                                $totalNonBumiputra = 0;
                                $totalInternational = 0;
                            @endphp

                            <tr><td colspan="4"><hr></td></tr>

                            @foreach ($fees as $f)
                                <tr>
                                    <td>{{ $f->cf_name }}</td>
                                    <td class="text-center">RM{{ $f->cf_amount_bumi }}</td >
                                    <td class="text-center">RM{{ $f->cf_amount }}</td >
                                    <td class="text-center">RM{{ $f->cf_amount_international }}</td>
                                </tr>
                                <tr><td colspan="4"><hr></td></tr>
                                @php
                                    $totalBumiputra += $f->cf_amount_bumi;
                                    $totalNonBumiputra += $f->cf_amount;
                                    $totalInternational += $f->cf_amount_international;
                                @endphp
                            @endforeach

                            @foreach ($deposit as $d)
                                <tr>
                                    <td>{{ $d->cf_name }}</td>
                                    <td class="text-center">RM{{ $d->cf_amount_bumi }}</td>
                                    <td class="text-center">RM{{ $d->cf_amount }}</td>
                                    <td class="text-center">RM{{ $d->cf_amount_international }}</td>
                                </tr>
                                <tr><td colspan="4"><hr></td></tr>
                                @php
                                    $totalBumiputra += $d->cf_amount_bumi;
                                    $totalNonBumiputra += $d->cf_amount;
                                    $totalInternational += $d->cf_amount_international;
                                @endphp
                            @endforeach

                            <tr>
                                <td>Tuition Fees</td>
                                <td class="text-center">RM{{ $course->c_amount_bumi }}</td>
                                <td class="text-center">RM{{ $course->c_amount }}</td>
                                <td class="text-center">RM{{ $course->c_amount_international }}</td>
                            </tr>

                            <tr><td colspan="4"><hr></td></tr>

                            <tr>
                                <td><strong>Estimated Total Fees</strong></td>
                                <td class="text-center"><strong>RM{{ $totalBumiputra + $course->c_amount_bumi }}</strong></td>
                                <td class="text-center"><strong>RM{{ $totalNonBumiputra + $course->c_amount }}</strong></td>
                                <td class="text-center"><strong>RM{{ $totalInternational + $course->c_amount_international }}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="padding-top:36px;">
                    <button class="w-100 apply-scholarship-btn" data-bs-dismiss="modal">Okay</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-close-btn cursor" data-bs-dismiss="modal">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1 1L13 13" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M13 1L0.999999 13" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
        </svg>
    </div>
</div>
@endsection

@section('script')
<script>
  $(document).ready(function() {
    getScholarship();
    getPrice();

    $('input[name=student_type]').change(function(){
      getPrice();
      updateBtn()
      var value = $( 'input[name=student_type]:checked' ).val();

      if(value == 2) {
        $('input[name=bumiputera]').attr("disabled",true);
        $('input[name=bumiputera]:checked').each(function(i) {
          $(this).prop('checked', false);
        });
      } else{
        $('input[name=bumiputera]').attr("disabled",false);
      }
    });

    $('input[name=bumiputera]').change(function(){
      getPrice();
      updateBtn()
      var value = $( 'input[name=bumiputera]:checked' ).val();
    });

    // $('input[name=is_scholarship]').change(function(){
    //   updateBtn()
    //   var value = $( 'input[name=is_scholarship]:checked' ).val();

    //   if(value == 2) {
    //     $('input[name=scholarship]:checked').each(function(i) {
    //       $(this).prop('checked', false);
    //     });
    //     $('input[name=scholarship]').attr("disabled",true);
    //     $('.step-five-select-scholarship').hide();
    //   } else{
    //     $('input[name=scholarship]').attr("disabled",false);
    //     $('.step-five-select-scholarship').show();
    //   }
    // });

    $(document).on('change', 'input[name=scholarship]', function(){
      var amt = $( 'input[name=scholarship]:checked' ).data('amount');
      var type = $( 'input[name=scholarship]:checked' ).data('type');

      updateBtn();
      getScholarshipAmt(amt, type);
    });

    $('input[name=qualification]').change(function(){
      updateBtn()
      var value = $( 'input[name=qualification]:checked' ).val();
    });

    function getScholarship()
    {
      var uni_id = {{$course->u_id}};

      $.ajax({
        url: "/getScholarshipByUni",
        method: 'post',
        data: {
          uni_id: uni_id,
        },
        success: function(d) {
          var content = '';

          if (d.status) {
            var items = d.list;
            items.forEach(function(item, index) {
              content += '<div class="mb-5">';
              content += '<input type="radio" class="btn-check" name="scholarship" value="'+item.id+'" data-type="'+item.type+'" data-amount="'+item.amount+'" id="kt_scholarship_option_'+index+'"/>';
              content += '<label class="btn btn-admission btn-active-light-primary d-flex align-items-center" for="kt_scholarship_option_'+index+'">';
              content += '<div class="col-12 row m-0 p-0 align-items-center">';
              content += '<div class="col-8">';
              content += '<span class="d-block fw-semibold text-start">';
              content += '<span class="do-next-text-1 d-block">'+item.max_discount+'</span>';
              content += '<span class="do-next-text-2 d-block">'+item.name+'</span>';
              content += '</span>';
              content += '</div>';
              content += '<div class="col-4 p-0 d-flex justify-content-end align-items-center">';
              content += '<a class="requirement-text">Requirements</a>';
              content += '<svg class="ms-1" xmlns="http://www.w3.org/2000/svg" width="6" height="9" viewBox="0 0 6 9" fill="none">';
              content += '<path d="M1 0.5L5 4.5L1 8.5" stroke="#1A124E" stroke-linecap="round" stroke-linejoin="round"/>';
              content += '</svg>';
              content += '</div>';
              content += '</div>';
              content += '</label>';
              content += '</div>';
            });

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

    function getPrice()
    {
      var student_type = $( 'input[name=student_type]:checked' ).val();
      var bumiputera = $( 'input[name=bumiputera]:checked' ).val();

      var id = {{$course->c_id}};
      var type = 0;

      if(student_type == 2) {
        type = 3;
      } else if(student_type == 1) {
        if(bumiputera != undefined) {
          if(bumiputera == 1) {
            type = 2;
          } else{
            type = 1;
          }
        }
      }

      $.ajax({
        url: "/getCoursePrice",
        method: 'post',
        data: {
          id: id,
          type: type
        },
        success: function(d) {
          var content_deposit = '';
          var content = '';
          if(d.status) {
            var items = d.deposit;
            var fees = d.fees;

            $('#ttl_course_fee').html('MYR '+parseInt(d.total).toLocaleString());
            $('#ttl_course_hidden').val(d.total);
            $('#new_ttl_course_hidden').val(d.total);

            if(items.length > 0){
              content_deposit += '<div class="btn-admission">';
              items.forEach(function(item, index) {
                content_deposit += '<div class="col-12 d-flex align-items-center">';
                content_deposit += '<div class="col-6">';
                content_deposit += '<span class="d-block fw-semibold text-start">';
                content_deposit += '<span class="requiremnt-fees-text-1 d-block">'+item.name+'</span>';
                content_deposit += '</span>';
                content_deposit += '</div>';
                content_deposit += '<div class="col-6 p-0 d-flex justify-content-end align-items-center">';
                content_deposit += '<span class="requiremnt-fees-text-2">'+item.amt+'</span>';
                content_deposit += '</div>';
                content_deposit += '</div>';
              });
              content_deposit += '<span class="uni-location d-block pt-2">This deposit is claimable after graduation</span>';
              content_deposit += '</div>';
            }

            fees.forEach(function(item, index) {
              content += '<div class="mb-5 col-12 d-flex m-0 p-0 align-items-center">';
              content += '<div class="col-6">';
              content += '<span class="d-block fw-semibold text-start">';
              content += '<span class="requiremnt-fees-text-1 d-block">'+item.name+'</span>';
              content += '</span>';
              content += '</div>';
              content += '<div class="col-6 p-0 d-flex justify-content-end align-items-center">';
              content += '<span class="requiremnt-fees-text-2">'+item.amt+'</span>';
              content += '</div>';
              content += '</div>';
            });

            content += '<div class="mb-5 col-12 d-flex m-0 p-0 align-items-center">';
            content += '<div class="col-6">';
            content += '<span class="d-block fw-semibold text-start">';
            content += '<span class="requiremnt-fees-text-1 d-block">Tuition fee</span>';
            content += '</span>';
            content += '</div>';
            content += '<div class="col-6 p-0 d-flex justify-content-end align-items-center">';
            content += '<span class="requiremnt-fees-text-2">'+d.uni_fee+'</span>';
            content += '</div>';
            content += '</div>';

            $('#fees_list').empty().append(content);
            $('#deposit_list').empty().append(content_deposit);
          } else{
            $('#deposit_list').empty();
            $('#fees_list').empty().append(content);
          }
        }
      })
    }

    function updateBtn()
    {
      var qualification = $('input[name="qualification"]:checked').val();
      var student = $('input[name="student_type"]:checked').val();
      var bumiputera = $('input[name="bumiputera"]:checked').val();
      // var awards = $('input[name="is_scholarship"]:checked').val();
      var scholarship = $('input[name="scholarship"]:checked').val();

      var disable = true;
      var is_disable = true;

      // if(qualification != undefined && student != undefined && awards != undefined) {
      if(qualification != undefined && student != undefined) {

        // if(student == 1 && awards == 1) {
        if(student == 1) {
          if(bumiputera != undefined) {
          // if(bumiputera != undefined && scholarship != undefined) {
            disable = false;
          } else{
            disable = true;
          }
        // } else if(student == 1 && awards == 2) {
        } else if(student == 1) {
          if(bumiputera == undefined) {
            disable = true;
          } else{
            disable = false;
          }
        } else if(student == 2) {
        // } else if(student == 2 && awards == 1) {
          // if(scholarship == undefined) {
          //   disable = true;
          // } else{
            disable = false;
          // }
        } else if(student == 2) {
        // } else if(student == 2 && awards == 2) {
          disable = false;
        }

      } else{
        disable = true;
      }

      $('.select-offer-btn').prop("disabled",disable);
    }

    function getScholarshipAmt(amt, type)
    {
      var ttl = $('#ttl_course_hidden').val();
      var new_ttl = 0;

      if(type == 1) {
        new_ttl = ttl - amt;
      } else if(type == 2) {
        new_ttl = ttl * amt / 100
      }

      
      $('#scholarship_discount').css('display', 'flex');
      if(type == 1) {
        $('#scholarship_amt').html(parseInt(amt).toLocaleString());
        $('#ttl_course_fee').html('MYR '+parseInt(new_ttl).toLocaleString());
        $('#new_ttl_course_hidden').val(new_ttl);
      } else{
        $('#scholarship_amt').html(parseInt(new_ttl).toLocaleString());
        $('#ttl_course_fee').html('MYR '+parseInt(ttl - new_ttl).toLocaleString());
        $('#new_ttl_course_hidden').val(ttl - new_ttl);
      }
      
    }

    $('.select-offer-btn').on('click', function(){
      var url = $(this).data('navigate-url');
      var price = $('#new_ttl_course_hidden').val();

      window.location.href = url;
      sessionStorage.setItem("course_price", price);
    })

    $(document).ready(function() {
        $(document).on('click', '[id^="viewreq_"]', function(){
            var id = $(this).attr('id').split('_')[1];
            $('#viewreq_modal_' + id).modal('show');
        });
    });

    $(document).ready(function() {
        $(document).on('click', '[id="checkfees"]', function(){
            $('#checkfeesModal').modal('show');
        });
    });
  });
</script>
@endsection
