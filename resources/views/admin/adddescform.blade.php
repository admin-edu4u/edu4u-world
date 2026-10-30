@extends('adminLayout')
@section('style')
<style>
    .adddescform {
        max-width: 100%;
        margin: 20px;
        padding: 20px;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        background-color: white;
        height:auto;
    }
</style>
@endsection

@section('content')
    @include('formMsg')
<div class="adddescform">
<form id="adddesc" class="form-horizontal" method="post" action="/admin/addDesc">
    <h1><strong>Add Desc</strong></h1>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label">Course</label>
        <div class="col-sm-10">
            <select name="course" class="form-select course" id="course" required>
                <option value="" selected disabled>Select Course</option>
                @foreach($course as $c)
                    <option value="{{ $c->c_id }}">{{ $c->c_name . '   |   ' . $c->u_name }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div id="levelContainer">
        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Level</label>
            <div class="col-sm-10">
                <select name="Level" class="form-select level" id="Level" required>
                    <option value="" selected disabled>Select Level</option>
                    @foreach ($qlevel as $value => $ql)
                        <option value="{{ $value }}">{{ $ql }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="mb-3 row">
            <label class="col-sm-2 col-form-label">Desc</label>
            <div class="col-sm-10">
                <div id="kt_quill_1" style="height: 200px"></div>
            </div>
        </div>
    </div>

    <div class="mb-3 row">
        <label class="col-sm-2 col-form-label"></label>
        <div class="col-sm-10">
            <div class="float-end">
                <div class="mt-3"></div>
                <button type="submit" class="btn btn-primary mt-2 float-end">Submit</button>
            </div>
        </div>
    </div>

</form>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $('.course').select2();
    });

    var KTQuilDemos = function () {

        var demo1 = function () {
            var quill = new Quill('#kt_quill_1', {
                modules: {
                    toolbar: [
                        [{
                            header: [1, 2, false]
                        }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'bullet' }]
                    ]
                },
                placeholder: 'Enter description...',
                theme: 'snow'
            });

            quill.on('text-change', function () {
                var content = quill.root.innerHTML;

                var hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'desc';
                hiddenInput.value = content;
                var form = document.getElementById('adddesc');
                form.appendChild(hiddenInput);
            });
        }

        return {
            init: function () {
                demo1();
            }
        };
    }();

    jQuery(document).ready(function () {
        KTQuilDemos.init();
    });
</script>
@endsection