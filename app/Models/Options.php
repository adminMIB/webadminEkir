<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
 
class Options extends Model {
    protected $table = 'web_options'; 
    public $timestamps = false;
    protected $primaryKey = 'opt_key'; // or null
    public $incrementing = false;


    public static function getOption($key){
        $Opt = Options::select("opt_value")->where("opt_key", $key)->first();

        return $Opt->opt_value;
    }

    public static function filterByDate($obj, $field=null){
        if(!$field) {
            $field = "created_at";
        }

        if(\Request::get('tgl_1') || \Request::get('tgl_2')){
            if (\Request::get('tgl_1') && \Request::get('tgl_2')){
                $date1 = date("Y-m-d", strtotime(\Request::get('tgl_1')));
                $date2 = date("Y-m-d", strtotime(\Request::get('tgl_2')));
                $obj = $obj->whereRaw("DATE(".$field.") >= ?", $date1);
                $obj = $obj->whereRaw("DATE(".$field.") <= ?", $date2);

            }else if (\Request::get('tgl_1')){
                $date1 = date("Y-m-d", strtotime(\Request::get('tgl_1')));
                $obj = $obj->whereRaw("DATE(".$field.") = ?", $date1);

            }else if (\Request::get('tgl_2')){
                $date1 = date("Y-m-d", strtotime("-3 months", time()));
                $date2 = date("Y-m-d", strtotime(\Request::get('tgl_2')));
                $obj = $obj->whereRaw("DATE(".$field.") >= ?", $date1);
                $obj = $obj->whereRaw("DATE(".$field.") <= ?", $date2);
            }
        }

        return $obj;
    }
    
}