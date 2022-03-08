<?php
namespace App\Http\Controllers\Epkb;

use App\Http\Controllers\Controller;
use App\Models\Epkb\Member;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    private $title = "Management User";

    public function __construct(){
		
    }

    /**
     * Show the application dashboard.
     */
    public function index(){
      $Account = Member::whereNull("deleted_at")->orderBy("created_at", "DESC");

      if(\Request::get('nama')){
          $Account = $Account->whereRaw("UPPER(nama) like ? ", "%".strtoupper(trim(\Request::get('nama')))."%");
      }
      if(\Request::get('email')){
        $Account = $Account->whereRaw("UPPER(user_mail) like ? ", "%".strtoupper(trim(\Request::get('email')))."%");
      }
      if(\Request::get('status')){
        $Account = $Account->where("status", \Request::get('status'));
      }

      $title =$this->title;
      $sub_title ="List Akun Member Terdaftar";

      $Account =$Account->paginate(config('app.perpage'));

      return view('epkb.member.index', compact('Account', 'title', 'sub_title'));
    }

    /*
    * Detail
    */
    public function detail($id){
      $Account = Member::find($id);
      
      $title =$this->title;
      $sub_title ="Detail Akun Member";

      return view('epkb.member.detail', compact('Account', 'title', 'sub_title'));
  }

  /*
	* Edit
	*/
	public function edit($id){
      $Account = Member::find($id);
      $title = $this->title;
      $sub_title ="Edit Akun Member > ".$Account->nama;

      return view('epkb.member.edit', compact('Account', 'title', 'sub_title'));
  }

  /*
  * Update
  */
  public function update($id){
    $input = \Request::all();
    $Account = Member::find($id);
    if (isset($Account->user_id)){
        $email = trim($input['user_mail']);
        $status = trim($input['status']);

        //Check Email
        $AccountEmail = Member::whereNotIn('user_id',[$id])->where("user_mail", $email)->first();
        if(isset($AccountEmail->user_id)){
            \Session::flash("error", "Perubahan Akun Member GAGAL disimpan. <b>Email: ".$email."</b> tidak tersedia, silahkan menggunakan email lain!");
            return redirect()->route('edit_member', ['id'=>$id]);
        }else{
          $password = trim($input['password']);
          if($password){
            $Account->user_pass = md5($email.$password);
          }

          $Account->user_mail = $email;
          $Account->status = $status;
          $Account->updated_at = date("Y-m-d H:i:s", time());

          if($Account->save()){
              \Session::flash("success", "Perubahan Akun Member <b>".$Account->nama."</b> BERHASIL disimpan...");
              return redirect()->route('member');
          }else{
              \Session::flash("error", "Perubahan Akun Member  GAGAL disimpan!");
              return redirect()->route('edit_member', ['id'=>$id]);
          }
        }
    }else{
        \Session::flash("error", "Perubahan Akun Member GAGAL disimpan!");
        return redirect()->route('edit_member', ['id'=>$id]);
    }
  }

  /*
  * Delete
  */
  public function destroy($id){
    $input = \Request::all();
    $Account = Member::find($id); 
    if (isset($Account->user_id)){
        $subject = $Account->nama;

        $Account->deleted_at = date("Y-m-d H:i:s", time());

        if($Account->save()){
            \Session::flash("success", "Akun Member '".$subject."' BERHASIL dihapus...");
            return redirect()->route('member');
        }else{
            \Session::flash("error", "Akun Member '".$subject."' GAGAL dihapus!");
            return redirect()->route('member');
        }

    }else{
        \Session::flash("error", "Akun Member GAGAL dihapus!");
        return redirect()->route('member');
    }
  }


}
