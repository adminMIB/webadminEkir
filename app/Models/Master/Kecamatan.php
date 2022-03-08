<?php
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model {

    protected $table = 'master_wil_kecamatan'; 
    public $timestamps = false;
    protected $primaryKey = 'kecamatan_id'; // or null
    public $incrementing = false;
    
    public function KotaKab(){
        return $this->belongsTo('App\Models\Master\KotaKabupaten', 'kokab_id', 'kokab_id');
    }
	
}