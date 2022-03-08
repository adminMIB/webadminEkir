<?php
namespace App\Models\Platform;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Permissions extends Model {

    protected $table = 'mgt_permissions'; 
	
    //public $timestamps = false;
    protected $primaryKey = 'perm_id'; // or null
    //public $incrementing = false;
	
	const CREATED_AT = 'perm_created_at';
    const UPDATED_AT = 'perm_updated_at';
	
	private $permission =['view', 'create', 'update', 'delete'];
	private $CRUD =['READ', 'CREATE', 'UPDATE', 'DELETE'];
	
	public static function can($perm_name){		
		if(User::isSuperuser()){
			return true;
		}
		
		$Perm =Permissions::where("perm_name", $perm_name)->first();
		if($Perm){
			return Permissions::isRole2PermissionGranted(\Session::get('user')->user_role, $Perm->perm_id);
		}
		
		return false;
	}


	public static function canUpdate($slug){		
		if(User::isSuperuser()){
			return true;
		}
		
		$Perm =Permissions::where("perm_name", "update-".$slug)->first();
		if($Perm){
			return true;
		}
		
		return false;
	}

	public static function isRole2AppGranted($role_id, $app_id){
		$countRoleToApp = \DB::table("mgt_role_to_app")
			->where("role_id", $role_id)
			->where("app_id", $app_id)
			->count("role_id");

		return $countRoleToApp > 0 ? true : false;
	}
	public static function isRole2PermissionGranted($role_id, $perm_id){
		$countRoleToPerm = \DB::table("mgt_role_to_permissions")
			->where("role_id", $role_id)
			->where("perm_id", $perm_id)
			->count("role_id");
		//dd($countRoleToPerm);

		return $countRoleToPerm > 0 ? true : false;
	}

	
	/*
	* User Permissions By Role
	*/
	public static function userPermissionsByRole(){
		$role_id = \Session::get('user')->account_role;
		$isSuperadmin = $role_id == "1" ? true : false;
		
		if($isSuperadmin){
			$sQ = "SELECT 
						APP.app_id, 
						APP.app_name,
						APP.app_slug,
						MAX(CASE WHEN PERM.perm_crud='CREATE' THEN '1' ELSE '0' END) AS \"perm_create\",
						MAX(CASE WHEN PERM.perm_crud='READ' THEN '1' ELSE '0' END) AS \"perm_read\",
						MAX(CASE WHEN PERM.perm_crud='UPDATE' THEN '1' ELSE '0' END) AS \"perm_update\",
						MAX(CASE WHEN PERM.perm_crud='DELETE' THEN '1' ELSE '0' END) AS \"perm_delete\"
					FROM mgt_app APP
						JOIN mgt_permissions PERM ON 
						(
							APP.app_slug=REPLACE(PERM.perm_name, 'view-', '') OR 
							APP.app_slug=REPLACE(PERM.perm_name, 'create-', '') OR
							APP.app_slug=REPLACE(PERM.perm_name, 'update-', '') OR
							APP.app_slug=REPLACE(PERM.perm_name, 'delete-', '')
						)
				WHERE APP.app_active='1' 
				GROUP BY APP.app_id,APP.app_name,APP.app_slug
				ORDER BY APP.app_name";
		}else{
			$sQ = "SELECT 
					APP.app_id, 
					APP.app_name,
					APP.app_slug,
					MAX(CASE WHEN PERM.perm_crud='CREATE' THEN '1' ELSE '0' END) AS \"perm_create\",
					MAX(CASE WHEN PERM.perm_crud='READ' THEN '1' ELSE '0' END) AS \"perm_read\",
					MAX(CASE WHEN PERM.perm_crud='UPDATE' THEN '1' ELSE '0' END) AS \"perm_update\",
					MAX(CASE WHEN PERM.perm_crud='DELETE' THEN '1' ELSE '0' END) AS \"perm_delete\"
				FROM mgt_app APP
					JOIN mgt_permissions PERM ON 
					(
						APP.app_slug=REPLACE(PERM.perm_name, 'view-', '') OR 
						APP.app_slug=REPLACE(PERM.perm_name, 'create-', '') OR
						APP.app_slug=REPLACE(PERM.perm_name, 'update-', '') OR
						APP.app_slug=REPLACE(PERM.perm_name, 'delete-', '')
					)
					JOIN mgt_role_to_app R2A ON R2A.app_id=APP.app_id
			WHERE APP.app_active='1' AND R2A.role_id='".$role_id."
			GROUP BY APP.app_id,APP.app_name,APP.app_slug
			ORDER BY APP.app_name";
		}
		
		$UserPermissions =\DB::select($sQ);
		
		return $UserPermissions;
	}
	
	//Clear Permissions for the slug
	public static function cleanPermissionsForSlug($slug){
		$P = Permissions::where("perm_name", "view-".$slug)
			->orWhere("perm_name", "create-".$slug)
			->orWhere("perm_name", "update-".$slug)
			->orWhere("perm_name", "delete-".$slug)
			->get();
		if(!$P->isEmpty()){
			foreach($P as $perm){
				\DB::table("mgt_role_to_permissions")->where('perm_id', $perm->perm_id)->delete();
				Permissions::where("perm_id", $perm->perm_id)->delete();
			}
		}
	}
	
	//Create Permissions roles
	public static function createPermissionRoles($roles, $slug){
		$crudPrefixs = with(new Permissions)->permission;
		$CRUD = with(new Permissions)->CRUD;
		foreach($crudPrefixs as $key=>$prefix){
			$perm_name = $prefix."-".$slug;
			$Perm = Permissions::where('perm_name', $perm_name)->first();
			if(!isset($Perm->perm_id)){
				$Perm = new Permissions;
				$Perm->perm_name = $perm_name;
				$Perm->perm_crud = isset($CRUD[$key]) ? $CRUD[$key] : null;
				
				$Perm->save();
			}
			
			//Permissions to Role
			\Role::setRoleToPermission($roles, $Perm->perm_id);
		}
	}
	
	
}