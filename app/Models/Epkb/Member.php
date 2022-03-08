<?php
namespace App\Models\Epkb;

use Illuminate\Database\Eloquent\Model;
 
class Member extends Model {
    protected $table = 'user_account'; 
    public $timestamps = false;
    protected $primaryKey = 'user_id'; // or null
    public $incrementing = false;
    
}