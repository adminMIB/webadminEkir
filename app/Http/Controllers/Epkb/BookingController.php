<?php
namespace App\Http\Controllers\Epkb;

use App\Http\Controllers\Controller;
use App\Models\Epkb\Booking;
use App\Models\Epkb\CustKendaraan;
use App\Models\Epkb\Persyaratan;
use App\Models\Epkb\StatusBooking;
use App\Models\Epkb\JenisUjiMaster;
use App\Models\Epkb\StatusBayar;
use App\Models\Epkb\Member;

use App\RequestApi;

class BookingController extends Controller
{
    public function __construct(){
		
    }

    /*
    * Index
    */
    public function index(){
        $Booking = Booking::orderBy("created_at", "desc");

        if(\Request::get('no_uji')){
            $Booking = $Booking->whereRaw("UPPER(no_uji) LIKE ? ", "%".strtoupper(trim(\Request::get('no_uji'))."%"));
        }
        if(\Request::get('no_kendaraan')){
            $Booking = $Booking->whereRaw("UPPER(no_kendaraan) LIKE ? ", "%".strtoupper(trim(\Request::get('no_kendaraan'))."%"));
        }
        if(\Request::get('jenis_pengujian')){
            $Booking = $Booking->where("jenis_pengujian", \Request::get('jenis_pengujian'));
        }
        if(\Request::get('status') || \Request::get('status') == "0"){
            $Booking = $Booking->where("status", \Request::get('status'));
        }
        if(\Request::get('flag')){
            $Booking = $Booking->where("flag", \Request::get('flag'));
        }

        //By Tanggal
        $Booking = \Options::filterByDate($Booking);

        if(\Request::get('export-xls')){
            $Booking = $Booking->get();		
			return \Excel::download(new \App\Models\Exports\Excel('Laporan Booking Pengujian', $Booking, 'exports.excel.epkb.lap-booking'), 'laporan-booking-pengujian.xlsx');
			
		}else{
            $title ="Laporan";
            $sub_title ="Booking Pengujian";

            $OptStsBooking = StatusBooking::getOptionStatusPengajuan();
            $OptJenisUji = JenisUjiMaster::getOptionJenisUji();
            $OptStsBayar = StatusBayar::getOptionStatusBayar();

            $Booking =$Booking->paginate(config('app.perpage'));

            return view('epkb.booking.index', compact('Booking', 'OptStsBooking', 'OptJenisUji', 'OptStsBayar', 'title', 'sub_title'));
        }
    }

    /*
    * Detail
    */
    public function detail($id){
        $title ="Laporan";
        $sub_title ="Detail Booking Pengujian";

        $Data = Booking::find($id);

        //Booking Pengujian diterima
        if(isset($Data->status) && $Data->status == "1"){
            return view('epkb.booking.detail-info', compact('Data', 'title', 'sub_title'));
        }

        $Persyaratan = Persyaratan::getPersyaratanImage($id, $Data->jenis_pengujian);
		$Kendaraan = CustKendaraan::getKendaraan($Data->no_uji, $Data->no_kendaraan);

        $UjiLalu = new \stdClass;
        $Regnew = new \stdClass;

        //Regnew
        if($Kendaraan){
            $params = new \stdClass;
            $params->data = new \stdClass;
            $params->data->user_id = $Data->user_id;
            $params->data->no_uji = $Kendaraan->no_uji;
            $params->data->no_kendaraan = $Kendaraan->no_kendaraan;
            $params->data->keterangan = $Kendaraan->keterangan;
            $params->data->kartu_hilang = strval($Data->kartu_hilang_rp);
            $params->data->jenis_pengujian = $Data->jenis_pengujian;
            $params->data->tgl_uji = date("d-m-Y", strtotime($Data->tgl_uji));
            $params->data->tempat_uji = $Data->lokasi_pengujian;
            $params->method = "POST";
			
			//dd($params);
			
            $Regnew = RequestApi::request('POST', 'api/v1/kir/regnew', $params);
			//dd($Regnew);
			if(!isset($Regnew->data->NoUji)){
				\Session::flash("error", $Regnew->data->rcm);
			}
			
            //View, Uji Lalu
            $params = new \stdClass;
            $params->data = new \stdClass;
            $params->data->user_id = $Data->user_id;
            $params->data->kendaraan_id = $Kendaraan->kendaraan_id;
            $params->data->no_uji = $Kendaraan->no_uji;
            $params->data->keterangan = $Kendaraan->keterangan;
            $params->method = "POST";

            $UjiLalu = RequestApi::request('POST', 'api/v1/kir/view', $params);
			
			
        }
        if(!isset($UjiLalu->rc)){
            if(!$Kendaraan){
                $UjiLalu->rc = "0014";
                $UjiLalu->rc_message = "Data kendaraan tidak ditemukan";
            }else{
                $UjiLalu->rc = "0068";
                $UjiLalu->rc_message = \Options::getOption('adm.message.timeout');
            }
        }

        if($UjiLalu->rc != "0000"){
            \Session::flash("error_other", $UjiLalu->rc_message);
        }

        return view('epkb.booking.detail', compact('Data', 'Regnew', 'UjiLalu', 'Kendaraan', 'Persyaratan', 'title', 'sub_title'));
    }

    /*
    * Submit Pengujian
    * ak: Create/Store Booking
    */
    public function submit(){
        $input = \Request::all();
		
        /*
        array:4 [▼
        "_token" => "b8mR1TkMy5jiHxIpHDrlTWBPnEZnSw9fFQdBO2eH"
        "booking_id" => "cf58e54f-e702-4894-9a43-a18267d71594"
        "kendaraan_id" => "c53a2f93-b1d0-40c3-8718-ad5e6d0566b3"
        "tgl_uji" => "07-08-2021"
        ]
        */
        $Booking = Booking::find($input["booking_id"]);
        $Kendaraan = CustKendaraan::find($input["kendaraan_id"]);
        $tgl_uji = trim($input["tgl_uji"]);

        if($Booking && $Kendaraan && $tgl_uji){
            $params = new \stdClass;
            $params->data = new \stdClass;
            $params->data->booking_id = $Booking->booking_id;
			$params->data->user_id = $Booking->user_id;
            $params->data->no_uji = $Booking->no_uji;
            $params->data->no_kendaraan = $Kendaraan->no_kendaraan;
            $params->data->kartu_hilang = strval($Booking->kartu_hilang_rp);
            $params->data->jenis_pengujian = $Booking->jenis_pengujian;
            $params->data->tgl_uji = date("Y-m-d", strtotime($tgl_uji));
            $params->data->tempat_uji = $Booking->lokasi_pengujian;
            $params->data->status = "1";
            $params->data->keterangan = $Kendaraan->keterangan;
			$params->data->NoUrut = strval($input["nomor_urut"]);
			
            $params->method = "POST";
			
			//dd($params);
			
            $oResponse = RequestApi::request('POST', 'api/v1/kir/submit', $params);
            if(!isset($oResponse->rc)){
                \Session::flash("error", \Options::getOption('web.error.timeout'));
            }else{
				$notification_title = "";
				$notification_message = "";
                if($oResponse->rc == "0000"){
					$notification_title = \Options::getOption("notifications.submit_title_success");
					$notification_message = \Options::getOption("notifications.submit_message_success");
                    \Session::flash("success", $oResponse->rc_message);					
                }else{
					$notification_title = \Options::getOption("notifications.submit_title_error");
					$notification_message = \Options::getOption("notifications.submit_message_error");
                    \Session::flash("error", $oResponse->rc_message);
                }
            }
			
			//Kirim Notifikasi
			$Member = Member::find($Booking->user_id);
			$oResponse = RequestApi::sendNotification($Member->firebase_token, $notification_title, $notification_message , $Booking->booking_id);
					
        }else{
            \Session::flash("error", "Data Booking atau Data Kendaraan tidak valid!");
        }

        return redirect()->route('read_booking_pengujian', $input["booking_id"]);
    }

    /*
    * Tolak Pengajuan
    * ak: Delete Pengajuan
    */
    public function tolak(){
        $input = \Request::all();
        
        /*
        array:3 [▼
        "_token" => "b8mR1TkMy5jiHxIWpHDrlTBPnEZnSw9fFQdBO2eH"
        "booking_id" => "cf58e54f-e702-4894-9a43-a18267d71594"
        "pesan" => "dsadad"
        ]
        */

        $Booking = Booking::find($input["booking_id"]);
        if($Booking){
            $Booking->status = "9";
			$Booking->status_keterangan = trim($input['pesan']);
			$Booking->updated_at = date("Y-m-d H:i:s", time());
            if($Booking->save()){
				//Kirim Notifikasi
				$Member = Member::find($Booking->user_id);
				$oResponse = RequestApi::sendNotification($Member->firebase_token, "Booking Pengujian Ditolak", $Booking->status_keterangan, $Booking->booking_id);
			
                \Session::flash("success", sprintf("Pengajuan No Uji %s BERHASIL di tolak...", $Booking->no_uji));
            }else{
                \Session::flash("error", sprintf("Pengajuan No Uji %s GAGAL di tolak!", $Booking->no_uji));
            }
			
			//Kirim Notifikasi
			//$Member = Member::find($Booking->user_id);
			//$oResponse = RequestApi::sendNotification($Member->firebase_token, $notification_title, $notification_message , $Booking->booking_id);
			
        }else{
            \Session::flash("error", sprintf("Tidak ditemukan pengajuan bookin id: %s!", $id));
        }

        return redirect()->route('read_booking_pengujian', $input["booking_id"]);
    }

    /*
    * Kirim Pesan
    * ak: Update
    */
    public function kirimPesan(){
        $input = \Request::all();
		
		$Res = new \stdClass;
        $Res->rc = "0000";
        $Res->rc_message = "Pesan notifikasi berhasil dikirim...";
		
		$Booking = Booking::find($input["booking_id"]);
		if($Booking){
			$Member = Member::find($Booking->user_id);
			$oResponse = RequestApi::sendNotification($Member->firebase_token, "Booking Pengujian", trim($input["pesan"]), $input["booking_id"]);
			
			if($oResponse->RC != "0000"){
				$Res->rc = $oResponse->RC;
				$Res->rc_message = $oResponse->RCM;
			}
		}else{
			$Res->rc = "0014";
			$Res->rc_message = "Data Booking tidak ditemukan";
		}
        
        echo json_encode($Res);
    }
    
}