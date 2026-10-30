@extends('adminLayout')
@section('title', 'Edu4u')
@section('style')
	<style>
		.vertical-line {
			border-right: 1px solid #e0e0e0;
			padding-right: 15px;
		}
	</style>
@endsection

@section('content')
<div class="container mt-10">
    <h1>Welcome back!</h1>
    <div class="row mt-4">
        <!-- First Card Row -->
        <div class="col-md-3">
			<a href="/admin/users" class="card-link">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title mb-4">Users</h4>
						<p class="card-text">
							<i class="fas fa-users fa-2x align-middle ms-2"></i>
							<span class="float-end" style="font-size: 1.5rem;">
								{{ \App\Models\User::where('isAdmin', '!=', 1)->count() }}
							</span> 
						</p>
					</div>
				</div>
			</a>
		</div>
        <div class="col-md-3">
			<a href="/admin/courseapplication" class="card-link">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title mb-4">Course Applications</h4>
						<p class="card-text">
							<i class="fas fa-file-alt fa-2x align-middle ms-2"></i>
							<span class="float-end" style="font-size: 1.5rem;">
								{{ \App\Models\CourseApplication::where('ca_status', '=', 1)->count() }}
							</span> 
						</p>
					</div>
				</div>
			</a>
        </div>
        <div class="col-md-3">
			<a href="/admin/visitcampus" class="card-link">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title mb-4">Visit Campus</h4>
						<p class="card-text">
							<i class="fas fa-map-marker-alt fa-2x align-middle ms-2"></i>
							<span class="float-end" style="font-size: 1.5rem;">
								{{ \App\Models\VisitCampus::all()->count() }}
							</span> 
						</p>
					</div>
				</div>
			</a>
        </div>
        <div class="col-md-3">
			<a href="/admin/counsellor" class="card-link">
				<div class="card">
					<div class="card-body">
						<h4 class="card-title mb-4">Counsellor</h4>
						<p class="card-text">
							<i class="fas fa-user-tie fa-2x align-middle ms-2"></i>
							<span class="float-end" style="font-size: 1.5rem;">
								{{ \App\Models\ConsultCounsellor::where('cc_status', '=', 1)->count() }}
							</span> 
						</p>
					</div>
				</div>
			</a>
        </div>
    </div>
    <!-- User Card Row -->
    <div class="row mt-4">
        <div class="col-md-12">
			<a href="/admin/users" class="card-link">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title mb-15">Users</h5>
						<div class="row justify-content-around">
							<div class="col-md-3 text-center vertical-line">
								<h4>Today</h4>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<h4>This Week</h4>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<h4>This Month</h4>
							</div>
							<div class="col-md-3 text-center">
								<h4>Total</h4>
							</div>
						</div>
						<div class="row justify-content-around">
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
									{{ \App\Models\User::whereDate('updated_at', now()->toDateString())->where('isAdmin', '!=', 1)->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\User::where('updated_at', '>=', now()->startOfWeek())->where('isAdmin', '!=', 1)->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\User::where('updated_at', '>=', now()->startOfMonth())->where('isAdmin', '!=', 1)->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\User::where('isAdmin', '!=', 1)->count() }}
									</span> 
								</p>
							</div>
						</div>
					</div>
				</div>
			</a>
        </div>
    </div>
	<!-- Course Application Card Row -->
    <div class="row mt-4">
        <div class="col-md-12">
			<a href="/admin/courseapplication" class="card-link">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title mb-15">Course Applications</h5>
						<div class="row justify-content-around">
							<div class="col-md-3 text-center vertical-line">
								<h4>Today</h4>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<h4>This Week</h4>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<h4>This Month</h4>
							</div>
							<div class="col-md-3 text-center">
								<h4>Total</h4>
							</div>
						</div>
						<div class="row justify-content-around">
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\CourseApplication::whereDate('updated_at', now()->toDateString())->where('ca_status', '=', 1)->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\CourseApplication::where('updated_at', '>=', now()->startOfWeek())->where('ca_status', '=', 1)->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\CourseApplication::where('updated_at', '>=', now()->startOfMonth())->where('ca_status', '=', 1)->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\CourseApplication::where('ca_status', '=', 1)->count() }}
									</span> 
								</p>
							</div>
						</div>
					</div>
				</div>
			</a>
        </div>
    </div>
	<!-- Visit Campus Card Row -->
    <div class="row mt-4">
        <div class="col-md-12">
			<a href="/admin/visitcampus" class="card-link">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title mb-15">Visit Campus</h5>
						<div class="row justify-content-around">
							<div class="col-md-3 text-center vertical-line">
								<h4>Today</h4>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<h4>This Week</h4>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<h4>This Month</h4>
							</div>
							<div class="col-md-3 text-center">
								<h4>Total</h4>
							</div>
						</div>
						<div class="row justify-content-around">
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\VisitCampus::whereDate('updated_at', now()->toDateString())->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\VisitCampus::where('updated_at', '>=', now()->startOfWeek())->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\VisitCampus::where('updated_at', '>=', now()->startOfMonth())->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\VisitCampus::all()->count() }}
									</span> 
								</p>
							</div>
						</div>
					</div>
				</div>
			</a>
        </div>
    </div>
	<!-- Counsellor Card Row -->
    <div class="row mt-4">
        <div class="col-md-12">
			<a href="/admin/counsellor" class="card-link">
				<div class="card">
					<div class="card-body">
						<h5 class="card-title mb-15">Counsellor</h5>
						<div class="row justify-content-around">
							<div class="col-md-3 text-center vertical-line">
								<h4>Today</h4>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<h4>This Week</h4>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<h4>This Month</h4>
							</div>
							<div class="col-md-3 text-center">
								<h4>Total</h4>
							</div>
						</div>
						<div class="row justify-content-around">
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\ConsultCounsellor::whereDate('updated_at', now()->toDateString())->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\ConsultCounsellor::where('updated_at', '>=', now()->startOfWeek())->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center vertical-line">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\ConsultCounsellor::where('updated_at', '>=', now()->startOfMonth())->count() }}
									</span> 
								</p>
							</div>
							<div class="col-md-3 text-center">
								<p class="card-text mb-3">
									<span style="font-size: 1.3rem;">
										{{ \App\Models\ConsultCounsellor::where('cc_status', '=', 1)->count() }}
									</span> 
								</p>
							</div>
						</div>
					</div>
				</div>
			</a>
        </div>
    </div>
	<div class="pt-10"></div>
</div>
@endsection

@section('script')

@endsection