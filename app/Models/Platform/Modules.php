<?php
namespace App\Models\Platform;

use Illuminate\Database\Eloquent\Model;

class Modules extends Model {

    protected $table = 'web_modules'; 
	
    //public $timestamps = false;
    protected $primaryKey = 'mod_id'; // or null
    //public $incrementing = false;
	
	const CREATED_AT = 'mod_created_at';
    const UPDATED_AT = 'mod_updated_at';
	
	/**
    * User
    */
    public function created_by(){
        return $this->belongsTo('App\User', 'mod_created_by', 'user_id');
    }
	public function updated_by(){
        return $this->belongsTo('App\User', 'mod_updated_at', 'user_id');
    }
	
	public static function getModules($app_id){
		$Modules = Modules::join("web_app_to_mod", "web_app_to_mod.mod_id", "=", "web_modules.mod_id")
			->where("web_app_to_mod.app_id", $app_id)		
			->get();
			
		return $Modules;
	}
	
	
}