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

    .consultcounsellor-card {
        background-color: #F6F6F6;
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
        width: 700px;
        margin: 20px auto;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .consultcounsellor-details {
        margin-top: 20px;
    }

    .consultcounsellor-details p {
        margin-bottom: 10px;
    }

    .consultcounsellor-details strong {
        font-weight: bold;
    }
</style>
@endsection

@section('content')
    @php
        $id = request('id');
        $ConsultCounsellorModel = new \App\Models\ConsultCounsellor();
        $ccData = $ConsultCounsellorModel->find($id);
    @endphp
<a href="javascript:history.back()" class="back-btn">Back</a>

<div class="consultcounsellor-card">
    <div class="consultcounsellor-details">
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Salutation</div>
            <div class="col-6">{{ $ccData->cc_salutation ? : '-' }}</div>
        </div>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Full name</div>
            <div class="col-6">{{ $ccData->cc_name ? : '-' }}</div>
        </div>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Gender</div>
            <div class="col-6">{{ $gender[$ccData->cc_gender] ?? '-' }}</div>
        </div>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Date of Birth</div>
            <div class="col-6">{{ $ccData->cc_dob ? : '-' }}</div>
        </div>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Secondary School</div>
            <div class="col-6">{{ $ccData->cc_school_name ? : '-' }}</div>
        </div>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Mobile Number</div>
            <div class="col-6">{{ $ccData->cc_contact_no ? : '-' }}</div>
        </div>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Home Phone Number</div>
            <div class="col-6">{{ $ccData->cc_home_contact_no ? : '-' }}</div>
        </div>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">What is your preferred course?</div>
            <div class="col-6">{{ $ccData->cc_course ? : '-' }}</div>
        </div>
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">What is your highest qualification?</div>
            <div class="col-6">{{ $qlevel[$ccData->cc_qualification] ?? '-' }}</div>
        </div>
        @php
            $transcripts = explode(',', $ccData->cc_transcript);
            $firstTranscript = true;
        @endphp
        @foreach($transcripts as $transcript)
            <div class="row col-12">
            @if($firstTranscript)
                <div class="col-6" style="color:#999999;">Transcript</div>
                @php $firstTranscript = false; @endphp
            @else
                <div class="col-6"></div>
            @endif
                <div class="col-6">
                    @if(pathinfo($transcript, PATHINFO_EXTENSION) == 'jpg')
                        <a href="{{ asset('images/application/' . $transcript) }}" data-lightbox="transcript" data-title="Transcript">
                        <img src="{{ asset('images/application/' . $transcript) }}" alt="Transcript" style="max-width: 100px;">
                        </a><br>
                        <a href="{{ asset('images/application/' . $transcript) }}" download><i class="fas fa-download"></i> Transcript</a>
                    @elseif(pathinfo($transcript, PATHINFO_EXTENSION) == 'pdf')
                        <a href="{{ asset('images/application/' . $transcript) }}" download><i class="fas fa-file-pdf"></i> Transcript</a>
                    @endif
                </div>
            </div>
        @endforeach
        <div class="row col-12">
            <div class="col-6" style="color:#999999;">Remarks</div>
            <div class="col-6">{{ $ccData->cc_remarks }}</div>
        </div>
    </div>
</div>

@endsection