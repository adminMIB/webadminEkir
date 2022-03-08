<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Epkb\Booking;
use App\Models\Epkb\CustKendaraan;
use App\Models\Epkb\JenisUjiMaster;
use App\Models\Chart\ChartJs;
 
class Dashboard extends Model {
    /*
    * Summary Counter
    */
    public static function SummaryCounter(){
        $isByDate = (\Request::get('tgl_1') || \Request::get('tgl_2')) ? true : false;
        $month = date("m", time());
        $year = date("Y", time());

        $Kendaraan = CustKendaraan::select("kendaraan_id");
        if($isByDate){
            $Kendaraan = \Options::filterByDate($Kendaraan);
        }else{
            $Kendaraan = $Kendaraan->whereMonth("created_at", $month)->whereYear("created_at", $year);
        }

        $jumlahKendaraan = $Kendaraan->count("kendaraan_id");

        $Booking = Booking::select("booking_id");
        if($isByDate){
            $Booking = \Options::filterByDate($Booking);
        }else{
            $Booking = $Booking->whereMonth("created_at", $month)->whereYear("created_at", $year);
        }

        //Pengajuan
        $Pengajuan = clone $Booking;
        $jumlahPengajuan = $Pengajuan->where("status", "0")->count("booking_id");

        //Ditolak
        $Ditolak = clone $Booking;
        $jumlahDitolak = $Ditolak->where("status", "9")->count("booking_id");

        //Diterima
        $Diterima = clone $Booking;
        $jumlahDiterima = $Diterima->where("status", "1")->count("booking_id");
        
        $Result =[];
        $Res = [
            'name'  => 'Kendaraan',
            'text'  => 'text-warning',
            'icon'  => 'lnr lnr-bus',
            'counter'=> $jumlahKendaraan,
        ];
        array_push($Result, (object)$Res);
        $Res = [
            'name'  => 'Pengajuan',
            'text'  => 'text-info',
            'icon'  => 'lnr lnr-laptop-phone',
            'counter'=> $jumlahPengajuan,
        ];
        array_push($Result, (object)$Res);
        $Res = [
            'name'  => 'Ditolak',
            'text'  => 'text-danger',
            'icon'  => 'lnr lnr-warning',
            'counter'=> $jumlahDitolak,
        ];
        array_push($Result, (object)$Res);
        $Res = [
            'name'  => 'Diterima',
            'text'  => 'text-success',
            'icon'  => 'lnr lnr-store',
            'counter'=> $jumlahDiterima,
        ];
        array_push($Result, (object)$Res);

        return (object)  $Result;
    }

    /*
    * Grafik Harian
    */
    public static function GrafikHarian(){
        $year = date("Y", time());
        $lastDt = date("t", time());
		$nowDt = intval(date("d", time()));

        $Booking = Booking::select('booking_id')
            ->whereYear("created_at", $year);

        $labels =[];
		$Data = new \stdClass;
		$Data->data =[];
        $Data->label =[];

        //Master Jenis Pengujian
        $JenisUjiMaster = JenisUjiMaster::get();
        foreach($JenisUjiMaster as $key=>$JenisUji){
            $Data->data[$key] =[];
            $Data->label[$key] = $JenisUji->id;

            $BookByJenis = clone $Booking;
            $BookByJenis = $BookByJenis->where("jenis_pengujian", $JenisUji->id);

            //Per tanggal
            for($i=1; $i<=$lastDt; $i++){
                if($key==0){
                    array_push($labels, $i);
                }

                if($i<=$nowDt){
                    $qry = clone $BookByJenis;
                    $qry = $qry->whereRaw("to_char(kir_booking.created_at, 'YYYY-MM-DD') = ?", date("Y-m-").str_pad($i,2,"0",STR_PAD_LEFT));
                    $jumlah = $qry->count('booking_id');
                }else{
                    $jumlah =0;
                }
                array_push($Data->data[$key], $jumlah);
            }
        }
		
		$options = new \stdClass;
		$options->colors = ["rgb(255,193,7, .8)", "rgb(0,123,255,.8)", "rgb(40,167,69, .8)"];
        $options->legend =new \stdClass;
        $options->legend->display = true;
		return ChartJs::line("bookingHarian", $labels, $Data, $options);
    }

    /*
    * Grafik Bulanan
    */
    public static function GrafikBulanan(){
        $year = date("Y", time());

        $Booking = Booking::select('booking_id')
            ->whereYear("created_at", $year);

        $labels =[];
        $Data = new \stdClass;
		$Data->data =[];
        $Data->label =[];

        $mNow =intval(date("Y", time()));

        $JenisUjiMaster = JenisUjiMaster::get();
        foreach($JenisUjiMaster as $key=>$JenisUji){
            $Data->data[$key] =[];
            $Data->label[$key] = $JenisUji->id;

            $BookByJenis = clone $Booking;
            $BookByJenis = $BookByJenis->where("jenis_pengujian", $JenisUji->id);

            for($i=1; $i<=12; $i++){
                if($key==0){
                    array_push($labels, toIndonesianMonth($i));
                }

                if($i <= $mNow){
                    $tgl_trx = date("Y", time())."-".str_pad($i,2,"0",STR_PAD_LEFT);
				    $qry = clone $BookByJenis;
				    $qry = $qry->whereRaw("to_char(kir_booking.created_at, 'YYYY-MM') = ?", $tgl_trx);
                    $jumlah = $qry->count("booking_id");
                }else{
                    $jumlah =0;
                }

                array_push($Data->data[$key], $jumlah);
            }
        }

        $options = new \stdClass;		
		$options->colors = ["rgb(255,193,7, .8)", "rgb(0,123,255,.8)", "rgb(40,167,69, .8)"];
        $options->legend =new \stdClass;
        $options->legend->display = true;
        //$options->legend->position = "top";
		
		return ChartJs::line("bookingBulanan", $labels, $Data, $options);
    }

    /*
    * Grafik Jenis Pengujian
    */
    public static function GrafikJenisPengujian(){
        $year = date("Y", time());

        $Booking = Booking::select('booking_id')
            ->whereYear("created_at", $year);

        $labels =[];
        $Data = new \stdClass;
		$Data->data =[];
        
        $JenisUjiMaster = JenisUjiMaster::get();
        foreach($JenisUjiMaster as $JenisUji){
            $BookByJenis = clone $Booking;
            $BookByJenis = $BookByJenis->where("jenis_pengujian", $JenisUji->id);
            
            array_push($labels, $JenisUji->id);
            array_push($Data->data, $BookByJenis->count("booking_id"));
        }

        $options = new \stdClass;		
		$options->colors = ["rgb(255,193,7, .8)", "rgb(0,123,255,.8)", "rgb(40,167,69, .8)"];
		
		return ChartJs::pie("jenisUji", $labels, $Data, $options);
    }

    /*
    * Status Pengujian
    */
    public static function StatusPengajuan(){
        $year = date("Y", time());

        $Booking = Booking::select('booking_id')
            ->whereYear("created_at", $year);

        $labels =[];
        $Data = new \stdClass;
		$Data->data =[];

        //Ditolak
        $Ditolak = clone $Booking;
        $jumlahDitolak = $Ditolak->where("status", "9")->count("booking_id");
        array_push($labels, "Ditolak");
        array_push($Data->data, $jumlahDitolak);

        //Diterima
        $Diterima = clone $Booking;
        $jumlahDiterima = $Diterima->where("status", "1")->count("booking_id");
        array_push($labels, "Diterima");
        array_push($Data->data, $jumlahDiterima);

        $options = new \stdClass;		
		$options->colors = ["rgb(217,83,79, .8)", "rgb(40,167,69, .8)"];
		
		return ChartJs::pie("statusPengajuan", $labels, $Data, $options);
    }



}