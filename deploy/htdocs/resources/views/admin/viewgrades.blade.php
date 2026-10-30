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

    .edit-icon,
    .delete-icon {
        font-size: 15px; /* Adjust the icon size as needed */
        cursor: pointer;
        margin: 0 5px; /* Add margin for spacing */
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
        @php
            $id = request('id');
            $Level = \App\Models\Course::qlevelArr();
            $selectedLevel = isset($Level[$id]) ? $Level[$id] : null;
            $title = $selectedLevel;
            $counter = 0;
         @endphp
<form action="/admin/editGrade" method="post">
<div class="viewsubjectgradetable">
    <div class="card">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title ms-5">
                <h1><strong>{{ $title }}</strong></h1>
            </div>
            <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button type="submit" class="btn btn-light-primary">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                            Update
                    </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                    <thead>
                        <tr>
                            <th class="border-bottom-0 text-start" style="width:30px;"><strong>No.</strong></th>
                            <th class="border-bottom-0 text-start" style="max-width:50px;"><strong>Grades</strong></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($grades as $grade)
                        @if($grade->sg_s_id == $id)
                        @php
                            $counter++;
                        @endphp
                            <tr>
                                <td>{{ $counter }}.</td>
                                <input type="hidden" name="sg_id" value="{{$grade->sg_id}}">
                                <td class="text-start"><input class="form-control" type="text" name="grade{{ $grade->sg_id }}" value="{{ $grade->sg_grade }}"></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</form>
@endsection
