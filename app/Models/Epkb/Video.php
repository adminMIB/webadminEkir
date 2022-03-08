<?php
namespace App\Models\Epkb;

use Illuminate\Database\Eloquent\Model;
 
class Video extends Model {
    protected $table = 'info_video'; 
    public $timestamps = false;
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;
    
}