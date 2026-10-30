@extends('adminLayout')
@section('title', 'Grades')
@section('style')
<style>
    .viewsubjectgradetable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .delete-icon {
        font-size: 15px; /* Adjust the icon size as needed */
        cursor: pointer;
        margin: 0 5px; /* Add margin for spacing */
    }
</style>
@endsection

@section('content')
@php $counter = 0; @endphp
<div class="viewsubjectgradetable">
        <div class="card">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title ms-5">
                    <h1><strong>Grades</strong></h1>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th class=" border-bottom-0 text-start"><strong>Level</strong></th>
                                <th class=" border-bottom-0 text-end"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(isset($level) && count($level) > 0)
                            @php
                                $totalLevels = count($level);
                                $levelsToDisplay = max(0, $totalLevels - 3);
                                $levelsToDisplay = min($totalLevels, $levelsToDisplay);
                                $displayLevels = array_slice($level, 0, $levelsToDisplay, true);
                            @endphp
                            @foreach($displayLevels as $value => $l)
                                @php $counter++; @endphp
                                <tr>
                                    <td style="width:30px;">{{ $counter }}.</td>
                                    <td>{{ $l }}</td>
                                    <td class="text-end">
                                        <button class="form-control float-end" style="width:100px;" onclick="redirectToViewPage('{{ $value }}')">View</button>
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
    @include('formMsg')
<div class="modal fade" id="addGradeModal" tabindex="-1" aria-labelledby="addGradeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="addGradeForm" method="post" action="/admin/addGrade">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGradeModalLabel">Add New Grade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="level">Level:</label>
                    <select class="form-select" name="level" required>
                        <option selected readonly value="">Please select a level</option>
                            @foreach(\App\Models\Subject::levelArr() as $index => $level)
                        <option value="{{ $index }}">{{ $level }}</option>
                            @endforeach
                    </select>
                    <div class="mt-3"></div>
                    <label for="grade">Grade:</label>
                    <input id="levelgrade" name="grade" class="form-control" type="text" placeholder="Please enter grade..." required>
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
			<form action="/admin/deleteGrade" method="post">
				<input type="hidden" name="sg_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this grade?</span>
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
    function redirectToViewPage($index) {
        // Log s_level_id to the browser console
        console.log('id:', $index);

        /// Redirect to the viewsubjects page with s_level_id as a query parameter
        window.location.href = '/admin/viewgrades?id=' + $index;
    }
</script>
@endsection