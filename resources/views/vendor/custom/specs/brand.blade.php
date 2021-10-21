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
    .brand-link:hover {
        transform: scale(1.2, 1.2);
    }
    .brand-link {
        width: 12.5%;
        float: left;
        box-sizing: border-box;
        margin: 0 0 4% 4%;
        padding: 12px;
        position: relative;
        z-index: 1;
        overflow: hidden;
        display: block;
        background-color: #fff;
        border-radius: 4px;
        box-shadow: 0 0 4px rgba(0,0,0,.4);
        transform: scale(1, 1);
        transition: transform .3s ease;
    }
    .brand-link img {
        width: 100%; /* This if for the object-fit */
        height: 100%; /* This if for the object-fit */
        object-fit: cover; /* Equivalent of the background-size: cover; of a background-image */
        object-position: center;
    }
    @media (max-width: 1100px) {
        .brand-link {
            width: 21%;
        }
    }
    @media (max-width: 700px) {
        .brand-link {
            width: 26%;
        }
    }
    </style>
    <div class="login-container">
        <div class="col-right-browser">
            <div class="box box-default">
                <div class="box-body reg-box">
                    <div class="box-title login-title">Tuning Files</div>
                    <p>Please select the make of your car below.</p>
                    <div class="row">
                        @foreach($brands as $brand)
                            <a class="brand-link" href="{{ backpack_url('/car/category'.'?make='.$brand['brand']) }}">
                                <img src="{{ $brand['logo'] }}">
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
