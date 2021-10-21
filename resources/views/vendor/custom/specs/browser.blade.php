@extends('backpack::layout')

@section('header')
	<section class="content-header">
		<h1>
	        <span class="text-capitalize">Browse Car Tuning Specs</span>
		</h1>
	  <ol class="breadcrumb">
	    <li>
	    	<a href="{{ url(config('backpack.base.route_prefix'), 'dashboard') }}">{{ trans('backpack::crud.admin') }}</a>
	    </li>
	    <li class="active">Browse Car Tuning Specs</li>
	  </ol>
	</section>
@endsection
@section('content')
<style>
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
        <div class="col-right-browser">
            <div class="box box-default">
                <div class="box-body reg-box">
                    <div class="box-title login-title" style="font-weight: bold">Tuning files</div>
                    <p>You can freely browse all our tuning file specifications online using the form below.</p>
                    <form action="{{$category_link}}">
                        <div class="box-title drop-down-container">
                            <div class="drop-down">
                                <label class="control-label">Make</label>
                                <select class="form-control" id="make" name="make">
                                    <option value="">--Choose a Make--</option>
                                    @foreach($brands as $b)
                                        <option value="{{ $b }}">{{ $b }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="drop-down">
                                <label class="control-label">Model</label>
                                <select class="form-control" id="model" name="model">
                                    <option value="">--Choose a Model--</option>
                                </select>
                            </div>
                            <div class="drop-down">
                                <label class="control-label">Generation</label>
                                <select class="form-control" id="generation" name="generation">
                                    <option value="">--Choose a Generation--</option>
                                </select>
                            </div>
                            <div class="drop-down">
                                <label class="control-label">Engine</label>
                                <select class="form-control" id="engine" name="engine">
                                    <option value="">--Choose a Engine--</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <button class="btn btn-danger col-md-12" id="btnFind">
                                {{ __('Find my car') }}
                            </button>
                        </div>
                    </form>
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
            }

            function capitalizeFirstLetter(string) {
                return string.charAt(0).toUpperCase() + string.slice(1);
            }
        </script>
@endsection
