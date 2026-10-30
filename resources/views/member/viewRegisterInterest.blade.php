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
  <div class="d-flex flex-column flex-root">
    <div class="mx-auto">
      <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
        <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
          <div class="toolbar" id="kt_toolbar">
            <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
              <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <h1 class="d-flex text-dark fw-bolder fs-3 align-items-center my-1">Register Interest List</h1>
              </div>
            </div>
          </div>
          <div class="post d-flex flex-column-fluid" id="kt_post">
            <div id="kt_content_container" class="container-xxl">
              <div class="card">
                <div class="card-body py-4">
                  <table class="table align-middle table-striped fs-6 gy-5" id="register_table">
                    <thead>
                      <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase gs-0">
                        <th class="min-w-125px">Full name</th>
                        <th class="min-w-70px">Email</th>
                        <th class="min-w-125px">Contact No</th>
                        <th class="min-w-125px">Birthday</th>
                        <th class="min-w-125px">Secondary School</th>
                        <th class="min-w-125px">State of Residence</th>
                        <th class="min-w-125px">Course Interested</th>
                        <th class="min-w-125px">Level of Study Interested</th>
												<th class="min-w-125px">University Interested</th>
                        <th class="min-w-125px">Result</th>
                        <th class="min-w-125px">Remarks</th>
                      </tr>
                    </thead>

                    <tbody class="fw-bold text-gray-600">
                    </tbody>
                  </table>
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
  $(document).ready(function() {
    var table = $('#register_table').DataTable({
      paging: true,
      pageLength: 25,
      processing:true,
      serverSide: true,
      searching: true,
      ajax: function ( data, callback, settings ) {
        $.ajax({
          url: '/getRegisterInterest',
          type: 'post',
          contentType: 'application/x-www-form-urlencoded',
          data: {
            records_start: data.start,
            page_size: data.length,
          },
          success: function( data, textStatus, jQxhr ){
            callback({
              data: data.Data,
              recordsTotal:  data.TotalRecords,
              recordsFiltered:  data.RecordsFiltered,
            });
          },
          error: function( jqXhr, textStatus, errorThrown ){
            console.log(errorThrown);
          }
        });
      },
      columns: [
        {
          data: "ri_name"
        },
        {
          data: "ri_email"
        },
        {
          data: 'ri_contact',
        },
        {
          data: 'ri_birthday',
        },
        {
          data: 'ri_school',
        },
        {
          data: 'state_name',
        },
        {
          data: 'ri_course',
        },
        {
          data: 'level',
        },
        {
          data: 'ri_uni',
        },
				{
					"mRender": function ( data, type, row ) {
						var r = '';
						if(row.ri_result != null){
							r += '<a target="_blank" href="/images/result/'+row.ri_result+'">View</a>';
						}

						return r;
					}
				},
        {
          data: 'ri_remarks',
        },
      ],
    });
  });

  </script>
  </body>
  </html>
