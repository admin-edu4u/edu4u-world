@extends('adminLayout')
@section('title', 'Course Applicaiton')
@section('style')
<style>
    .applicationtable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }
</style>
@endsection

@section('content')
    @php $counter = 0; @endphp
<div class="applicationtable">
    <div class="card card-p-0 card-flush mt-5">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title ms-5">
                <h1>Course Application</h1>
            </div>
            <form action="{{ route('export.courseapplication') }}" method="GET">
                <button type="submit" class="btn btn-primary"><i class="fas fa-file-export"></i> Export</button>
            </form>
        </div>
        <div class="card-body">
            <table class="table align-middle border rounded table-row-dashed fs-6 g-5" id="kt_datatable_example">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">
                        <th>No.</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Contact No.</th>
                        <th>School</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">
                @if(isset($application) > 0)
                    @foreach($application as $ca)
                    @php $counter++; @endphp
                    <tr class="odd text-start align-middle">
                        <td style="width:30px;">{{ $counter }}.</td>
                        <td>{{ $ca->ca_name }}</td>
                        <td>
                            @foreach($course as $crs)
                                @if($crs->c_id == $ca->ca_c_id)
                                    {{ $crs->c_name }}
                                @endif
                            @endforeach
                        </td>
                        <td>{{ $ca->ca_phone }}</td>
                        <td>{{ $ca->ca_qualification_school_name }}</td>
                        <td>
                            @foreach($course as $crs)
                                @if($crs->c_id == $ca->ca_c_id)
                                <button class="form-control float-end" style="width:100px;" onclick="redirectToViewCourseApplication('{{ $ca->ca_id }}')">View</button>
                                @endif
                            @endforeach
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

@section('script')
<script>
    function redirectToViewCourseApplication(id) {
        window.location.href = '/admin/viewcourseapplication?id=' + id;
    }
</script>
@endsection