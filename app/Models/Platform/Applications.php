<?php
namespace App\Models\Platform;

use Illuminate\Database\Eloquent\Model;

class Applications extends Model {

    protected $table = 'mgt_app'; 
	
    //public $timestamps = false;
    protected $primaryKey = 'app_id'; // or null
    //public $incrementing = false;
	
	const CREATED_AT = 'app_created_at';
    const UPDATED_AT = 'app_updated_at';
	
	public static function boot(){
        parent::boot();

        self::creating(function($model){
            // slug harus unik tidak boleh ada yang sama			
			$model->app_slug = Applications::generateSlug($model->app_slug, $model->app_id);
        });

        self::created(function($model){
			//Assign App To
			$roles = (\Request::input('assign_to')) ? \Request::input('assign_to') : null;
			\Role::setRoleToApp($roles, $model->app_id);
			
			//Create Permissions Roles
			\Permissions::createPermissionRoles($roles, $model->app_slug);
			
        });

        self::updating(function($model){
			//Clean Permissions
			if(\Request::input('slug_old') && \Request::input('slug_old') != \Request::input('slug')){
				\Permissions::cleanPermissionsForSlug(\Request::input('slug_old'));
			}
			
			// slug harus unik tidak boleh ada yang sama			
            $model->app_slug = Applications::generateSlug($model->app_slug, $model->app_id);
        });

        self::updated(function($model){
            //Assign App To
			$roles = (\Request::input('assign_to')) ? \Request::input('assign_to') : null;
			\Role::setRoleToApp($roles, $model->app_id);
			
			//Create Permissions Roles
			\Permissions::createPermissionRoles($roles, $model->app_slug);
        });

        self::deleting(function($model){
            // ... code here
        });

        self::deleted(function($model){
            // ... code here
			//Clean All Roles and Permissions
			\Role::cleanRoleForApp($id);
			\Permissions::cleanPermissionsForSlug($Application->app_slug);
        });
    }
	
	//Generate Slug
	public static function generateSlug($slug, $appId){
		$count = \DB::table("mgt_app")->select('app_slug')
			->where("app_id", "<>", $appId)
			->where("app_slug", $slug)->count('app_slug');
		
		return ($count) == 0 ? $slug : $slug."-".($count + 1);
	}
	
	
	//Group
	public function group(){
        return $this->belongsTo(Group::class, 'app_group', 'group_id');
    }
    //User
    public function created_by(){
        return $this->belongsTo('App\User', 'app_created_by', 'user_id');
    }
	public function updated_by(){
        return $this->belongsTo('App\User', 'app_updated_at', 'user_id');
    }
	//ChildOf
	public function chidlOf(){
		return $this->belongsTo(Applications::class, 'app_parent', 'app_id');
	}
	
	//Assign To
	public static function assignTo($app_id, &$arrResult=[]){
		$Roles =\DB::table("mgt_roles")
			->join("mgt_role_to_app", "mgt_role_to_app.role_id", "=", "mgt_roles.role_id")
			->where("mgt_role_to_app.app_id", $app_id)
			->get();
		
		$sAssignTo ="";
		if(!$Roles->isEmpty()){
			foreach($Roles as $role){
				$arrResult[] = $role->role_id;
				$sAssignTo .='<span class="badge badge-pill badge-success">'.$role->role_display_name."</span>&nbsp;";
			}
		}
		
		return trim($sAssignTo);
	}
	
}