@extends('memberLayout')
@section('style')
<style>
  .home-btn-get-started-2 {
    border:2px solid;
    border-color:#1A124E;
  }

  .home-btn-get-started-2:hover {
    background-color:white;
    color:#1A124E;
  }
</style>
@endsection

@section('content')
@php
use \Jenssegers\Agent\Agent;
$agent = new Agent();
@endphp

@if(!$agent->isMobile())
<div class="mw-1200 mx-auto pt-140 pb-20">
  <div class="row">
    <div class="col-2">
      <img src="{{$scholarship->logo}}" class="uni-logo">
    </div>
    <div class="col-8 mt-5">
      <div class="ms-5">
        <div class="col-12 d-flex justify-content-between">
          <span class="uni-name-1">{{$scholarship->s_title}}</span>
        </div>
        <span class="review-desc d-block">{{$scholarship->u_name}}</span>
        <div class="d-flex align-items-center mt-5">
          <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
            <path d="M18 24C23.799 24 28.5 19.299 28.5 13.5C28.5 7.70101 23.799 3 18 3C12.201 3 7.5 7.70101 7.5 13.5C7.5 19.299 12.201 24 18 24Z" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M18 18C20.4853 18 22.5 15.9853 22.5 13.5C22.5 11.0147 20.4853 9 18 9C15.5147 9 13.5 11.0147 13.5 13.5C13.5 15.9853 15.5147 18 18 18Z" fill="#1F1F1F"/>
            <path d="M10.5 21V30.351C10.5002 30.6163 10.5708 30.8767 10.7045 31.1059C10.8382 31.335 11.0302 31.5245 11.2611 31.6552C11.4919 31.7859 11.7533 31.853 12.0186 31.8497C12.2838 31.8464 12.5435 31.7728 12.771 31.6365L17.229 28.9635C17.462 28.8239 17.7284 28.7502 18 28.7502C18.2716 28.7502 18.538 28.8239 18.771 28.9635L23.229 31.6365C23.4565 31.7728 23.7162 31.8464 23.9814 31.8497C24.2467 31.853 24.5081 31.7859 24.7389 31.6552C24.9698 31.5245 25.1618 31.335 25.2955 31.1059C25.4292 30.8767 25.4998 30.6163 25.5 30.351V21" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
          </svg>
          <span class="ps-3 award-text">{{__('lang.awardsUpTo')}}</span>
          <span class="ps-3 award-amt">{{$return_max_discount}}</span>
        </div>
        <div class="col-12 mt-8 d-flex">
          <div class="col-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M8 9C7.50555 9 7.0222 8.85338 6.61108 8.57868C6.19995 8.30397 5.87952 7.91353 5.6903 7.45671C5.50108 6.99989 5.45157 6.49723 5.54804 6.01228C5.6445 5.52732 5.8826 5.08187 6.23223 4.73223C6.58187 4.3826 7.02732 4.1445 7.51228 4.04804C7.99723 3.95157 8.4999 4.00108 8.95671 4.1903C9.41353 4.37952 9.80397 4.69995 10.0787 5.11108C10.3534 5.5222 10.5 6.00555 10.5 6.5C10.4992 7.1628 10.2356 7.79822 9.76689 8.26689C9.29822 8.73556 8.6628 8.99921 8 9ZM8 5C7.70333 5 7.41332 5.08797 7.16665 5.2528C6.91997 5.41762 6.72771 5.65189 6.61418 5.92598C6.50065 6.20007 6.47095 6.50167 6.52882 6.79264C6.5867 7.08361 6.72956 7.35088 6.93934 7.56066C7.14912 7.77044 7.41639 7.9133 7.70737 7.97118C7.99834 8.02906 8.29994 7.99935 8.57403 7.88582C8.84812 7.77229 9.08238 7.58003 9.24721 7.33336C9.41203 7.08668 9.5 6.79667 9.5 6.5C9.4996 6.1023 9.34144 5.721 9.06022 5.43978C8.779 5.15856 8.3977 5.0004 8 5Z" fill="#999999"/>
              <path d="M8.00001 15L3.78201 10.0255C3.7234 9.95081 3.66539 9.87564 3.60801 9.8C2.8875 8.85089 2.49826 7.69161 2.50001 6.5C2.50001 5.04131 3.07947 3.64236 4.11092 2.61091C5.14237 1.57946 6.54132 1 8.00001 1C9.4587 1 10.8576 1.57946 11.8891 2.61091C12.9205 3.64236 13.5 5.04131 13.5 6.5C13.5018 7.69107 13.1127 8.84982 12.3925 9.7985L12.392 9.8C12.392 9.8 12.242 9.997 12.2195 10.0235L8.00001 15ZM4.40601 9.1975C4.40701 9.1975 4.52301 9.3515 4.54951 9.3845L8.00001 13.454L11.455 9.379C11.477 9.3515 11.594 9.1965 11.5945 9.196C12.1831 8.42056 12.5012 7.47352 12.5 6.5C12.5 5.30653 12.0259 4.16193 11.182 3.31802C10.3381 2.47411 9.19348 2 8.00001 2C6.80653 2 5.66194 2.47411 4.81803 3.31802C3.97411 4.16193 3.50001 5.30653 3.50001 6.5C3.49896 7.47412 3.81689 8.42171 4.40601 9.1975Z" fill="#999999"/>
            </svg>
            <span class="submission-user-label">{{__('lang.location')}}:</span>
          </div>
          <div class="col-5 ms-5">
            <span class="course-details">{{$scholarship->s_is_corporate == 0 ? $scholarship->u_name : $scholarship->s_c_name}}</span>
          </div>
        </div>
        <div class="col-12 mt-3 d-flex">
          <div class="col-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M14.25 9.25V6L8 2.75L1.75 6L8 9.25L11.25 7.75V11.25C11.25 12.25 9.75 13.25 8 13.25C6.25 13.25 4.75 12.25 4.75 11.25V7.75" stroke="#999999" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span class="submission-user-label">{{__('lang.courseLevel')}}:</span>
          </div>
          <div class="ms-5">
            <span class="course-details">{{ implode(', ', $scholarship->level) }}</span>
          </div>
        </div>
        <div class="col-12 mt-3 d-flex">
          <div class="col-2">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
              <path d="M14.5 2.51563H10.925C10.1578 2.51563 9.40781 2.73594 8.7625 3.15156L8 3.64063L7.2375 3.15156C6.59283 2.73602 5.84199 2.51522 5.075 2.51563H1.5C1.22344 2.51563 1 2.73906 1 3.01563V11.8906C1 12.1672 1.22344 12.3906 1.5 12.3906H5.075C5.84219 12.3906 6.59219 12.6109 7.2375 13.0266L7.93125 13.4734C7.95156 13.4859 7.975 13.4938 7.99844 13.4938C8.02187 13.4938 8.04531 13.4875 8.06563 13.4734L8.75937 13.0266C9.40625 12.6109 10.1578 12.3906 10.925 12.3906H14.5C14.7766 12.3906 15 12.1672 15 11.8906V3.01563C15 2.73906 14.7766 2.51563 14.5 2.51563ZM5.075 11.2656H2.125V3.64063H5.075C5.62812 3.64063 6.16562 3.79844 6.62969 4.09688L7.39219 4.58594L7.5 4.65625V11.875C6.75625 11.475 5.925 11.2656 5.075 11.2656ZM13.875 11.2656H10.925C10.075 11.2656 9.24375 11.475 8.5 11.875V4.65625L8.60781 4.58594L9.37031 4.09688C9.83438 3.79844 10.3719 3.64063 10.925 3.64063H13.875V11.2656ZM6.20156 5.64063H3.29844C3.2375 5.64063 3.1875 5.69375 3.1875 5.75781V6.46094C3.1875 6.525 3.2375 6.57813 3.29844 6.57813H6.2C6.26094 6.57813 6.31094 6.525 6.31094 6.46094V5.75781C6.3125 5.69375 6.2625 5.64063 6.20156 5.64063ZM9.6875 5.75781V6.46094C9.6875 6.525 9.7375 6.57813 9.79844 6.57813H12.7C12.7609 6.57813 12.8109 6.525 12.8109 6.46094V5.75781C12.8109 5.69375 12.7609 5.64063 12.7 5.64063H9.79844C9.7375 5.64063 9.6875 5.69375 9.6875 5.75781ZM6.20156 7.82813H3.29844C3.2375 7.82813 3.1875 7.88125 3.1875 7.94531V8.64844C3.1875 8.7125 3.2375 8.76563 3.29844 8.76563H6.2C6.26094 8.76563 6.31094 8.7125 6.31094 8.64844V7.94531C6.3125 7.88125 6.2625 7.82813 6.20156 7.82813ZM12.7016 7.82813H9.79844C9.7375 7.82813 9.6875 7.88125 9.6875 7.94531V8.64844C9.6875 8.7125 9.7375 8.76563 9.79844 8.76563H12.7C12.7609 8.76563 12.8109 8.7125 12.8109 8.64844V7.94531C12.8125 7.88125 12.7625 7.82813 12.7016 7.82813Z" fill="#999999"/>
            </svg>
            <span class="submission-user-label">{{__('lang.fieldOfStudy')}}:</span>
          </div>
          <div class="ms-5">
            @if($scholarship->has_all_cat)
            <span class="course-details">{{__('lang.allFields')}}</span>
            @else 
            <span class="course-details">{{$scholarship->applicable}}</span>
            @endif
          </div>
        </div>

        <div class="mt-10">
          <span class="criteria d-block">{{__('lang.criteria')}}</span>
          <div class="mt-5">
            {!! $scholarship->s_criteria !!}
          </div>
        </div>

        <div class="mt-20">
          <span class="criteria d-block">{{__('lang.highlights')}}</span>
          <div class="mt-5">
            {!! $scholarship->s_highlights !!}
          </div>
        </div>

        <div class="mt-20">
          <span class="criteria d-block">{{__('lang.awards')}}</span>
          <div class="mt-5">
            {!! $scholarship->s_award !!}
          </div>
          <div class="mt-8">
            @if($type == 0)
            <table class="col-12">
              <tr class="">
                <th class="col-4 award-text table-header">{{__('lang.course')}}</th>
                <th class="col-4 award-text table-header">{{__('lang.grade')}}</th>
                <th class="col-4 award-text table-header">{{__('lang.scholarshipAmount')}}</th>
              </tr>
              @if(isset($return_discount) && count($return_discount) > 0)
                @foreach($return_discount as $i => $rd)
                <tr>
                  <td class="award-text table-body">{{$i}}</td>
                  <td class="table-body">
                    @foreach($rd as $r)
                    <span class="award-text d-block pt-3">{{$r['total']}}</span>
                    @endforeach
                  </td>
                  <td class="table-body">
                    @foreach($rd as $r)
                    <span class="award-text d-block pt-3">{{$r['amount']}}</span>
                    @endforeach
                  </td>
                </tr>
                @endforeach
              @endif
              <!-- <tr>
                <td class="award-text table-body">STPM</td>
                <td class="table-body">
                  <span class="award-text d-block pt-3">≥ CGPA 3.55</span>
                  <span class="award-text d-block pt-3">≥ CGPA 3.22</span>
                  <span class="award-text d-block pt-3">≥ CGPA 3.00</span>
                </td>
                <td class="table-body">
                  <span class="award-text d-block pt-3">MYR 12,000/year</span>
                  <span class="award-text d-block pt-3">MYR 7,000/year</span>
                  <span class="award-text d-block pt-3">MYR 4,000/year</span>
                </td>
              </tr>
              <tr>
                <td class="award-text table-body">STPM</td>
                <td class="table-body">
                  <span class="award-text d-block pt-3">≥ CGPA 3.55</span>
                  <span class="award-text d-block pt-3">≥ CGPA 3.22</span>
                  <span class="award-text d-block pt-3">≥ CGPA 3.00</span>
                </td>
                <td class="table-body">
                  <span class="award-text d-block pt-3">MYR 12,000/year</span>
                  <span class="award-text d-block pt-3">MYR 7,000/year</span>
                  <span class="award-text d-block pt-3">MYR 4,000/year</span>
                </td>
              </tr>
              <tr>
                <td class="award-text table-body">STPM</td>
                <td class="table-body">
                  <span class="award-text d-block pt-3">≥ CGPA 3.55</span>
                  <span class="award-text d-block pt-3">≥ CGPA 3.22</span>
                  <span class="award-text d-block pt-3">≥ CGPA 3.00</span>
                </td>
                <td class="table-body">
                  <span class="award-text d-block pt-3">MYR 12,000/year</span>
                  <span class="award-text d-block pt-3">MYR 7,000/year</span>
                  <span class="award-text d-block pt-3">MYR 4,000/year</span>
                </td>
              </tr> -->
            </table>
            @else 
            <div class="col-12 bg-grey br-12" style="padding: 24px;">
              {!! $return_discount['sd_desc'] !!}
            </div>
            @endif
          </div>
        </div>

        <div class="mt-20">
          <span class="criteria d-block">{{__('lang.details')}}</span>
          <div class="mt-5">
            {!! $scholarship->s_details !!}
          </div>
        </div>

        <div class="mt-20">
          <span class="criteria d-block">{{__('lang.application')}}</span>
          <div class="mt-5">
            {!! $scholarship->s_application !!}
          </div>
        </div>

        <div class="mt-20">
        <!-- <a href="/" style="color:white;"><div class="home-btn-get-started-2 text-center">Apply This Scholarship</div></a> -->
        </div>

      </div>
    </div>
    <div class="col-2 mt-5 d-flex justify-content-flex-start flex-column">
      <button class="header-started-active" id="login_btn" style="border: 0px;">{{__('lang.findOffers')}}</button>
    </div>
  </div>
</div>
@else 
<div class="mw-1200 pt-140 pb-20">
  <div class="col-12 d-flex align-items-center">
    <div>
      <img src="{{$scholarship->logo}}" class="uni-logo">
    </div>
    <div>
      <span class="uni-name-1 ms-3">{{$scholarship->s_title}}</span>
      <span class="review-desc d-block ms-3">{{$scholarship->u_name}}</span>
    </div>
    <div style="margin-left: auto; margin-right: 0;">
      <button class="header-started-active" id="login_btn" style="border: 0px;">{{__('lang.findOffers')}}</button>
    </div>
  </div>
  <div class="d-flex align-items-center mt-5">
    <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 36 36" fill="none">
      <path d="M18 24C23.799 24 28.5 19.299 28.5 13.5C28.5 7.70101 23.799 3 18 3C12.201 3 7.5 7.70101 7.5 13.5C7.5 19.299 12.201 24 18 24Z" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      <path d="M18 18C20.4853 18 22.5 15.9853 22.5 13.5C22.5 11.0147 20.4853 9 18 9C15.5147 9 13.5 11.0147 13.5 13.5C13.5 15.9853 15.5147 18 18 18Z" fill="#1F1F1F"/>
      <path d="M10.5 21V30.351C10.5002 30.6163 10.5708 30.8767 10.7045 31.1059C10.8382 31.335 11.0302 31.5245 11.2611 31.6552C11.4919 31.7859 11.7533 31.853 12.0186 31.8497C12.2838 31.8464 12.5435 31.7728 12.771 31.6365L17.229 28.9635C17.462 28.8239 17.7284 28.7502 18 28.7502C18.2716 28.7502 18.538 28.8239 18.771 28.9635L23.229 31.6365C23.4565 31.7728 23.7162 31.8464 23.9814 31.8497C24.2467 31.853 24.5081 31.7859 24.7389 31.6552C24.9698 31.5245 25.1618 31.335 25.2955 31.1059C25.4292 30.8767 25.4998 30.6163 25.5 30.351V21" stroke="#1F1F1F" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
    <span class="ps-3 award-text">{{__('lang.awardsUpTo')}}</span>
    <span class="ps-3 award-amt">{{$return_max_discount}}</span>
  </div>
  <div class="col-12 mt-8 d-flex">
    <div class="col-5">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
        <path d="M8 9C7.50555 9 7.0222 8.85338 6.61108 8.57868C6.19995 8.30397 5.87952 7.91353 5.6903 7.45671C5.50108 6.99989 5.45157 6.49723 5.54804 6.01228C5.6445 5.52732 5.8826 5.08187 6.23223 4.73223C6.58187 4.3826 7.02732 4.1445 7.51228 4.04804C7.99723 3.95157 8.4999 4.00108 8.95671 4.1903C9.41353 4.37952 9.80397 4.69995 10.0787 5.11108C10.3534 5.5222 10.5 6.00555 10.5 6.5C10.4992 7.1628 10.2356 7.79822 9.76689 8.26689C9.29822 8.73556 8.6628 8.99921 8 9ZM8 5C7.70333 5 7.41332 5.08797 7.16665 5.2528C6.91997 5.41762 6.72771 5.65189 6.61418 5.92598C6.50065 6.20007 6.47095 6.50167 6.52882 6.79264C6.5867 7.08361 6.72956 7.35088 6.93934 7.56066C7.14912 7.77044 7.41639 7.9133 7.70737 7.97118C7.99834 8.02906 8.29994 7.99935 8.57403 7.88582C8.84812 7.77229 9.08238 7.58003 9.24721 7.33336C9.41203 7.08668 9.5 6.79667 9.5 6.5C9.4996 6.1023 9.34144 5.721 9.06022 5.43978C8.779 5.15856 8.3977 5.0004 8 5Z" fill="#999999"/>
        <path d="M8.00001 15L3.78201 10.0255C3.7234 9.95081 3.66539 9.87564 3.60801 9.8C2.8875 8.85089 2.49826 7.69161 2.50001 6.5C2.50001 5.04131 3.07947 3.64236 4.11092 2.61091C5.14237 1.57946 6.54132 1 8.00001 1C9.4587 1 10.8576 1.57946 11.8891 2.61091C12.9205 3.64236 13.5 5.04131 13.5 6.5C13.5018 7.69107 13.1127 8.84982 12.3925 9.7985L12.392 9.8C12.392 9.8 12.242 9.997 12.2195 10.0235L8.00001 15ZM4.40601 9.1975C4.40701 9.1975 4.52301 9.3515 4.54951 9.3845L8.00001 13.454L11.455 9.379C11.477 9.3515 11.594 9.1965 11.5945 9.196C12.1831 8.42056 12.5012 7.47352 12.5 6.5C12.5 5.30653 12.0259 4.16193 11.182 3.31802C10.3381 2.47411 9.19348 2 8.00001 2C6.80653 2 5.66194 2.47411 4.81803 3.31802C3.97411 4.16193 3.50001 5.30653 3.50001 6.5C3.49896 7.47412 3.81689 8.42171 4.40601 9.1975Z" fill="#999999"/>
      </svg>
      <span class="submission-user-label">{{__('lang.location')}}:</span>
    </div>
    <div class="col-5 ms-5">
      <span class="course-details">{{$scholarship->s_is_corporate == 0 ? $scholarship->u_name : $scholarship->s_c_name}}</span>
    </div>
  </div>
  <div class="col-12 mt-3 d-flex">
    <div class="col-5">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
        <path d="M14.25 9.25V6L8 2.75L1.75 6L8 9.25L11.25 7.75V11.25C11.25 12.25 9.75 13.25 8 13.25C6.25 13.25 4.75 12.25 4.75 11.25V7.75" stroke="#999999" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
      <span class="submission-user-label">{{__('lang.courseLevel')}}:</span>
    </div>
    <div class="col-5 ms-5">
      <span class="course-details">{{ implode(', ', $scholarship->level) }}</span>
    </div>
  </div>
  <div class="col-12 mt-3 d-flex">
    <div class="col-5">
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
        <path d="M14.5 2.51563H10.925C10.1578 2.51563 9.40781 2.73594 8.7625 3.15156L8 3.64063L7.2375 3.15156C6.59283 2.73602 5.84199 2.51522 5.075 2.51563H1.5C1.22344 2.51563 1 2.73906 1 3.01563V11.8906C1 12.1672 1.22344 12.3906 1.5 12.3906H5.075C5.84219 12.3906 6.59219 12.6109 7.2375 13.0266L7.93125 13.4734C7.95156 13.4859 7.975 13.4938 7.99844 13.4938C8.02187 13.4938 8.04531 13.4875 8.06563 13.4734L8.75937 13.0266C9.40625 12.6109 10.1578 12.3906 10.925 12.3906H14.5C14.7766 12.3906 15 12.1672 15 11.8906V3.01563C15 2.73906 14.7766 2.51563 14.5 2.51563ZM5.075 11.2656H2.125V3.64063H5.075C5.62812 3.64063 6.16562 3.79844 6.62969 4.09688L7.39219 4.58594L7.5 4.65625V11.875C6.75625 11.475 5.925 11.2656 5.075 11.2656ZM13.875 11.2656H10.925C10.075 11.2656 9.24375 11.475 8.5 11.875V4.65625L8.60781 4.58594L9.37031 4.09688C9.83438 3.79844 10.3719 3.64063 10.925 3.64063H13.875V11.2656ZM6.20156 5.64063H3.29844C3.2375 5.64063 3.1875 5.69375 3.1875 5.75781V6.46094C3.1875 6.525 3.2375 6.57813 3.29844 6.57813H6.2C6.26094 6.57813 6.31094 6.525 6.31094 6.46094V5.75781C6.3125 5.69375 6.2625 5.64063 6.20156 5.64063ZM9.6875 5.75781V6.46094C9.6875 6.525 9.7375 6.57813 9.79844 6.57813H12.7C12.7609 6.57813 12.8109 6.525 12.8109 6.46094V5.75781C12.8109 5.69375 12.7609 5.64063 12.7 5.64063H9.79844C9.7375 5.64063 9.6875 5.69375 9.6875 5.75781ZM6.20156 7.82813H3.29844C3.2375 7.82813 3.1875 7.88125 3.1875 7.94531V8.64844C3.1875 8.7125 3.2375 8.76563 3.29844 8.76563H6.2C6.26094 8.76563 6.31094 8.7125 6.31094 8.64844V7.94531C6.3125 7.88125 6.2625 7.82813 6.20156 7.82813ZM12.7016 7.82813H9.79844C9.7375 7.82813 9.6875 7.88125 9.6875 7.94531V8.64844C9.6875 8.7125 9.7375 8.76563 9.79844 8.76563H12.7C12.7609 8.76563 12.8109 8.7125 12.8109 8.64844V7.94531C12.8125 7.88125 12.7625 7.82813 12.7016 7.82813Z" fill="#999999"/>
      </svg>
      <span class="submission-user-label">{{__('lang.fieldOfStudy')}}:</span>
    </div>
    <div class="col-5 ms-5">
      @if($scholarship->has_all_cat)
        <span class="course-details">{{__('lang.allFields')}}</span>
      @else 
        <span class="course-details">{{$scholarship->applicable}}</span>
      @endif
    </div>
  </div>

  <div class="mt-10">
    <span class="criteria d-block">{{__('lang.criteria')}}</span>
    <div class="mt-5">
      {!! $scholarship->s_criteria !!}
    </div>
  </div>

  <div class="mt-20">
    <span class="criteria d-block">{{__('lang.highlights')}}</span>
    <div class="mt-5">
      {!! $scholarship->s_highlights !!}
    </div>
  </div>

  <div class="mt-20">
    <span class="criteria d-block">{{__('lang.awards')}}</span>
      <div class="mt-5">
        {!! $scholarship->s_award !!}
      </div>
      <div class="mt-8">
        @if($type == 0)
        <table class="col-12">
          <tr class="">
            <th class="col-4 award-text table-header">{{__('lang.course')}}</th>
            <th class="col-4 award-text table-header">{{__('lang.grade')}}</th>
            <th class="col-4 award-text table-header">{{__('lang.scholarshipAmount')}}</th>
          </tr>
          @if(isset($return_discount) && count($return_discount) > 0)
          @foreach($return_discount as $i => $rd)
          <tr>
            <td class="award-text table-body">{{$i}}</td>
            <td class="table-body">
              @foreach($rd as $r)
                <span class="award-text d-block pt-3">{{$r['total']}}</span>
              @endforeach
            </td>
            <td class="table-body">
              @foreach($rd as $r)
                <span class="award-text d-block pt-3">{{$r['amount']}}</span>
              @endforeach
            </td>
            </tr>
            @endforeach
            @endif
        </table>
        @else 
          <div class="col-12 bg-grey br-12" style="padding: 24px;">
            {!! $return_discount['sd_desc'] !!}
          </div>
        @endif
      </div>
  </div>

  <div class="mt-20">
    <span class="criteria d-block">{{__('lang.details')}}</span>
    <div class="mt-5">
      {!! $scholarship->s_details !!}
    </div>
  </div>

  <div class="mt-20">
    <span class="criteria d-block">{{__('lang.application')}}</span>
    <div class="mt-5">
      {!! $scholarship->s_application !!}
    </div>
  </div>
  <!-- <a href="/" style="color:white;"><div class="mt-20 home-btn-get-started-2 text-center">Apply This Scholarship</div><a href="/" style="color:white;"> -->
</div>
@endif

@endsection

@section('script')
@endsection
