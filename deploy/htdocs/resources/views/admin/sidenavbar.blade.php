<style>
	.menu-link.active {
		background-color: #f0f0f0;
		color: #333;
	}
</style>
	<!-- sidenavbar -->
	<div class="page d-flex flex-row flex-column-fluid">
				<!--begin::Aside-->
				<div id="kt_aside" class="aside aside-dark aside-hoverable" data-kt-drawer="true" data-kt-drawer-name="aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'200px', '300px': '250px'}" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_aside_mobile_toggle">
					<!--begin::Brand-->
					<div class="aside-logo flex-column-auto" id="kt_aside_logo">
						<!--begin::Logo-->
						<a href="/">
							<img alt="Logo" src="/images/logo/edu4ulogo-light.png" class="h-25px logo" />
						</a>
						<!--end::Logo-->
						<!--begin::Aside toggler-->
						<div id="kt_aside_toggle" class="btn btn-icon w-auto px-0 btn-active-color-primary aside-toggle" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="aside-minimize">
							<!--begin::Svg Icon | path: icons/duotune/arrows/arr079.svg-->
							<span class="svg-icon svg-icon-1 rotate-180">
								<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
									<path opacity="0.5" d="M14.2657 11.4343L18.45 7.25C18.8642 6.83579 18.8642 6.16421 18.45 5.75C18.0358 5.33579 17.3642 5.33579 16.95 5.75L11.4071 11.2929C11.0166 11.6834 11.0166 12.3166 11.4071 12.7071L16.95 18.25C17.3642 18.6642 18.0358 18.6642 18.45 18.25C18.8642 17.8358 18.8642 17.1642 18.45 16.75L14.2657 12.5657C13.9533 12.2533 13.9533 11.7467 14.2657 11.4343Z" fill="currentColor" />
									<path d="M8.2657 11.4343L12.45 7.25C12.8642 6.83579 12.8642 6.16421 12.45 5.75C12.0358 5.33579 11.3642 5.33579 10.95 5.75L5.40712 11.2929C5.01659 11.6834 5.01659 12.3166 5.40712 12.7071L10.95 18.25C11.3642 18.6642 12.0358 18.6642 12.45 18.25C12.8642 17.8358 12.8642 17.1642 12.45 16.75L8.2657 12.5657C7.95328 12.2533 7.95328 11.7467 8.2657 11.4343Z" fill="currentColor" />
								</svg>
							</span>
							<!--end::Svg Icon-->
						</div>
						<!--end::Aside toggler-->
					</div>
					<!--end::Brand-->
					<!--begin::Aside menu-->
					<div class="aside-menu flex-column-fluid">
						<!--begin::Aside Menu-->
						<div class="hover-scroll-overlay-y my-5 my-lg-5" id="kt_aside_menu_wrapper" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_aside_logo, #kt_aside_footer" data-kt-scroll-wrappers="#kt_aside_menu" data-kt-scroll-offset="0">
							<!--begin::Menu-->
							<div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="#kt_aside_menu" data-kt-menu="true" data-kt-menu-expand="false">
								<div data-kt-menu-trigger="click" class="menu-item here show menu-accordion">
									<a href="/admin/dashboard"><span class="menu-link">
										<span class="menu-icon">
											<span class="svg-icon svg-icon-2">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<rect x="2" y="2" width="9" height="9" rx="2" fill="currentColor" />
													<rect opacity="0.3" x="13" y="2" width="9" height="9" rx="2" fill="currentColor" />
													<rect opacity="0.3" x="13" y="13" width="9" height="9" rx="2" fill="currentColor" />
													<rect opacity="0.3" x="2" y="13" width="9" height="9" rx="2" fill="currentColor" />
												</svg>
											</span>
										</span>
										<span class="menu-title">Dashboard</span>
									</span></a>
								</div>
								<!-- <div class="menu-item">
									<div class="menu-content pt-8 pb-2">
										<span class="menu-section text-muted text-uppercase fs-8 ls-1">Event & Blog Management</span>
									</div>
								</div> -->
								<div class="menu-item">
									<div class="menu-content pt-8 pb-2">
										<span class="menu-section text-muted text-uppercase fs-8 ls-1">Home</span>
									</div>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/popup">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Pop Up</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/home">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Background</span>
									</a>
								</div>

								<div class="menu-item">
									<div class="menu-content pt-8 pb-2">
										<span class="menu-section text-muted text-uppercase fs-8 ls-1">Blog Management</span>
									</div>
								</div>
								<!-- <div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/event">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Event</span>
									</a>
								</div> -->
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/blog">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Blog</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/blogcat">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Blog Category</span>
									</a>
								</div>
								<div class="menu-item">
									<div class="menu-content pt-8 pb-2">
										<span class="menu-section text-muted text-uppercase fs-8 ls-1">Job Vacancy</span>
									</div>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/job">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Career</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/jobcat">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Career Category</span>
									</a>
								</div>
								<div class="menu-item">
									<div class="menu-content pt-8 pb-2">
										<span class="menu-section text-muted text-uppercase fs-8 ls-1">University</span>
									</div>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/university">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">University</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/universitytype">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Type</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/course">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Course</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/category">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Category</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/scholarship">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Scholarship</span>
									</a>
								</div>
								<div class="menu-item">
									<div class="menu-content pt-8 pb-2">
										<span class="menu-section text-muted text-uppercase fs-8 ls-1">K-12 Study Management</span>
									</div>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/k-12">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">K-12 Study</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/k12scholarship">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Scholarship</span>
									</a>
								</div>
								<div class="menu-item">
									<div class="menu-content pt-8 pb-2">
										<span class="menu-section text-muted text-uppercase fs-8 ls-1">Subjects</span>
									</div>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/subjects">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Levels</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/subjectscategories">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Subjects Category</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/subjectsgrades">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Grades</span>
									</a>
								</div>
								<div class="menu-item">
									<div class="menu-content pt-8 pb-2">
										<span class="menu-section text-muted text-uppercase fs-8 ls-1">User Management</span>
									</div>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/users">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Users</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/characters">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Characters</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/personality">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Personality</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/schools">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Schools</span>
									</a>
								</div>
								<div class="menu-item">
									<div class="menu-content pt-8 pb-2">
										<span class="menu-section text-muted text-uppercase fs-8 ls-1">Contact Us</span>
									</div>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/courseapplication">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Course Application</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/counsellor">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Counsellor</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/visitcampus">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Campus Visit</span>
									</a>
								</div>
								<div class="menu-item menu-accordion">
									<a class="menu-link" href="/admin/enquiries">
										<span class="menu-bullet">
										</span>
										<span class="menu-title">Enquiries</span>
									</a>
								</div>
							</div>
							<!--end::Menu-->
						</div>
						<!--end::Aside Menu-->
					</div>
					<!--end::Aside menu-->
				</div>
				<!--end::Aside-->
				<!--begin::Wrapper-->
				<div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
					<!--begin::Header-->
					<div id="kt_header" style="" class="header align-items-stretch">
						<!--begin::Container-->
						<div class="container-fluid d-flex align-items-stretch justify-content-between">
							<!--begin::Aside mobile toggle-->
							<div class="d-flex align-items-center d-lg-none ms-n2 me-2" title="Show aside menu">
								<div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_aside_mobile_toggle">
									<!--begin::Svg Icon | path: icons/duotune/abstract/abs015.svg-->
									<span class="svg-icon svg-icon-1">
										<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
											<path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor" />
											<path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor" />
										</svg>
									</span>
									<!--end::Svg Icon-->
								</div>
							</div>
							<!--end::Aside mobile toggle-->
							<!--begin::Mobile logo-->
							<div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
								<a href="../../demo1/dist/index.html" class="d-lg-none">
									<img alt="Logo" src="/images/logo/edu4ulogo.jpg" class="h-30px" />
								</a>
							</div>
							<!--end::Mobile logo-->
							<!--begin::Wrapper-->
							<div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1">
								<!--begin::Navbar-->
								<div class="d-flex align-items-stretch" id="kt_header_nav">
									<!--begin::Menu wrapper-->
									<!--end::Menu wrapper-->
								</div>
								<!--end::Navbar-->
								<!--begin::Toolbar wrapper-->
								<div class="d-flex align-items-stretch flex-shrink-0">
									<!--begin::User menu-->
									<div class="d-flex align-items-center ms-1 ms-lg-3" id="kt_header_user_menu_toggle">
										<!--begin::Menu wrapper-->
										<div class="cursor-pointer symbol symbol-30px symbol-md-40px" data-kt-menu-trigger="click" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
											<i class="fas fa-user fa-lg"></i>
										</div>
										<!--begin::User account menu-->
										<div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-primary fw-bold py-4 fs-6 w-275px" data-kt-menu="true">
											<!--begin::Menu separator-->
											<div class="separator my-2"></div>
											<!--end::Menu separator-->
											<!--begin::Menu item-->
											<div class="menu-item px-5">
												<a href="/admin/adminLogout" class="menu-link px-5">Sign Out</a>
											</div>
											<!--end::Menu item-->
											<!--begin::Menu separator-->
											<div class="separator my-2"></div>
											<!--end::Menu separator-->
										</div>
										<!--end::User account menu-->
										<!--end::Menu wrapper-->
									</div>
									<!--end::User menu-->
									<!--begin::Header menu toggle-->
									<div class="d-flex align-items-center d-lg-none ms-2 me-n3" title="Show header menu">
										<div class="btn btn-icon btn-active-light-primary w-30px h-30px w-md-40px h-md-40px" id="kt_header_menu_mobile_toggle">
											<!--begin::Svg Icon | path: icons/duotune/text/txt001.svg-->
											<span class="svg-icon svg-icon-1">
												<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
													<path d="M13 11H3C2.4 11 2 10.6 2 10V9C2 8.4 2.4 8 3 8H13C13.6 8 14 8.4 14 9V10C14 10.6 13.6 11 13 11ZM22 5V4C22 3.4 21.6 3 21 3H3C2.4 3 2 3.4 2 4V5C2 5.6 2.4 6 3 6H21C21.6 6 22 5.6 22 5Z" fill="currentColor" />
													<path opacity="0.3" d="M21 16H3C2.4 16 2 15.6 2 15V14C2 13.4 2.4 13 3 13H21C21.6 13 22 13.4 22 14V15C22 15.6 21.6 16 21 16ZM14 20V19C14 18.4 13.6 18 13 18H3C2.4 18 2 18.4 2 19V20C2 20.6 2.4 21 3 21H13C13.6 21 14 20.6 14 20Z" fill="currentColor" />
												</svg>
											</span>
											<!--end::Svg Icon-->
										</div>
									</div>
									<!--end::Header menu toggle-->
								</div>
								<!--end::Toolbar wrapper-->
							</div>
							<!--end::Wrapper-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Header-->

					<main>
						@yield('style')
						@yield('content')
						@yield('modal')
						@yield('script')
					</main>
				</div>
				<!--end::Wrapper-->
	</div>
	<script>
		function highlightActiveMenuItem() {
		// Get the current URL
		var currentUrl = window.location.pathname;

		// Get all menu links
		var menuLinks = document.querySelectorAll('.menu-link');

		// Loop through each menu link
		menuLinks.forEach(function(link) {
			// Get the URL of the menu link
			var menuUrl = link.getAttribute('href');

			// Check if the current URL contains the menu URL
			if (currentUrl === menuUrl) {
				// Add a class to highlight the active menu item
				link.classList.add('active');
			}
		});
	}

	document.addEventListener('DOMContentLoaded', function() {
		highlightActiveMenuItem();
	});
	</script>
