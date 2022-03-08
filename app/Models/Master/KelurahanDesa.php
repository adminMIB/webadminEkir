<?php
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

class KelurahanDesa extends Model {

    protected $table = 'master_wil_kelurahan_desa'; 
    public $timestamps = false;
    protected $primaryKey = 'keldes_id'; // or null
    public $incrementing = false;
	
	public function Kecamatan(){
        return $this->belongsTo('App\Models\Master\Kecamatan', 'kecamatan_id', 'kecamatan_id');
    }

}