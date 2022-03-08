<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Models\Dashboard;

class DashboardController extends Controller{/**
     * Create a new controller instance.
     * @return void
     */
    public function __construct(){
    }

    /**
     * Show the application dashboard.
     */
    public function index(Request $request){
      $title = getAppName();
      $sub_title ="Dashboard";

      $SummaryCount = Dashboard::SummaryCounter();

      $sGrafikHarian = Dashboard::GrafikHarian();    
      $sGrafikBulanan = Dashboard::GrafikBulanan();
      $sGrafikJenisUji = Dashboard::GrafikJenisPengujian();
      $sGrafikStatusPengajuan = Dashboard::StatusPengajuan();

		  return view('dashboard', compact("SummaryCount", "sGrafikHarian", "sGrafikBulanan", "sGrafikJenisUji", "sGrafikStatusPengajuan", "title", "sub_title"));
    }
}
