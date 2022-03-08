<?php
namespace App\Models\Platform;

use Illuminate\Database\Eloquent\Model;

class Group extends Model {

    protected $table = 'mgt_app_groups'; 
	
    public $timestamps = false;
    protected $primaryKey = 'group_id';
}