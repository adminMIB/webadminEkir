<?php
namespace App\Models\Epkb;

use Illuminate\Database\Eloquent\Model;
 
class JenisUjiMaster extends Model {
    protected $table = 'm_jenis_pengujian'; 
    public $timestamps = false;
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;

    /*
    * Options Jenis Uji
    */
    public static function getOptionJenisUji(){
        $Opt = static::where("status", "1")
            ->orderBy("keterangan")
            ->pluck("keterangan", "id");

        return $Opt;
    }
    
}