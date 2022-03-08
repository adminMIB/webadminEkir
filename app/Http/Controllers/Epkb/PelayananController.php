<?php
namespace App\Http\Controllers\Epkb;

use App\Http\Controllers\Controller;
use App\Models\Epkb\Pelayanan;
use App\Models\Epkb\Persyaratan;
//use GrahamCampbell\Markdown\Facades\Markdown;
//use League\HTMLToMarkdown\HtmlConverter;

class PelayananController extends Controller
{
    public function __construct(){
		
    }

    /*
    * Index
    */
    public function index(){
        $Pelayanan = Pelayanan::whereNull("deleted_at")->orderBy("menu_order");

        if(\Request::get('pelayanan')){
            $Pelayanan = $Pelayanan->whereRaw("UPPER(subject) like ? ", "%".strtoupper(trim(\Request::get('pelayanan')))."%");
        }
        if(\Request::get('status')){
			$Pelayanan =$Pelayanan->where("status", \Request::get('status'));
        }

        $title ="Pelayanan";
        $sub_title ="List Pelayanan";

        $Pelayanan =$Pelayanan->paginate(config('app.perpage'));

        return view('epkb.pelayanan.index', compact('Pelayanan', 'title', 'sub_title'));
    }

    /*
    * Detail
    */
    public function detail($id){
        $Pelayanan = Pelayanan::find($id);
        
        $title ="Pelayanan";
        $sub_title ="Detail";

        return view('epkb.pelayanan.detail', compact('Pelayanan', 'title', 'sub_title'));
    }

    /*
	* Create
	*/
	public function create(){
        $title ="Pelayanan";
        $sub_title ="Entri Layanan Baru";

		return view('epkb.pelayanan.form', compact('title', 'sub_title'));
    }
    
    /*
    * Save
    */
    public function store(){
        //https://github.com/GrahamCampbell/Laravel-Markdown
        $input = \Request::all();

        $sort = isset($input['sort']) ? intval($input['sort']) : 0;
        if(!isset($input['sort'])){
            $sort =1;
            $SortPelayanan = Pelayanan::select("menu_order")->orderBy("menu_order", "DESC")->first();
            if($SortPelayanan){
                $sort = intval($SortPelayanan->menu_order)+1;
            }
        }

        $id =\Illuminate\Support\Str::uuid()->toString();
        //Persyaratan
        $Persyaratan = isset($input['persyaratan']) ? $input['persyaratan'] : null;
        $sContent = Persyaratan::storePesyaratanPelayanan( $Persyaratan, $id );

        $Pelayanan = new Pelayanan;
        $Pelayanan->id = $id;
        $Pelayanan->subject = trim($input['subject']);
        $Pelayanan->status = $input['status'];
        $Pelayanan->menu_order = $sort;
        $Pelayanan->created_at = date("Y-m-d H:i:s", time());
            
        $Pelayanan->content = $sContent;
        if($Pelayanan->save()){
            \Session::flash("success", "Pelayanan BERHASIL disimpan!");
            return redirect()->route('pelayanan');
        }else{
            \Session::flash("error", "Pelayanan GAGAL disimpan!");
            return redirect()->route('pelayanan');
        }
    }


    /*
	* Edit
	*/
	public function edit($id){
        $Pelayanan = Pelayanan::find($id);
        $title ="Pelayanan";
        $sub_title ="Ubah > ".$Pelayanan->subject;

        $Persyaratan = Persyaratan::where("pelayanan", $id)->orderBy("sort")->get();

        //$converter = new HtmlConverter();
        //$Pelayanan->content = $converter->convert($Pelayanan->content);

        return view('epkb.pelayanan.form', compact('Pelayanan', 'Persyaratan', 'title', 'sub_title'));
    }


    /*
    * Update
    */
    public function update($id){
        //https://github.com/GrahamCampbell/Laravel-Markdown
        $input = \Request::all();
        $Pelayanan = Pelayanan::find($id);
        if (isset($Pelayanan->id)){
            //$content = Markdown::convertToHtml($input['content']);
            //$content = str_replace("\n", "", $content);

            //Persyaratan
            $sContent = Persyaratan::storePesyaratanPelayanan( $input, $id );         

            $Pelayanan->content = $sContent;
            $Pelayanan->subject = $input['subject'];
            $Pelayanan->status = $input['status'];
            $Pelayanan->menu_order = intval($input['menu_order']);
            $Pelayanan->updated_at = date("Y-m-d H:i:s", time());
            if($Pelayanan->save()){
                \Session::flash("success", "Perubahan BERHASIL disimpan!");
                return redirect()->route('read_pelayanan', ['id'=>$id]);
            }else{
                \Session::flash("error", "Perubahan GAGAL disimpan!");
                return redirect()->route('edit_pelayanan', ['id'=>$id]);
            }

        }else{
            \Session::flash("error", "Perubahan GAGAL disimpan!");
            return redirect()->route('pelayanan');
        }
    }

     /*
    * Delete
    */
    public function destroy($id){
        $input = \Request::all();
        $Pelayanan = Pelayanan::find($id); 
        if (isset($Pelayanan->id)){
            $subject = $Pelayanan->subject;
            $Pelayanan->deleted_at = date("Y-m-d H:i:s", time());

            //hapus persyaratan
            Persyaratan::where("pelayanan", $id)->delete();

            if($Pelayanan->save()){
                \Session::flash("success", "Pelayanan '".$subject."' BERHASIL dihapus!");
                return redirect()->route('pelayanan');
            }else{
                \Session::flash("error", "Pelayanan '".$subject."' GAGAL dihapus!");
                return redirect()->route('pelayanan');
            }

        }else{
            \Session::flash("error", "Pelayanan GAGAL dihapus!");
            return redirect()->route('pelayanan');
        }
    }
    
}