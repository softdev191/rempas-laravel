<!-- select2 -->

<div @include('crud::inc.field_wrapper_attributes') >
    <label>{!! $field['label'] !!}</label>
    @include('crud::inc.field_translatable_icon')
    <?php $entity_model = $crud->model; ?>
    <select
        name="{{ $field['name'] }}@if (isset($field['allows_multiple']) && $field['allows_multiple']==true)[]@endif"
        @include('crud::inc.field_attributes')
        @if (isset($field['allows_multiple']) && $field['allows_multiple']==true)multiple @endif>
            <option value="">Select tuning type</option>
            @if (count($field['options']))
                @foreach ($field['options'] as $key => $value)
                    <option value="{{ $key }}"
                        @if (isset($field['value']) && ($key==$field['value'] || (is_array($field['value']) && in_array($key, $field['value'])))
                            || ( ! is_null( old($field['name']) ) && old($field['name']) == $key))
                             selected
                        @endif
                    >{{ $value }}</option>
                @endforeach
            @endif
    </select>

    {{-- HINT --}}
    @if (isset($field['hint']))
        <p class="help-block">{!! $field['hint'] !!}</p>
    @endif

</div>
<!-- select2 -->
<div class="form-group col-xs-12">
    @php 
        $entity_model = 'TuningTypeOptions'; 
        if(isset($entry) && !empty($entry->getKey())){
            $tuningTypeOptions =  $entry->tuningType->tuningTypeOptions;
        } 
    @endphp
    <div class="tuning-type-option">
        @if(@$tuningTypeOptions)
            <label>Tuning type options <small class="text-muted">(optional)</small></label>
            <div class="row">
                @foreach ($tuningTypeOptions as $connected_entity_entry)
                    <div class="col-sm-12">
                        <div class="checkbox">
                          <label>
                            @if($connected_entity_entry->fileServices()->where('id', $entry->getKey())->exists())
                                <input type="checkbox"
                                  name="tuning_type_options[]"
                                  value="{{ $connected_entity_entry->getKey() }}"
                                  checked="checked" 
                                > 
                            @else
                                <input type="checkbox"
                                  name="tuning_type_options[]"
                                  value="{{ $connected_entity_entry->getKey() }}"
                                > 
                            @endif
                            {!! $connected_entity_entry->label !!}
                          </label>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@push('crud_fields_scripts')
    <script type="text/javascript">
        getTuningOptions = function(tuningTypeId){
        	if(tuningTypeId > 0){
        		var route = "{{ route('get.tuning.type.options') }}";
	            var htmlData = '';
	            route = route+"/"+tuningTypeId;
	            $.ajax({
	                url: route,
	                type: 'GET',
	                dataType: 'html',
	                async: false,
	                success: function (data) {
	                    $('.tuning-type-option').html(data);
	                }
	            });
        	}
        }

        jQuery("document").ready(function () {
        	var tuningTypeId = $('select[name=tuning_type_id] option:selected').val();
            $("#tuningType").on("change", function () {
                var tuningTypeId = $("#tuningType").val();
                getTuningOptions(tuningTypeId);
            });
            getTuningOptions(tuningTypeId);

        });
    </script>
@endpush
 