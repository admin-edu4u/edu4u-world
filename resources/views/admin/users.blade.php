@extends('adminLayout')
@section('style')
<style>
    .userstable {
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
<div class="userstable">
    <div class="card card-p-0 card-flush mt-5">
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
            <div class="card-title ms-5">
                <h1>Users</h1>
            </div>
            <form action="{{ route('export.users') }}" method="GET">
                <button type="submit" class="btn btn-primary"><i class="fas fa-file-export"></i> Export</button>
            </form>
        </div>
        <div class="card-body">
            <table class="table align-middle border rounded table-row-dashed fs-6 g-5" id="kt_datatable_example">
                <thead>
                    <tr class="text-start text-gray-400 fw-bolder fs-7 text-uppercase">
                        <th>No.</th>
                        <th>Edu4u ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Contact No.</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="fw-bold text-gray-600">
                @if(isset($users) > 0)
                    @foreach($users as $u)
                    @php $counter++; @endphp
                    <tr class="odd text-start align-middle">
                        <td style="width:30px;">{{ $counter }}.</td>
                        <td>{{ $u->mlm_id }}</td>
                        <td>{{ $u->username ?? '-' }}</td>
                        <td>{{ $u->email ?? '-' }}</td>
                        <td>
                            @if ($u->contact_code && $u->contact_no)
                                +{{ $u->contact_code }} {{ $u->contact_no }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <button class="form-control float-end" style="width:100px;" onclick="redirectToViewUser('{{ $u->id }}')">View</button>
                            <!-- <button class="form-control float-end" style="width:100px;" onclick="viewUsers('{{ $u->mlm_id }}', '{{ $u->email }}', '{{ $u->username }}', '{{ $u->fullname }}', '{{ $u->first_name }}', 
                            '{{ $u->last_name }}', '{{ $u->contact_code }}', '{{ $u->contact_no }}', '{{ $u->dob_day }}', '{{ $u->dob_month }}', '{{ $u->dob_year }}', '{{ $u->gender }}', '{{ $u->profile_pic }}', 
                            '{{ $u->personality }}', '{{ $u->interests }}', '{{ $u->preferred_course }}', '{{ $u->character }}')">View</button> -->
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
@endsection

@section('script')
<script>
    function redirectToViewUser(id) {
        window.location.href = '/admin/viewuser?id=' + id;
    }

    // function viewUsers(uid, email, username, fullname, firstname, lastname, contactcode, contactno, dobday, dobmonth, dobyear, gender, profilepic, personality, interests, preferredcourse, character) {
        
    //     $('#uId').text(uid);
    //     $('#uEmail').html(email ? email : '<i>null</i>');
    //     $('#uUsername').html(username ? username : '<i>null</i>');
    //     $('#uFullName').html(fullname ? fullname : '<i>null</i>');
    //     $('#uFirstName').html(firstname ? firstname : '<i>null</i>');
    //     $('#uLastName').html(lastname ? lastname : '<i>null</i>');
    //     $('#uContact').html(contactcode && contactno ? '+' + contactcode + ' ' + contactno : '<i>null</i>');
    //     $('#uDOB').html((dobday != 0 && dobmonth != 0 && dobyear != 0) ? (dobday + '-' + dobmonth + '-' + dobyear) : '<i>null</i>');
        

    //     var genderText = '';
    //     if (gender == 1) {
    //         genderText = 'Male';
    //     } else if (gender == 2) {
    //         genderText = 'Female';
    //     } else {
    //         genderText = '<i>null</i>';
    //     }

    //     $('#uGender').html(genderText);

    //     var personalityArray = personality ? personality.split(',') : [];
    //     var personalityNames = [];
    //     personalityArray.forEach(function(personalityId) {
    //         var name = findPersonalityName(personalityId); // You need to implement this function
    //         personalityNames.push(name ? name : '<i>null</i>');
    //     });
    //     $('#uPersonality').html(personalityNames.join(', '));

    //     // $('#uPersonality').html(personality ? personality : '<i>null</i>');
    //     $('#uInterests').html(interests ? interests : '<i>null</i>');
    //     $('#uPreferredCourse').text(preferredcourse);
    //     $('#uCharacter').text(character);

    //     $('#viewUserModal').modal('show');
    // }
</script>
@endsection