<?php
namespace App\Models\Epkb;

use Illuminate\Database\Eloquent\Model;
 
class PersyaratanImage extends Model {
    protected $table = 'app_images'; 
    public $timestamps = false;
    protected $primaryKey = 'images_id'; // or null
    public $incrementing = false;
    
}