<?php
namespace App\Models\Epkb;

use Illuminate\Database\Eloquent\Model;
 
class Booking extends Model {
    protected $table = 'kir_booking'; 
    public $timestamps = false;
    protected $primaryKey = 'booking_id'; // or null
    public $incrementing = false;

    public function member(){
        return $this->belongsTo(Member::class, 'user_id');
    }

    public function stsBooking(){
        return $this->belongsTo(StatusBooking::class, 'status', 'id');
    }

    public function statusBayar(){
        return $this->belongsTo(StatusBayar::class, 'flag', 'id');
    }
    
}