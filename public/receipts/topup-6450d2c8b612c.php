@extends('shopLayout')

@section('style')
<style>
::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  color: #999999;
  opacity: 1; /* Firefox */
  font-size: 13px;
}

:-ms-input-placeholder { /* Internet Explorer 10-11 */
  color: #999999;
  font-size: 13px;
}

::-ms-input-placeholder { /* Microsoft Edge */
  color: #999999;
  font-size: 13px;
}
.verification{
  margin-bottom: 0px !important;
  margin-top: 20px !important;
}
.register-panel .password-body{
  margin-bottom: 0px !important;
}
.countryCode{
  margin-right: 0px !important;
  border-top-left-radius: 6px !important;
  border-bottom-left-radius: 0px !important;
  border-top-right-radius: 0px !important;
  border-bottom-right-radius: 0px !important;
  padding-left: 1.3rem !important;
  color: #424143 !important;
  width:18% !important;
}
.contact_no{
  border-top-left-radius: 0px !important;
  border-bottom-left-radius: 0px !important;
  border-top-right-radius: 6px !important;
  border-bottom-right-radius: 0px !important;
}
.register-panel .form-control{
  border-radius: 0px ;
  background-color: #FFF;
  border: 0px solid #EFEFEF !important;
  border-bottom: 1px solid #F6F6F6 !important;
}
select {
  -webkit-appearance: none;
  -moz-appearance: none;
  text-indent: 1px;
  text-overflow: '';
}
.malaysia-logo{
  left: 30px;
  width: 25px;
  margin-top: 10px;
}
.skipBtn{
  border: none !important;
  outline: none !important;
  background: none !important;
  cursor: pointer !important;
  color: #0000EE !important;
  padding: 0 !important;
  font-family: inherit !important;
  font-size: inherit !important;
}
.register-panel{
  margin-left: 0px !important;
  margin-right: 0px !important;
}
.verify-btn{
  border-radius: 23px !important;
  background-color: #1ED694 !important;
  color: #FFF !important;
  font-weight: 500 !important;
}
.toggle-signup:hover, #phone_number_btn, #email_send_btn{
  cursor: pointer;
}
header{
  background: #FFF;
}
@media (max-width: 767px){
  .special_characters{
    line-height: 18px !important;
  }
  .countryCode{
    width: 24% !important;
  }
  .logo-lime{
    margin-left: 10px;
  }
  .register-panel .verify-btn {
    font-size: 16px !important;
  }
}
</style>
@endsection

@section('content')
@php
use \Jenssegers\Agent\Agent;
$agent = new Agent();
@endphp
<header>
  @php
    $store = 'market://details?id=com.bullmart';
    $app_link = 'bullmart://app/HomeScreen';

    if($agent->isiOS() || (strtoupper($agent->browser()) == 'SAFARI')){
      $store = 'https://apps.apple.com/us/app/bullmart-social-commerce-app/id1527779249';
    }

  if(Session::has('register_referral')){
    $app_link .= ('&uplineFromWeb=' . Session::get('register_referral'));
  }
  @endphp
  <div class="container">
    <div class="header-middle">
      <div class="logo-lime">
        <a href="/">
          <img class="" src="/images/lime/lime-logo-green.png" style="width: 100px !important;">
        </a>
      </div>
    </div>
  </div>
</header>
<div class="content-push">
  <div class="information-blocks container">
    <div class="row justify-content-center">
      <div class="col-md-5 col-12">
        <!-- <form id="signUpForm"> -->
          <div class="register-panel">
            @include('formMsg')
            <div class="card">
              <div id="phone_number_section">
                <div class="title text-center">
                  @lang('lang.welcome')
                </div>
                <div class="phone-number-content">
                  <div class="phone-number">
                    <i class="fa fa-chevron-down position-absolute" aria-hidden="true" style="left: 70px;top: 80px;font-size: 12px;"></i>
                    <select id="smsContactCode" name="contact_code" class="form-control countryCode p-1" value="60">
                      <option value="60">+60</option>
                    </select>
                    <input type="text" id="smsContactNo" class="form-control contact_no" name="contact_no" placeholder="@lang('lang.yourPhoneNum')" autocomplete="off">
                  </div>
                  <div class="mt-3">
                    <input id="verificationCode" type="number" onKeyPress="if(this.value.length==6) return false;" class="form-control" name="verification_code" placeholder="@lang('lang.enterVerificationCode')" autocomplete="off">
                    <span class="position-absolute" id="phone_number_btn" style="right: 25px; top: 135px;color: #1ED694">@lang('lang.sendCode')</span>
                    <span class="position-absolute" id="codeCheck" style="right: 30px; top: 135px;color: #1ED694; display:none">@lang('lang.resendCode') <span id="timerCountdown">120</span></span>
                  </div>
                  <div class="mt-3">
                    <input id="password" type="password" name="password" class="form-control" placeholder="@lang('lang.enterPwd')" autocomplete="off">
                  </div>
                </div>

                <div class="email-content mt-3" style="display: none;">
                  <input type="text"  id="email" class="form-control email" name="email" placeholder="@lang('lang.enterEmail')" autocomplete="off">
                  <div class="mt-3">
                    <input id="emailVerificationCode" type="number" onKeyPress="if(this.value.length==6) return false;" class="form-control"  name="email_verification_code" placeholder="@lang('lang.enterVerificationCode')" autocomplete="off">
                    <span class="position-absolute" id="email_send_btn" style="right: 25px; top: 135px;color: #1ED694">@lang('lang.sendCode')</span>
                  </div>
                  <div class="mt-3">
                    <input id="ePassword" type="password" name="e_password" class="form-control" placeholder="@lang('lang.enterPwd')" autocomplete="off">
                  </div>
                </div>

                <div class="row mt-3 ml-1 toggle-signup">
                  <img class="img-fluid mt-1" src="/shop-assets/img/icons/exchange.png" style="width: 16px; height: 15px;">
                  <span id="login_text" style="color: #803CD9; padding-left: 10px;">Sign up with email</span>
                  <input type="hidden" name="type" value="1" id="toggle">
                </div>

                <div class="phone-number-bottom">
                  <div class="apply">
                    <button id="submit_btn" class="btn verify-btn">
                      @lang('lang.signUp')
                    </button>
                  </div>
                  <div class="desc col-12 mx-auto mt-3">
                    <span style="font-size: 12px;">@lang('lang.agreetoTnC')</span>
                  </div>
                </div>
              </div>

              <div id="success_register" style="display: none;">
                <img class="img-fluid mx-auto mb-3 mt-2" src="/images/lime/success.png" style="width: 20%">
                <div class="title text-center" style="font-size: 13pt !important">
                  Congrats! You have registered successfully
                </div>
                <div class="phone-number-bottom">
                  <div class="apply">
                    <button class="btn verify-btn" data-app-store="{{ $store }}" data-app-link="{{ $app_link }}" style="border-radius: 5px !important; margin-top: 100px;">
                      @lang('lang.downloadApp')
                    </button>
                  </div>
                </div>
              </div>

            </div>
          </div>
        <!-- </form> -->
      </div>
    </div>
  </div>
</div>

<div id="cloneForm" class="d-none"></div>
@endsection

@section('script')
<script src="https://www.google.com/recaptcha/api.js?render={{ config('app.site_key') }}"></script>
<script>
$(document).ready(function(){

  $('#password').bind('input propertychange', function() {
    var x = document.getElementById('password').value;
    var btn = false;
    var btn1 = false;
    var btn2 = false;
    var btn3 = false;
    if (x.length >= 8) {
      $('#characters_num').attr("src", "/shop-assets/img/icons/tick-icon-yellow.png");
      btn = true;
    }else{
      $('#characters_num').attr("src", "/shop-assets/img/icons/untick-icon.png");
      btn = false;
    }

    if(x.match(/^(?=.*?[A-Z])(?=.*?[a-z])/)){
      $('#upper_lower').attr("src", "/shop-assets/img/icons/tick-icon-yellow.png");
      btn1 = true;
    }else{
      $('#upper_lower').attr("src", "/shop-assets/img/icons/untick-icon.png");
      btn1 = false;
    }

    if(x.match(/[0-9]/)){
      $('#number').attr("src", "/shop-assets/img/icons/tick-icon-yellow.png");
      btn2 = true;
    }else{
      $('#number').attr("src", "/shop-assets/img/icons/untick-icon.png");
      btn2 = false;
    }

    $('#c_password').bind('input propertychange', function() {
      var x = document.getElementById('password').value;
      var y = document.getElementById('c_password').value;

      if(x == y){
        $('#pass_match').attr("src", "/shop-assets/img/icons/tick-icon-yellow.png");
        btn3 = true;
      }else{
        $('#pass_match').attr("src", "/shop-assets/img/icons/untick-icon.png");
        btn3 = false;
      }


      if(btn == false || btn1 == false || btn2 == false || btn3 == false){
        $('#password_btn').attr('disabled', true)
        document.getElementById('password_btn').style.background = "#999999";
      }else if(btn == true || btn1 == true || btn2 == true || btn3 == true){
        $('#password_btn').attr('disabled', false)
        document.getElementById('password_btn').style.background = "#FDCF33";

      }
    });
  });

  $('#username').bind('input propertychange', function() {
    var x = document.getElementById('username').value;
    var btn = false;
    var btn1 = false;
    if (x.length >= 5 && x.length <= 32) {
      $('#characters_more_five').attr("src", "/shop-assets/img/icons/tick-icon-yellow.png");
      btn = true;
    }else{
      $('#characters_more_five').attr("src", "/shop-assets/img/icons/untick-icon.png");
      btn = false;
    }

    if(x.match(/^[a-zA-Z0-9-_.]+$/)){
      $('#special_characters').attr("src", "/shop-assets/img/icons/tick-icon-yellow.png");
      btn1 = true;
    }else{
      $('#special_characters').attr("src", "/shop-assets/img/icons/untick-icon.png");
      btn1 = false;
    }

    if(btn == false || btn1 == false){
      // $('button').attr('disabled', 'disabled');
      $('#username_btn').attr('disabled', true)
      document.getElementById('username_btn').style.background = "#999999";
    }else if(btn == true || btn1 == true){
      // $('button').removeAttr("disabled");
      $('#username_btn').attr('disabled', false)
      document.getElementById('username_btn').style.background = "#FDCF33";

    }

  });

  $('#referral').bind('input propertychange', function(){
    var x = document.getElementById('referral').value;

    if(x.length >= 1){
      $('.referral_btn').attr('disabled', false)
    }else{
      $('.referral_btn').attr('disabled', true)
    }
  });

  $('#smsContactNo').on('blur', function(){
    $('#contactNo').html($(this).val());
  });

  $('#phone_number_btn').click(function(){
    grecaptcha.ready(function() {
      grecaptcha
      .execute('{{ config('app.site_key') }}', {
        action: 'login'
      })
      .then(function(token) {
        if (token) {
          smsContactCode = $('#smsContactCode').val();
          smsContactNo = $('#smsContactNo').val();
          $.ajax({
            url: '/shop/ajaxSendSmsCode',
            method: 'post',
            data: {
              contact_code: smsContactCode,
              contact_no: smsContactNo,
              recaptcha: token,
              type: 1
            },
            success: function(d){
              if(d.status){

                $('#errMsgAlert').hide();
                var codeCheck = $('#codeCheck');
                var codeResend = $('#phone_number_btn');

                if (codeResend.css('display') == "block") {
                  codeCheck.css('display', 'block');
                  codeResend.css('display', 'none');
                } else {
                  codeCheck.css('display', 'none');
                  codeResend.css('display', 'block');
                }
                // countdown before requesting a new sms code
                countdown();
              } else{

                $('#errMsgAlert').show();
                $('#errMsgAlertTxt').html(d.msg);
              }
            },
            error: function(xhr) {
              console.log(xhr.responseText);
            }
          });
        }
      });
    });
  });

  $('#sms_number_btn').click(function(){
    let code = '';
    let smsCodes = $('.sms_code');

    smsCodes.each(function(c){
      code += $(this).val();
    });

    $.ajax({
      url: '/shop/ajaxVerifyCode',
      method: 'post',
      data: {
        sms_code: code
      },
      success: function(d){
        // console.log(d);
        if(d.status){
          $("#sms_verification_section").hide();
          $("#password_setting_section").removeClass("d-none");
          $('#errMsgAlert').hide();
        } else{
          $('#errMsgAlert').show();
          $('#errMsgAlertTxt').html(d.msg);
        }
      },
      error: function(xhr){
        console.log(xhr.responseText);
      }
    });
  });

  $('#password_btn').click(function(){
    $("#password_setting_section").hide();
    $("#username_setting_section").removeClass("d-none");
  });

  $('#username_btn').click(function(){
    $("#username_setting_section").hide();
    $("#referral_section").removeClass("d-none");
  });

  $('#close_splash_btn').click(function(){
    $("#splash_screen_section").hide();
    $("#phone_number_section").removeClass("d-none");
    $('.register-panel').removeClass("d-none");
    $('#phone_num').removeClass("col-md-6 col-12");
    $('.row').css("justify-content", "center");
    $('.register-panel').css({"margin-left": "0", "margin-right": "0"});
    $('.background-text').addClass('d-none');
  });

  $('#submit_btn').click(function(){
    smsContactCode = $('#smsContactCode').val();
    smsContactNo = $('#smsContactNo').val();
    password = $('#password').val();
    verificationCode = $('#verificationCode').val();
    email = $('#email').val();
    emailPass = $('#ePassword').val();
    emailVerificationCode = $('#emailVerificationCode').val();
    signupType = $('#toggle').val();

    $.ajax({
      url: '/shop/submitSignUp',
      method: 'post',
      data: {
        contact_code: smsContactCode,
        contact_no: smsContactNo,
        verification_code: verificationCode,
        password: password,
        email: email,
        e_password: emailPass,
        email_verification_code: emailVerificationCode,
        type:  signupType,
      },
      success: function(d){
        if(d.status){
          $('#errMsgAlert').hide();
          $('#phone_number_section').css('display', 'none');
          $('#success_register').css('display', 'block');
          // window.location.href = d.link;
          navigate();
        } else{
          $('#errMsgAlert').show();
          $('#errMsgAlertTxt').html(d.msg);
        }
      },
      error: function(xhr) {
        console.log(xhr.responseText);
      }
    });
  });


  $(".sms_code").keyup(function () {
    $(this).next('.sms_code').focus();
  });

  // $('#signUpForm').submit(function(e){
  //   e.preventDefault();
  //
  //   // let smsContactCode = $('#smsContactCode').val();
  //   let smsContactNo = $('#smsContactNo').val();
  //   let password = $('#password').val();
  //   // let cPassword = $('#c_password').val();
  //
  //   // let phoneNumber = $('#phone_number_section');
  //   // let smsVerification = $('#sms_verification_section');
  //   // let passwordSetting = $('#password_setting_section');
  //
  //   if(smsContactNo && password){
  //     let cloneForm = $(this).clone();
  //
  //     cloneForm.removeAttr('id');
  //
  //     $('#cloneForm').append(cloneForm);
  //     $('#cloneForm form').submit();
  //   }
  //   // else{
  //   //   if(phoneNumber.css('display') != 'none'){
  //   //     phoneNumber.find('.verify-btn').click();
  //   //   }else if(smsVerification.css('display') != 'none'){
  //   //     smsVerification.find('.verify-btn').click();
  //   //   }else if(passwordSetting.css('display') != 'none'){
  //   //     passwordSetting.find('.verify-btn').click();
  //   //   }
  //   // }
  // });

  $('.toggle-signup').click(function(e){
    e.preventDefault();
    let toggle = $('#toggle').val();

    if(toggle == 1){
      $('.phone-number-content').hide();
      $('.email-content').show();
      $('#toggle').val(0);
      $('#login_text').html('Sign up with phone number');
    }else if(toggle == 0){
      $('.phone-number-content').show();
      $('.email-content').hide();
      $('#toggle').val(1);
      $('#login_text').html('Sign up with email');
    }
  });

  $('#email_send_btn').click(function(){
    userEmail = $('.email').val();
    $.ajax({
      url: '/sendEmailCode',
      method: 'post',
      data: {
        email: userEmail,
      },
      success: function(d){
        if(d.status){
          $('#errMsgAlert').hide();
        } else{
        }
      },
      error: function(xhr) {
        console.log(xhr.responseText);
      }
    });
  })
});

// resend a new sms code when click on resend
function smsResend() {
  // grecaptcha.ready(function() {
  //   grecaptcha
  //   .execute('{{ config('app.site_key') }}', {
  //     action: 'login'
  //   })
  //   .then(function(token) {
  //     if (token) {
  contactCode = $('#ContactCode').val();
  contactNo = $('#ContactNo').val();
  $.ajax({
    url: '/shop/ajaxSendSmsCode',
    method: 'post',
    data: {
      contact_code: contactCode,
      contact_no: contactNo,
      // recaptcha: token,
      type: 1
    },
    error: function(xhr) {
      console.log(xhr.responseText);
    }
  });
  //   }
  // });
  // });

  var codeCheck = $('#codeCheck');
  var codeResend = $('#codeResend');

  if (codeResend.css('display') == "block") {
    codeCheck.css('display', 'block');
    codeResend.css('display', 'none');
  } else {
    codeCheck.css('display', 'none');
    codeResend.css('display', 'block');
  }

  countdown();
}

function countdown(){
  var countDown = 120;
  var i = setInterval(function () {
    var codeCheck = $('#codeCheck');
    var codeResend = $('#phone_number_btn');
    var timerCountdown = $('#timerCountdown');

    if(countDown === 1) {
      if(codeCheck.css('display') == 'none') {
        codeCheck.css('display', 'block');
        codeResend.css('display', 'none');
      } else {
        codeCheck.css('display', 'none');
        codeResend.css('display', 'block');
      }

      clearInterval(i); // stop interval
    }

    countDown = (countDown - 1 > 0)? (countDown - 1): 60;
    timerCountdown.html(countDown);
  }, 1000);
}

function navigate(){
  if(navigator.userAgent.match(/Android/i)){
    setTimeout(function () { window.location = "market://details?id=com.bullmart"; }, 25);
    window.location = 'bullmart://app/HomeScreen';
  }else if(navigator.userAgent.match(/iPhone/i)) {
    setTimeout(function () { window.location = 'https://apps.apple.com/us/app/bullmart-social-commerce-app/id1527779249'; }, 1000);
    // window.location = 'bullmart://app/RedirectScreen?feed_id='+feed_id+'&feed_type='+feed_type;
    window.location = 'bullmart://app/HomeScreen';
  }
}

</script>
@endsection
