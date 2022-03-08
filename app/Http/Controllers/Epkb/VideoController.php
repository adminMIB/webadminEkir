<?php
namespace App\Http\Controllers\Epkb;

use App\Http\Controllers\Controller;
use App\Models\Epkb\Video;

class VideoController extends Controller
{
    public function __construct(){
		
    }

    /*
    * Index
    */
    public function index(){
        $Video = Video::orderBy("created_at", "desc");

        if(\Request::get('subject')){
            $Video = $Video->whereRaw("UPPER(subject) like ? ", "%".strtoupper(trim(\Request::get('subject')))."%");
        }
        if(\Request::get('status')){
            $Video = $Video->whereRaw("status = ? ", \Request::get('status'));
        }

        $title ="E-PKB";
        $sub_title ="Video";

        $Video =$Video->paginate(config('app.perpage'));

        return view('epkb.video.index', compact('Video', 'title', 'sub_title'));
    }

    /*
	* Create
	*/
	public function create(){
        $title ="E-PKB";
        $sub_title ="Entri Video Baru";

		return view('epkb.video.form', compact('title', 'sub_title'));
    }
    
    /*
    * Save
    */
    public function store(){
        $input = \Request::all();

        $subject = trim($input['subject']);
        $video = trim($input['video']);

        $Video = new Video;
        $Video->id = \Illuminate\Support\Str::uuid()->toString();
        $Video->subject = $subject;
        $Video->content = $video;
        $Video->status = $input['status'];
        $Video->created_at = date("Y-m-d H:i:s", time());
        
        if($Video->save()){            
            \Session::flash("success", "Video <b>".$Video->subject."</b> BERHASIL disimpan...");
            return redirect()->route('video');
        }else{
            \Session::flash("error", "An ERROR occured!");
            return redirect()->route('create_video');
        }
    }


    /*
	* Edit
	*/
	public function edit($id){
        $Video = Video::find($id);
        $title ="E-PKB";
        $sub_title ="Edit Video > ".$Video->subject;

        return view('epkb.video.form', compact('Video', 'title', 'sub_title'));
    }


    /*
    * Update
    */
    public function update($id){
        $input = \Request::all();
        $Video = Video::find($id);
        if (isset($Video->id)){
            $subject = trim($input['subject']);
            $video = trim($input['video']);
            
            $Video->subject = $subject;
            $Video->content = $video;
            $Video->status = $input['status'];
            $Video->updated_at = date("Y-m-d H:i:s", time());

            if($Video->save()){
                \Session::flash("success", "Perubahan BERHASIL disimpan...");
                return redirect()->route('video');
            }else{
                \Session::flash("error", "Perubahan GAGAL disimpan!");
                return redirect()->route('edit_video', ['id'=>$id]);
            }

        }else{
            \Session::flash("error", "Perubahan GAGAL disimpan!");
            return redirect()->route('edit_video', ['id'=>$id]);
        }
    }

    /*
    * Delete
    */
    public function destroy($id){
        $input = \Request::all();
        $Video = Video::find($id); 
        if (isset($Video->id)){
            $subject = $Video->subject;

            if($Video->delete()){
                \Session::flash("success", "Video '".$subject."' BERHASIL dihapus...");
                return redirect()->route('video');
            }else{
                \Session::flash("error", "Video '".$subject."' GAGAL dihapus!");
                return redirect()->route('video');
            }

        }else{
            \Session::flash("error", "Video GAGAL dihapus!");
            return redirect()->route('video');
        }
    }
    
}