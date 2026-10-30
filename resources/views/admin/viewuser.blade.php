@extends('adminLayout')
@section('style')
<style>
    .profile-card {
        border: 1px solid #ccc;
        border-radius: 10px;
        overflow: hidden;
        width: 500px;
        margin: 20px auto;
        padding: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .profile-pic {
        text-align: center;
    }

    .profile-pic img {
        width: 100px;
        height: 100px; 
        border-radius: 50%;
    }

    .profile-details {
        margin-top: 20px;
    }

    .profile-details p {
        margin-bottom: 10px;
    }

    .profile-details strong {
        font-weight: bold;
    }

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

    .personality-box,
    .interests-box {
        display: inline-block;
        padding: 5px 10px;
        background-color: #f0f0f0;
        border-radius: 5px;
        margin-right: 5px;
        margin-bottom: 5px;
    }

    .character-boxes {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .character-box {
        background-color: #f0f0f0;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding: 10px;
        width: calc(33.33% - 10px);
        text-align: center;
    }

    .character-box:last-child {
        margin-right: 0;
    }

    .character-image {
        max-width: 100%;
        height: auto;
    }

    .email-password-box {
        display: inline-block;
        margin-left: 20px;
    }

    .completed-box,
    .incompleted-box {
        border: 1px;
        border-radius: 5px;
        padding: 10px;
        width: calc(33.33% - 10px);
        text-align: center;
    }

    .completed-box {
        background-color:forestgreen;
        color: white;
    }

    .incompleted-box {
        background-color:maroon;
        color: white;
    }

    .result-item {
        display: inline-block;
        margin-right: 20px; /* Adjust margin as needed */
    }
    .result-item p {
        display: inline; /* Ensure subject ID and grade are displayed on the same line */
    }
</style>
@endsection

@section('content')
    @php
        $id = request('id');
        $userModel = new \App\Models\User();
        $userData = $userModel->find($id);

        $malaysianStatus = $userData->is_malaysian ?? null;

        $maincat = $userData->pref_main_cat;
        $subcat = $userData->pref_sub_cat;
        $category = App\Models\Category::find($maincat);
        $subCategory = App\Models\SubCat::find($subcat);

        $streamNames = [0 => 'Others',1 => 'Science Stream',2 => 'Art Stream',3 => 'Sub Science'];

        $academic = App\Models\Academic::where('a_user_id', $userData->id)->first();
        $educationallevel = $academic->a_level ?? null;
        $qualification = $academic->a_qualification ?? null;
        $school = $academic->a_school ?? null;

        $results = App\Models\Result::where('r_user_id', $userData->id)->where('r_status', '1')->get();
        $resultCount = $results->count();
        $resultstype = $results->isNotEmpty() ? $results->first()->r_type : null;

        $achievements = App\Models\Achievement::where('a_user_id', $userData->id)->get();
        $sports = $achievements->where('a_type', '1')->first();
        $talents = $achievements->where('a_type', '2')->first();
        $leaderships = $achievements->where('a_type', '3')->first();

        $sports_is_represent = $sports ? $sports->a_is_represent : null;
        $sports_level = $sports ? $sports->a_level : null;
        $sports_represent = $sports ? $sports->a_represent : null;

        $talents_is_represent = $talents ? $talents->a_is_represent : null;
        $talents_level = $talents ? $talents->a_level : null;
        $talents_represent = $talents ? $talents->a_represent : null;

        $leaderships_is_represent = $leaderships ? $leaderships->a_is_represent : null;
        $leaderships_role = $leaderships ? $leaderships->a_role : null;
        $leaderships_level = $leaderships ? $leaderships->a_level : null;
        $leaderships_represent = $leaderships ? $leaderships->a_represent : null;

        $family = App\Models\Family::where('f_user_id', $userData->id)->first();
        $income = $family ? $family->f_income : null;
        $budget = $family ? $family->f_budget : null;
        $bumiputra = $family ? $family->f_is_bumiputera : null;

        $universitypreferences = App\Models\UniversityPreference::where('up_user_id', $userData->id)->first();
        $pathway = $universitypreferences ? $universitypreferences->up_pathway : null;
        $plandate = $universitypreferences ? $universitypreferences->up_start : null;
        $location = $universitypreferences ? $universitypreferences->up_location : null;
        $state = $universitypreferences ? $universitypreferences->up_state : null;
        $country = $universitypreferences ? $universitypreferences->up_country : null;
        $consideruni = $universitypreferences ? $universitypreferences->up_type : null;
        $registerfee = $universitypreferences ? $universitypreferences->up_is_fee_paid : null;
        $uniname = $universitypreferences ? $universitypreferences->up_uni_name : null;
    @endphp
    <a href="javascript:history.back()" class="back-btn">Back</a>
    <div class="profile-card">
        <div class="profile-pic">
            @if (!empty($userModel->profile_pic))
                <img src="{{ $userModel->profile_pic }}" alt="Profile Picture">
            @else
                <i class="fas fa-user fa-lg"></i>
            @endif
        </div>
        <div class="profile-details">
            <p><strong>User ID:</strong> <span id="uId">{{ $userData->mlm_id }}</span></p>
            <p><strong>Name:</strong> <span id="uFullName" style="{{ !$userData->fullname ? 'font-style: italic;' : '' }}">{{ $userData->username ? $userData->username : 'null' }}</span></p>
            <p><strong>Gender:</strong> 
                <span id="uGender">
                    @if ($userData->gender == 1)
                        Male
                    @elseif ($userData->gender == 2)
                        Female
                    @else
                        <em>null</em>
                    @endif
                </span>
            </p>
            <!-- <p><strong>Username:</strong> <span id="uUsername" style="{{ !$userData->username ? 'font-style: italic;' : '' }}">{{ $userData->username ? $userData->username : 'null' }}</span></p> -->
            <p><strong>Email:</strong> <span id="uEmail" style="{{ !$userData->email ? 'font-style: italic;' : '' }}">{{ $userData->email ? $userData->email : 'null' }}</span></p>
            <p><strong>Contact No.:</strong> <span id="uContact">
            @if ($userData->contact_code && $userData->contact_no)
                        +{{ $userData->contact_code }}{{ $userData->contact_no }}
                    @else
                        <em>null</em>
                    @endif
                </span>
            </p>
            <p><strong>DOB:</strong> 
                <span id="uDOB">
                    @if ($userData->dob_day != 0 && $userData->dob_month != 0 && $userData->dob_year != 0)
                        {{ $userData->dob_day }}-{{ $userData->dob_month }}-{{ $userData->dob_year }}
                    @else
                        <em>null</em>
                    @endif
                </span>
            </p>
            <p>
                <strong>Nationality:</strong> 
                <span style="{{ !$malaysianStatus ? 'font-style: italic;' : '' }}">
                    {{ $malaysianStatus == 1 ? 'Malaysian' : ($malaysianStatus == 2 ? 'Foreigner' : 'null') }}
                </span>
            </p>
            <p><strong>Personality:</strong> 
                <span id="uPersonality">
                    @php
                        $personalities = explode(',', $userData->personality);
                    @endphp
                    @foreach ($personalities as $personalityId)
                        @php
                            $personality = App\Models\Personality::find($personalityId);
                        @endphp
                        <span class="personality-box">{{ $personality ? $personality->p_name : 'N/A' }}</span>
                    @endforeach
                </span>
            </p>
            <p><strong>Interests:</strong> 
                <span id="uInterests">
                    @php
                        $personalities = explode(',', $userData->interests);
                    @endphp
                    @foreach ($personalities as $personalityId)
                        @php
                            $personality = App\Models\Personality::find($personalityId);
                        @endphp
                        <span class="interests-box">{{ $personality ? $personality->p_name : 'N/A' }}</span>
                    @endforeach
                </span>
            </p>
            <p><strong>Educational Level:</strong>
                <span>
                    @if ($educationallevel == 1)
                        Secondary
                    @elseif ($educationallevel == 2)
                        Pre-University
                    @elseif ($educationallevel == 3)
                        Others
                    @else
                        null
                    @endif
                </span>
            </p>
            <p><strong>Study Stream:</strong>
                <span>
                    {{ $streamNames[$userData->stream] ?? 'N/A' }}
                </span>
            </p>
            <p><strong>Qualification Level:</strong>
                <span>
                    @if ($qualification == 1)
                        SPM
                    @elseif ($qualification == 2)
                        O-Level
                    @elseif ($qualification == 3)
                        STPM
                    @elseif ($qualification == 4)
                        UEC
                    @elseif ($qualification == 5)
                        Foundation
                    @elseif ($qualification == 6)
                        Diploma
                    @elseif ($qualification == 7)
                        A-Level
                    @elseif ($qualification == 8)
                        IB Diploma
                    @elseif ($qualification == 9)
                        CPU/CIMP
                    @elseif ($qualification == 10)
                        SACE/WACE
                    @else
                        Others
                    @endif
                </span>
            </p>
            <p><strong>School:</strong>
                <span>
                    {{ $school }}
                </span>
            </p>
            <p><strong>Results Type:</strong>
                <span>
                    @if ($resultstype == 1)
                        Final Result
                    @elseif ($resultstype == 2)
                        Trial / Forecast Result
                    @elseif ($resultstype == 3)
                        Partial Results or Monthly / Semester Exam
                    @else
                        Others
                    @endif
                </span>
            </p>
            @if ($resultCount === 1)
                <div class="cgpa">
                    <p><strong>CGPA/Score:</strong> {{ $results->first()->r_cgpa }}</p>
                </div>
            @else
                @foreach ($results as $result)
                    <div class="result-item">
                        <p><strong>{{ App\Models\Subject::find($result->r_subject_id)->s_name }}:</strong></p>
                        <p>{{ App\Models\SubjectGrade::find($result->r_grade)->sg_grade }}</p>
                    </div>
                @endforeach
            @endif

            <p class="mt-3"><strong>Represent in sports: </strong><span>{{ $sports ? ($sports->a_is_represent == 1 ? 'Yes' : 'No') : 'N/A' }}</span></p>
            @if ($sports && $sports->a_is_represent == 1)
                <p><strong>Level:</strong> <span>
                    @if ($sports->a_level == 1)
                        School
                    @elseif ($sports->a_level == 2)
                        District
                    @elseif ($sports->a_level == 3)
                        State
                    @elseif ($sports->a_level == 4)
                        National
                    @else
                        null
                    @endif
                </span></p>
                <p><strong>Sports: </strong><span>{{ $sports->a_represent }}</span></p>
            @endif

            <p><strong>Represent in talents: </strong><span>{{ $talents ? ($talents->a_is_represent == 1 ? 'Yes' : 'No') : 'N/A' }}</span></p>
            @if ($talents && $talents->a_is_represent == 1)
                <p><strong>Level:</strong> <span>
                    @if ($talents->a_level == 1)
                        School
                    @elseif ($talents->a_level == 2)
                        District
                    @elseif ($talents->a_level == 3)
                        State
                    @elseif ($talents->a_level == 4)
                        National
                    @else
                        null
                    @endif
                </span></p>
                <p><strong>Talents: </strong><span>{{ $talents->a_represent }}</span></p>
            @endif

            <p><strong>Leadership Role: </strong><span>{{ $leaderships ? ($leaderships->a_is_represent == 1 ? 'Yes' : 'No') : 'N/A' }}</span></p>
            @if ($leaderships && $leaderships->a_is_represent == 1)
                <p><strong>Role:</strong> <span>
                    @if ($leaderships->a_role == 1)
                        President
                    @elseif ($leaderships->a_role == 2)
                        Vice President
                    @elseif ($leaderships->a_role == 3)
                        Secretary
                    @elseif ($leaderships->a_role == 4)
                        Treasurer
                    @elseif ($leaderships->a_role == 5)
                        Director / Team Lead
                    @elseif ($leaderships->a_role == 6)
                        Others
                    @else
                        null
                    @endif
                </span></p>
                <p><strong>Level:</strong> <span>
                    @if ($leaderships->a_level == 1)
                        School
                    @elseif ($leaderships->a_level == 2)
                        District
                    @elseif ($leaderships->a_level == 3)
                        State
                    @elseif ($leaderships->a_level == 4)
                        National
                    @else
                        null
                    @endif
                </span></p>
                <p><strong>Society / Event: </strong><span>{{ $leaderships->a_represent }}</span></p>
            @endif


            <p><strong>Family monthly income: </strong><span>
                @if ($income == 1)
                    Below MYR 4,000
                @elseif ($income == 2)
                    Below MYR 8,000
                @elseif ($income == 3)
                    Above MYR 8,000
                @else
                    N/A
                @endif
            </span></p>

            <p><strong>Budget for course: </strong><span>
                @if ($budget == 1)
                    Below MYR 20,000
                @elseif ($budget == 2)
                    MYR 20,001 - MYR 40,000
                @elseif ($budget == 3)
                    MYR 40,001 - MYR 60,000
                @elseif ($budget == 4)
                    MYR 60,001 - MYR 80,000
                @elseif ($budget == 5)
                    MYR 80,001 - MYR 100,000
                @elseif ($budget == 6)
                    Above MYR 100,000
                @else
                    N/A
                @endif
            </span></p>

            <p><strong>Bumiputera: </strong><span>
                @if ($bumiputra == 1)
                    Yes
                @elseif ($bumiputra == 2)
                    No
                @else
                    N/A
                @endif
            </span></p>

            <p><strong>Preferred Field Of Study:</strong> 
                <span id="uPreferredCourse">
                    {{ $category->c_name ?? 'N/A' }}
                </span>
            </p>

            <p><strong>Preferred Major:</strong> 
                <span id="uPreferredCourse">
                    {{ $subCategory->sc_name ?? 'N/A' }}
                </span>
            </p>

            <p><strong>Preferred Pathway: </strong><span>
                @if ($pathway == 1)
                    Foundation
                @elseif ($pathway == 2)
                    International Pre-University
                @elseif ($pathway == 3)
                    Diploma
                @elseif ($pathway == 4)
                    Degree
                @elseif ($pathway == 5)
                    Master
                @elseif ($pathway == 6)
                    PHD
                @else
                    N/A
                @endif
            </span></p>

            <p><strong>Planning Start Date: </strong><span>
                @if ($plandate == 1)
                    Jan - Mar
                @elseif ($plandate == 2)
                    Apr - Jun
                @elseif ($plandate == 3)
                    Jul - Sep
                @elseif ($plandate == 4)
                    Oct - Dec
                @else
                    N/A
                @endif
            </span></p>
        
            <p><strong>Preferred Location: </strong><span>
                @if ($location == 1)
                    Malaysia, for my entire studies
                @elseif ($location == 2)
                    Malaysia, but I plan to go overseas later
                @elseif ($location == 3)
                    Overseas, for my entire studies
                @else
                    N/A
                @endif
            </span></p>

            @if ($location == 1)
            <p><strong>Preferred State: </strong><span>
                @if ($state == 1)
                    Johor
                @elseif ($state == 2)
                    Kedah
                @elseif ($state == 3)
                    Kelantan
                @elseif ($state == 4)
                    Melaka
                @elseif ($state == 5)
                    Negeri Sembilan
                @elseif ($state == 6)
                    Pahang
                @elseif ($state == 7)
                    Perak
                @elseif ($state == 8)
                    Perlis
                @elseif ($state == 9)
                    Pulau Pinang
                @elseif ($state == 10)
                    Selangor
                @elseif ($state == 11)
                    Terengganu
                @elseif ($state == 12)
                    Kuala Lumpur
                @elseif ($state == 13)
                    Putrajaya
                @elseif ($state == 14)
                    Sarawak
                @elseif ($state == 15)
                    Sabah
                @elseif ($state == 16)
                    Labuan
                @else
                    N/A
                @endif
            </span></p>
            @endif

            @if ($location == 2)
            <p><strong>Preferred State: </strong><span>
                @if ($state == 1)
                    Johor
                @elseif ($state == 2)
                    Kedah
                @elseif ($state == 3)
                    Kelantan
                @elseif ($state == 4)
                    Melaka
                @elseif ($state == 5)
                    Negeri Sembilan
                @elseif ($state == 6)
                    Pahang
                @elseif ($state == 7)
                    Perak
                @elseif ($state == 8)
                    Perlis
                @elseif ($state == 9)
                    Pulau Pinang
                @elseif ($state == 10)
                    Selangor
                @elseif ($state == 11)
                    Terengganu
                @elseif ($state == 12)
                    Kuala Lumpur
                @elseif ($state == 13)
                    Putrajaya
                @elseif ($state == 14)
                    Sarawak
                @elseif ($state == 15)
                    Sabah
                @elseif ($state == 16)
                    Labuan
                @else
                    N/A
                @endif
            </span></p>
            <p><strong>Preferred country abroad after: </strong>
                @if ($country == 129)
                    Malaysia
                @elseif ($country == 192)
                    Singapore
                @elseif ($country == 13)
                    Australia
                @elseif ($country == 826)
                    United Kingdom
                @elseif ($country == 840)
                    United States
                @else
                    N/A
                @endif
            </p>
            @endif

            @if ($location == 3)
            <p><strong>Preferred country: </strong>
                @if ($country == 129)
                    Malaysia
                @elseif ($country == 192)
                    Singapore
                @elseif ($country == 13)
                    Australia
                @elseif ($country == 826)
                    United Kingdom
                @elseif ($country == 840)
                    United States
                @else
                    N/A
                @endif
            </p>
            @endif

            <p><strong>Preferred university type: </strong><span>
                @if ($consideruni == 1)
                    Private University
                @elseif ($consideruni == 2)
                    Public University
                @elseif ($consideruni == 3)
                    Both Private & Public University
                @else
                    N/A
                @endif
            </span></p>

            <p><strong>Paid Register Fee: </strong><span>{{ $registerfee == 1 ? 'Yes' : 'No' }}</span></p>

            @if ($registerfee == 1)
            <p><strong>Registered university: </strong><span>{{ $uniname }}</span></p>
            @endif

            <p><strong>Character:</strong></p>
            <div id="uCharacter" class="character-boxes">
                @php
                    $characterData = json_decode($userData->character);

                    if (!empty($characterData)) {
                        usort($characterData, function($a, $b) {
                            return $b->percentage - $a->percentage;
                        });

                        $numCharacters = min(count($characterData), 3);

                        for ($i = 0; $i < $numCharacters; $i++) {
                            $character = $characterData[$i];
                            $characterModel = App\Models\Characters::find($character->id);
                            if ($characterModel) {
                                $characterImage = ($userData->gender == 1) ? $characterModel->c_img_male : $characterModel->c_img_female;
                                echo '<div class="character-box mb-3">';
                                echo '<img class="character-image" src="/images/characters/' . $characterImage . '" alt="' . $characterModel->c_name . '">';
                                echo '<br>' . $characterModel->c_name . '<br>' . $character->percentage . '%';
                                echo '</div>';
                            }
                        }
                    } else {
                        echo '<div class="character-box">N/A</div>';
                    }
                @endphp
            </div>

           <strong>Sign Up At:</strong> 
            <span id="uCreatedAt">
                {{ date('Y-m-d', strtotime($userData->created_at)) }}
            </span>
    
            <div class="email-password-box float-end">
                <span id="uEmailPassword">
                    @if ($userData->email && $userData->password)
                        <span class="completed-box"><i class="fas fa-check"></i>  Completed</span>
                    @else
                        <span class="incompleted-box"><i class="fas fa-times"></i>  Incomplete</span>
                    @endif
                </span>
            </div>
        </div>
    </div>
@endsection