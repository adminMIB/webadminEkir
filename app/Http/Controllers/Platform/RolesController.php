<?php
namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Platform\Role;

class RolesController extends Controller
{
    public function __construct(){
		
    }

    /**
     * List of
     */
    public function index(){
		$Role = Role::where("role_id", "!=", "1")->orderBy("role_updated_at", "desc");
		if(\Request::get('name')){
			$Role =$Role->whereRaw("UPPER(role_name) like '%".strtoupper(trim(\Request::get('name')))."%'");
		}
		
		
		$Role =$Role->paginate(config('app.perpage'));
		return view('platform.role.index', compact('Role'));
		
    }
	
	/*
	* Create
	*/
	public function create(){		
		return view('platform.role.form');
	}
	
	/*
	* Store
	*/
	public function store(){
		$input = \Request::all();
		
		$Role = new Role;
		$Role->role_name = trim($input['name']);
		$Role->role_display_name = trim($input['display_name']);
		$Role->role_active = $input['status'];
		$Role->role_order = $input['menu_order'];
		$Role->role_created_by = \Session::get('user')->user_id;
		
		if($Role->save()){			
			\Session::flash("success", "<b>".$Role->role_display_name."</b> created SUCCESSFULLY...");
			return redirect()->route('role');
		}else{
			\Session::flash("error", "An ERROR occured!");
			return redirect()->route('create_role');
		}		
	}	
	
	/*
	* Edit
	*/
	public function edit($id){
		$Role = Role::find($id);

		return view('platform.role.form', compact('Role'));
	}
	
	/*
	* Update
	*/
	public function update($id){
		$input = \Request::all();
		
		$Role =\Role::where("role_id", $id)->first();
		$Role->role_name = trim($input['name']);
		$Role->role_display_name = trim($input['display_name']);
		$Role->role_active = $input['status'];
		$Role->role_order = $input['menu_order'];
		$Role->role_updated_at =date("Y-m-d H:i:s", time());
		$Role->role_updated_by = \Session::get('user')->user_id;
		
		if($Role->save()){
			\Session::flash("success", "<b>".$Role->role_display_name."</b> updated SUCCESSFULLY...");
			return redirect()->route('role');
		}else{
			\Session::flash("error", "An ERROR occured!");
			return redirect()->route('edit_role');
		}
	}
	
	/*
	* Delete
	*/
	public function destroy($id){
		$Role = Role::find($id);
		if(Role::where("role_id", $id)->delete()){			
			\Session::flash("success", "<b>".$Role->role_display_name."</b> deleted SUCCESSFULLY...");
			return redirect()->route('role');
		}else{
			\Session::flash("error", "An ERROR occured!");
			return redirect()->route('role');
		}
		
	}

	/*
	* Role To Applications
	* ====================
	*/
	public function role2AppList(){
		$Role = Role::where("role_id", "!=", "1")->orderBy("role_name");
		if(\Request::get('role')){
			$Role =$Role->where("role_id", \Request::get('role'));
		}
		
		$Applications = \Applications::orderBy("app_name")->get();
		
		
		$Role =$Role->paginate(1);
		return view('platform.role2application.index', compact('Role', 'Applications'));
	}
	public function updateRole2App($role_id){
		$input = \Request::all();
		//Clear Role
		\DB::table("web_role_to_app")->where("role_id", $role_id)->delete();

		//Create New
		if(isset($input['role2app'])){
			foreach($input['role2app'] as $app_id){
				\DB::table("web_role_to_app")->insert(['role_id'=>$role_id, 'app_id'=>$app_id]);
			}
		}
		//\Session::flash("success", "Role to Application updated SUCCESSFULLY...");
		return \Redirect::back()->with('success','Role to Application updated SUCCESSFULLY...');
	}

	/*
	* Role to Permission
	* ==================
	*/
	public function role2PermissionList(){
		$Role = Role::where("role_id", "!=", "1")->orderBy("role_name");
		if(\Request::get('role')){
			$Role =$Role->where("role_id", \Request::get('role'));
		}
		
		if(\Request::get('permission')){
			$Permissions =\Permissions::whereRaw("UPPER(perm_name) like '%".strtoupper(trim(\Request::get('permission')))."%'")
				->get();
		}else{
			$Permissions = \Permissions::all();
		}
		
		
		$Role =$Role->paginate(1);
		return view('platform.role2permission.index', compact('Role', 'Permissions'));
	}
	public function updateRole2Permission($role_id){
		$input = \Request::all();

		dd($input);

		//Clear Role
		\DB::table("web_role_to_app")->where("role_id", $role_id)->delete();

		//Create New
		if(isset($input['role2app'])){
			foreach($input['role2app'] as $app_id){
				\DB::table("web_role_to_app")->insert(['role_id'=>$role_id, 'app_id'=>$app_id]);
			}
		}
		//\Session::flash("success", "Role to Application updated SUCCESSFULLY...");
		return \Redirect::back()->with('success','Role to Application updated SUCCESSFULLY...');
	}
}
