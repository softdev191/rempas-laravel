<!-- file input -->
<div class="hidden">
    <input type="hidden" name="uploaded_file" value="">
</div>
<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')

    @if(isset($field['prefix']) || isset($field['suffix'])) <div class="input-group"> @endif
        @if(isset($field['prefix'])) <div class="input-group-addon">{!! $field['prefix'] !!}</div> @endif
        <input
            type="file"
            name="{{ $field['name'] }}"
            value="{{ old($field['name']) ? old($field['name']) : (isset($field['value']) ? $field['value'] : (isset($field['default']) ? $field['default'] : '' )) }}"
            @include('crud::inc.field_attributes')
        >
        @if(isset($field['suffix'])) <div class="input-group-addon">{!! $field['suffix'] !!}</div> @endif
    @if(isset($field['prefix']) || isset($field['suffix'])) </div> @endif

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif
</div>

{{-- ########################################## --}}
{{-- Extra CSS and JS for this particular field --}}
{{-- If a field type is shown multiple times on a form, the CSS and JS will only be loaded once --}}
@if ($crud->checkIfFieldIsFirstOfItsType($field, $fields))

    {{-- FIELD CSS - will be loaded in the after_styles section --}}
    @push('crud_fields_styles')
        <link rel="stylesheet" href="{{ asset('vendor/adminlte/bower_components/bootstrap-fileinput-master/css/fileinput.min.css') }}">
    @endpush

    {{-- FIELD JS - will be loaded in the after_scripts section --}}
    @push('crud_fields_scripts')
        <script src="{{ asset('vendor/adminlte/bower_components/bootstrap-fileinput-master/js/fileinput.min.js') }}"></script>
        <script>
            $(document).ready(function(){
                $("input[type=file]").fileinput({
                    showUpload:false,
                    showRemove: false,
                    layoutTemplates: {footer: ''},
                    overwriteInitial: true,
                    @if(@$entry->logo != null)
                        initialPreview: [
                            '{{ asset("uploads/logo/" . @$entry->logo) }}',
                        ],
                        initialPreviewAsData: true, 
                        initialPreviewConfig: [
                            {caption: "", url: "", key: "{{ @$entry->id }}"},
                        ],
                    @endif
                });
            });
        </script>
    @endpush

@endif
{{-- End of Extra CSS and JS --}}