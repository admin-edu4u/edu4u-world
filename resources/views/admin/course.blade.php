@extends('adminLayout')
@section('style')
<style>
    .viewcoursetable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    thead th{
        vertical-align: middle;
        text-align: center;
    }

    tbody td{
        vertical-align: middle;
        text-align: center;
    }

    .month-box {
        display: inline-block;
        padding: 5px 10px;
        margin: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f0f0f0;
    }

    .eye-icon,
    .edit-icon,
    .delete-icon {
        font-size: 15px; /* Adjust the icon size as needed */
        cursor: pointer;
        margin: 0 5px; /* Add margin for spacing */
    }

    .eye-icon {
        color: white;
        background-color: grey;
        border: 1px solid grey;
        border-radius: 10%;
        padding: 5px;
    }

    .edit-icon {
        color: white;
        background-color: lightskyblue;
        border: 1px solid lightskyblue;
        border-radius: 10%;
        padding: 5px;
    }

    .delete-icon {
        color: white;
        background-color: red;
        border: 1px solid red;
        border-radius: 10%;
        padding: 5px;
    }
</style>
@endsection
@section('content')
    @include('formMsg')
    @php $counter = 0; @endphp
<div class="viewcoursetable">
        <div class="card">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title ms-5">
                    <h1><strong>Course</strong></h1>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button type="button" class="btn btn-light-primary" onclick="redirectToNewPage()">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                            Add Course
                    </button>
                    <button type="button" class="btn btn-light-primary" onclick="redirectToDescPage()">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                            Add Desc
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th class=" border-bottom-0"><strong>University</strong></th>
                                <th class=" border-bottom-0"><strong>Course</strong></th>
                                <th class=" border-bottom-0"><strong>Level</strong></th>
                                <th class=" border-bottom-0"><strong>Intake Months</strong></th>
                                <th class=" border-bottom-0"><strong>Status</strong></th>
                                <th class=" border-bottom-0"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($course) && count($course) > 0)
                                @foreach($course as $c)
                                    @php
                                        $intake = explode(',', $c->c_intake);
                                        $counter++;
                                    @endphp
                                <tr>
                                    <td>{{ $counter }}.</td>
                                    <td>{{ App\Models\University::find($c->c_uni)->u_name ?? 'N/A' }}</td>
                                    <td>{{$c->c_name}}</td>
                                    <td>{{ App\Models\Course::levelArr()[$c->c_level] ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                            $monthArr = \App\Models\Course::monthArr();
                                            $monthValues = explode(',', $c->c_intake ?? '');

                                            foreach ($monthValues as $monthValue) {
                                                $trimmedMonthValue = trim($monthValue);
                                                if (isset($monthArr[$trimmedMonthValue])) {
                                                    echo '<div class="month-box">' . $monthArr[$trimmedMonthValue] . '</div>';
                                                }
                                            }
                                        @endphp
                                    </td>
                                    <td>@if($c->c_status == 1)Active @elseif($c->c_status == 98) Inactive @else Unknown Status @endif</td>
                                    <td>
                                        <!-- <i class="bi bi-eye view-icon" onclick=""></i> -->
                                        <i class="bi bi-eye eye-icon" onclick="toggleHide({{$c->c_id}})"></i>
                                        <i class="bi bi-pencil edit-icon" onclick="redirectToEditPage('{{ $c->c_id }}', '{{ $c->c_uni }}', '{{ $c->c_name }}', '{{ $c->c_level }}', '{{ $c->c_intake }}', '{{ $c->c_duration }}', '{{ $c->c_award }}', '{{ $c->c_has_internship }}', '{{ $c->c_internship_duration }}', '{{ $c->c_outline }}', '{{ $c->c_is_malaysian }}', '{{ $c->c_represent_sport }}', '{{ $c->c_represent_level }}', '{{ $c->c_talent }}', '{{ $c->c_talent_level}}', '{{ $c->c_leadership }}', '{{ $c->c_leadership_level }}', '{{ $c->c_amount }}', '{{ $c->c_is_bumiputra }}', '{{ $c->c_main_cat }}', '{{ $c->c_sub_cat }}')"></i>
                                        <i class="bi bi-trash delete-icon" onclick="toggleReject({{$c->c_id}})"></i>
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
</div>
@endsection

@section('modal')
<div class="modal fade text-left" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Course</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteCourse" method="post">
				<input type="hidden" name="c_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this course?</span>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger" >Delete</button>
					<button type="button" class="btn" data-bs-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade text-left" id="hideModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="myModalLabel140">Hide/Unhide Course</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/hideCourse" method="post">
				<input type="hidden" name="c_id" id="deactivate_ids" value="">
				<div class="modal-body">
					<span>Are you sure to hide/unhide this course?</span>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger" >Yes</button>
					<button type="button" class="btn" data-bs-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
    function redirectToNewPage() {
        var newPageUrl = '/admin/addcourseform';
        window.location.href = newPageUrl;
    }

    function redirectToDescPage() {
        var newPageUrl = '/admin/adddescform';
        window.location.href = newPageUrl;
    }

    function redirectToEditPage(c_id, c_name, c_uni, c_level, c_intake, c_duration, c_award, c_has_internship, c_internship_duration, c_outline, c_is_malaysian, c_represent_sport, c_represent_level, c_talent, c_talent_level, c_leadership, c_leadership_level, c_amount, c_is_bumiputra, c_main_cat, c_sub_cat) {
        window.location.href = '/admin/editcourseform?c_id=' + c_id;
    }

    function toggleReject(id) {
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }

    function toggleHide(id) {
	    if(id != undefined && id != null) {
		    $('#deactivate_ids').val(id);
		    $('#hideModal').modal('toggle');
	    }
    }
</script>
@endsection

