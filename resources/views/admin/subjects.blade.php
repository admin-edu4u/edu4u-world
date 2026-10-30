@extends('adminLayout')
@section('title', 'Subjects')
@section('style')
<style>
    .subjectstable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .view-icon,
    .delete-icon {
        font-size: 15px; /* Adjust the icon size as needed */
        cursor: pointer;
        margin: 0 5px; /* Add margin for spacing */
    }

    .view-icon {
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
<div class="subjectstable">
    <div class="card card-p-0 card-flush">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title ms-5">
                <h1><strong>Level</strong></h1>
            </div>
        </div>
        <div class="card-body">
            <table class="table align-middle border rounded table-row-dashed fs-6 g-5" id="kt_datatable_example_1">
            <thead>
                <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">
                    <th class="min-w-100px">Level</th>
                    <th class="min-w-100px text-center">Total Subjects</th>
                    <th class="min-w-130px text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="fw-bold text-gray-600">
            @if(isset($level) && count($level) > 0)
                @foreach($level as $value => $l)
                    @if($loop->iteration > count($level))
                        @continue
                    @endif
                    <tr>
                        <td>{{ $l }}</td>
                        <td class="text-center">
                            @php
                                $count = $subject->where('s_level_id', $value)->count();
                            @endphp
                            {{ $count }}
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <i class="bi bi-eye view-icon" onclick="redirectToViewPage('{{ $value }}')"></i>
                            </div>
                        </td>
                    </tr>
                @endforeach
            @endif
            </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade text-left" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel140" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" role="document">
		<div class="modal-content">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="myModalLabel140">Delete Subject</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="/admin/deleteQualificationLevel" method="post">
				<input type="hidden" name="q_id" id="deactivate_id" value="">
				<div class="modal-body">
					<span>Are you sure to delete this level?</span>
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
    function redirectToViewPage(id) {
        window.location.href = '/admin/viewsubjects?id=' + id;
    }

    function toggleReject(id) {
        console.log(id);
	    if(id != undefined && id != null) {
		    $('#deactivate_id').val(id);
		    $('#rejectModal').modal('toggle');
	    }
    }
</script>
@endsection