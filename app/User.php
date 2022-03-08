<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;
	
	protected $table = 'mgt_users'; 
	
    //public $timestamps = false;
    protected $primaryKey = 'user_id'; // or null
    public $incrementing = false;
	
	const CREATED_AT = 'user_created_at';
    const UPDATED_AT = 'user_updated_at';
	

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function boot(){
        parent::boot();

        //After Insert
        self::created(function($model){
            
			
        });
        //After Updated
        self::updated(function($model){
           
        });
    }

    public static function isSuperuser(){
        $role_id = \Session::get('user')->user_role;
        $isSuperadmin = $role_id == "1" ? true : false;
        
        return $isSuperadmin;
    }
    public static function isSuperuserOrAdminGosol(){
        $role_id = \Session::get('user')->user_role;
        $isSuperuserOrAdminGosol = ($role_id == "1" || $role_id == "4") ? true : false;
        
        return $isSuperuserOrAdminGosol;
    }

    public function role(){
        return $this->belongsTo('App\Models\Platform\Role', 'user_role', 'role_id');
    }

    public function Desa(){
        return $this->belongsTo('App\Models\Master\KelurahanDesa', 'user_kel_desa_id', 'keldes_id');
    }

    public function Status(){
        return $this->belongsTo('App\Models\Master\StatusAkun', 'user_status', 'sts_id');
    }

    

    public static function generateUserCode($oPrefixOption){
        $prefix = "GS"; //make default
        if($oPrefixOption){
            $prefix = $oPrefixOption->opt_value;
        }
        $User = User::select("user_code")
            ->whereRaw("UPPER(user_code) like ? ", strtoupper(trim($prefix)."%"))
            ->orderBy("user_created_at", "desc")
            ->first();
        $userNum =0;
        if(isset($User->user_code)){
            $userNum = intval(str_replace($prefix, "", $User->user_code))+1;
        }else{
            $userNum =1;
        }

        return $prefix.str_pad($userNum, 4, "0", STR_PAD_LEFT);
    }

    //Alamat
    public static function Alamat($userId){
        $user = \DB::table('mgt_users')
            ->join('master_wil_kelurahan_desa', 'master_wil_kelurahan_desa.keldes_id', '=', 'mgt_users.user_kel_desa_id')
            ->join('master_wil_kecamatan', 'master_wil_kecamatan.kecamatan_id', '=', 'master_wil_kelurahan_desa.kecamatan_id')
            ->join('master_wil_kota_kabupaten', 'master_wil_kota_kabupaten.kokab_id', '=', 'master_wil_kecamatan.kokab_id')
            ->join('master_wil_provinsi', 'master_wil_provinsi.provinsi_id', '=', 'master_wil_kota_kabupaten.provinsi_id')
            ->selectRaw("CONCAT(mgt_users.user_alamat,', ',master_wil_kelurahan_desa.keldes_name,', ',master_wil_kecamatan.kecamatan_name,', ',master_wil_kota_kabupaten.kokab_name,' - ',master_wil_provinsi.provinsi_name, ' ', master_wil_kelurahan_desa.zip_code) AS alamat")
            ->where("mgt_users.user_id", $userId)
            ->first();

        if($user){
            return trim($user->alamat);
        }
        return "";
    }

    //Get Firebase Token
    public static function GetFcmToken($userCode){
        $User = User::select("ftoken")->where("user_code", $userCode)->first();

        return $User->ftoken;
    }
}
