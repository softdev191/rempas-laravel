<?php

namespace App\Http\Controllers\Admin;

//use Backpack\CRUD\app\Http\Controllers\CrudController;
use App\Http\Controllers\MasterController;
// VALIDATION: change the requests to match your own file names if you need form validation
use App\Http\Requests\SliderManagerRequest as StoreRequest;
use App\Http\Requests\SliderManagerRequest as UpdateRequest;
use Backpack\CRUD\CrudPanel;

/**
 * Class SliderManagerCrudController
 * @package App\Http\Controllers\Admin
 * @property-read CrudPanel $crud
 */
//class SliderManagerCrudController extends CrudController
class SliderManagerCrudController extends MasterController
{
    public function setup()
    {
        /*
        |--------------------------------------------------------------------------
        | CrudPanel Basic Information
        |--------------------------------------------------------------------------
        */
        $this->crud->setModel('App\Models\SliderManager');
        $this->crud->setRoute(config('backpack.base.route_prefix') . '/slidermanager');
        $this->crud->setEntityNameStrings('slidermanager', 'slider Managers');

        /*
        |--------------------------------------------------------------------------
        | CrudPanel Configuration
        |--------------------------------------------------------------------------
        */

        // TODO: remove setFromDb() and manually define Fields and Columns
        //$this->crud->setFromDb();
		
	   // Columns
        $this->crud->addColumn(['name' => 'title', 'type' => 'text', 'label' => 'Title']);

        // Fields
        $this->crud->addField(['name' => 'title', 'type' => 'text', 'label' => 'Title']);
		
		// Columns
        $this->crud->addColumn(['name' => 'description', 'type' => 'text', 'label' => 'Description']);

        // Fields
        $this->crud->addField(['name' => 'description', 'type' => 'text', 'label' => 'Description']);
		
		$this->crud->addField([
            'name' => 'image',
            'label' => "Image",
            'type' => 'preview_file',
            'upload' => true,
            'wrapperAttributes'=>['class'=>'form-group col-md-6 col-xs-12']
        ]);
		
		// Columns
        $this->crud->addColumn([
			'label' => "Image", 
		    'name' => 'image', 
			'type' => "model_function",
			'function_name' => 'showImage',
		    
		]);
		
		
		$this->crud->addField(['name' => 'button_text', 'type' => 'text', 'label' => 'Button Text']);
		$this->crud->addField(['name' => 'button_link', 'type' => 'text', 'label' => 'Button Link']);
		

        // add asterisk for fields that are required in SliderManagerRequest
        $this->crud->setRequiredFields(StoreRequest::class, 'create');
        $this->crud->setRequiredFields(UpdateRequest::class, 'edit');
    }

    public function store(StoreRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::storeCrud($request);
		$slider = $this->crud->entry;
		if($request->hasFile('image')){
			if($request->file('image')->isValid()){
				$file = $request->file('image');
				$filename = time() . '.' . $file->getClientOriginalExtension();
				$file->move(public_path('/uploads/logo'), $filename);
				$slider->image = $filename;
				$slider->save();
			}
		}
		else {
			$slider->image = '';
			$slider->save();
		}
		
		
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }

    public function update(UpdateRequest $request)
    {
        // your additional operations before save here
        $redirect_location = parent::updateCrud($request);
		$slider = $this->crud->entry;
		if($request->hasFile('image')){
			if($request->file('image')->isValid()){
				$file = $request->file('image');
				$filename = time() . '.' . $file->getClientOriginalExtension();
				$file->move(public_path('/uploads/logo'), $filename);
				$slider->image = $filename;
				$slider->save();
			}
		}
		else {
			$slider->save();
		}
		
        // your additional operations after save here
        // use $this->data['entry'] or $this->crud->entry
        return $redirect_location;
    }
}
