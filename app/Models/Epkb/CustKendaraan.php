<?php
namespace App\Models\Epkb;

use Illuminate\Database\Eloquent\Model;
 
class CustKendaraan extends Model {
    protected $table = 'kir_kendaraan'; 
    public $timestamps = false;
    protected $primaryKey = 'kendaraan_id'; // or null
    public $incrementing = false;

    public function member(){
        return $this->belongsTo(Member::class, 'user_id');
    }

    /*
    * Kendaraan by No Uji dan No Kendaraan
    */
    public static function getKendaraan($no_uji, $no_kendaraan){
        $K = static::where("no_uji", $no_uji)
            ->where("no_kendaraan", $no_kendaraan)
            ->orderBy("created_at", "desc")
            ->first();
        
        return $K;      
    }
    
}