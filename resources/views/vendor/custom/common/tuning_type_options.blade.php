@if($tuningTypeOptions)
	<label>
		Tuning type options <small class="text-muted">(optional)</small>
	</label>
	<div class="row">
		@foreach($tuningTypeOptions as $tuningTypeOption)
			<div class="col-sm-12">
	            <div class="checkbox" title="{{ $tuningTypeOption->tooltip }}">
	              <label>
	                <input type="checkbox"
	                  name="tuning_type_options[]"
	                  value="{{ $tuningTypeOption->id }}"> 
	                  {{ $tuningTypeOption->label }} ({{ $tuningTypeOption->credits }} credits)
	              </label>
	            </div>
	        </div>
		@endforeach
	</div>
@endif
