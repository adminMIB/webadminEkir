<?php
namespace App\Models\Epkb;

use Illuminate\Database\Eloquent\Model;
 
class StatusBayar extends Model {
    protected $table = 'm_flag_booking'; 
    public $timestamps = false;
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;

    /*
    * Options Status Pembayaran
    */
    public static function getOptionStatusBayar(){
        $Opt = static::pluck("flag_keterangan", "id");

        return $Opt;
    }
    
}