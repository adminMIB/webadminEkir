<?php
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use DB;

class KotaKabupaten extends Model {
    protected $table = 'master_wil_kota_kabupaten'; 
    public $timestamps = false;
    protected $primaryKey = 'kokab_id'; // or null
    public $incrementing = false;

    public function Provinsi(){
        return $this->belongsTo('App\Models\Master\Province', 'provinsi_id', 'provinsi_id');
    }
}