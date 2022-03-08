<?php
namespace App\Models\Master;

use Illuminate\Database\Eloquent\Model;

use File;
use Image;
use Carbon\Carbon;

class Images extends Model {
    /*
    * Create or Update Product Images
    */
    public static function makeImagesResize($title, $Images, $options=[]){
        $title =slugify($title);
        $year = date("Y");

        $base_path_dest = config('app.product_img_path');
        if(isset($options['base_path_dest'])){
            $base_path_dest = $options['base_path_dest'];
        }

        $path2image_dest =$base_path_dest."/".$year;

        //Medium size
        if(isset($options['image_w_md'])){
            $img_w_md = $options['image_w_md'];
            $img_h_md = $options['image_h_md'];

            $img_w_sm = $options['image_w_sm'];
            $img_h_sm = $options['image_h_sm'];

        }else{
            $img_w_md = config('app.product_image_w_md');
            $img_h_md = config('app.product_image_h_md');

            $img_w_sm = config('app.product_image_w_sm');
            $img_h_sm = config('app.product_image_h_sm');
        }

        //JIKA FOLDERNYA BELUM ADA
        if (!File::isDirectory($path2image_dest)) {
            File::makeDirectory($path2image_dest);
        }

        $ImgResult =[];
        $img_title = slugify($title);
        
        foreach($Images as $img){
            $img_name_real = $img_title.'_'.Carbon::now()->timestamp . '_' . uniqid();
            $img_name_md = $img_title.'_'.$img_w_md.'_'.$img_h_md."_".Carbon::now()->timestamp . '_' . uniqid();
            $img_name_sm = $img_title.'_'.$img_w_sm.'_'.$img_h_sm."_".Carbon::now()->timestamp . '_' . uniqid();

            $file = Images::createImage($path2image_dest."/".$img_name_real, $img, $img_ex);

            //=============
            //MEDIUM IMAGE
            //=============

            //CANVAS
            $canvas = Image::canvas($img_w_md, $img_h_md);
            //RESIZE
            $resizeImage  = Image::make($img)->resize($img_w_md, $img_h_md, function($constraint) {
                $constraint->aspectRatio();
            });
            $canvas->insert($resizeImage, 'center');
            $canvas->save($path2image_dest . '/'.$img_name_md.'.'.$img_ex);

            //=============
            //SMALL IMAGE
            //=============

            //CANVAS
            $canvas = Image::canvas($img_w_sm, $img_h_sm);
            //RESIZE
            $resizeImage  = Image::make($img)->resize($img_w_sm, $img_h_sm, function($constraint) {
                $constraint->aspectRatio();
            });      	
            $canvas->insert($resizeImage, 'center');
            $canvas->save($path2image_dest . '/' . $img_name_sm.'.'.$img_ex);

            array_push($ImgResult, [
                'origin' => $img_name_real.".".$img_ex,
                'medium' => $img_name_md.'.'.$img_ex,
                'small' => $img_name_sm.'.'.$img_ex
            ]);

            //Remove Real Image ?
            $idx = count($ImgResult) - 1;
            if(isset($options['img_origin_remove']) && $options['img_origin_remove'] == true){
                if(file_exists($path2image_dest."/".$ImgResult[$idx]['origin'])){
                    @unlink($path2image_dest."/".$ImgResult[$idx]['origin']);
                }
                $ImgResult[$idx]['origin'] ="";
            }

            //fix image name with folder
            if($ImgResult[$idx]['origin']){
                $ImgResult[$idx]['origin'] = $year."/".$ImgResult[$idx]['origin'];
            }
            if($ImgResult[$idx]['medium']){
                $ImgResult[$idx]['medium'] = $year."/".$ImgResult[$idx]['medium'];
            }
            if($ImgResult[$idx]['small']){
                $ImgResult[$idx]['small'] = $year."/".$ImgResult[$idx]['small'];
            }
        }

        return $ImgResult;
    }


    /*
    * Put Image
    */
    private static function createImage($imgpathname, $data, &$type){
        if (preg_match('/^data:image\/(\w+);base64,/', $data, $type)) {
            $data = substr($data, strpos($data, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif
        
            if (!in_array($type, [ 'jpg', 'jpeg', 'gif', 'png' ])) {
                throw new \Exception('invalid image type');
            }
        
            $data = base64_decode($data);
        
            if ($data === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $imgPath =$imgpathname.".{$type}";

        
        file_put_contents($imgPath, $data);

        return  $imgPath;
    }

    //Delete Images 
    public static function deleteImages($images){
        $path2image_dest =config('app.product_img_path');
        foreach($images as $img){
            $filename =$path2image_dest."/".$img;
            if($img && file_exists($filename)){
                unlink($filename);
            }
        }
    }

}