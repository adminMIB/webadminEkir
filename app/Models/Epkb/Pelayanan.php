<?php
namespace App\Models\Epkb;

use Illuminate\Database\Eloquent\Model;
 
class Pelayanan extends Model {
    protected $table = 'info_pelayanan'; 
    public $timestamps = false;
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;
    
}