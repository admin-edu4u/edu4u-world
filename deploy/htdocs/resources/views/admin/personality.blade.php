@extends('adminLayout')
@section('title', 'Personality')
@section('style')
<style>
    .viewpersonalitytable {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
    }

    .character-box {
    display: inline-block;
    border: 1px solid #ccc;
    padding: 5px;
    margin-right: 5px; /* Adjust the margin as needed */
    margin-bottom: 5px;
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
    @php $counter = 0; @endphp
<div class="viewpersonalitytable">
        <div class="card">
            <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                <div class="card-title ms-5">
                    <h1><strong>Personality</strong></h1>
                </div>
                <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
                    <button type="button" class="btn btn-light-primary" data-bs-toggle="modal" data-bs-target="#addPersonalityModal">
                        <span class="svg-icon svg-icon-1 position-absolute ms-4"></span>
                            Add Personality
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table border-top-0 table-bordered text-nowrap border-bottom" id="datatable">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th class=" border-bottom"><strong>Name</strong></th>
                                <th class=" border-bottom"><strong>Character</strong></th>
                                <th class=" border-bottom"><strong>Type</strong></th>
                                <th class=" border-bottom"><strong>Action</strong></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($personality) && count($personality) > 0)
                            @foreach($personality as $p)
                            @php $counter++; @endphp
                            <tr>
                                <td>{{ $counter }}.</td>
                                <td>{{$p->p_name}}</td>
                                <td>
                                    @php
                                        $characterIds = explode(',', $p->p_character);
                                        $characterNames = \App\Models\Characters::whereIn('c_id', $characterIds)->pluck('c_name')->toArray();
                                        $totalCharacterCount = count($characterNames);
                                    @endphp

                                    @php $displayedCharacterCount = 0; @endphp
                                    @foreach ($characterNames as $characterName)
                                        @if ($displayedCharacterCount < 5)
                                            <div class="character-box">{{$characterName}}</div>
                                            @php $displayedCharacterCount++; @endphp
                                        @endif
                                    @endforeach

                                    @if ($totalCharacterCount > 5)
                                        <div class="character-box">+{{$totalCharacterCount - 5}}</div>
                                    @endif
                                </td>
                                <td>{{ \App\Models\Personality::typeArr($p->p_type) }}</td>
                                <td>
                                    <!-- <button  onclick="toggleEdit('{{$p->p_id}}', '{{$p->p_name}}', '{{$p->p_character}}', '{{$p->p_type}}')">
                                    <i class="bi bi-pencil edit-icon"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="toggleReject({{$p->p_id}})">
                                    <i class="fas fa-trash"></i>
                                    </button> -->
                                    <i class="bi bi-pencil edit-icon" onclick="toggleEdit('{{$p->p_id}}', '{{$p->p_name}}', '{{$p->p_character}}', '{{$p->p_type}}')"></i>
                                    <i class="bi bi-trash delete-icon" onclick="toggleReject('{{$p->p_id}}')"></i>
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

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deletePersonalityModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-middle">
		<div class="modal-content">
        <form action="/admin/deletePersonality" method="post">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="deletePersonalityModalLabel">Delete Personality</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
				<input type="hidden" name="p_id" id="deactivate_id" value="">
			<div class="modal-body">
				<span>Are you sure to delete this personality?</span>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger" >Yes</button>
				<button type="button" class="btn" data-bs-dismiss="modal">Close</button>
			</div>
        </form>
		</div>
	</div>
</div>
@endsection

@section('modal')
<div class="modal fade" id="addPersonalityModal" tabindex="-1" aria-labelledby="addPersonalityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="addPersonalityForm" method="post" action="/admin/addPersonality">
                <div class="modal-header">
                    <h5 class="modal-title" id="addPersonalityModalLabel">Add New Personality</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label for="CharacterTitle">Personality Type:</label>
                    <select class="form-select" id="characterType" name="type" required>
                        <option selected readonly value="">Please select personality type</option>
                        <option value="1">Describe</option>
                        <option value="2">Interest</option>
                    </select>
                    <div class="mt-3"></div>
                    <label for="personalityTitle">Personality Name:</label>
                    <input id="personalityInput" name="title" class="form-control" type="text" placeholder="Enter personality name..." required>
                    <div class="mt-3"></div>
                    <label for="personality">Personality Character:</label>
                    <select class="form-select personality personalityCharacter" name="character[]" multiple="multiple" required>
                        @foreach ($characters as $characters)
                            <option value="{{ $characters->c_id }}">{{ $characters->c_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editPersonalityModal" tabindex="-1" aria-labelledby="editPersonalityModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-middle">
        <div class="modal-content">
            <form id="editPersonalityForm" method="post" action="/admin/editPersonality">
                <div class="modal-header">
                    <h5 class="modal-title" id="editPersonalityModalLabel">Edit Personality</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="editPersonalityId" name="p_id" class="form-control mb-3 bg-secondary" readonly>
                    <label for="editPersonalityCategory">Personality Type:</label>
                    <div class="input-group mb-3">
                        <select id="editPersonalityType" name="type" class="form-select" data-control="select2" data-placeholder="Select an option" data-dropdown-parent="#editPersonalityModal" required>
                            <option value="1">Describe</option>
                            <option value="2">Interest</option>
                        </select>
                    </div>

                    <label for="editPersonalityName">Personality Name:</label>
                    <input type="text" id="editPersonalityTitle" name="title" class="form-control mb-3" placeholder="Enter personality name..." required>

                    <label for="editPersonalityCategory">Personality Character:</label>
                    <div class="input-group mb-3">
                    <select id="editPersonalityCharacter" class="form-select personality editpersonalityCharacter" name="editcharacter[]" multiple="multiple" required>
                        @foreach ($editcharacters as $character)
                            <option value="{{ $character->c_id }}">{{ $character->c_name }}</option>
                        @endforeach
                    </select>
                    </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deletePersonalityModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-middle">
		<div class="modal-content">
        <form action="/admin/deletePersonality" method="post">
			<div class="modal-header bg-danger">
				<h5 class="modal-title text-white" id="deletePersonalityModalLabel">Delete Personality</h5>
				<button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
				<input type="hidden" name="p_id" id="deactivate_id" value="">
			<div class="modal-body">
				<span>Are you sure to delete this personality?</span>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-danger" >Yes</button>
				<button type="button" class="btn" data-bs-dismiss="modal">Close</button>
			</div>
        </form>
		</div>
	</div>
</div> -->
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.personalityCharacter').select2();
        $('.editpersonalityCharacter').select2();
    });

    function toggleEdit(p_id, p_name, p_character, p_type) {
        console.log('Received data:', p_id, p_name, p_character, p_type);

        $('#editPersonalityId').val(p_id);
        $('#editPersonalityTitle').val(p_name);
        $('#editPersonalityType').val(p_type).trigger('change');
        $('#editPersonalityCharacter').val(p_character.split(',')).trigger('change');
        $('#editPersonalityModal').modal('toggle');
    }

    function toggleReject(id) {
	    if(id != undefined && id != null) {
            console.log(id);
		    $('#deactivate_id').val(id);
		    $('#deleteModal').modal('show');
	    }
    }
</script>
@endsection