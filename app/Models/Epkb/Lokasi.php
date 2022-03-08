<?php
namespace App\Models\Epkb;

use Illuminate\Database\Eloquent\Model;
 
class Lokasi extends Model {
    protected $table = 'info_lokasi'; 
    public $timestamps = false;
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;
    
}