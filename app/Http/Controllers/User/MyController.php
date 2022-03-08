<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;

class MyController extends Controller
{
    public function __construct(){
		
    }

    /**
     * Show the application dashboard.
     */
    public function profile(){
		$UserPermission =\Permissions::userPermissionsByRole();
		return view('user.profile', compact('UserPermission'));
    }
}
