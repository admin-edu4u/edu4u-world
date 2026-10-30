@extends('adminLayout')
@section('title', 'Counsellor')
@section('style')
<style>
    .counsellortable {
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
<div class="counsellortable">
    <div class="card card-p-0 card-flush mt-5">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title ms-5">
                <h1>Counsult Counsellor</h1>
            </div>
            <form action="{{ route('export.campusvisit') }}" method="GET">
                <button type="submit" class="btn btn-primary"><i class="fas fa-file-export"></i> Export</button>
            </form>
        </div>
        <div class="card-body">
            <table class="table align-middle border rounded table-row-dashed fs-6 g-5" id="kt_datatable_example">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">
                        <th>No.</th>
                        <th>Name</th>
                        <th>Contact No.</th>
                        <th>Pax</th>
                        <th>Date Preferred</th>
                        <th>Time Preferred</th>
                        <th>Apply Date</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">
                @if(count($visitcampus) > 0)
                    @foreach($visitcampus as $vc)
                    @php $counter++; @endphp
                    <tr class="odd text-start align-middle">
                        <td style="width:30px;">{{ $counter }}.</td>
                        <td>{{ $vc->vc_name }}</td>
                        <td>{{ $vc->vc_contact_no }}</td>
                        <td>{{ $vc->vc_pax }}</td>
                        <td>{{ $vc->vc_date . '-' . $vc->vc_month . '-' . $vc->vc_year }}</td>
                        <td>{{ $time[$vc->vc_time] }}</td>
                        <td>{{ \Carbon\Carbon::parse($vc->created_at)->format('d-m-Y') }}</td>
                        <td>
                            <button class="form-control float-end" style="width:100px;" onclick="redirectToViewVisitCampus('{{ $vc->vc_id }}')">View</button>
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
    function redirectToViewVisitCampus(id) {
        window.location.href = '/admin/viewvisitcampus?id=' + id;
    }
</script>
@endsection