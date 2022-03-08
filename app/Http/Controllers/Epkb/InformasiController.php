<?php
namespace App\Http\Controllers\Epkb;

use App\Http\Controllers\Controller;
use App\Models\Epkb\Informasi;
use GrahamCampbell\Markdown\Facades\Markdown;
use League\HTMLToMarkdown\HtmlConverter;
use App\Models\Master\Images;

class InformasiController extends Controller
{
    public function __construct(){
		
    }

    /*
    * Index
    */
    public function index(){
        $Informasi = Informasi::whereNull("deleted_at")->orderBy("created_at", "desc");

        if(\Request::get('subject')){
            $Informasi = $Informasi->whereRaw("UPPER(subject) like ? ", "%".strtoupper(trim(\Request::get('subject')))."%");
        }
        if(\Request::get('status')){
			$Informasi =$Informasi->where("status", \Request::get('status'));
        }

        $title ="E-PKB";
        $sub_title ="Informasi dan Berita";

        $Informasi =$Informasi->paginate(config('app.perpage'));

        return view('epkb.informasi.index', compact('Informasi', 'title', 'sub_title'));
    }

    /*
    * Detail
    */
    public function detail($id){
        $Informasi = Informasi::find($id);
        
        $title ="E-PKB";
        $sub_title ="Detail";

        return view('epkb.informasi.detail', compact('Informasi', 'title', 'sub_title'));
    }

    /*
	* Create
	*/
	public function create(){
        $title ="E-PKB";
        $sub_title ="Informasi/Berita Baru";

		return view('epkb.informasi.form', compact('title', 'sub_title'));
    }
    
    /*
    * Save
    */
    public function store(){
        //https://github.com/GrahamCampbell/Laravel-Markdown
        $input = \Request::all();

        $Informasi = new Informasi;
        $Informasi->id = \Illuminate\Support\Str::uuid()->toString();
        $Informasi->subject = trim($input['subject']);
        $Informasi->status = $input['status'];
        $Informasi->created_at = date("Y-m-d H:i:s", time());

        $content = Markdown::convertToHtml($input['content']);
        $content = str_replace("\n", "", $content);
            
        $Informasi->content = $content;
        if($Informasi->save()){
            Informasi::storeProfile((isset($input['profil']) ? $input['profil'] : null), $Informasi);

            \Session::flash("success", "Informasi/Berita <b>".$Informasi->subject."</b> BERHASIL disimpan...");
            return redirect()->route('informasi');
        }else{
            \Session::flash("error", "Informasi/Berita GAGAL disimpan!");
            return redirect()->route('informasi');
        }
    }


    /*
	* Edit
	*/
	public function edit($id){
        $Informasi = Informasi::find($id);
        $title ="E-PKB";
        $sub_title ="Edit > ".$Informasi->subject;

        $converter = new HtmlConverter();

        $Informasi->content = $converter->convert($Informasi->content);

        return view('epkb.informasi.form', compact('Informasi', 'title', 'sub_title'));
    }


    /*
    * Update
    */
    public function update($id){
        //https://github.com/GrahamCampbell/Laravel-Markdown
        $input = \Request::all();
        $Informasi = Informasi::find($id);
        if (isset($Informasi->id)){
            $content = Markdown::convertToHtml($input['content']);
            $content = str_replace("\n", "", $content);
            
            $Informasi->content = $content;
            $Informasi->subject = $input['subject'];
            $Informasi->status = $input['status'];
            $Informasi->updated_at = date("Y-m-d H:i:s", time());
            if($Informasi->save()){
                Informasi::storeProfile((isset($input['profil']) ? $input['profil'] : null), $Informasi);

                \Session::flash("success", "Perubahan BERHASIL disimpan...");
                return redirect()->route('read_informasi', ['id'=>$id]);
            }else{
                \Session::flash("error", "Perubahan GAGAL disimpan!");
                return redirect()->route('edit_informasi', ['id'=>$id]);
            }

        }else{
            \Session::flash("error", "Perubahan GAGAL disimpan!");
            return redirect()->route('informasi');
        }
    }

     /*
    * Delete
    */
    public function destroy($id){
        $input = \Request::all();
        $Informasi = Informasi::find($id); 
        if (isset($Informasi->id)){
            $subject = $Informasi->subject;

            $Informasi->deleted_at = date("Y-m-d H:i:s", time());

            if($Informasi->save()){
                \Session::flash("success", "Informasi/Berita <b>".$subject."</b> BERHASIL dihapus!");
                return redirect()->route('informasi');
            }else{
                \Session::flash("error", "Informasi/Berita '".$subject."' GAGAL dihapus!");
                return redirect()->route('informasi');
            }

        }else{
            \Session::flash("error", "Informasi/Berita GAGAL dihapus!");
            return redirect()->route('informasi');
        }
    }
    
}