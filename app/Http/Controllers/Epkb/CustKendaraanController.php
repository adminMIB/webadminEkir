<?php
namespace App\Http\Controllers\Epkb;

use App\Http\Controllers\Controller;
use App\Models\Epkb\CustKendaraan;

class CustKendaraanController extends Controller
{
    public function __construct(){
		
    }

    /*
    * Index
    */
    public function index(){
        $CustKendaraan = CustKendaraan::orderBy("created_at", "DESC");

        if(\Request::get('no_uji')){
            $CustKendaraan = $CustKendaraan->whereRaw("UPPER(no_uji) LIKE ? ", "%".strtoupper(trim(\Request::get('no_uji'))."%"));
        }
        if(\Request::get('no_kendaraan')){
            $CustKendaraan = $CustKendaraan->whereRaw("UPPER(no_kendaraan) LIKE ? ", "%".strtoupper(trim(\Request::get('no_kendaraan'))."%"));
        }

        if(\Request::get('export-xls')){
            //$CustKendaraan = $CustKendaraan->get();		
			//return \Excel::download(new \App\Models\Exports\Excel('Laporan Kode Bayar Pendaftaran', $CustKendaraan, 'exports.excel.sambat.lap-pendaftaran'), 'laporan-kode-bayar-pendaftaran.xlsx');
			
		}else{
            $title ="Laporan";
            $sub_title ="Kendaraan Pelanggan";

            $CustKendaraan =$CustKendaraan->paginate(config('app.perpage'));

            return view('epkb.kendaraan.index', compact('CustKendaraan', 'title', 'sub_title'));
        }
    }
    
    /*
	* Edit
	*/
	public function edit($id){
        $CustKendaraan = CustKendaraan::find($id);
        $title ="Kendaraan Pelanggan";
        $sub_title ="Ubah Data";

        return view('epkb.kendaraan.form', compact('CustKendaraan', 'title', 'sub_title'));
    }

    /*
    * Update
    */
    public function update($id){
        $input = \Request::all();
        $CustKendaraan = CustKendaraan::find($id);
        if (isset($CustKendaraan->kendaraan_id)){
            $CustKendaraan->no_uji = trim($input['no_uji']);
            $CustKendaraan->no_kendaraan = trim($input['no_kendaraan']);
            $CustKendaraan->keterangan = trim($input['keterangan']);
            $CustKendaraan->updated_at = date("Y-m-d H:i:s", time());

            if($CustKendaraan->save()){
                \Session::flash("success", sprintf("Perubahan Data Kendaraan No Uji: %s BERHASIL disimpan...", $CustKendaraan->no_uji));
                return redirect()->route('kendaraan_pelanggan');
            }else{
                \Session::flash("error", sprintf("Perubahan Data Kendaraan No Uji: %s GAGAL disimpan!"), $CustKendaraan->no_uji);
                return redirect()->route('edit_kendaraan_pelanggan', ['id'=>$id]);
            }

        }else{
            \Session::flash("error", "Perubahan GAGAL disimpan!");
            return redirect()->route('edit_kendaraan_pelanggan', ['id'=>$id]);
        }
    }

    /*
    * Delete
    */
    public function destroy($id){
        $input = \Request::all();
        $CustKendaraan = CustKendaraan::find($id); 
        if (isset($CustKendaraan->kendaraan_id)){
            $subject = $CustKendaraan->no_uji;

            if($CustKendaraan->delete()){
                \Session::flash("success", "Kendaraan Pelanggan No Uji: '".$subject."' BERHASIL dihapus...");
                return redirect()->route('kendaraan_pelanggan');
            }else{
                \Session::flash("error", "Kendaraan Pelanggan No Uji: '".$subject."' GAGAL dihapus!");
                return redirect()->route('kendaraan_pelanggan');
            }

        }else{
            \Session::flash("error", "Kendaraan Pelanggan GAGAL dihapus!");
            return redirect()->route('kendaraan_pelanggan');
        }
    }
}