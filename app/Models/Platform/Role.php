<?php
namespace App\Models\Platform;

use Illuminate\Database\Eloquent\Model;

class Role extends Model {

    protected $table = 'mgt_roles'; 
	
    //public $timestamps = false;
    protected $primaryKey = 'role_id'; // or null
    //public $incrementing = false;
	
	const CREATED_AT = 'role_created_at';
	const UPDATED_AT = 'role_updated_at';
	
	public function created_by(){
        return $this->belongsTo('App\User', 'role_created_by', 'user_id');
    }
	
	
	//Set Role To App
	public static function setRoleToApp($roles, $appId){
		//Clean Role App
		Role::cleanRoleForApp($appId);
		if($roles){
			foreach($roles as $role_id){
				\DB::table("mgt_role_to_app")->insert([
					'role_id' => $role_id,
					'app_id' => $appId,
				]);
			}
		}
	}
	
	//Clean Role for App
	public static function cleanRoleForApp($appId){
		\DB::table("mgt_role_to_app")->where("app_id", $appId)->delete();
	}
	
	//Set Role To Permissions
	public static function setRoleToPermission($roles, $permId){
		Role::cleanRoleForPermissions($permId);
		if($roles){
			foreach($roles as $role_id){
				\DB::table("mgt_role_to_permissions")->insert([
					'role_id' => $role_id,
					'perm_id' => $permId,
				]);
			}
		}
	}
	
	//Clean Role for Permissions
	public static function cleanRoleForPermissions($permId){
		\DB::table("mgt_role_to_permissions")->where("perm_id", $permId)->delete();
	}
	
	
}