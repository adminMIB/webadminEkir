<?php
namespace App\Models\Epkb;

use Illuminate\Database\Eloquent\Model;
 
class StatusBooking extends Model {
    protected $table = 'm_status_booking'; 
    public $timestamps = false;
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;

    /*
    * Options Status Booking
    */
    public static function getOptionStatusPengajuan(){
        $Opt = static::pluck("status_keterangan", "id");

        return $Opt;
    }
    
}