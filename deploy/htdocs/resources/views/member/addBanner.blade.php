<!DOCTYPE html>
<html lang="en">
<head><base href="">
	<title>Edu4u</title>
	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="Description" content="">
	<meta name="Author" content="">
	<link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
	<link rel="shortcut icon" href="/assets/media/logos/icon_360.svg" />
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<link href="/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets/css/custom.css" rel="stylesheet" type="text/css" />
	<style>

	</style>
</head>
<body>
  <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
      <div class="post d-flex flex-column-fluid" id="kt_post">
        <div id="kt_content_container" class="container-fluid">
          <div class="row g-7">
            <div class="col-lg-6 col-xl-6 mx-auto">
              <div>
                <div class="card card-flush h-lg-100" id="kt_contacts_main">
                  <div class="card-header pt-7" id="kt_chat_contacts_header">
                    <div class="card-title">
                      <h2>Add New Banner</h2>
                    </div>
                  </div>
                  <div class="card-body pt-5">
                    <form id="add_banner" class="form">
                      <div class="mb-7" id="feed_type_1">
                        <label class="fs-6 fw-bold mb-3">
                          <span>Image</span>
                          <i class="fas fa-exclamation-circle ms-1 fs-7" data-bs-toggle="tooltip" title="Allowed file types: png, jpg, jpeg."></i>
                        </label>


                        <div class="col-md-8" >
                          <div class="form-group mb-10" id="feed_img">
                            <div class="shopee-image-manager__item ignore-item">
                              <div class="shopee-image-manager__content">
                                <div  class="shopee-image-manager__upload">
                                  <div class="shopee-file-upload">
                                    <input type="file" id="file" name="banner_img" aspect="1" class="shopee-upload__input img_input" style="display:none;" accept=".png, .jpg, .jpeg" >
                                    <div class="shopee-upload img_container " aspect="1">
                                      <div class="shopee-upload-wrapper shopee-upload-dragger" style="border:1px dashed green;height:150px;width:100%">
                                        <div class="shopee-image-manager__upload__content">
                                          <i class="fas fa-plus-circle fa-2x add_icon" style="color:green;margin-top:60px;margin-left: 40%;"></i>

                                          <div class="shopee-icon remove-icon" title="@lang('lang.remove')" style="display:none;">
                                            <div class="product-border" style="padding:7px;border-radius:50%;background-color:rgb(253,207,51,0.7);z-index:999;margin-top:60px;width:30px;margin-left: 50%;">
                                              <i class="far fa-trash-alt fa-lg" style="color:white;"></i>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="separator mb-6"></div>
                      <div class="d-flex justify-content-end">
                        <button type="reset" data-kt-contacts-type="cancel" class="btn btn-light me-3">Cancel</button>
                        <button type="submit" id="btn" data-kt-contacts-type="submit" class="btn btn-primary">
                          <span class="indicator-label">Save</span>
                          <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                          </button>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

<script>var hostUrl = "assets/";</script>
<script src="/assets/plugins/global/plugins.bundle.js"></script>
<script src="/assets/js/scripts.bundle.js"></script>
<script src="/assets/plugins/custom/fullcalendar/fullcalendar.bundle.js"></script>
<script src="/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script src="/assets/js/widgets.bundle.js"></script>
<script src="/assets/js/custom/widgets.js"></script>
<script src="/assets/js/custom/apps/chat/chat.js"></script>
<script src="/assets/js/custom/utilities/modals/upgrade-plan.js"></script>
<script src="/assets/js/custom/utilities/modals/create-app.js"></script>
<script src="/assets/js/custom/utilities/modals/users-search.js"></script>
<script src="/assets/js/custom/apps/contacts/edit-contact.js"></script>
<script src="/assets/js/crawler.js"></script>

<script>
$(document).ready(function(){
  $(document).on('change', '.img_input', function() {
    var input = this;
    var parent = $(this).parent();
    if (input.files && input.files[0]) {
      parent.find('.add_icon').hide();
      parent.find('.remove-icon').show();
      parent.find('.img_container').removeClass('img_container');

      var reader = new FileReader();

      var file = input.files[0];
      var reader = new FileReader();
      reader.onloadend = function () {
        var target = parent.find('.shopee-upload-dragger');
        if(target) {
          target.css('background-image', 'url("' + reader.result + '")');
          target.css('background-position', 'center');
          target.css('background-size', 'cover');
          target.find('.add_icon').css('color', 'rgb(0, 0, 0, 0)');
        }
      }

      if(file) {
        reader.readAsDataURL(file);
      }
    }
  });

  $(document).on('click', '.remove-icon', function() {
    var parent = $(this).parent().parent().parent().parent();
    parent.find('.img_input').val('');
    target = parent.find('.shopee-upload-dragger');
    if(target != undefined) {
      target.css('background-image', '');
      parent.find('.remove-icon').hide();
      parent.find('.add_icon').show();
      parent.find('.add_icon').css('color', 'green');
      parent.find('.shopee-upload').addClass('img_container');
    }
  });

  $(document).on('click', '.remove-container', function(e) {
    e.preventDefault();
    var parent = $(this).parent().parent().parent().parent();
    parent.remove();
  });

  $(document).on('click', '.img_container', function() {
    var parent = $(this).parent();
    parent.find('.img_input').click();
  });

  $('#add_banner').on('submit', function (e) {
    e.preventDefault();
    let f = new FormData($(this)[0]);

    $('#btn').prop("disabled",true);
    $('.indicator-label').hide();
    $('.indicator-progress').show();

    $.ajax({
      url: "/submitAddBanner",
      method: 'post',
      data: f,
      contentType: false,
      processData: false,
      success: function(d) {

        console.log(d)
        if (d.status) {
          $('#btn').prop("disabled",false);
          $('.indicator-label').show();
          $('.indicator-progress').hide();

          Swal.fire({
            position: "top-end",
            icon: "success",
            title: d.msg,
            showConfirmButton: false,
            timer: 2500
          });
          window.setTimeout(function() {
              window.location.href = '/add-banner';
          }, 2000);

        }else{
          $('#btn').prop("disabled",false);
          $('.indicator-label').show();
          $('.indicator-progress').hide();
          Swal.fire({
            title: "Oops...",
            text: d.msg,
            icon: "error"
          });
        }
      }
    });
  });

});
</script>
</body>
</html>
