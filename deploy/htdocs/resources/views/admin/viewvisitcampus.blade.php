@extends('adminLayout')
@section('style')
<style>
    .back-btn {
        display: inline-block;
        padding: 10px;
        color: black;
        text-decoration: none;
    }

    .back-btn:hover {
        text-decoration: underline;
    }

    .back-btn::before {
        content: '\00AB';
        margin-right: 5px;
    }

    .visitcampus-card {
        background-color: #F6F6F6;
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
        width: 700px;
        margin: 20px auto;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .visitcampus-details {
        margin-top: 20px;
    }

    .visitcampus-details p {
        margin-bottom: 10px;
    }

    .visitcampus-details strong {
        font-weight: bold;
    }
</style>
@endsection

@section('content')
    @php
        $id = request('id');
        $VisitCampusModel = new \App\Models\VisitCampus();
        $vcData = $VisitCampusModel->find($id);
    @endphp
<a href="javascript:history.back()" class="back-btn">Back</a>

<div class="visitcampus-card">
    <div class="visitcampus-details">
        <div class="row col-12">
            @php
                $uniData = json_decode($vcData->vc_uni_id, true);
            @endphp
            <div class="col-6" style="color:#999999;">Preferred University</div>
            <div class="col-6">
                @foreach ($uniData as $data)
                    @php
                        $universityName = $university->where('u_id', $data['uni_id'])->first()->u_name;
                        $state = App\Models\UniversityBranch::getBranchById($data['branch']);
                    @endphp
                    <p>{{ $universityName }} - {{ $state ? $state->state_name : 'Main Branch' }}</p>
                @endforeach
            </div>
        </div>
        <hr>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Salutation</div>
            <div class="col-6">{{ $vcData->vc_salutation }}</div>
        </div>
        <hr>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Full name</div>
            <div class="col-6">{{ $vcData->vc_name ? : '-' }}</div>
        </div>
        <hr>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">IC number</div>
            <div class="col-6">{{ $vcData->vc_ic_num ? : '-' }}</div>
        </div>
        <hr>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Contact number</div>
            <div class="col-6">{{ $vcData->vc_contact_no ? : '-' }}</div>
        </div>
        <hr>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Date preferred</div>
            <div class="col-6">{{ $vcData->vc_date . '-' . $vcData->vc_month . '-' . $vcData->vc_year }}</div>
        </div>
        <hr>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Time preferred</div>
            <div class="col-6">{{ $time[$vcData->vc_time] }}</div>
        </div>
        <hr>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Pax</div>
            <div class="col-6">{{ $vcData->vc_pax }}</div>
        </div>
        <hr>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Remarks</div>
            <div class="col-6">{{ $vcData->vc_remarks ? : '-' }}</div>
        </div>
    </div>
</div>

@endsection


