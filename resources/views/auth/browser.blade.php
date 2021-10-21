@extends('backpack::auth.layout')

@section('content')
<style>
.content{display:flex; width:100%; flex-wrap:wrap;min-height:calc(100vh - 82px); align-items:center;}
.drop-down-container {
    overflow: hidden;
    margin-bottom: 10px;
    display: flex;
    flex-wrap:wrap
}
.drop-down {
    max-width: 25%;
    flex: 0 0 25%;
    padding-right: 10px;
    padding-bottom: 8px;
    position: relative;
}
#btnFind {
    width: calc(100% - 10px);
}
@media (max-width: 1100px) {
    .drop-down {
        max-width: 50%;
        flex: 0 0 50%;
    }
}
@media (max-width: 700px) {
    .drop-down {
        max-width: 100%;
        flex: 0 0 100%;
    }
}
</style>
<div class="login-container">
    <div class="col-left-browser">
        <div class="box box-default">
            <div class="box-header with-border">
                @if(\File::exists(public_path('uploads/logo/' . $company->logo)))
                    <div class="logo-admin">
                        <img src="{{ asset('uploads/logo/' . $company->logo) }}" width="340px">
                    </div>
                @endif
            </div>
            <div class="box-body">
                <div class="box-title login-title">
                    <a href="{{ route('login') }}">Login Instead</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-right-browser">
        <div class="box box-default">
            <div class="box-body reg-box">
                <div class="box-title login-title" style="font-weight: bold">Tuning files</div>
                <p>You can freely browse all our tuning file specifications online using the form below. Interested in using our File Service?
                    <a href="{{ route('users_registers') }}">
                        Register for an account
                    </a>
                </p>
                <div class="box-title drop-down-container">
                    <div class="drop-down">
                        <label class="control-label">Make</label>
                        <select class="form-control" id="make">
                            <option value="">--Choose a Make--</option>
                            @foreach($brands as $b)
                                <option value="{{ $b }}">{{ $b }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="drop-down">
                        <label class="control-label">Model</label>
                        <select class="form-control" id="model">
                            <option value="">--Choose a Model--</option>
                        </select>
                    </div>
                    <div class="drop-down">
                        <label class="control-label">Generation</label>
                        <select class="form-control" id="generation">
                            <option value="">--Choose a Generation--</option>
                        </select>
                    </div>
                    <div class="drop-down">
                        <label class="control-label">Engine</label>
                        <select class="form-control" id="engine">
                            <option value="">--Choose a Engine--</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <a class="btn btn-danger col-md-12" href="{{ route('browser.category') }}" id="btnFind">
                        {{ __('Find my car') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('after_scripts')
	<script>
		$("#make").change(function () {
            updateNextOption('make', 'model');
		});
        $('#model').change(function() {
            updateNextOption('model', 'generation');
        });
        $('#generation').change(function() {
            updateNextOption('generation', 'engine');
        });
        $('#engine').change(function() {
            updateButtonLink();
        });

        function updateNextOption(fromKey, toKey)  {
            $("#make").prop('disabled', 'disabled');
            $("#model").prop('disabled', 'disabled');
            $("#generation").prop('disabled', 'disabled');
            $("#engine").prop('disabled', 'disabled');

            if (fromKey === 'make') {
                $('#model').html(`<option value="">--Choose a ${capitalizeFirstLetter(toKey)}--</option>`);
                $('#generation').html(`<option value="">--Choose a ${capitalizeFirstLetter(toKey)}--</option>`);
                $('#engine').html(`<option value="">--Choose a ${capitalizeFirstLetter(toKey)}--</option>`);
            } else if (fromKey === 'model') {
                $('#generation').html(`<option value="">--Choose a ${capitalizeFirstLetter(toKey)}--</option>`);
                $('#engine').html(`<option value="">--Choose a ${capitalizeFirstLetter(toKey)}--</option>`);
            } else if (fromKey === 'generation') {
                $('#engine').html(`<option value="">--Choose a ${capitalizeFirstLetter(toKey)}--</option>`);
            }

            if ($(`#${fromKey}`).val() !== '') {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('api.car.query') }}",
                    data: {
                        make: $('#make').val(),
                        model: $('#model').val(),
                        generation: $('#generation').val(),
                        engine: $('#engine').val(),
                    },
                    success: function(result) {
                        console.log(result);
                        $(`#${toKey}`).html(`<option value="">--Choose a ${capitalizeFirstLetter(toKey)}--</option>`)
                        for(const item of result){
                            if (toKey === 'engine') {
                                $(`#${toKey}`).append(`<option value='${item.id}'>${item.engine_type + ' ' + item.std_bhp}</option>`)
                            } else {
                                $(`#${toKey}`).append(`<option value='${item}'>${item}</option>`)
                            }
                        }
                        $("#make").prop('disabled', false);
                        $("#model").prop('disabled', false);
                        $("#generation").prop('disabled', false);
                        $("#engine").prop('disabled', false);
                        $(`#${toKey}`).trigger('change');
                    }
                })
            } else {
                $("#make").prop('disabled', false);
                $("#model").prop('disabled', false);
                $("#generation").prop('disabled', false);
                $("#engine").prop('disabled', false);
            }
            updateButtonLink();
        }

        function updateButtonLink() {
            var url = `{{ route('browser.category') }}`
            if ($('#make').val()) {
                url += `/?make=${$('#make').val()}`;
            }
            if ($('#model').val()) {
                url += `&model=${$('#model').val()}`;
            }
            if ($('#generation').val()) {
                url += `&generation=${$('#generation').val()}`;
            }
            if ($('#engine').val()) {
                url += `&engine=${$('#engine').val()}`;
            }
            $('#btnFind').attr('href', url);
        }

        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
	</script>
@endsection
