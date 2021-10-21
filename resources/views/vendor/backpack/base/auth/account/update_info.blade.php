@extends('backpack::layout')

@section('after_styles')
<style media="screen">
    .backpack-profile-form .required::after {
        content: ' *';
        color: red;
    }
</style>
@endsection

@section('header')
<section class="content-header">

    <h1>
        {{ trans('backpack::base.my_account') }}
    </h1>

    <ol class="breadcrumb">

        <li>
            <a href="{{ backpack_url() }}">{{ config('backpack.base.project_name') }}</a>
        </li>

        <li>
            <a href="{{ route('account.info') }}">{{ trans('backpack::base.my_account') }}</a>
        </li>

        <li class="active">
            {{ trans('backpack::base.update_account_info') }}
        </li>

    </ol>

</section>
@endsection

@section('content')
<div class="row">
    <div class="col-md-3">
        @include('backpack::auth.account.sidemenu')
    </div>
    <div class="col-md-9">

        <form class="form" action="{{ route('account.info') }}" method="post">

            {!! csrf_field() !!}
            <input type="hidden" name="id" value="{{ $user->id }}">
            <div class="box">

                <div class="box-body backpack-profile-form">

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->count())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $e)
                                <li>{{ $e }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="form-group col-md-4">
                        <label>Title</label>
                        <select class="form-control" name="title">
                            <option value="Mr">Mr</option>
                            <option value="Ms">Ms</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <label class="required">First name</label>
                        <input required class="form-control" type="text" name="first_name" value="{{ old('first_name') ? old('first_name') : $user->first_name }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="required">Last name</label>
                        <input required class="form-control" type="text" name="last_name" value="{{ old('last_name') ? old('last_name') : $user->last_name }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="required">Business name </label>
                        <input required class="form-control" type="text" name="business_name" value="{{ old('business_name') ? old('business_name') : $user->business_name }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="required">Email</label>
                        <input required class="form-control" type="text" name="email" value="{{ old('email') ? old('email') : $user->email }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="required">Phone</label>
                        <input required class="form-control" type="text" name="phone" value="{{ old('phone') ? old('phone') : $user->phone }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label class="required">Address line 1</label>
                        <input required class="form-control" type="text" name="address_line_1" value="{{ old('address_line_1') ? old('address_line_1') : $user->address_line_1 }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label>Address line 2 <small class="text-muted">(optional)</small></label>
                        <input class="form-control" type="text" name="address_line_2" value="{{ old('address_line_2') ? old('address_line_2') : $user->address_line_2 }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="required">County</label>
                        <input required class="form-control" type="text" name="county" value="{{ old('county') ? old('county') : $user->county }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label class="required">Town</label>
                        <input required class="form-control" type="text" name="town" value="{{ old('town') ? old('town') : $user->town }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label>Post code <small class="text-muted">(optional)</small></label>
                        <input class="form-control" type="text" name="post_code" value="{{ old('post_code') ? old('post_code') : $user->post_code }}">
                    </div>

                    <div class="form-group col-md-12">
                        <label>Tools <small class="text-muted">(optional)</small></label>
                        <textarea class="form-control" type="text" name="tools" >{{ old('tools') ? old('tools') : $user->tools }}</textarea>
                    </div>
					
					<div class="form-group col-md-12">
                        <label>More info <small class="text-muted">(500 Charcters Max)</small></label>
                        <textarea id="ckeditor-description" class="form-control" maxlength="255"  type="text" name="more_info" >{{ old('more_info') ? old('more_info') : $user->more_info }}</textarea>
                    </div>
                </div>
                <div class="box-footer">

                    <button type="submit" class="btn btn-danger"><span class="ladda-label"><i class="fa fa-save"></i> {{ trans('backpack::base.save') }}</span></button>
                    <a href="{{ backpack_url() }}" class="btn btn-default"><span class="ladda-label">{{ trans('backpack::base.cancel') }}</span></a>

                </div>
            </div>

        </form>

    </div>
</div>
@endsection


@section('after_scripts')

<script src="/vendor/backpack/ckeditor/ckeditor.js"></script>
<script src="/vendor/backpack/ckeditor/adapters/jquery.js"></script>
<script>
    jQuery(document).ready(function($) {
        $('#ckeditor-description').ckeditor({
            "filebrowserBrowseUrl": "/admin/elfinder/ckeditor",
            "extraPlugins" : 'oembed,widget',
			
        });
    });
	
	jQuery(document).ready(function($) {
		CKEDITOR.instances['ckeditor-description'].on('key', function( evt ){
			var data = evt.editor.getData();
			var lengths = data.replace(/<[^>]*>|\s/g, '').length
			console.log(evt.data.keyCode);
			if(lengths > 500 && evt.data.keyCode != '8'){
				return false;
			}
			/*if (evt.editor.getData().length > 10) {
             editor.setData(editor.getData().substring(0, maximumLength ));
           }*/
		});
	});
	
</script>



@endsection