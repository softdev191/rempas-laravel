<?php

namespace App\Models;

use Backpack\CRUD\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class TuningCreditGroup extends Model
{
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'name', 'is_default', 'group_type'
    ];


    /**
     * Get the tuning credit tire that owns the tuning credit group.
     */
    public function tuningCreditTires()
    {
        return $this->belongsToMany('App\Models\TuningCreditTire', 'tuning_credit_group_tuning_credit_tire', 'tuning_credit_group_id', 'tuning_credit_tire_id')->withPivot('from_credit', 'for_credit');
    }


	//changes
		public function set_default_tier(){
			$id = $this->id;
			$script = "<script>
							function set_default_tier(id){
								$.ajax(
								   {
									  type:'POST',
									  url:'/admin/set_default_tier',
									  data:'id='+id,
									  success: function(data){
											location.reload();
									  }
								   }
								);
							}
                        </script>";
            if ($this->group_type == 'evc') {
                $script = "<script>
							function set_default_tier(id){
								$.ajax(
								   {
									  type:'POST',
									  url:'/admin/set_evc_default_tier',
									  data:'id='+id,
									  success: function(data){
											location.reload();
									  }
								   }
								);
							}
                        </script>";
            }
			$checked ='';
			if($this->set_default_tier == 1){
				$checked = "checked ='checked'";
			}
			return $script.'<input type="radio" onclick="set_default_tier('.$id.')" name="set_default_tier" '.$checked.' /> ';
		}

	public function tuningCreditTiresWithPivot()
    {
        return $this->belongsToMany('App\Models\TuningCreditTire', 'tuning_credit_group_tuning_credit_tire', 'tuning_credit_group_id', 'tuning_credit_tire_id')->select(['id'])->withPivot('from_credit', 'for_credit');
    }



    /**
     * Get the company that owns tuning credit group.
     */
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    /**
     * Get the created at.
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('d M Y g:i A');
    }

    /**
     * Get the updated at.
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value) {
        return \Carbon\Carbon::parse($value)->format('d M Y g:i A');
    }
}
