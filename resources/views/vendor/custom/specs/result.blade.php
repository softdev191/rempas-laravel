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
    .col-spec {
        width: 23.5%;
        float: left;
        box-sizing: border-box;
    }
    .car-title {
        font-size: 2.5rem;
        font-weight: bold;
        margin-top: 2em;
    }
    .arrow-container {
        padding: 0.5em 1em;
    }
    .header-arrow {
        clip-path: polygon(0% 0%, 80% 0, 100% 50%, 80% 100%, 0% 100%);
        background-color: #CCCCCC;
        height: 80px;
        padding-left: 20px;
    }
    .header-arrow-text {
        line-height: 80px;
    }
    .header-spec {
        border: 1px solid grey;
        border-radius: 5px;
        background-color: white;
        height: 80px;
        padding-left: 20px;
    }
    .spec-container {
        padding: 0.5em 1em;
    }
    .spec-text {
        line-height: 80px;
        font-size: 28px;
    }
    .text-500 {
        font-weight: 500;
    }
    .text-bold {
        font-weight: bold;
    }
    .fs-20 {
        font-size: 17px;
    }
    @media (max-width: 1100px) {
        .subitem-link {
            width: 48%;
        }
        .header-arrow {
            padding-left: 10px;
            font-size: 12px;
        }
        .header-spec {
            padding-left: 10px;
        }
        .spec-text {
            font-size: 16px;
        }
    }
    @media (max-width: 700px) {
        .subitem-link {
            width: 48%;
        }
        .col-spec {
            font-size: 11px;
        }
        .login-container .box-body {
            padding: 10px
        }
        .header-arrow {
            height: 60px;
            padding-left: 2px;
        }
        .header-arrow-text {
            line-height: 60px;
        }
        .header-spec {
            height: 60px;
            padding-left: 2px;
        }
        .spec-text {
            line-height: 60px;
            font-size: 14px;
        }
    }
    </style>
    <div class="login-container">
        <div class="col-right-browser">
            <div class="box box-default">
                <div class="box-body reg-box">
                    <div class="box-title login-title">{{ $car->title }}</div>
                    <div class="car-specs">
                        <div class="row">
                            <div class="col-spec">
                                &nbsp;
                            </div>
                            <div class="col-spec arrow-container text-500">
                                STANDARD
                            </div>
                            <div class="col-spec arrow-container text-500">
                                CHIPTUNING
                            </div>
                            <div class="col-spec arrow-container text-500">
                                DIFFERENCE
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-spec arrow-container">
                                <div class="header-arrow">
                                    <span class="header-arrow-text text-500">BHP</span>
                                </div>
                            </div>
                            <div class="col-spec spec-container">
                                <div class="header-spec">
                                    <span class="spec-text text-500">
                                        {{ $car->std_bhp }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-spec spec-container">
                                <div class="header-spec text-500">
                                    <span class="spec-text">
                                        {{ $car->tuned_bhp }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-spec spec-container">
                                <div class="header-spec text-bold">
                                    <span class="spec-text">
                                        {{ intval($car->tuned_bhp) - intval($car->std_bhp) }} hp
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-spec arrow-container">
                                <div class="header-arrow">
                                    <span class="header-arrow-text text-500">TORQUE</span>
                                </div>
                            </div>
                            <div class="col-spec spec-container">
                                <div class="header-spec">
                                    <span class="spec-text text-500">
                                        {{ $car->std_torque }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-spec spec-container">
                                <div class="header-spec">
                                    <span class="spec-text text-500">
                                        {{ $car->tuned_torque }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-spec spec-container">
                                <div class="header-spec text-bold">
                                    <span class="spec-text">
                                        {{ intval($car->tuned_torque) - intval($car->std_torque) }} Nm
                                    </span>
                                </div>
                            </div>
                        </div>

                        <p class="car-title">
                            Tuning file {{ $car->title }}
                        </p>
                        <p class="fs-20">
                            <img src="{{ $logofile }}" style="width: 100px; height: 100px; float:left; margin:20px" />
                            {{ $company->name }} is leading in the development of {{ $car->title }} tuning files.
                            The development of each {{ $car->title }} tuning file is the result of perfection and dedication by {{ $company->name }} programmers.
                            The organization only uses the latest technologies and has many years experience in ECU remapping software.
                            Many (chiptuning) organizations around the globe download their tuning files for {{ $car->title }} at {{ $company->name }} for the best possible result.
                            All {{ $car->title }} tuning files deliver the best possible performance and results within the safety margins.
                        </p>
                        <ul class="fs-20">
                            <li>100% custom made tuning file guarantee</li>
                            <li>Tested and developed via a 4x4 Dynometer</li>
                            <li>Best possible performance and results, within the safety margins</li>
                            <li>Reduced fuel consumption</li>
                        </ul>
                    </div>
                    <div class="row">
                        @if(isset($_GET['make']))
                            <a class="subitem-link" href="{{ backpack_url('/car/category') }}">
                                Overview
                            </a>
                        @endif
                        @if(isset($_GET['model']))
                            <a class="subitem-link" href="{{ backpack_url('/car/category'.'?make='.$_GET['make']) }}">
                                Back to {{ $_GET['make'] }}
                            </a>
                        @endif
                        @if(isset($_GET['generation']))
                            <a class="subitem-link" href="{{ backpack_url('/car/category'.'?make='.$_GET['make'].'&model='.$_GET['model']) }}">
                                Back to {{ $_GET['model'] }}
                            </a>
                        @endif
                        @if(isset($_GET['engine']))
                            <a class="subitem-link" href="{{ backpack_url('/car/category'.'?make='.$_GET['make'].'&model='.$_GET['model'].'&generation='.$_GET['generation']) }}">
                                Back to {{ $_GET['generation'] }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
