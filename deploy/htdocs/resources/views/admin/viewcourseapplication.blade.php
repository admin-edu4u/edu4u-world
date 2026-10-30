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

    .courseapplication-card {
        background-color: #F6F6F6;
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
        width: 700px;
        margin: 20px auto;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .courseapplication-details {
        margin-top: 20px;
    }

    .courseapplication-details p {
        margin-bottom: 10px;
    }

    .courseapplication-details strong {
        font-weight: bold;
    }
</style>
@endsection

@section('content')
    @php
        $id = request('id');
        $CourseApplicationModel = new \App\Models\CourseApplication();
        $caData = $CourseApplicationModel->find($id);
    @endphp
<a href="javascript:history.back()" class="back-btn">Back</a>

<div class="courseapplication-card">
        <div class="courseapplication-details">
            <div class="personaldetails">
                <h6>1. Personal Details</h6>
                <div class="row col-12">
                    <div class="col-7" style="color:#999999;">Salutation</div>
                    <div class="col-5">{{ $caData->ca_salutation ? : '-' }}</div>
                </div>
                <div class="row col-12">
                    <div class="col-7" style="color:#999999;">Full Name</div>
                    <div class="col-5">{{ $caData->ca_name ? : '-' }}</div>
                </div><div class="row col-12">
                    <div class="col-7" style="color:#999999;">Date of Birth</div>
                    <div class="col-5">{{ $caData->ca_dob ? : '-' }}</div>
                </div><div class="row col-12">
                    <div class="col-7" style="color:#999999;">NRIC / Passport No.</div>
                    <div class="col-5">{{ $caData->ca_ic_num ? : '-' }}</div>
                </div><div class="row col-12">
                    <div class="col-7" style="color:#999999;">Gender</div>
                    <div class="col-5">{{ $gender[$caData->ca_gender] ?? '-' }}</div>
                </div><div class="row col-12">
                    <div class="col-7" style="color:#999999;">Nationality</div>
                    <div class="col-5">{{ $nationality[$caData->ca_nationality] ?? '-' }}</div>
                </div><div class="row col-12">
                    <div class="col-7" style="color:#999999;">Martial Status</div>
                    <div class="col-5">{{ $martial[$caData->ca_martial_status] ?? '-' }}</div>
                </div><div class="row col-12">
                    <div class="col-7" style="color:#999999;">Ethnicity</div>
                    <div class="col-5">{{ $ethnicity[$caData->ca_ethnicity] ?? '-' }}</div>
                </div><div class="row col-12">
                    <div class="col-7" style="color:#999999;">Religion</div>
                    <div class="col-5">{{ $religion[$caData->ca_religion] ?? '-' }}</div>
                </div><div class="row col-12">
                    <div class="col-7" style="color:#999999;">Home Phone Number</div>
                    <div class="col-5">{{ $caData->ca_home_phone ? : '-' }}</div>
                </div><div class="row col-12">
                    <div class="col-7" style="color:#999999;">Mobile Number</div>
                    <div class="col-5">{{ $caData->ca_phone ? : '-' }}</div>
                </div><div class="row col-12">
                    <div class="col-7" style="color:#999999;">Email Address</div>
                    <div class="col-5">{{ $caData->ca_email ? : '-' }}</div>
                </div>
                <div class="mb-3"></div>
            </div>
            <div class="emergencycontact">
                <h6>2. Emergency Contact</h6>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Emergency Contact Name</div>
                        <div class="col-5">{{ $caData->ca_e_name ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Emergency Contact No.</div>
                        <div class="col-5">{{ $caData->ca_e_contact ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Emergency Contact Email Address</div>
                        <div class="col-5">{{ $caData->ca_e_email ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Relationship</div>
                        <div class="col-5">{{ $relationship[$caData->ca_e_relationship] ?? '-' }}</div>
                    </div>
                <div class="mb-3"></div>
            </div>
            <div class="permanentaddress">
                <h6>3. Permanent Address</h6>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Address</div>
                        <div class="col-5">{{ $caData->ca_p_addr ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">City / Town</div>
                        <div class="col-5">{{ $caData->ca_p_city ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">State</div>
                        <div class="col-5">{{ $caData->ca_p_state ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Country</div>
                        <div class="col-5">{{ $caData->ca_p_country ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Post Code</div>
                        <div class="col-5">{{ $caData->ca_p_postcode ? : '-' }}</div>
                    </div>
                <div class="mb-3"></div>
            </div>
            <div class="mailingaddress">
                <h6>4. Mailing Address</h6>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Address</div>
                        <div class="col-5">{{ $caData->ca_m_addr ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">City / Town</div>
                        <div class="col-5">{{ $caData->ca_m_city ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">State</div>
                        <div class="col-5">{{ $caData->ca_m_state ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Country</div>
                        <div class="col-5">{{ $caData->ca_m_country ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Post Code</div>
                        <div class="col-5">{{ $caData->ca_m_postcode ? : '-' }}</div>
                    </div>
                <div class="mb-3"></div>
            </div>
            <div class="lastacademicqualification">
                <h6>5. Last Academic Qualification</h6>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Name of Qualification</div>
                        <div class="col-5">{{ $qlevel[$caData->ca_qualification] ?? '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Name and State / Country School</div>
                        <div class="col-5">{{ $caData->ca_qualification_school_name ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Year of Completion</div>
                        <div class="col-5">{{ $caData->ca_qualification_year ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Apply for Credit Transfer?</div>
                        <div class="col-5">{{ $credit[$caData->ca_credit_transfer] ?? '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">First in your family to enter a University?</div>
                        <div class="col-5">{{ $family[$caData->ca_first_family] ?? '-' }}</div>
                    </div>
                <div class="mb-3"></div>
            </div>
            <div class="englishqualification">
                <h6>6. English Qualification</h6>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">English Qualification</div>
                        <div class="col-5">{{ $english[$caData->ca_e_qualification] ?? '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Score</div>
                        <div class="col-5">{{ $caData->ca_e_score ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Year Taken</div>
                        <div class="col-5">{{ $caData->ca_e_year ? : '-' }}</div>
                    </div>
                <div class="mb-3"></div>
            </div>
            <div class="householdincome">
                <h6>7. Household Income</h6>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">The total household income per month</div>
                        <div class="col-5">{{ $income[$caData->ca_h_income] ?? '-' }}</div>
                    </div>
                <div class="mb-3"></div>
            </div>
            <div class="programmereferences">
                <h6>8. Programme References</h6>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Programme Name</div>
                        <div class="col-5">{{ $caData->ca_programme_name ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Intake Month</div>
                        <div class="col-5">{{ $caData->ca_programme_month ? : '-' }}</div>
                    </div>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Intake Year</div>
                        <div class="col-5">{{ $caData->ca_programme_year ? : '-' }}</div>
                    </div>
                <div class="mb-3"></div>
            </div>
            <div class="disabilityneeds">
                <h6>9. Disability Needs</h6>
                    <div class="row col-12">
                        <div class="col-7" style="color:#999999;">Do you have a disability, impairment or long term medical condition which may affect your studies?</div>
                        <div class="col-5">{{ $disability[$caData->ca_has_disability] ?? '-' }}</div>
                    </div><div class="row col-12">
                        <div class="col-7" style="color:#999999;">Please Specify</div>
                        <div class="col-5">{{ $caData->ca_disability_desc ? : '-' }}</div>
                    </div>
                <div class="mb-3"></div>
            </div>

             <div class="document">
                <h6>10. Documents</h6>
                @php
                    $transcripts = explode(',', $caData->ca_transcript);
                    $firstTranscript = true;
                @endphp
                @foreach($transcripts as $transcript)
                <div class="row col-12">
                @if($firstTranscript)
                    <div class="col-7" style="color:#999999;">Transcript</div>
                @php $firstTranscript = false; @endphp
                @else
                    <div class="col-7"></div>
                @endif
                    <div class="col-5">
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
                @php
                    $resumes = explode(',', $caData->ca_resume);
                    $firstResume = true;
                @endphp
                @foreach($resumes as $resume)
                <div class="row col-12">  
                @if($firstResume)
                    <div class="col-7" style="color:#999999;">Resume</div>
                @php $firstResume = false; @endphp
                @else
                    <div class="col-7"></div>
                @endif
                    <div class="col-5">
                        @if(pathinfo($resume, PATHINFO_EXTENSION) == 'jpg')
                            <a href="{{ asset('images/application/' . $resume) }}" data-lightbox="resume" data-title="Resume">
                                <img src="{{ asset('images/application/' . $resume) }}" alt="Resume" style="max-width: 100px;">
                            </a><br>
                            <a href="{{ asset('images/application/' . $transcript) }}" download><i class="fas fa-download"></i> Resume</a>
                        @elseif(pathinfo($resume, PATHINFO_EXTENSION) == 'pdf')
                            <a href="{{ asset('images/application/' . $resume) }}" download><i class="fas fa-file-pdf"></i> Resume</a>
                        @endif
                    </div>
                </div>
                @endforeach
                <div class="mb-3"></div>
            </div>
        </div>
</div>


@endsection

@section('script')
@endsection