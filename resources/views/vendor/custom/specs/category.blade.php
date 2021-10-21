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
    .subitem-link:hover {
        background-color: rgba(0,0,0,.05)
    }
    .subitem-link {
        width: 31.5%;
        float: left;
        box-sizing: border-box;
        margin: 0 0 1.5% 1.5%;
        position: relative;
        z-index: 1;
        overflow: hidden;
        display: block;
        text-align: center;
        padding-right: 20px;
        padding-left: 20px;
        font-weight: 400;
        color: #455a64!important;
        background: linear-gradient(180deg,transparent 0,rgba(0,0,0,.065));
        border: 1px solid rgba(0,0,0,.25);
        padding: 10px 22px 12px;
    }
    @media (max-width: 1100px) {
        .subitem-link {
            width: 48%;
        }
    }
    @media (max-width: 700px) {
        .subitem-link {
            width: 48%;
        }
    }
    </style>
    <div class="login-container">
        <div class="col-right-browser">
            <div class="box box-default">
                <div class="box-body reg-box">
                    <div class="box-title login-title">
                        {{ $title }}
                        @if($mode == 'make')
                            Models
                        @elseif($mode == 'model')
                            Generations
                        @elseif($mode == 'generation')
                            Engine Types
                        @endif
                    </div>
                    <div class="row" style="margin-top: 30px">
                        @foreach($subitems as $si)
                            @if($mode == 'make')
                            <a href="{{ backpack_url('/car/category'.'?make='.$title.'&model='.$si) }}" class="subitem-link">
                                {{ $si }}
                            </a>
                            @elseif($mode == 'model')
                            <a href="{{ backpack_url('/car/category'.'?make='.$brand.'&model='.$title.'&generation='.$si) }}">
                                <button type="button" class="subitem-link">{{ $si }}</button>
                            </a>
                            @elseif($mode == 'generation')
                            <a href="{{ backpack_url('/car/category'.'?make='.$brand.'&model='.$model.'&generation='.$title.'&engine='.$si->id) }}">
                                <button type="button" class="subitem-link">{{ $si->engine_type.' '.$si->std_bhp }}</button>
                            </a>
                            @endif
                        @endforeach
                    </div>
                    <p class="car-title">
                        @if($mode == 'make')
                            {{ $title }}
                        @elseif($mode == 'model')
                            {{ $brand.' '.$title }}
                        @elseif($mode == 'generation')
                            {{ $brand.' '.$model.'('.$title.')' }}
                        @endif
                    </p>
                    <p class="fs-20">
                        <img src="{{ $logo }}" style="width: 100px; height: 100px; float:left; margin:20px" />
                        {{ $company->name }} is leading in the development of {{ $title }} tuning files.
                        The development of each {{ $title }} tuning file is the result of perfection and dedication by {{ $company->name }} programmers.
                        The organization only uses the latest technologies and has many years experience in ECU remapping software.
                        Many (chiptuning) organizations around the globe download their tuning files for {{ $title }} at {{ $company->name }} for the best possible result.
                        All {{ $title }} tuning files deliver the best possible performance and results within the safety margins.
                    </p>
                    <ul class="fs-20">
                        <li>100% custom made tuning file guarantee</li>
                        <li>Tested and developed via a 4x4 Dynometer</li>
                        <li>Best possible performance and results, within the safety margins</li>
                        <li>Reduced fuel consumption</li>
                    </ul>
                    <div class="row">
                        @if(!empty($_GET['make']))
                            <a class="subitem-link" href="{{ backpack_url('/car/category') }}">Overview</a>
                        @endif
                        @if(!empty($_GET['model']))
                            <a class="subitem-link" href="{{ backpack_url('/car/category'.'?make='.$_GET['make']) }}">
                                Back to {{ $_GET['make'] }}
                            </a>
                        @endif
                        @if(!empty($_GET['generation']))
                            <a class="subitem-link" href="{{ backpack_url('/car/category'.'?make='.$_GET['make'].'&model='.$_GET['model']) }}">
                                Back to {{ $_GET['model'] }}
                            </a>
                        @endif
                        @if(!empty($_GET['engine']))
                        <div class="col-md-3">
                            <a class="subitem-link" href="{{ backpack_url('/car/category'.'?make='.$_GET['make'].'&model='.$_GET['model'].'&generation='.$_GET['generation']) }}">
                                Back to {{ $_GET['generation'] }}
                            </a>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
