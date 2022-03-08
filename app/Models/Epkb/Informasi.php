<?php
namespace App\Models\Epkb;

use Image;
use File;
use Illuminate\Database\Eloquent\Model;
 
class Informasi extends Model {
    protected $table = 'info_berita'; 
    public $timestamps = false;
    protected $primaryKey = 'id'; // or null
    public $incrementing = false;
 
    /*
    * Store Profile
    */
    public static function storeProfile($img, $informasi){
        if(!$img){
            return false;
        }
        $width = getImageWidth();
        $height = getImageHeight();
        $dirname = date("Y-m", time());
        $uploadsPath = config("app.uploads_path");

        $path2image = $dirname.DIRECTORY_SEPARATOR.date("YmdHis", time())."_".slugify($informasi->subject);
        $extension = $img->getClientOriginalExtension();
        $filename = $uploadsPath.DIRECTORY_SEPARATOR.$path2image.".".$extension;

        //Clean old image if exists
        if($informasi->img_path && file_exists($uploadsPath.DIRECTORY_SEPARATOR.$informasi->img_path)){
            unlink($uploadsPath.DIRECTORY_SEPARATOR.$informasi->img_path);
        }


        //Create dirname if not exists
        if(!File::exists($uploadsPath.DIRECTORY_SEPARATOR.$dirname)) {
            // path does not exist
            File::makeDirectory($uploadsPath.DIRECTORY_SEPARATOR.$dirname, 0777, true, true);
        }

        $canvas = Image::canvas($width, $height);
        //RESIZE
        $resizeImage  = Image::make($img)->resize($width, $height, function($constraint) {
            $constraint->aspectRatio();
        });      	
        $canvas->insert($resizeImage, 'center');
        $canvas->save($filename);

        if(file_exists($filename)){
            $informasi->img_url = config("app.image_url")."/".$path2image.".".$extension;
            $informasi->img_path = $path2image.".".$extension;

            $informasi->save();
        }
    }
}