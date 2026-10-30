@extends('memberLayout')
@section('style')
<style>
.tns.tns-default .tns-outer {
  margin: 0 !important;
}
.btn-navi {
  background: #FFF;
  border-radius: 100px;
  margin-right: 20px;
  margin-left: 20px;
}

</style>
@endsection

@section('content')
@php
use \Jenssegers\Agent\Agent;
$agent = new Agent();
@endphp

@if(isset($uni->img) && count($uni->img) > 0)
<div class="pt-80 mb-20">
  <div class="tns tns-default" style="direction: ltr">
    <div
    data-tns="true"
    data-tns-loop="false"
    data-tns-swipe-angle="false"
    data-tns-speed="2000"
    data-tns-autoplay="false"
    data-tns-autoplay-timeout="18000"
    data-tns-controls="true"
    data-tns-nav="false"
    data-tns-items="1"
    data-tns-center="false"
    data-tns-dots="false"
    data-tns-prev-button="#kt_team_slider_prev1"
    data-tns-next-button="#kt_team_slider_next1">

    @foreach($uni->img as $img)
    <div class="text-center px-1 py-5">
      <img src="{{$img}}" class="uni-img-slider" alt=""/>
    </div>
    @endforeach
  </div>

  <button class="btn btn-icon btn-active-color-primary btn-navi" id="kt_team_slider_prev1">
    <!-- <span class="svg-icon fs-3x"> -->
      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="18" viewBox="0 0 10 18" fill="none">
        <path d="M9 17L1 9L9 1" stroke="#1F1F1F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    <!-- </span> -->
  </button>

  <button class="btn btn-icon btn-active-color-primary btn-navi" id="kt_team_slider_next1">
    <!-- <span class="svg-icon fs-3x"> -->
      <svg xmlns="http://www.w3.org/2000/svg" width="10" height="18" viewBox="0 0 10 18" fill="none">
        <path d="M1 1L9 9L1 17" stroke="#1F1F1F" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    <!-- </span> -->
  </button>
</div>
@endif

<div class="mw-1200 mx-auto">
  <div class="row">
    @if(!$agent->isMobile())
    <div class="col-3">
      <img src="{{$uni->u_logo}}" class="uni-logo">
    </div>
    <div class="col-9 mt-10">
      <div class="col-12 d-flex justify-content-between">
        <span class="uni-name-1">{{$uni->int_name}}</span>
        <a class="visit-campus" href="/visit-campus/{{$uni->int_url_code}}">{{__('lang.visitTheCampus')}}</a>
      </div>
      <div class="col-12 d-flex mt-5">
        <div>
          <span class="d-block uni-name">> {{$uni->int_student}}</span>
          <span class="d-block uni-location">{{__('lang.studentNum')}}</span>
        </div>
        <div class="mx-5 separator-right"></div>
        <div>
          <span class="d-block uni-name">> {{$uni->int_staff}}</span>
          <span class="d-block uni-location">{{__('lang.staffRatio')}}</span>
        </div>
        <div class="mx-5 separator-right"></div>
        <div>
          <span class="d-block uni-name">{{$uni->int_year}}</span>
          <span class="d-block uni-location">{{__('lang.foundedYear')}}</span>
        </div>
      </div>
      <div class="mt-10">
        <span class="d-block uni-name">{{$uni->int_desc}}</span>
        <div class="mt-7">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <g clip-path="url(#clip0_110_1340)">
              <path d="M20.8907 2.006L20.9967 2L21.1267 2.008L21.2167 2.024L21.3397 2.059L21.4467 2.105L21.5467 2.162L21.6367 2.229L21.7187 2.304L21.7707 2.363L21.8527 2.479L21.9047 2.575C21.9517 2.675 21.9817 2.781 21.9947 2.891L21.9997 2.997C21.9997 3.072 21.9917 3.146 21.9757 3.217L21.9407 3.34L15.4087 21.417C15.2849 21.6866 15.0863 21.9149 14.8365 22.075C14.5868 22.2351 14.2963 22.3201 13.9997 22.32C13.7325 22.3208 13.4697 22.2525 13.2368 22.1216C13.0039 21.9907 12.8088 21.8017 12.6707 21.573L12.6057 21.446L9.25369 14.744L2.58369 11.408C2.33689 11.2951 2.12405 11.1194 1.96646 10.8985C1.80887 10.6775 1.71205 10.4191 1.68569 10.149L1.67969 10C1.67969 9.44 1.98069 8.928 2.52069 8.63L2.66069 8.56L20.6777 2.054L20.7837 2.024L20.8907 2.006Z" fill="#666666"/>
            </g>
            <defs>
              <clipPath id="clip0_110_1340">
                <rect width="24" height="24" fill="white"/>
              </clipPath>
            </defs>
          </svg>
          <span class="uni-addr">{{$uni->address}}</span>
        </div>
        <div class="mt-5">
          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
            <path d="M12 2C17.523 2 22 6.477 22 12C22 17.523 17.523 22 12 22C6.477 22 2 17.523 2 12C2 6.477 6.477 2 12 2ZM11.07 7.581C10.8849 7.581 10.7073 7.65454 10.5764 7.78544C10.4455 7.91634 10.372 8.09388 10.372 8.279V13.86C10.372 14.246 10.684 14.558 11.07 14.558H16.651C16.7448 14.5616 16.8384 14.5461 16.9261 14.5127C17.0138 14.4792 17.0939 14.4284 17.1615 14.3633C17.2292 14.2982 17.283 14.2201 17.3197 14.1337C17.3565 14.0473 17.3754 13.9544 17.3754 13.8605C17.3754 13.7666 17.3565 13.6737 17.3197 13.5873C17.283 13.5009 17.2292 13.4228 17.1615 13.3577C17.0939 13.2926 17.0138 13.2418 16.9261 13.2083C16.8384 13.1749 16.7448 13.1594 16.651 13.163H11.767V8.279C11.767 8.09405 11.6936 7.91666 11.5629 7.78579C11.4322 7.65492 11.2549 7.58126 11.07 7.581Z" fill="#666666"/>
          </svg>
          <span class="uni-addr">{{__('lang.operatingHours')}}{{$uni->operating_hours}}</span>
        </div>
      </div>
    </div>
    @else 
    <div class="col-3">
      <img src="{{$uni->u_logo}}" class="uni-logo">
    </div>
    <div class="col-9"> 
      <span class="uni-name-1 d-block">{{$uni->int_name}}</span>
      <a class="mt-2 visit-campus d-inline-block" href="/visit-campus/{{$uni->int_url_code}}">{{__('lang.visitTheCampus')}}</a>
    </div>
    <div class="col-12 mt-5">
      <div class="d-flex">
        <div>
          <span class="d-block uni-name">> {{$uni->int_student}}</span>
          <span class="d-block uni-location">{{__('lang.studentNum')}}</span>
        </div>
        <div class="mx-5 separator-right"></div>
        <div>
          <span class="d-block uni-name">> {{$uni->int_staff}}</span>
          <span class="d-block uni-location">{{__('lang.staffRatio')}}</span>
        </div>
        <div class="mx-5 separator-right"></div>
        <div>
          <span class="d-block uni-name">{{$uni->int_year}}</span>
          <span class="d-block uni-location">{{__('lang.foundedYear')}}</span>
        </div>
      </div>
      <div class="mt-10">
        <span class="d-block uni-name">{{$uni->int_desc}}</span>
      </div>
      <div class="mt-7">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
          <g clip-path="url(#clip0_110_1340)">
            <path d="M20.8907 2.006L20.9967 2L21.1267 2.008L21.2167 2.024L21.3397 2.059L21.4467 2.105L21.5467 2.162L21.6367 2.229L21.7187 2.304L21.7707 2.363L21.8527 2.479L21.9047 2.575C21.9517 2.675 21.9817 2.781 21.9947 2.891L21.9997 2.997C21.9997 3.072 21.9917 3.146 21.9757 3.217L21.9407 3.34L15.4087 21.417C15.2849 21.6866 15.0863 21.9149 14.8365 22.075C14.5868 22.2351 14.2963 22.3201 13.9997 22.32C13.7325 22.3208 13.4697 22.2525 13.2368 22.1216C13.0039 21.9907 12.8088 21.8017 12.6707 21.573L12.6057 21.446L9.25369 14.744L2.58369 11.408C2.33689 11.2951 2.12405 11.1194 1.96646 10.8985C1.80887 10.6775 1.71205 10.4191 1.68569 10.149L1.67969 10C1.67969 9.44 1.98069 8.928 2.52069 8.63L2.66069 8.56L20.6777 2.054L20.7837 2.024L20.8907 2.006Z" fill="#666666"/>
          </g>
          <defs>
            <clipPath id="clip0_110_1340">
              <rect width="24" height="24" fill="white"/>
            </clipPath>
          </defs>
        </svg>
        <span class="uni-addr">{{$uni->address}}</span>
      </div>
      <div class="mt-5">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
          <path d="M12 2C17.523 2 22 6.477 22 12C22 17.523 17.523 22 12 22C6.477 22 2 17.523 2 12C2 6.477 6.477 2 12 2ZM11.07 7.581C10.8849 7.581 10.7073 7.65454 10.5764 7.78544C10.4455 7.91634 10.372 8.09388 10.372 8.279V13.86C10.372 14.246 10.684 14.558 11.07 14.558H16.651C16.7448 14.5616 16.8384 14.5461 16.9261 14.5127C17.0138 14.4792 17.0939 14.4284 17.1615 14.3633C17.2292 14.2982 17.283 14.2201 17.3197 14.1337C17.3565 14.0473 17.3754 13.9544 17.3754 13.8605C17.3754 13.7666 17.3565 13.6737 17.3197 13.5873C17.283 13.5009 17.2292 13.4228 17.1615 13.3577C17.0939 13.2926 17.0138 13.2418 16.9261 13.2083C16.8384 13.1749 16.7448 13.1594 16.651 13.163H11.767V8.279C11.767 8.09405 11.6936 7.91666 11.5629 7.78579C11.4322 7.65492 11.2549 7.58126 11.07 7.581Z" fill="#666666"/>
        </svg>
        <span class="uni-addr">{{__('lang.operatingHours')}}{{$uni->operating_hours}}</span>
      </div>
    </div>
    </div>
    @endif
    <span class="d-block options-toggle active mt-20">{{ __('lang.scholarship') }}</span>
    <div id="scholarship_list"></div>
  </div>
</div>
</div>
@endsection
@section('script')
<script>
  $(document).ready(function() {
    getScholarship();

    function getScholarship()
    {
      var id = {{$uni->int_id}};

      $.ajax({
        url: "/getScholarshipBySchool",
        method: 'post',
        data: {
          id: id,
        },
        success: function(d) {
          var content = '';
          if (d.status) {
            var items = d.list;

            content += '<div class="col-12 row m-0">';
            items.forEach(function(item, index) {
              content += '<div class="col-12 col-lg-4 mt-5">';
              content += '<a href="/k12-scholarship-details/'+item.url_code+'">';
              content += '<div class="col-12 explore-course-outter">';
              content += '<img class="course-uni-img" src="'+item.uni_logo+'">';
              content += '<div>';
              content += '<span class="d-block uni-name pt-5">'+item.name+'</span>';
              content += '<span class="uni-location d-block">'+item.uni_name+'</span>';
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
              content += '<span class="uni-location">{{ __("lang.requirements") }}</span>';
              content += '<span class="uni-total">'+item.requirement+'</span>';
              content += '</div>';
              content += '<div class="separator my-5"></div>';
              content += '<span class="uni-total d-block text-end">{{ __("lang.feesWaiver") }}<span class="ttl-amt"> '+item.max_discount+'</span></span>';
              content += '<button class="w-100 mt-10 apply-scholarship-btn">Pathway360 - Applicable</button>';
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
  });
</script>
@endsection
