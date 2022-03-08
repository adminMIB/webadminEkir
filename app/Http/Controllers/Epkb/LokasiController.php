<?php
namespace App\Http\Controllers\Epkb;

use App\Http\Controllers\Controller;
use App\Models\Epkb\Lokasi;
//use GrahamCampbell\Markdown\Facades\Markdown;
//use League\HTMLToMarkdown\HtmlConverter;

class LokasiController extends Controller
{
    public function __construct(){
		
    }

    /*
    * Index
    */
    public function index(){
        $Lokasi = Lokasi::whereNull("deleted_at")->orderBy("created_at");

        if(\Request::get('lokasi')){
            $Lokasi = $Lokasi->whereRaw("UPPER(subject) like ? ", "%".strtoupper(trim(\Request::get('lokasi')))."%");
        }
        if(\Request::get('status')){
			$Lokasi =$Lokasi->where("status", \Request::get('status'));
        }

        $title ="E-PKB";
        $sub_title ="Lokasi Pengujian";

        $Lokasi =$Lokasi->paginate(config('app.perpage'));

        return view('epkb.lokasi.index', compact('Lokasi', 'title', 'sub_title'));
    }

    /*
	* Create
	*/
	public function create(){
        $title ="E-PKB";
        $sub_title ="Lokasi Baru";

		return view('epkb.lokasi.form', compact('title', 'sub_title'));
    }
    
    /*
    * Save
    */
    public function store(){
        //https://github.com/GrahamCampbell/Laravel-Markdown
        $input = \Request::all();

        $Lokasi = new Lokasi;
        $Lokasi->id = \Illuminate\Support\Str::uuid()->toString();
        $Lokasi->subject = trim($input['subject']);
        $Lokasi->status = $input['status'];
        $Lokasi->created_at = date("Y-m-d H:i:s", time());

        $content = trim($input['content']);
            
        $Lokasi->content = $content;
        if($Lokasi->save()){
            \Session::flash("success", "Lokasi Pengujian BERHASIL disimpan!");
            return redirect()->route('lokasi');
        }else{
            \Session::flash("error", "Lokasi Pengujian GAGAL disimpan!");
            return redirect()->route('lokasi');
        }
    }


    /*
	* Edit
	*/
	public function edit($id){
        $Lokasi = Lokasi::find($id);
        $title ="Lokasi Pengujian";
        $sub_title ="Ubah > ".$Lokasi->subject;

        return view('epkb.lokasi.form', compact('Lokasi', 'title', 'sub_title'));
    }


    /*
    * Update
    */
    public function update($id){
        //https://github.com/GrahamCampbell/Laravel-Markdown
        $input = \Request::all();
        $Lokasi = Lokasi::find($id);
        if (isset($Lokasi->id)){
            $content = trim($input['content']);
            
            $Lokasi->content = $content;
            $Lokasi->subject = $input['subject'];
            $Lokasi->status = $input['status'];
            $Lokasi->updated_at = date("Y-m-d H:i:s", time());
            if($Lokasi->save()){
                \Session::flash("success", "Perubahan BERHASIL disimpan!");
                return redirect()->route('lokasi');
            }else{
                \Session::flash("error", "Perubahan GAGAL disimpan!");
                return redirect()->route('edit_lokasi', ['id'=>$id]);
            }

        }else{
            \Session::flash("error", "Perubahan GAGAL disimpan!");
            return redirect()->route('lokasi');
        }
    }

     /*
    * Delete
    */
    public function destroy($id){
        $input = \Request::all();
        $Lokasi = Lokasi::find($id); 
        if (isset($Lokasi->id)){
            $subject = $Lokasi->subject;
            $Lokasi->deleted_at = date("Y-m-d H:i:s", time());

            if($Lokasi->save()){
                \Session::flash("success", "Lokasi Pengujian '".$subject."' BERHASIL dihapus!");
                return redirect()->route('lokasi');
            }else{
                \Session::flash("error", "Lokasi Pengujian '".$subject."' GAGAL dihapus!");
                return redirect()->route('lokasi');
            }

        }else{
            \Session::flash("error", "Lokasi Pengujian GAGAL dihapus!");
            return redirect()->route('lokasi');
        }
    }
    
}