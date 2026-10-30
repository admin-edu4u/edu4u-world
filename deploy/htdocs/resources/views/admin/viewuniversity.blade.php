@extends('adminLayout')
@section('title', 'Intake')
@section('style')
<style>
    .universitytable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .month-box {
        display: inline-block;
        padding: 5px 10px;
        margin: 5px;
        border: 1px solid #ccc;
        border-radius: 5px;
        background-color: #f0f0f0;
    }

    .delete-icon {
        font-size: 15px; /* Adjust the icon size as needed */
        cursor: pointer;
        margin: 0 5px; /* Add margin for spacing */
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
        @php
            $u_id = request('u_id');
            $universityModel = new \App\Models\University();
            $universityName = $universityModel->find($u_id);
        @endphp
<div class="universitytable">
    <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ $universityName->u_name }}</h3>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addIntakeModal">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                        Add Intake
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                        <thead>
                            <tr>
                                <th class="border-bottom-0"><strong>Level</strong></th>
                                <th class="border-bottom-0"><strong>Intake Months</strong></th>
                                <th class="border-bottom-0 text-end"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($university as $u)
                                @if($u->ui_uni_id == $u_id) <!-- Compare ui_uni_id with the u_id from GET data -->
                                    <tr>
                                        <td>
                                            @php
                                                $levelArr = \App\Models\UniversityIntake::levelArr();
                                                $levelValue = $u->ui_level_id ?? null;
                                            @endphp

                                            @if(isset($levelArr[$levelValue]))
                                                {{ $levelArr[$levelValue] }}
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $monthArr = \App\Models\UniversityIntake::monthArr();
                                                $monthValues = explode(',', $u->ui_month ?? '');

                                                foreach ($monthValues as $monthValue) {
                                                    $trimmedMonthValue = trim($monthValue);
                                                    if (isset($monthArr[$trimmedMonthValue])) {
                                                        echo '<div class="month-box">' . $monthArr[$trimmedMonthValue] . '</div>';
                                                    }
                                                }
                                            @endphp
                                        </td>
                                        <td class="text-end">
                                            <i class="bi bi-trash delete-icon" onclick="toggleReject({{$u->ui_id}})"></i>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade" id="addIntakeModal" tabindex="-1" aria-labelledby="addIntakeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="addIntakeForm" method="post" action="/admin/addIntake">
                <input type="hidden" name="u_id" value="{{$u_id}}" readonly>
                <div class="modal-header">
                    <h5 class="modal-title" id="addIntakeModalLabel">Add New Intake</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <label for="level">Level:</label>
                    <select class="form-select" name="level" required>
                        <option selected disabled value="">Please select a level</option>
                        @foreach(\App\Models\UniversityIntake::levelArr() as $index => $level)
                        <option value="{{ $index }}">{{ $level }}</option>
                        @endforeach
                    </select>
                <div class="mt-3"></div>
                <label for="personality">Intake Months:</label>
                    <select class="form-select intake" name="intake[]" multiple="multiple" required>
                        @foreach(\App\Models\UniversityIntake::monthArr() as $index => $month)
                        <option value="{{ $index }}">{{ $month }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade text-left" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Subject</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteIntake" method="post">
				<input type="hidden" name="ui_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this intake?</span>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger" >Delete</button>
					<button type="button" class="btn" data-bs-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
	</div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.intake').select2();
    });

    function toggleReject(id) {
        console.log(id);
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }
</script>
@endsection