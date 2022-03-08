<?php
namespace App\Models\Epkb;

use Illuminate\Database\Eloquent\Model;
 
class Persyaratan extends Model {
    protected $table = 'app_persyaratan'; 
    public $timestamps = false;
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;

    public static function storePesyaratanPelayanan($input=[], $pelayanan){
        $Persyaratan = isset($input['persyaratan']) ? $input['persyaratan'] : null;
        $PersyaratanId = isset($input['persyaratan_id']) ? $input['persyaratan_id'] : null;

        $JenisUjiMaster = JenisUjiMaster::where("pelayanan", $pelayanan)->first();
        $m_jenis_uji_id = ($JenisUjiMaster) ? $JenisUjiMaster->id : null;

        $sContent ="";
        $sContent ="<ul>";
        foreach($Persyaratan as $key=>$val){
            $name = trim($val);
            if($name){                
                $id = isset($PersyaratanId[$key]) ? $PersyaratanId[$key] : null;
                if($id){
                    $P = static::find($id);
                    $P->sort = $key;
                    $P->name = $name;
                    $P->jenis_uji = $m_jenis_uji_id;
                }else{
                    $P = new Persyaratan;
                    $P->id = \Illuminate\Support\Str::uuid()->toString();
                    $P->pelayanan = $pelayanan;
                    $P->name = $name;
                    $P->status = '1';
                    $P->sort = $key;
                    $P->created_at =date("Y-m-d H:i:s", time());
                    $P->jenis_uji = $m_jenis_uji_id;
                }
                $P->save();
                $sContent .='<li>'.$name.'</li>';
            }
        }
        $sContent .="</ul>";

        return $sContent;        
    }

    /*
    * Status Persyaratan
    */
    public static function statusPersyaratan($booking_id){
        $status ="9";
        $Booking = Booking::find($booking_id);
        if($Booking){
            $total_persyaratan =static::select("id")
                ->where("jenis_uji", $Booking->jenis_pengujian)->count("id");

            $jumlah_persyaratan = PersyaratanImage::where("booking_id", $Booking->booking_id)
                ->count("images_id");
            
            if($total_persyaratan == $jumlah_persyaratan){
                $status ="1"; //Lengkap
            }elseif($jumlah_persyaratan < $total_persyaratan){
                $status ="2"; //tidak lengkap / sebagian
            }
        }

        return $status;
    }

    /*
    * Get Persyaratan
    */
    public static function getPersyaratanImage($booking_id, $jenis_uji){
        $Result =[];

        $Pesyaratan = static::where("jenis_uji", $jenis_uji)
            ->orderBy("sort")
            ->get();

        if($Pesyaratan->isNotEmpty()){
            $img_url = \Options::getOption("web.image_url");
            foreach($Pesyaratan as $data){
                $Img = PersyaratanImage::where("booking_id", $booking_id)
                    ->where("persyaratan_id", $data->id)
                    ->first();
                
                if(trim($data->name)){
                    $res = new \stdClass;
                    $res->image_status ="0";
                    $res->persyaratan_id = $data->id;
                    $res->persyaratan_name = $data->name;

                    if($Img){
                        $res->image_status ="1";
                        $res->image_url = $img_url."/".$Img->images;
                    }else{
                        $res->image_url = $img_url."/no-image.png";
                    }
                    
                    array_push($Result, $res);
                }
            }
        }
       
        return $Result;
    }
    
}