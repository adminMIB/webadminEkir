<?php
namespace App\Http\Controllers\Platform;

use App\Http\Controllers\Controller;
use App\Models\Platform\Group;

class GroupsController extends Controller
{
    public function __construct(){
		
    }

    /**
     * List Applications
     */
    public function index(){
		$title ="Platform";
		$sub_title ="List Group";
		
		$Groups = Group::orderBy("group_order");
		if(\Request::get('name')){
			$Groups =$Groups->whereRaw("UPPER(group_name) like '%".strtoupper(trim(\Request::get('name')))."%'");
		}
		
		
		$Groups =$Groups->paginate(config('app.perpage'));
		return view('platform.groups.index', compact('Groups', 'title', 'sub_title'));
		
    }
	
	/*
	* Create
	*/
	public function create(){	
		$title ="Platform";
		$sub_title ="Create New Group";

		return view('platform.groups.form', compact('title', 'sub_title'));
	}
	
	/*
	* Store
	*/
	public function store(){
		$input = \Request::all();
		
		$Group = new Group;
		$Group->group_name = trim($input['name']);
		$Group->group_order = intval($input['menu_order']);

		if($Group->save()){			
			\Session::flash("success", "<b>".$Group->group_name."</b> created SUCCESSFULLY...");
			return redirect()->route('group');
		}else{
			\Session::flash("error", "An ERROR occured!");
			return redirect()->route('create_group');
		}		
	}	
	
	/*
	* Edit
	*/
	public function edit($id){
		$Group = Group::find($id);

		return view('platform.groups.form', compact('Group'));
	}
	
	/*
	* Update
	*/
	public function update($id){
		$input = \Request::all();
		
		$Group =\Group::where("group_id", $id)->first();
		$Group->group_name = trim($input['name']);
		$Group->group_order = intval($input['menu_order']);
		
		if($Group->save()){
			\Session::flash("success", "<b>".$Group->group_name."</b> updated SUCCESSFULLY...");
			return redirect()->route('group');
		}else{
			\Session::flash("error", "An ERROR occured!");
			return redirect()->route('create_group');
		}
	}
	
	/*
	* Delete
	*/
	public function destroy($id){
		$Group = Group::find($id);
		if(Group::where("group_id", $id)->delete()){
			
			\Session::flash("success", "<b>".$Group->group_name."</b> deleted SUCCESSFULLY...");
			return redirect()->route('group');
		}else{
			\Session::flash("error", "An ERROR occured!");
			return redirect()->route('group');
		}
		
	}
}
