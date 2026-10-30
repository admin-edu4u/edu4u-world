<!DOCTYPE html>
<html lang="en" translate="no">
<head>
	<title>Edu4u</title>

	<meta charset="UTF-8">
	<meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="Description" content="">
	<meta name="Author" content="">

	
	<link rel="canonical" href="https://preview.keenthemes.com/metronic8" />
	<link rel="icon" type="image/jpg" href="/images/favicon/favicon.ico" />

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<link href="/assets/plugins/custom/fullcalendar/fullcalendar.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
	<link href="/assets/css/custom.css" rel="stylesheet" type="text/css" />
	<link href="https://cdn.jsdelivr.net/npm/owl.carousel@2.3.4/dist/assets/owl.carousel.min.css" rel="stylesheet">
	<style>
		.exploreaccordion{
			margin: 15px 0 0;
			padding: 0 9px;
			font-weight: 200;
		}

		.header-apply:hover {
			color: #1a124e !important;
			border-color:#1A124E !important;
		}

		.header-started {
			border: 2px solid white !important;
		}

		.header-started:hover {
			color: white !important;
			border-color:#1A124E !important;
			background-color:#1A124E !important;
		}

		.header-started-active {
			border: 2px solid #1A124E !important;
		}

		.header-started-active:hover {
			color: #1A124E !important;
			border-color:#1A124E !important;
			background-color:white !important;
		}
		
		.header-apply-active:hover {
			color:white !important;
			background-color:#1A124E !important;
		}
	</style>
	@yield('style')
</head>
<body>
	@php
	use \Jenssegers\Agent\Agent;
	$agent = new Agent();
	@endphp

	<div class="d-flex flex-column">
		@include('member._header')
		@include('formToast')
		@yield('content')
		<!-- <div class="dropdown-option">
			<div class="home-dropdown-option-outer">
				<a href="/explore?tab=university">
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex">
							<div class="dropdown-option-icon">
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
								<path d="M21 9.99999C21.2652 9.99999 21.5196 9.89463 21.7071 9.70709C21.8946 9.51956 22 9.2652 22 8.99999V5.99999C22 5.79016 21.9339 5.58566 21.8111 5.41549C21.6884 5.24533 21.5151 5.11815 21.316 5.05199L12.316 2.05199C12.1108 1.98381 11.8892 1.98381 11.684 2.05199L2.684 5.05199C2.48487 5.11815 2.31164 5.24533 2.18886 5.41549C2.06609 5.58566 2.00001 5.79016 2 5.99999V8.99999C2 9.2652 2.10536 9.51956 2.29289 9.70709C2.48043 9.89463 2.73478 9.99999 3 9.99999H4V17.184C3.41669 17.3894 2.91128 17.7704 2.55318 18.2746C2.19507 18.7789 2.00184 19.3816 2 20V22C2 22.2652 2.10536 22.5196 2.29289 22.7071C2.48043 22.8946 2.73478 23 3 23H21C21.2652 23 21.5196 22.8946 21.7071 22.7071C21.8946 22.5196 22 22.2652 22 22V20C21.9982 19.3816 21.8049 18.7789 21.4468 18.2746C21.0887 17.7704 20.5833 17.3894 20 17.184V9.99999H21ZM20 21H4V20C4.00026 19.7349 4.10571 19.4807 4.29319 19.2932C4.48066 19.1057 4.73486 19.0003 5 19H19C19.2651 19.0003 19.5193 19.1057 19.7068 19.2932C19.8943 19.4807 19.9997 19.7349 20 20V21ZM6 17V9.99999H8V17H6ZM10 17V9.99999H14V17H10ZM16 17V9.99999H18V17H16ZM4 7.99999V6.71999L12 4.05399L20 6.72099V7.99999H4Z" fill="#1A124E"/>
							</svg>
							</div>
							<div class="ms-3">
								<span class="d-block dropdown-option-icon-main-title">Universities</span>
								<span class="d-block dropdown-option-icon-desc">Browse universities from all over the world</span>
							</div>
						</div>
						<div class="ms-5">
							<svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 6 10" fill="none">
								<path d="M1 1L5 5L1 9" stroke="#1F1F1F" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
					</div>
				</a>
			</div>
			<div class="home-dropdown-option-outer">
				<a href="/explore?tab=course">
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex">
							<div class="dropdown-option-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path d="M18 2H6C4.9 2 4 2.9 4 4V20C4 21.1 4.9 22 6 22H18C19.1 22 20 21.1 20 20V4C20 2.9 19.1 2 18 2ZM9 4H11V9L10 8.25L9 9V4ZM18 20H6V4H7V13L10 10.75L13 13V4H18V20Z" fill="#1A124E"/>
								</svg>
							</div>
							<div class="ms-3">
								<span class="d-block dropdown-option-icon-main-title">Courses</span>
								<span class="d-block dropdown-option-icon-desc">Discover courses suitable for you</span>
							</div>
						</div>
						<div>
							<svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 6 10" fill="none">
								<path d="M1 1L5 5L1 9" stroke="#1F1F1F" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
					</div>
				</a>
			</div>
			<div class="home-dropdown-option-outer">
				<a href="/explore?tab=scholarship">
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex">
							<div class="dropdown-option-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path d="M2.573 8.46298L11.232 4.13398C11.3152 4.09243 11.407 4.0708 11.5 4.0708C11.593 4.0708 11.6848 4.09243 11.768 4.13398L20.427 8.46398C20.5266 8.51384 20.6103 8.59044 20.6688 8.68519C20.7274 8.77994 20.7584 8.88911 20.7584 9.00048C20.7584 9.11185 20.7274 9.22102 20.6688 9.31577C20.6103 9.41052 20.5266 9.48712 20.427 9.53698L11.768 13.866C11.6848 13.9075 11.593 13.9292 11.5 13.9292C11.407 13.9292 11.3152 13.9075 11.232 13.866L2.573 9.53598C2.47342 9.48612 2.38968 9.40952 2.33116 9.31477C2.27264 9.22002 2.24164 9.11085 2.24164 8.99948C2.24164 8.88811 2.27264 8.77894 2.33116 8.68419C2.38968 8.58944 2.47342 8.51284 2.573 8.46298Z" stroke="#1A124E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
									<path d="M22.5 13V9.5L20.5 8.5M4.5 10.5V15.912C4.49993 16.2904 4.60719 16.661 4.80933 16.9809C5.01148 17.3007 5.30021 17.5567 5.642 17.719L10.642 20.093C10.9101 20.2203 11.2032 20.2864 11.5 20.2864C11.7968 20.2864 12.0899 20.2203 12.358 20.093L17.358 17.719C17.6998 17.5567 17.9885 17.3007 18.1907 16.9809C18.3928 16.661 18.5001 16.2904 18.5 15.912V10.5" stroke="#1A124E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</div>
							<div class="ms-3">
								<span class="d-block dropdown-option-icon-main-title">Scholarships</span>
								<span class="d-block dropdown-option-icon-desc">Browse scholarships from all universities</span>
							</div>
						</div>
						<div>
							<svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 6 10" fill="none">
								<path d="M1 1L5 5L1 9" stroke="#1F1F1F" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
					</div>
				</a>
			</div>
			<div class="home-dropdown-option-outer">
				<a href="/visit-campus">
					<div class="d-flex align-items-center justify-content-between">
						<div class="d-flex">
							<div class="dropdown-option-icon">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path d="M5.252 9.97501L16.912 4.42301C18.612 3.61301 20.386 5.38801 19.577 7.08901L14.025 18.748C13.266 20.341 10.966 20.243 10.346 18.59L9.32 15.851C9.21975 15.5838 9.06347 15.3412 8.86167 15.1393C8.65986 14.9375 8.41721 14.7813 8.15 14.681L5.41 13.654C3.758 13.034 3.659 10.734 5.252 9.97501Z" stroke="#1A124E" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								</svg>
							</div>
							<div class="ms-3">
								<span class="d-block dropdown-option-icon-main-title">Visit Campus</span>
								<span class="d-block dropdown-option-icon-desc">Apply to visit campuses of our universities</span>
							</div>
						</div>
						<div>
							<svg xmlns="http://www.w3.org/2000/svg" width="6" height="10" viewBox="0 0 6 10" fill="none">
								<path d="M1 1L5 5L1 9" stroke="#1F1F1F" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
						</div>
					</div>
				</a>
			</div>
		</div> -->
		@yield('modal')
		<div class="modal fade" tabindex="-1" id="profile_modal">
			<div class="modal-dialog modal-dialog-centered modal-lg">
				<div class="modal-content" style="padding:24px;">
					<div class="modal-header p-0">
						<h3 class="modal-title">{{__('lang.account')}}</h3>
						<div class="btn btn-icon btn-sm ms-2 btn-close-modal" data-bs-dismiss="modal" aria-label="Close">
							<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
							  <path d="M1 1L9 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
							  <path d="M9 1L1 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
							</svg>
						</div>
					</div>
					<div class="separator-top mt-4"></div>

					<div class="modal-body p-0">
						<div class="col-12 row mt-8">
							<div class="col-12 col-lg-4 separator-right-profile">
								<div class="mb-5 mb-lg-0 me-5 d-flex d-lg-block">
									<div class="modal-profile-side active" data-sidebar="1">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<circle cx="12" cy="12" r="12" fill="white"/>
											<g clip-path="url(#clip0_221_1538)">
												<path d="M14.0895 13.5259L14.0221 13.5783L16.8901 16.1435C16.9521 16.2639 16.9667 16.4048 16.9305 16.5364C16.8943 16.6681 16.8102 16.7795 16.6967 16.8475C16.5832 16.9154 16.4489 16.9349 16.3221 16.902C16.1954 16.8691 16.0855 16.7865 16.0159 16.6708C15.1686 15.1537 13.6711 14.2462 12.009 14.2462C10.3467 14.2462 8.849 15.1535 8.00175 16.6715L8.00173 16.6715L8.00099 16.6729C7.96885 16.7352 7.92504 16.7899 7.87223 16.8341C7.81944 16.8782 7.75872 16.9108 7.69374 16.93C7.62875 16.9492 7.56074 16.9546 7.49373 16.946C7.42671 16.9375 7.36196 16.915 7.30333 16.8799C7.2447 16.8447 7.19335 16.7977 7.15241 16.7414C7.11147 16.6851 7.08179 16.6208 7.06523 16.5522C7.04867 16.4836 7.04557 16.4122 7.05615 16.3423C7.06672 16.2725 7.09073 16.2056 7.12667 16.1457L7.1267 16.1457L7.12761 16.144C7.75709 14.9988 8.73979 14.1069 9.91721 13.6114L9.99594 13.5783L9.9285 13.5259C9.34605 13.073 8.91554 12.4415 8.69827 11.7205C8.48099 10.9995 8.48808 10.2259 8.71851 9.5093C8.94894 8.79273 9.39092 8.1698 9.98154 7.72853C10.5721 7.28729 11.2814 7.05 12.009 7.05C12.7366 7.05 13.4459 7.28729 14.0365 7.72853C14.6271 8.1698 15.0691 8.79273 15.2995 9.5093C15.53 10.2259 15.5371 10.9995 15.3198 11.7205C15.1025 12.4415 14.672 13.073 14.0895 13.5259ZM9.5515 10.6488V10.6489C9.55225 11.3232 9.81099 11.97 10.2715 12.4474C10.732 12.9248 11.3568 13.1937 12.009 13.1944H12.009C12.4955 13.1944 12.9709 13.0449 13.375 12.765C13.7792 12.485 14.0939 12.0874 14.2797 11.6224C14.4655 11.1575 14.5141 10.646 14.4194 10.1526C14.3247 9.6591 14.0909 9.2056 13.7474 8.84948C13.4038 8.49334 12.9659 8.2506 12.4888 8.15223C12.0117 8.05387 11.5173 8.10437 11.068 8.29727C10.6187 8.49017 10.235 8.81669 9.96522 9.23528C9.69541 9.65384 9.5515 10.1458 9.5515 10.6488Z" fill="#1F1F1F" stroke="white" stroke-width="0.1"/>
											</g>
											<defs>
												<clipPath id="clip0_221_1538">
													<rect width="10" height="10" fill="white" transform="translate(7 7)"/>
												</clipPath>
											</defs>
										</svg>

										<span class="profile-modal-text">{{__('lang.myAccount')}}</span>
									</div>
									<div class="modal-profile-side mt-lg-5" data-sidebar="2">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<rect width="24" height="24" rx="12" fill="white"/>
											<path d="M16.7369 8.61975L12.1216 7.02052C12.0426 6.99316 11.9573 6.99316 11.8783 7.02052L7.26298 8.61975C7.1864 8.64629 7.11979 8.6972 7.07259 8.76528C7.02539 8.83336 6.99999 8.91515 7 8.99907V12.9971C7 13.1032 7.04052 13.2049 7.11265 13.2798C7.18478 13.3548 7.28261 13.3969 7.38461 13.3969C7.48662 13.3969 7.58445 13.3548 7.65657 13.2798C7.7287 13.2049 7.76922 13.1032 7.76922 12.9971V9.5538L9.38411 10.113C8.95506 10.8336 8.81862 11.7017 9.00476 12.5267C9.1909 13.3517 9.6844 14.0663 10.3769 14.5134C9.51152 14.8662 8.76345 15.5044 8.21634 16.377C8.18788 16.421 8.16812 16.4704 8.15819 16.5223C8.14827 16.5743 8.14838 16.6278 8.15852 16.6797C8.16866 16.7317 8.18863 16.781 8.21727 16.8248C8.2459 16.8686 8.28263 16.9061 8.32533 16.9351C8.36802 16.964 8.41582 16.9838 8.46595 16.9934C8.51609 17.003 8.56755 17.0021 8.61735 16.9908C8.66715 16.9795 8.7143 16.9581 8.75605 16.9277C8.7978 16.8973 8.83333 16.8586 8.86056 16.8138C9.58507 15.6584 10.7293 14.9962 12 14.9962C13.2706 14.9962 14.4148 15.6584 15.1394 16.8138C15.1958 16.9009 15.2829 16.9615 15.3819 16.9824C15.481 17.0033 15.5839 16.9829 15.6686 16.9255C15.7532 16.8681 15.8127 16.7784 15.8342 16.6758C15.8558 16.5731 15.8376 16.4658 15.7836 16.377C15.2365 15.5044 14.4855 14.8662 13.623 14.5134C14.3149 14.0663 14.8079 13.3522 14.994 12.5278C15.1801 11.7034 15.0441 10.8359 14.6158 10.1155L16.7369 9.38088C16.8135 9.35436 16.8802 9.30345 16.9274 9.23537C16.9746 9.16729 17 9.08549 17 9.00156C17 8.91764 16.9746 8.83584 16.9274 8.76776C16.8802 8.69968 16.8135 8.64877 16.7369 8.62225V8.61975ZM14.3076 11.7977C14.3077 12.177 14.2213 12.5508 14.0555 12.8886C13.8897 13.2265 13.6492 13.5186 13.3538 13.7411C13.0583 13.9636 12.7163 14.1101 12.3558 14.1685C11.9954 14.227 11.6267 14.1958 11.2801 14.0775C10.9335 13.9592 10.6188 13.7572 10.3618 13.488C10.1048 13.2187 9.91294 12.89 9.80186 12.5288C9.69077 12.1676 9.66366 11.7841 9.72276 11.4099C9.78185 11.0356 9.92546 10.6813 10.1418 10.3759L11.8783 10.9756C11.9573 11.003 12.0426 11.003 12.1216 10.9756L13.8581 10.3759C14.1503 10.7877 14.3079 11.286 14.3076 11.7977ZM12 10.177L8.60095 8.99907L12 7.82113L15.399 8.99907L12 10.177Z" fill="#1F1F1F"/>
										</svg>

										<span class="profile-modal-text">{{__('lang.studentProfile')}}</span>
									</div>
									<div class="modal-profile-side mt-lg-5" data-sidebar="3">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<rect width="24" height="24" rx="12" fill="white"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M9.20156 6.90001C8.98444 6.90001 8.77921 6.98309 8.63028 7.12629C8.48188 7.26898 8.40156 7.45909 8.40156 7.65385V15.3462C8.40156 15.5409 8.48188 15.731 8.63028 15.8737C8.77921 16.0169 8.98444 16.1 9.20156 16.1H14.8016C15.0187 16.1 15.2239 16.0169 15.3728 15.8737C15.5212 15.731 15.6016 15.5409 15.6016 15.3462V10.6647C15.6016 10.6647 15.6016 10.6648 15.6016 10.6647C15.6015 10.572 15.5634 10.4798 15.4901 10.4093M15.4901 10.4093L11.9586 7.01361C11.9586 7.0136 11.9586 7.01362 11.9586 7.01361C11.8847 6.9426 11.7813 6.90004 11.6703 6.90001C11.6703 6.90001 11.6703 6.90001 11.6703 6.90001H9.20156M8.07579 6.54963C8.37695 6.26006 8.78217 6.10001 9.20156 6.10001H11.6703C11.9835 6.10005 12.2869 6.21954 12.513 6.43688L16.0446 9.83259C16.2711 10.0505 16.4015 10.3494 16.4016 10.6646V15.3462C16.4016 15.7634 16.229 16.1603 15.9273 16.4504C15.6262 16.74 15.221 16.9 14.8016 16.9H9.20156C8.78217 16.9 8.37695 16.74 8.07579 16.4504C7.7741 16.1603 7.60156 15.7634 7.60156 15.3462V7.65385C7.60156 7.23657 7.7741 6.83971 8.07579 6.54963Z" fill="#1F1F1F"/>
											<path fill-rule="evenodd" clip-rule="evenodd" d="M12.0016 6.10001C12.2225 6.10001 12.4016 6.27909 12.4016 6.50001V9.6579C12.4016 9.77515 12.4481 9.88761 12.5311 9.97052C12.614 10.0534 12.7264 10.1 12.8437 10.1H16.0016C16.2225 10.1 16.4016 10.2791 16.4016 10.5C16.4016 10.7209 16.2225 10.9 16.0016 10.9H12.8437C12.5142 10.9 12.1983 10.7691 11.9654 10.5362C11.7324 10.3033 11.6016 9.98733 11.6016 9.6579V6.50001C11.6016 6.27909 11.7806 6.10001 12.0016 6.10001Z" fill="#1F1F1F"/>
										</svg>

										<span class="profile-modal-text">{{__('lang.myDocument')}}</span>
									</div>
								</div>
								<a class="btn-logout position-absolute" href="/logout">{{__('lang.logOut')}}</a>
							</div>
							<div class="col-12 col-lg-8">
								<div class="ms-5 mb-20" id="my_account">
									<form id="update_user_form">
										<div>
											<div class="d-flex align-items-center image-input image-input-outline" data-kt-image-input="true" style="background-image: url('/assets/media/svg/avatars/blank_1.svg')">
												<div class="image-input-wrapper w-100px h-100px modal-profile-img" style="background-image: url('/assets/media/svg/avatars/blank_1.svg')"></div>
												<div class="ms-5 modal-upload-img">
													<label class="" >
														<span>Upload Photo</span>
														<input type="file" name="profile_pic" accept=".png, .jpg, .jpeg" class="d-none"/>
														<input type="hidden" name="avatar_remove" />
													</label>
												</div>
											</div>
										</div>
										<div class="mt-10">
											<div class="mb-8">
												<label for="exampleFormControlInput1" class="form-label modal-profile-title">{{__('lang.name')}}</label>
												<input type="text" id="profile_name" class="form-control form-control-solid" name="name" placeholder="Enter name"/>
											</div>
											<div class="mb-8">
												<label for="exampleFormControlInput1" class="form-label modal-profile-title">{{__('lang.phoneNumber')}}</label>
												<input type="number" id="profile_contact" class="form-control form-control-solid" name="contact_no" placeholder="Enter phone number"/>
											</div>
											<div class="mb-8">
												<span class="modal-profile-title d-block">{{__('lang.emailPassword')}}</span>
												<span class="modal-email-desc d-block">{{__('lang.youCanLogin')}}<a id="profile_email"></a>{{__('lang.andYourPassword')}}</span>
												<button class="modal-upload-img mt-3 chnage-pwd-btn" data-bs-stacked-modal="#">{{__('lang.changePassword')}}</button>
											</div>
										</div>
										<button type="submit" class="btn-submit-contact-us float-end">Save</button>
									</form>
								</div>
								<div class="ms-5 mb-20" id="student_profile" style="display:none;">
									<span class="modal-profile-title">{{__('lang.myAcademicResult')}}</span>
									<div id="academic_result_list"></div>
									<span class="modal-profile-title d-block pt-10">{{__('lang.top3Characters')}}</span>
									<span class="d-block profile-character-desc pt-2">{{__('lang.selectCharacter')}}</span>

									<div class="mt-5" data-kt-buttons="false">
										<div id="character_list"></div>
										<!-- <div class="col-12 row">
											<div class="col-4">
												<label class="w-100 btn btn-character-profile">
													<div>
														<img src="/images/assets/lawyer-character.png" class="character-img">
														<span class="character-profile-title d-block mt-2">Lawyer</span>
														<div class="form-check form-check-custom form-check-danger form-check-solid form-check-lg justify-content-center mt-10">
															<input class="form-check-input character-checkbox" type="checkbox" value="1" name="character"/>
														</div>
													</div>
												</label>
											</div>
											<div class="col-4">
												<label class="w-100 btn btn-character-profile">
													<div>
														<img src="/images/assets/mass-comm-character.png" class="character-img">
														<span class="character-profile-title d-block mt-2">Mass Comm</span>
														<div class="form-check form-check-custom form-check-danger form-check-solid form-check-lg justify-content-center mt-10">
															<input class="form-check-input character-checkbox" type="checkbox" value="2" name="character"/>
														</div>
													</div>
												</label>
											</div>
											<div class="col-4">
												<label class="w-100 btn btn-character-profile">
													<div>
														<img src="/images/assets/hospitality-characters.png" class="character-img">
														<span class="character-profile-title d-block mt-2">Hospitality</span>
														<div class="form-check form-check-custom form-check-danger form-check-solid form-check-lg justify-content-center mt-10">
															<input class="form-check-input character-checkbox" type="checkbox" value="3" name="character"/>
														</div>
													</div>
												</label>
											</div>
										</div> -->
									</div>
									<a class="text-edit-pathway d-block pt-10" href="/characters">{{__('lang.editProfile')}}</a>
								</div>
								<div class="ms-5 mb-20" id="document_profile" style="display:none;">
									<div>
										<span class="modal-profile-title d-block">{{__('lang.verificationDocument')}}</span>
										<span class="d-block profile-character-desc pt-2">{{__('lang.uploadVerificationDocument')}}</span>
										<div id="user_ic"></div>
										<div class="col-12 row mt-5" id="add_user_id" style="display:none">
											<div class="col-6">
												<div class="dropzone dropzone-ic d-flex justify-content-center" id="ic_front_img">
													<div class="dz-message needsclick text-center py-10">
														<svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
															<g clip-path="url(#clip0_221_2797)">
																<path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 4C21.0304 4 21.5391 4.21071 21.9142 4.58579C22.2893 4.96086 22.5 5.46957 22.5 6V18C22.5 18.5304 22.2893 19.0391 21.9142 19.4142C21.5391 19.7893 21.0304 20 20.5 20H4.5C3.96957 20 3.46086 19.7893 3.08579 19.4142C2.71071 19.0391 2.5 18.5304 2.5 18V6C2.5 5.46957 2.71071 4.96086 3.08579 4.58579C3.46086 4.21071 3.96957 4 4.5 4H20.5ZM10 12H8C7.36528 11.9999 6.75429 12.2412 6.29095 12.675C5.82762 13.1088 5.54664 13.7026 5.505 14.336L5.5 14.5V15C5.50028 15.2549 5.59788 15.5 5.77285 15.6854C5.94782 15.8707 6.18695 15.9822 6.44139 15.9972C6.69584 16.0121 6.94638 15.9293 7.14183 15.7657C7.33729 15.6021 7.4629 15.3701 7.493 15.117L7.5 15V14.5C7.49996 14.383 7.54097 14.2696 7.61589 14.1797C7.69081 14.0898 7.79489 14.0291 7.91 14.008L8 14H10C10.117 14 10.2304 14.041 10.3203 14.1159C10.4102 14.1908 10.4709 14.2949 10.492 14.41L10.5 14.5V15C10.5003 15.2549 10.5979 15.5 10.7728 15.6854C10.9478 15.8707 11.187 15.9822 11.4414 15.9972C11.6958 16.0121 11.9464 15.9293 12.1418 15.7657C12.3373 15.6021 12.4629 15.3701 12.493 15.117L12.5 15V14.5C12.5001 13.8653 12.2588 13.2543 11.825 12.791C11.3912 12.3276 10.7974 12.0466 10.164 12.005L10 12ZM18.5 13H14.5L14.383 13.007C14.14 13.0359 13.9159 13.153 13.7534 13.336C13.5909 13.519 13.5011 13.7552 13.5011 14C13.5011 14.2448 13.5909 14.481 13.7534 14.664C13.9159 14.847 14.14 14.9641 14.383 14.993L14.5 15H18.5L18.617 14.993C18.8601 14.9641 19.0841 14.847 19.2466 14.664C19.4091 14.481 19.4989 14.2448 19.4989 14C19.4989 13.7552 19.4091 13.519 19.2466 13.336C19.0841 13.153 18.8601 13.0359 18.617 13.007L18.5 13ZM16.5 10H14.5C14.2451 10.0003 14 10.0979 13.8146 10.2728C13.6293 10.4478 13.5178 10.687 13.5028 10.9414C13.4879 11.1958 13.5707 11.4464 13.7343 11.6418C13.8979 11.8373 14.1299 11.9629 14.383 11.993L14.5 12H16.5C16.7549 11.9997 17 11.9021 17.1854 11.7272C17.3707 11.5522 17.4822 11.313 17.4972 11.0586C17.5121 10.8042 17.4293 10.5536 17.2657 10.3582C17.1021 10.1627 16.8701 10.0371 16.617 10.007L16.5 10ZM9 8C8.53587 8 8.09075 8.18437 7.76256 8.51256C7.43437 8.84075 7.25 9.28587 7.25 9.75C7.25 10.2141 7.43437 10.6592 7.76256 10.9874C8.09075 11.3156 8.53587 11.5 9 11.5C9.46413 11.5 9.90925 11.3156 10.2374 10.9874C10.5656 10.6592 10.75 10.2141 10.75 9.75C10.75 9.28587 10.5656 8.84075 10.2374 8.51256C9.90925 8.18437 9.46413 8 9 8Z" fill="#999999"/>
															</g>
															<defs>
																<clipPath id="clip0_221_2797">
																	<rect width="24" height="24" fill="white" transform="translate(0.5)"/>
																</clipPath>
															</defs>
														</svg>
														<span class="d-block ic-upload-desc pt-3">{{__('lang.frontIC')}}</span>
													</div>
												</div>
											</div>
											<div class="col-6">
												<div class="dropzone dropzone-ic d-flex justify-content-center" id="ic_back_img">
													<div class="dz-message needsclick text-center py-10">
														<svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">
															<g clip-path="url(#clip0_221_2797)">
																<path fill-rule="evenodd" clip-rule="evenodd" d="M20.5 4C21.0304 4 21.5391 4.21071 21.9142 4.58579C22.2893 4.96086 22.5 5.46957 22.5 6V18C22.5 18.5304 22.2893 19.0391 21.9142 19.4142C21.5391 19.7893 21.0304 20 20.5 20H4.5C3.96957 20 3.46086 19.7893 3.08579 19.4142C2.71071 19.0391 2.5 18.5304 2.5 18V6C2.5 5.46957 2.71071 4.96086 3.08579 4.58579C3.46086 4.21071 3.96957 4 4.5 4H20.5ZM10 12H8C7.36528 11.9999 6.75429 12.2412 6.29095 12.675C5.82762 13.1088 5.54664 13.7026 5.505 14.336L5.5 14.5V15C5.50028 15.2549 5.59788 15.5 5.77285 15.6854C5.94782 15.8707 6.18695 15.9822 6.44139 15.9972C6.69584 16.0121 6.94638 15.9293 7.14183 15.7657C7.33729 15.6021 7.4629 15.3701 7.493 15.117L7.5 15V14.5C7.49996 14.383 7.54097 14.2696 7.61589 14.1797C7.69081 14.0898 7.79489 14.0291 7.91 14.008L8 14H10C10.117 14 10.2304 14.041 10.3203 14.1159C10.4102 14.1908 10.4709 14.2949 10.492 14.41L10.5 14.5V15C10.5003 15.2549 10.5979 15.5 10.7728 15.6854C10.9478 15.8707 11.187 15.9822 11.4414 15.9972C11.6958 16.0121 11.9464 15.9293 12.1418 15.7657C12.3373 15.6021 12.4629 15.3701 12.493 15.117L12.5 15V14.5C12.5001 13.8653 12.2588 13.2543 11.825 12.791C11.3912 12.3276 10.7974 12.0466 10.164 12.005L10 12ZM18.5 13H14.5L14.383 13.007C14.14 13.0359 13.9159 13.153 13.7534 13.336C13.5909 13.519 13.5011 13.7552 13.5011 14C13.5011 14.2448 13.5909 14.481 13.7534 14.664C13.9159 14.847 14.14 14.9641 14.383 14.993L14.5 15H18.5L18.617 14.993C18.8601 14.9641 19.0841 14.847 19.2466 14.664C19.4091 14.481 19.4989 14.2448 19.4989 14C19.4989 13.7552 19.4091 13.519 19.2466 13.336C19.0841 13.153 18.8601 13.0359 18.617 13.007L18.5 13ZM16.5 10H14.5C14.2451 10.0003 14 10.0979 13.8146 10.2728C13.6293 10.4478 13.5178 10.687 13.5028 10.9414C13.4879 11.1958 13.5707 11.4464 13.7343 11.6418C13.8979 11.8373 14.1299 11.9629 14.383 11.993L14.5 12H16.5C16.7549 11.9997 17 11.9021 17.1854 11.7272C17.3707 11.5522 17.4822 11.313 17.4972 11.0586C17.5121 10.8042 17.4293 10.5536 17.2657 10.3582C17.1021 10.1627 16.8701 10.0371 16.617 10.007L16.5 10ZM9 8C8.53587 8 8.09075 8.18437 7.76256 8.51256C7.43437 8.84075 7.25 9.28587 7.25 9.75C7.25 10.2141 7.43437 10.6592 7.76256 10.9874C8.09075 11.3156 8.53587 11.5 9 11.5C9.46413 11.5 9.90925 11.3156 10.2374 10.9874C10.5656 10.6592 10.75 10.2141 10.75 9.75C10.75 9.28587 10.5656 8.84075 10.2374 8.51256C9.90925 8.18437 9.46413 8 9 8Z" fill="#999999"/>
															</g>
															<defs>
																<clipPath id="clip0_221_2797">
																	<rect width="24" height="24" fill="white" transform="translate(0.5)"/>
																</clipPath>
															</defs>
														</svg>
														<span class="d-block ic-upload-desc pt-3">{{__('lang.backIC')}}</span>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="mt-10">
											<span class="modal-profile-title d-block">{{__('lang.proofAcademic')}}</span>
											<span class="d-block profile-character-desc pt-2 pb-2">{{__('lang.uploadTranscript')}}</span>
											<div id="upload_docs_profile">
												<div class="dropzone-items wm-200px pb-0" id="preview_result" style="display:none">
													<div class="dropzone-item" id="preview_result_list" style="display:none">
														<div id="result_list_append"></div>
														<div class="col-12 row">
															<div class="dropzone-file col-11">
																<div class="dropzone-filename" title="some_image_file_name.jpg">
																	<span data-dz-name>some_image_file_name.jpg</span>
																	<strong>(<span data-dz-size>340kb</span>)</strong>
																</div>
																<div class="dropzone-error" data-dz-errormessage></div>
															</div>
															<div class="dropzone-toolbar col-1">
																<span class="dropzone-delete" id="delete_dropzone_list" data-dz-remove><i class="bi bi-x fs-1"></i></span>
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
									<div class="mt-10">
											<span class="modal-profile-title d-block">{{__('lang.myResume')}}</span>
											<span class="d-block profile-character-desc pt-2 pb-2">{{__('lang.uploadResume')}}</span>
											<div id="upload_resume_profile">
												<div class="dropzone-items wm-200px pb-0" style="display:none">
													<div class="dropzone-item" style="display:none">
														<div class="col-12 row">
															<div class="dropzone-file col-11">
																<div class="dropzone-filename" title="some_image_file_name.jpg">
																	<span data-dz-name>some_image_file_name.jpg</span>
																	<strong>(<span data-dz-size>340kb</span>)</strong>
																</div>
																<div class="dropzone-error" data-dz-errormessage></div>
															</div>
															<div class="dropzone-toolbar col-1">
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
								<form id="user_doc_form"></form>
								<button id="submit_user_doc" class="btn-submit-contact-us float-end mt-6">Save</button>
							</div>
							</div>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="modal fade" tabindex="-1" id="profile_change_pwd_modal" style="background: rgba(0, 0, 0, 0.50);">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style="padding:24px;">
					<div class="modal-header p-0">
						<h3 class="modal-title">{{__('lang.changePassword')}}</h3>
						<div class="btn btn-icon btn-sm ms-2 btn-close-modal" data-bs-dismiss="modal" aria-label="Close">
							<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
							  <path d="M1 1L9 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
							  <path d="M9 1L1 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
							</svg>
						</div>
					</div>
					<div class="separator-top mt-4"></div>

					<div class="modal-body p-0">
						<div class="mt-8">
							<form id="forget_password_form">
								<div class="mb-8">
									<label for="exampleFormControlInput1" class="form-label modal-profile-title">{{__('lang.currentPassword')}}</label>
									<input type="password" class="form-control form-control-solid" name="curr_pass" placeholder="Enter current password"/>
								</div>
								<div class="mb-8">
									<label for="exampleFormControlInput1" class="form-label modal-profile-title">{{__('lang.newPassword')}}</label>
									<input type="password" class="form-control form-control-solid" name="pass" placeholder="Enter new password"/>
									<div class="d-flex mt-3">
										<!-- <svg class="mt-3" xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none">
										<path d="M1 4.81818L3.13333 7L9 1" stroke="#CCCCCC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg> -->
										<div class="ms-3"><span class="pwd-guide">{{__('lang.newPasswordReq')}}</span></div>
									</div>
								</div>
								<div class="mb-8">
									<label for="exampleFormControlInput1" class="form-label modal-profile-title">{{__('lang.repeatNewPassword')}}</label>
									<input type="password" class="form-control form-control-solid" name="confirm_pass" placeholder="Enter new password again"/>
									<div class="d-flex align-items-center mt-3">
										<!-- <svg xmlns="http://www.w3.org/2000/svg" width="10" height="8" viewBox="0 0 10 8" fill="none">
										<path d="M1 4.81818L3.13333 7L9 1" stroke="#CCCCCC" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										</svg> -->
										<div class="ms-3"><span class="pwd-guide">{{__('lang.passwordMatch')}}</span></div>
									</div>
								</div>
								<button class="w-100 apply-scholarship-btn">{{__('lang.confirm')}}</button>
							</form>
						</div>
					</div>

				</div>
			</div>
		</div>

		<div class="modal fade" tabindex="-1" id="login_modal" style="background: rgba(0, 0, 0, 0.50);">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content br-12" style="padding:24px;">
					<div class="modal-header p-0">
						<h3 class="modal-title login_title">{{__('lang.logIn')}}</h3>
						<div class="btn btn-icon btn-sm ms-2 btn-close-modal" data-bs-dismiss="modal" aria-label="Close">
							<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
							  <path d="M1 1L9 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
							  <path d="M9 1L1 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
							</svg>
						</div>
					</div>

					<div class="modal-body p-0">
						<form id="login_form">
							<div class="mt-8">
								<div class="mb-3">
									<input type="text" class="form-control form-control-solid login-input" name="email" placeholder="Email address"/>
								</div>
								<div class="mb-3">
									<input type="password" class="form-control form-control-solid login-input" name="password" placeholder="Password"/>
								</div>
								<a class="float-end forget-pass mb-3">{{__('lang.forgetPassword')}}</a>
								<button class="w-100 apply-scholarship-btn">{{__('lang.logIn')}}</button>
								<a href="javascript:void(0)" class="forget-pass mt-3 d-block text-center" id="pathway">{{__('lang.createAnAccount')}}</a>
							</div>
						</form>
					</div>

				</div>
			</div>
		</div>

		<div class="modal fade" tabindex="-1" id="signup_modal" style="background: rgba(0, 0, 0, 0.50);">
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
							<form id="sign_up_form">
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

		<div id="kt_drawer_example_dismiss" class="bg-white" data-kt-drawer="true" data-kt-drawer-activate="true" data-kt-drawer-toggle="#kt_drawer_example_dismiss_button" data-kt-drawer-close="#kt_drawer_example_dismiss_close" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'100%', 'md': '500px'}">
			<div class="col-12">
				<div>
					<div class="d-flex justify-content-between">
						<div class="text-start mt-6">
							<div class="dropdown">
								<button class="btn dropdown-toggle" type="button" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="margin-bottom:13px;">
									<svg class="ms-5 cursor" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="#666">
										<path d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
										<path d="M2 12H22M12 2C9.43223 4.69615 8 8.27674 8 12C8 15.7233 9.43223 19.3038 12 22C14.5678 19.3038 16 15.7233 16 12C16 8.27674 14.5678 4.69615 12 2Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
									</svg>
								</button>
								<ul class="dropdown-menu" aria-labelledby="languageDropdown">
									<li><a class="dropdown-item" href="/change-language/en">EN</a></li>
									<li><a class="dropdown-item" href="/change-language/ch">中文</a></li>
									<li><a class="dropdown-item" href="/change-language/bm">BM</a></li>
								</ul>
							</div>
						</div>
						<div class="text-end mt-9 me-8">
							<svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" data-kt-drawer-dismiss="true">
								<path d="M1 1L13 13" stroke="#9C9C9C" stroke-linecap="round"/>
								<path d="M13 1L0.999999 13" stroke="#9C9C9C" stroke-linecap="round"/>
							</svg>
						</div>
					</div>
					<div class="col-11 mt-5 mx-auto text-center row">
						<a class="mobile-sidebar-option mt-10 text-start cursor exploreaccordion accordion-button collapsed" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1" aria-expanded="false" aria-controls="kt_accordion_1_body_1" style="font-weight:500;">{{__('lang.explore')}}</a>
						<div id="kt_accordion_1_body_1" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
							<div class="accordion-body text-start mb-auto mx-auto">
								<a href="/explore?tab=university" class="explore-tab d-block mb-3" data-tab="university" style="font-size:16px;color:#666">{{__('lang.uni')}}</a>
								<a href="/explore?tab=course" class="explore-tab d-block mb-3" data-tab="course" style="font-size:16px;color:#666">{{__('lang.courses')}}</a>
								<a href="/compare-course" class="explore-tab d-block mb-3" data-tab="comparecourse" style="font-size:16px;color:#666">{{__('lang.compareCourse')}}</a>
								<!-- <a href="/explore?tab=k12" class="explore-tab d-block mb-3" data-tab="k12" style="font-size:16px;color:#666">{{__('lang.k12')}}</a> -->
								<a href="/explore?tab=scholarship" class="explore-tab d-block mb-3" data-tab="scholarship" style="font-size:16px;color:#666">{{__('lang.scholarship')}}</a>
								<a href="/visit-campus" style="font-size:16px;color:#666">{{__('lang.campusVisit')}}</a>
							</div>
						</div>
						

						<a class="mobile-sidebar-option mt-5 text-start cursor" style="font-weight:500;" href="/careers">{{__('lang.careers')}}</a>
						<a class="mobile-sidebar-option mt-5 text-start cursor" style="font-weight:500;" href="/blog">{{__('lang.blog')}}</a>
						<a class="mobile-sidebar-option mt-5 text-start cursor" style="font-weight:500;" id="pathway">{{__('lang.pathway360')}}</a>
						<a class="mobile-sidebar-option mt-5 text-start cursor" style="font-weight:500;" href="/contact-us">{{__('lang.enquireNow')}}</a>

						<div class="col-12 row mx-auto p-0 mt-10" id="mobile-sidebar-login">
							@if(Session::has('login_user_id'))
							<div class="col-12">
								<a class="sidebar-get-started d-block w-100" id="pathway">{{__('lang.homeGetStarted')}}</a>
							</div>
							@else 
							<div class="col-6">
								<a class="sidebar-login d-block text-center w-100" id="login_btn">{{__('lang.logIn')}}</a>
							</div>
							<div class="col-6">
								<a class="sidebar-get-started d-block w-100" id="pathway">{{__('lang.homeGetStarted')}}</a>
							</div>
							@endif
						</div>
						<div class="col-12">
							<a class="d-block w-100 sidebar-apply-now mt-5" href="/explore/?tab=course">{{__('lang.applyNow')}}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<div class="modal fade" tabindex="-1" id="pathway_modal" style="background: rgba(0, 0, 0, 0.50);">
			<div class="modal-dialog modal-dialog-centered">
				<div class="modal-content" style="padding:36px;">
					<!-- <div class="modal-header p-0">
						<div class="btn btn-icon btn-sm ms-2 btn-close-modal" data-bs-dismiss="modal" aria-label="Close">
							<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
							  <path d="M1 1L9 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
							  <path d="M9 1L1 9" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
							</svg>
						</div>
					</div> -->

					<div class="modal-body p-0">
						<span class="modal-pathway-title d-block">{{__('lang.introDesc')}}</span>
						<span class="d-block modal-pathway-desc mt-1">{{__('lang.introDesc1')}}</span>
						<form id="">
							<div class="mt-8">
								<div class="mb-3">
									<label for="exampleFormControlInput1" class="form-label">{{__('lang.whatsYourName')}}</label>
									<input type="text" class="form-control form-control-solid login-input" name="username" placeholder="{{__('lang.enterFullname')}}"/>
								</div>
								<button class="w-100 apply-scholarship-btn mt-10" id="pathway_start_btn">{{__('lang.start')}}</button>
								<span href="javascript:void(0)" class="pathway-agree-terms mt-10 d-block text-center">{{__('lang.startDesc')}}<a class="pathway-agree-terms-link" href="/terms">{{__('lang.terms1')}}</a>{{__('lang.and')}}<a class="pathway-agree-terms-link" href="/privacy">{{__('lang.privacy1')}}</a></span>
							</div>
						</form>
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

		@if(Request::is('/') || Request::is('university') || Request::is('scholarship') || Request::is('register-interest') || Request::is('course-details') || Request::is('blog') || Request::is('blog-details/*') || Request::is('careers') || Request::is('consult-counsellor/*') || Request::is('visit-campus/*') || Request::is('contact-us') || Request::is('terms') || Request::is('privacy') || Request::is('compare-course'))
		@include('member._footer')
		@endif
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
		
		$(".menu-explore").hover(function () {
			$('.dropdown-option').show();
		},
		
		function () {
			$('.dropdown-option').hide();
		});

		$(".dropdown-option").hover(function () {
			$('.dropdown-option').show();
		},
		function () {
			$('.dropdown-option').hide();
		});

		$("#header-active").hide();
		$('#header').show();

		$(document).on('click', '#pathway', function(){
			$('#pathway_modal').modal('show');
		})

		$(document).on('click', '#counsellor', function(){
			window.location.href = 'consult-counsellor/1';
		})

		$(document).on('click', '#profile_btn', function(){
			$('#profile_modal').modal('show');
			getProfile();
		})

		$(document).on('click', '#login_btn', function(){
			$('#login_modal').modal('show');
		})

		$(document).on('click', '#sign_up_btn', function(){
			$('#signup_modal').modal('show');
			$('#login_modal').modal('hide');
		})

		$('.chnage-pwd-btn').on('click', function(){
			$('#profile_change_pwd_modal').modal('show');
		})

		$('#sign_up_form').on('submit', function (e) {
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
						$('#signup_modal').modal('hide');
						$('#login_modal').modal('show');

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

		$('#login_form').on('submit', function (e) {
			e.preventDefault();
			let f = new FormData($(this)[0]);

			$.ajax({
				url: "/login",
				method: 'post',
				data: f,
				contentType: false,
				processData: false,
				success: function(d) {
					if (d.status) {
						$('#header').load(document.URL +  ' #header');
						$('#header-active').load(document.URL +  ' #header-active');
						$('#header-normal').load(document.URL +  ' #header-normal');
						$('#mobile-sidebar-login').load(document.URL +  ' #mobile-sidebar-login');

						$('#signup_modal').modal('hide');
						$('#login_modal').modal('hide');

						$('.toast-error').toast('hide');
						
						$('.toast-success').toast('show');
						$('.success-msg').html(d.msg);
						setTimeout(function(){
							$('.toast-success').toast('hide');
						},3000)
					} else{
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

		function getProfile()
		{
			$.ajax({
				url: "/getUserProfileJs",
				method: 'post',
				contentType: false,
				processData: false,
				success: function(d) {
					var details = d.details;
					var result = d.result;
					var character = d.character;

					var result_content = '';
					var character_list = '';
					var result_list = '';
					var ic_list = '';

					if(d.status) {

						$('#profile_name').val(details.name);
						$('#profile_contact').val(details.contact_no);
						$('#profile_email').html(details.email);

						if(details.profile_pic != null) {
							$('.modal-profile-img').css('background-image', 'url(/images/profile/'+details.profile_pic+')');
						}

						if(details.result.length > 0) {
							for (i = 0; i < details.result.length; i++) {
								result_myDropzone.emit("addedfile", details.result[i]);
								result_myDropzone.emit("complete", details.result[i]);                
							}
						}

						if(details.resume.length > 0) {
							for (i = 0; i < details.resume.length; i++) {
								resume_myDropzone.emit("addedfile", details.resume[i]);
								resume_myDropzone.emit("complete", details.resume[i]);                
							}
						}

						if(details.ic_front.length > 0 && details.ic_back.length > 0) {
							ic_list += '<div class="col-12 row mt-5">';
							ic_list += '<div class="col-6 position-relative">';
							ic_list += '<div class="text-center" style="width: 200px; height:120px;border-radius: 8px;background: linear-gradient(0deg, rgba(0, 0, 0, 0.50) 0%, rgba(0, 0, 0, 0.50) 100%), url(/images/document/'+details.ic_front[0]['img']+') lightgray 50% / cover no-repeat;">';
							ic_list += '<div style="position: absolute;top: 35%;left: 35%;">';
							ic_list += '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">';
							ic_list += '<path d="M12.5 2.25C7.11547 2.25 2.75 6.61547 2.75 12C2.75 17.3845 7.11547 21.75 12.5 21.75C17.8845 21.75 22.25 17.3845 22.25 12C22.25 6.61547 17.8845 2.25 12.5 2.25ZM17 13.5H12.5C12.3011 13.5 12.1103 13.421 11.9697 13.2803C11.829 13.1397 11.75 12.9489 11.75 12.75V6C11.75 5.80109 11.829 5.61032 11.9697 5.46967C12.1103 5.32902 12.3011 5.25 12.5 5.25C12.6989 5.25 12.8897 5.32902 13.0303 5.46967C13.171 5.61032 13.25 5.80109 13.25 6V12H17C17.1989 12 17.3897 12.079 17.5303 12.2197C17.671 12.3603 17.75 12.5511 17.75 12.75C17.75 12.9489 17.671 13.1397 17.5303 13.2803C17.3897 13.421 17.1989 13.5 17 13.5Z" fill="white"/>';
							ic_list += '</svg>';
							if(details.ic_front[0]['status'] == 1) {
								ic_list += '<span class="d-block" style="color: #fff;font-size: 14px;">Reviewing</span>';
							} else if(details.ic_front[0]['status'] == 2) {
								ic_list += '<span class="d-block" style="color: #fff;font-size: 14px;">Approved</span>';
							} else if(details.ic_front[0]['status'] == 3) {
								ic_list += '<span class="d-block" style="color: #fff;font-size: 14px;">Rejected <br>(Please upload again)</span>';
							}
							ic_list += '</div>';
							ic_list += '</div>';
							ic_list += '</div>';
							ic_list += '<div class="col-6 position-relative">';
							ic_list += '<div class="text-center" style="width: 200px; height:120px;border-radius: 8px;background: linear-gradient(0deg, rgba(0, 0, 0, 0.50) 0%, rgba(0, 0, 0, 0.50) 100%), url(/images/document/'+details.ic_back[0]['img']+') lightgray 50% / cover no-repeat;">';
							ic_list += '<div style="position: absolute;top: 35%;left: 35%;">';
							ic_list += '<svg xmlns="http://www.w3.org/2000/svg" width="25" height="24" viewBox="0 0 25 24" fill="none">';
							ic_list += '<path d="M12.5 2.25C7.11547 2.25 2.75 6.61547 2.75 12C2.75 17.3845 7.11547 21.75 12.5 21.75C17.8845 21.75 22.25 17.3845 22.25 12C22.25 6.61547 17.8845 2.25 12.5 2.25ZM17 13.5H12.5C12.3011 13.5 12.1103 13.421 11.9697 13.2803C11.829 13.1397 11.75 12.9489 11.75 12.75V6C11.75 5.80109 11.829 5.61032 11.9697 5.46967C12.1103 5.32902 12.3011 5.25 12.5 5.25C12.6989 5.25 12.8897 5.32902 13.0303 5.46967C13.171 5.61032 13.25 5.80109 13.25 6V12H17C17.1989 12 17.3897 12.079 17.5303 12.2197C17.671 12.3603 17.75 12.5511 17.75 12.75C17.75 12.9489 17.671 13.1397 17.5303 13.2803C17.3897 13.421 17.1989 13.5 17 13.5Z" fill="white"/>';
							ic_list += '</svg>';
							if(details.ic_front[0]['status'] == 1) {
								ic_list += '<span class="d-block" style="color: #fff;font-size: 14px;">Reviewing</span>';
							} else if(details.ic_front[0]['status'] == 2) {
								ic_list += '<span class="d-block" style="color: #fff;font-size: 14px;">Approved</span>';
							} else if(details.ic_front[0]['status'] == 3) {
								ic_list += '<span class="d-block" style="color: #fff;font-size: 14px;">Rejected <br>(Please upload again)</span>';
							}
							ic_list += '</div>';
							ic_list += '</div>';
							ic_list += '</div>';
							ic_list += '</div>';
							$('#user_ic').empty().append(ic_list);
						} else{
							$('#add_user_id').show();
							$('#user_ic').hide();
						}

						result_content += '<div class="col-12 row">';
						result.forEach(function(r, i) {
							result_content += '<div class="col-4 mt-5">';
							result_content += '<span class="d-block profile-grades-title">'+r.grade+'</span>';
							result_content += '<span class="profile-grades-sub-title">'+r.name+'</span>';
							result_content += '</div>';
						});
						result_content += '</div>';
						
						character_list += '<div class="col-12 row">';
						character_list += '<div class="col-4">';
						character_list += '<label class="w-100 btn btn-character-profile" style="height:150px;">';
						character_list += '<div>';
						character_list += '<img src="'+character[1].img+'" class="character-img">';
						character_list += '<span class="character-profile-title d-block mt-2">'+character[1].name+'</span>';
						character_list += '<div class="form-check-custom form-check-danger form-check-solid form-check-lg justify-content-center mt-10">';
						// character_list += '<input class="form-check-input character-checkbox" type="checkbox" value="1" name="character"/>';
						character_list += '</div>';
						character_list += '</div>';
						character_list += '</label>';
						character_list += '</div>';
						character_list += '<div class="col-4">';
						character_list += '<label class="w-100 btn btn-character-profile" style="height:150px;">';
						character_list += '<div>';
						character_list += '<img src="'+character[0].img+'" class="character-img">';
						character_list += '<span class="character-profile-title d-block mt-2">'+character[0].name+'</span>';
						character_list += '<div class="form-check form-check-custom form-check-danger form-check-solid form-check-lg justify-content-center mt-10">';
						// character_list += '<input class="form-check-input character-checkbox" type="checkbox" value="1" name="character"/>';
						character_list += '</div>';
						character_list += '</div>';
						character_list += '</label>';
						character_list += '</div>';
						character_list += '<div class="col-4">';
						character_list += '<label class="w-100 btn btn-character-profile" style="height:150px;">';
						character_list += '<div>';
						character_list += '<img src="'+character[2].img+'" class="character-img">';
						character_list += '<span class="character-profile-title d-block mt-2">'+character[2].name+'</span>';
						character_list += '<div class="form-check form-check-custom form-check-danger form-check-solid form-check-lg justify-content-center mt-10">';
						// character_list += '<input class="form-check-input character-checkbox" type="checkbox" value="1" name="character"/>';
						character_list += '</div>';
						character_list += '</div>';
						character_list += '</label>';
						character_list += '</div>';
						character_list += '</div>';
						$('#academic_result_list').empty().append(result_content);
						$('#character_list').empty().append(character_list);
					}
				}
			})

		}

		$('#forget_password_form').on('submit', function (e) {
			e.preventDefault();
    		let f = new FormData($(this)[0]);

			$.ajax({
				url: "/forgetPassword",
				method: 'post',
				data: f,
				contentType: false,
				processData: false,
				success: function(d) {
		
					if (d.status) {
						$('.toast-error').toast('hide');
						$('.toast-success').toast('show');
						$('.success-msg').html(d.msg);
						setTimeout(function(){
							$('.toast-success').toast('hide');
						},3000)

						$('#profile_change_pwd_modal').modal('hide');
						$("#forget_password_form")[0].reset();

					} else{
						$('.toast-error').toast('show');
						$('.toast-success').toast('hide');
						$('.err-msg').html(d.msg);
						setTimeout(function(){
							$('.toast-error').toast('hide');
						},3000)
					}
				}
			});
		});

		$('#pathway_start_btn').on('click', function(e){
			e.preventDefault();
			var name = $('input[name="username"]').val();

			if(name == null) {
				$('.toast-error').toast('show');
				$('.toast-success').toast('hide');
				$('.err-msg').html('Please enter name to continue');
				setTimeout(function(){
					$('.toast-error').toast('hide');
				},3000)
			} else{
				$.ajax({
					url: "/addName",
					method: 'post',
					data: {name:name},
					success: function(d) {

						if(d.status) {
							window.location.href = "/personality";
						}else{
							$('.toast-error').toast('show');
							$('.toast-success').toast('hide');
							$('.err-msg').html(d.msg);
							setTimeout(function(){
								$('.toast-error').toast('hide');
							},3000)
						}
					}
				});
			}
		})

		$('#update_user_form').on('submit', function(e){
			console.log('heloo')
			e.preventDefault();
    		let f = new FormData($(this)[0]);

			$.ajax({
				url: "/updateProfile",
				method: 'post',
				data: f,
				contentType: false,
				processData: false,
				success: function(d) {

					if (d.status) {
						$('.toast-error').toast('hide');
						$('.toast-success').toast('show');
						$('.success-msg').html(d.msg);
						setTimeout(function(){
							$('.toast-success').toast('hide');
						},3000)

						getProfile();
					} else{
						$('.toast-error').toast('show');
						$('.toast-success').toast('hide');
						$('.err-msg').html(d.msg);
						setTimeout(function(){
							$('.toast-error').toast('hide');
						},3000)
					}
				}
			});
		})

		$('#submit_user_doc').on('click', function(e){
			let f = new FormData($('#user_doc_form')[0]);

			result_myDropzone.files.forEach(function(item) {
				f.append('result[]', item);
			});

			resume_myDropzone.files.forEach(function(item) {
				f.append('resume[]', item);
			});

			f.append('ic_front', ic_front_myDropzone.files[0]);
			f.append('ic_back', ic_back_myDropzone.files[0]);

			$.ajax({
				url: "/submitUserDocument",
				method: 'post',
				data: f,
				enctype: 'multipart/form-data',
				contentType: false,
				processData: false,
				success: function(d) {
					if (d.status) {
						$('.toast-error').toast('hide');
						$('.toast-success').toast('show');
						$('.success-msg').html(d.msg);
						setTimeout(function(){
							$('.toast-success').toast('hide');
						},3000)

						getProfile();
					} else{
						$('.toast-error').toast('show');
						$('.toast-success').toast('hide');
						$('.err-msg').html(d.msg);
						setTimeout(function(){
							$('.toast-error').toast('hide');
						},3000)
					}
				}
			});
		});
	});

	$(window).on("scroll", function() {
		if($(window).scrollTop() > 50) {
			$("#header-active").show();
			$('#header').hide();
			$('.custom-header').addClass('active');
		} else {
			$("#header-active").hide();
			$('#header').show();
			$('.custom-header').removeClass('active');
		}
	})

	$('.character-checkbox').on('change', function(){
		$('.character-checkbox').not(this).prop('checked', false);
	});

	$('.modal-profile-side').on('click', function(){
		var type = $(this).data('sidebar');
		$(this).addClass('active');
		$(".modal-profile-side").not(this).removeClass('active');

		if(type == 1){
			$('#my_account').show();
			$('#student_profile').hide();
			$('#document_profile').hide();
		} else if(type == 2){
			$('#my_account').hide();
			$('#student_profile').show();
			$('#document_profile').hide();
		} else if(type == 3){
			$('#my_account').hide();
			$('#student_profile').hide();
			$('#document_profile').show();
		}

	});

	var ic_front_myDropzone = new Dropzone("#ic_front_img", {
		url: "/uploadDocument",
		paramName: "ic_front",
		maxFiles: 10,
		maxFilesize: 10,
		addRemoveLinks: true,
	});

	var ic_back_myDropzone = new Dropzone("#ic_back_img", {
		url: "/uploadDocument",
		paramName: "ic_back",
		maxFiles: 10,
		maxFilesize: 10,
		addRemoveLinks: true,
	});

	const id = "#upload_docs_profile";
	const dropzone = document.querySelector(id);

	var previewNode = dropzone.querySelector(".dropzone-item");
	previewNode.id = "";
	var previewTemplate = previewNode.parentNode.innerHTML;
	previewNode.parentNode.removeChild(previewNode);

	var result_myDropzone = new Dropzone(id, {
	    url: "/uploadDocument",
	    parallelUploads: 5,
	    maxFilesize: 5,
	    maxFiles: 5,
	    previewTemplate: previewTemplate,
	    previewsContainer: id + " .dropzone-items",
	    clickable: id + " .dropzone-select",
		paramName: "result",
	});

	result_myDropzone.on("addedfile", function (file) {
	    // Hookup the start button
	    const dropzoneItems = dropzone.querySelectorAll('.dropzone-item');
	    const dropzoneItems1 = dropzone.querySelectorAll('.dropzone-items');
	    dropzoneItems.forEach(dropzoneItem => {
	        dropzoneItem.style.display = '';
	    });

	    dropzoneItems1.forEach(dropzoneItem1 => {
	        dropzoneItem1.style.display = '';
	    });

		file.previewElement.addEventListener("click", function() {
			window.open('https://edu4u.world/images/document/'+file.name, '_blank');
		});
	});

	// Update the total progress bar
	result_myDropzone.on("totaluploadprogress", function (progress) {
	    const progressBars = dropzone.querySelectorAll('.progress-bar');
	    progressBars.forEach(progressBar => {
	        progressBar.style.width = progress + "%";
	    });
	});

	result_myDropzone.on("sending", function (file) {
	    // Show the total progress bar when upload starts
	    const progressBars = dropzone.querySelectorAll('.progress-bar');
	    progressBars.forEach(progressBar => {
	        progressBar.style.opacity = "1";
	    });
	});

	// Hide the total progress bar when nothing"s uploading anymore
	result_myDropzone.on("complete", function (progress) {
	    const progressBars = dropzone.querySelectorAll('.dz-complete');

	    setTimeout(function () {
	        progressBars.forEach(progressBar => {
	            progressBar.querySelector('.progress-bar').style.opacity = "0";
	            progressBar.querySelector('.progress').style.opacity = "0";
	        });
	    }, 300);
	});
	
	result_myDropzone.on("removedfile", function(file){
		$.post("/deleteUserDocument?id=" + file.id);
	});

	const id1 = "#upload_resume_profile";
	const dropzone1 = document.querySelector(id1);

	var previewNode1 = dropzone1.querySelector(".dropzone-item");
	previewNode1.id1 = "";
	var previewTemplate = previewNode1.parentNode.innerHTML;
	previewNode1.parentNode.removeChild(previewNode1);

	var resume_myDropzone = new Dropzone(id1, {
	    url: "/uploadDocument",
	    parallelUploads: 5,
	    maxFilesize: 5,
	    maxFiles: 5,
	    previewTemplate: previewTemplate,
	    previewsContainer: id1 + " .dropzone-items",
	    clickable: id1 + " .dropzone-select",
		paramName: "resume",
	});

	resume_myDropzone.on("addedfile", function (file) {
	    // Hookup the start button
	    const dropzoneItems = dropzone1.querySelectorAll('.dropzone-item');
	    const dropzoneItems1 = dropzone1.querySelectorAll('.dropzone-items');
	    dropzoneItems.forEach(dropzoneItem => {
	        dropzoneItem.style.display = '';
	    });

	    dropzoneItems1.forEach(dropzoneItem1 => {
	        dropzoneItem1.style.display = '';
	    });

		file.previewElement.addEventListener("click", function() {
			window.open('https://edu4u.world/images/document/'+file.name, '_blank');
		});
	});

	// Update the total progress bar
	resume_myDropzone.on("totaluploadprogress", function (progress) {
	    const progressBars = dropzone1.querySelectorAll('.progress-bar');
	    progressBars.forEach(progressBar => {
	        progressBar.style.width = progress + "%";
	    });
	});

	resume_myDropzone.on("sending", function (file) {
	    // Show the total progress bar when upload starts
	    const progressBars = dropzone1.querySelectorAll('.progress-bar');
	    progressBars.forEach(progressBar => {
	        progressBar.style.opacity = "1";
	    });
	});

	// Hide the total progress bar when nothing"s uploading anymore
	resume_myDropzone.on("complete", function (progress) {
	    const progressBars = dropzone1.querySelectorAll('.dz-complete');

	    setTimeout(function () {
	        progressBars.forEach(progressBar => {
	            progressBar.querySelector('.progress-bar').style.opacity = "0";
	            progressBar.querySelector('.progress').style.opacity = "0";
	        });
	    }, 300);
	});

	resume_myDropzone.on("removedfile", function(file){
		$.post("/deleteUserDocument?id=" + file.id);
	});

	</script>
	@yield('script')
</body>
</html>
