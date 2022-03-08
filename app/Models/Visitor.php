<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Visitor extends Model {
    protected $table = 'md_guests'; 
    public $timestamps = false;
    protected $primaryKey = 'stat_id'; // or null
    public $incrementing = false;

}