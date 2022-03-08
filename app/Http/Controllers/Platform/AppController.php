<?php
namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Platform\Applications;
use App\Models\Platform\Modules;

class AppController extends Controller
{
    public function __construct(){
		
    }

    /**
     * List Applications
     */
    public function index(){
		$Applications = Applications::orderBy("app_created_at", "DESC");
		if(\Request::get('child-of')){
			$Applications =$Applications->where("app_parent", \Request::get('child-of'));
		}
		if(\Request::get('name')){
			$Applications =$Applications->whereRaw("UPPER(app_name) like '%".strtoupper(trim(\Request::get('name')))."%'");
		}
		if(\Request::get('group')){
			$Applications =$Applications->where("app_group", \Request::get('group'));
		}
		
		if(\Request::get('export-xls')){
			$Applications = $Applications->get();			
			return \Excel::download(new \App\Models\Exports\Excel('Application', $Applications, 'exports.excel.applications'), 'applications.xlsx');
			
		}else{
			$Applications =$Applications->paginate(config('app.perpage'));

			$title ="Platform";
			$sub_title ="Applications";

			return view('platform.applications.index', compact('Applications', 'title', 'sub_title'));
		}
    }
	
	/*
	* View Detail
	* Read
	*/
	public function read($id){
		$App = Applications::find($id);
		$Modules = Modules::getModules($App->app_id);
		
		$title ="Platform";
		$sub_title ="Detail Application";

		return view('platform.applications.detail', compact('App', 'Modules', 'title', 'sub_title'));
	}
	
	/*
	* Create
	*/
	public function create(){
		$AppParents =Applications::orderBy('app_name')->get()->pluck('app_name', 'app_id');
		
		$title ="Platform";
		$sub_title ="Create New Application";

		return view('platform.applications.form', compact('AppParents', 'title', 'sub_title'));
	}
	
	/*
	* Store
	*/
	public function store(){
		$input = \Request::all();
		
		$App = new Applications;
		$App->app_name = trim($input['name']);
		$App->app_slug = trim($input['slug']);
		$App->app_active = $input['status'];
		$App->app_order = $input['order'];
		$App->app_type = '0';
		$App->app_group = $input['group'];
		$App->app_created_by = \Session::get('user')->user_id;
		
		$App->app_icon = isset($input['icon']) ? $input['icon'] : null;
		$App->app_parent = isset($input['child_of']) ? intval($input['child_of']) : 0;
		
		if($App->save()){			
			\Session::flash("success", "<b>".$App->app_name."</b> created SUCCESSFULLY...");
			return redirect()->route('application');
		}else{
			\Session::flash("error", "An ERROR occured!");
			return redirect()->route('create_application');
		}
		
	}	
	
	/*
	* Edit
	*/
	public function edit($id){
		$AppParents =Applications::orderBy('app_name')->get()->pluck('app_name', 'app_id');
		$AssignTo =[];
		$App = Applications::find($id);
		Applications::assignTo($id, $AssignTo);

		$title ="Platform";
		$sub_title ="Edit Application";
		
		return view('platform.applications.form', compact('App', 'AssignTo', 'AppParents', 'title', 'sub_title'));
	}
	
	/*
	* Update
	*/
	public function update($id){
		$input = \Request::all();
		
		$App =\Applications::where("app_id", $id)->first();
		$App->app_name = trim($input['name']);
		//$App->app_slug = trim($input['slug']);
		$App->app_active = $input['status'];
		$App->app_order = $input['order'];
		$App->app_type = '0';
		$App->app_group = $input['group'];
		$App->app_updated_at =date("Y-m-d H:i:s", time());
		$App->app_updated_by = \Session::get('user')->user_id;
		
		$App->app_icon = isset($input['icon']) ? $input['icon'] : null;
		$App->app_parent = isset($input['child_of']) ? intval($input['child_of']) : 0;
		
		if($App->save()){
			\Session::flash("success", "<b>".$App->app_name."</b> updated SUCCESSFULLY...");
			return redirect()->route('application');
		}else{
			\Session::flash("error", "An ERROR occured!");
			return redirect()->route('create_application');
		}
	}
	
	/*
	* Delete
	*/
	public function destroy($id){
		$Application = Applications::where("app_id", $id)->first();
		if(Applications::where("app_id", $id)->delete()){
			//Clean All Roles and Permissions
			\Role::cleanRoleForApp($id);
			\Permissions::cleanPermissionsForSlug($Application->app_slug);
			
			\Session::flash("success", "<b>".$Application->app_name."</b> deleted SUCCESSFULLY...");
			return redirect()->route('application');
		}else{
			\Session::flash("error", "An ERROR occured!");
			return redirect()->route('application');
		}
		
	}
}
