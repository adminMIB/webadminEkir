<?php
namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct(){
		
    }

    /**
     * Show the application dashboard.
     */
    public function index(){
      $User = User::whereNotIn('user_id', ["1", \Session::get('user')->user_id])->orderBy("user_created_at", "DESC");

      if(\Request::get('subject')){
          $User = $User->whereRaw("UPPER(subject) like ? ", "%".strtoupper(trim(\Request::get('subject')))."%");
      }

      $title ="Adm/User Web Dashboard";
      $sub_title ="List Adm/User";

      $User =$User->paginate(config('app.perpage'));

      return view('users.index', compact('User', 'title', 'sub_title'));
    }

        /*
	* Create
	*/
	public function create(){
      $title ="Adm/User Web Dashboard";
      $sub_title ="Entri Adm/User Baru";
      return view('users.create', compact('title', 'sub_title'));
  }

  /*
  * Save
  */
  public function store(){
    $input = \Request::all();

    $display_name = trim($input['display_name']);
    $email = trim($input['user_email']);
    $user_phone = toPhoneNumberFromStr(trim($input['user_phone']));
    $user_status = trim($input['user_status']);
    $password = trim($input['password']);

    $hashPassword = Hash::make($password);

    //Check Email
    $User = User::where("user_email", $email)->first();
    if(isset($User->user_id)){
        \Session::flash("error", "Adm/User GAGAL disimpan. <b>Email: ".$email."</b> tidak tersedia, silahkan menggunakan email lain!");
        return redirect()->route('create_akun_user');
    }else{
      $User = new User;
      $User->user_id = \Illuminate\Support\Str::uuid()->toString();
      $User->user_login = $email;
      $User->user_pass = $hashPassword;
      $User->user_phone = $user_phone;
      $User->user_email = $email;
      $User->display_name = $display_name;
      $User->user_role = "3";
      $User->user_status = $user_status;
      $User->user_created_at = date("Y-m-d H:i:s", time());
      
      if($User->save()){            
          \Session::flash("success", "Adm/User <b>".$User->display_name."</b> BERHASIL disimpan...");
          return redirect()->route('akun_user');
      }else{
          \Session::flash("error", "An ERROR occured!");
          return redirect()->route('create_akun_user');
      }
    }
  }

  /*
	* Edit
	*/
	public function edit($id){
      $User = User::find($id);
      $title ="Adm/User";
      $sub_title ="Edit > ".$User->display_name;

      return view('users.edit', compact('User', 'title', 'sub_title'));
  }

  /*
  * Update
  */
  public function update($id){
    $input = \Request::all();
    $User = User::find($id);
    if (isset($User->user_id)){  
        $display_name = trim($input['display_name']);
        $email = trim($input['user_email']);
        $user_phone = toPhoneNumberFromStr(trim($input['user_phone']));
        $user_status = trim($input['user_status']);

        //Check Email
        $UserEmail = User::whereNotIn('user_id',[$id])->where("user_email", $email)->first();
        if(isset($UserEmail->user_id)){
            \Session::flash("error", "Adm/User GAGAL disimpan. <b>Email: ".$email."</b> tidak tersedia, silahkan menggunakan email lain!");
            return redirect()->route('edit_akun_user', ['id'=>$id]);
        }else{
          $password = trim($input['password']);
          if($password){
            $User->user_pass = Hash::make($password);
          }

          $User->user_login = $email;
          $User->user_phone = $user_phone;
          $User->user_email = $email;
          $User->display_name = $display_name;
          $User->user_status = $user_status;
          $User->user_updated_at = date("Y-m-d H:i:s", time());

          if($User->save()){
              \Session::flash("success", "Perubahan Adm/User <b>".$display_name."</b> BERHASIL disimpan...");
              return redirect()->route('akun_user');
          }else{
              \Session::flash("error", "Perubahan Adm/User GAGAL disimpan!");
              return redirect()->route('edit_akun_user', ['id'=>$id]);
          }
        }
    }else{
        \Session::flash("error", "Perubahan Adm/User GAGAL disimpan!");
        return redirect()->route('edit_akun_user', ['id'=>$id]);
    }
  }

  /*
  * Delete
  */
  public function destroy($id){
    $input = \Request::all();
    $User = User::find($id); 
    if (isset($User->user_id)){
        $subject = $User->display_name;

        if($User->delete()){
            \Session::flash("success", "Adm/User '".$subject."' BERHASIL dihapus...");
            return redirect()->route('akun_user');
        }else{
            \Session::flash("error", "Adm/User '".$subject."' GAGAL dihapus!");
            return redirect()->route('akun_user');
        }

    }else{
        \Session::flash("error", "Adm/User GAGAL dihapus!");
        return redirect()->route('akun_user');
    }
  }


}
