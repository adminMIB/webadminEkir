<?php
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;
use DB;
 
class Province extends Model {
    protected $table = 'master_wil_provinsi'; 
    public $timestamps = false;
    protected $primaryKey = 'provinsi_id'; // or null
    public $incrementing = false;	
	
}