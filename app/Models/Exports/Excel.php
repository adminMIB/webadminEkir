<?php
namespace App\Models\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class Excel implements FromView, ShouldAutoSize, WithEvents{
	protected $Data;
	protected $title;
	protected $view;
	
	public function __construct($title, $data, $view){
		$this->title = $title;
		$this->Data = $data;
		$this->view = $view;
	}
	
	public function view(): View {
		$Data =$this->Data;
		$title =$this->title;
		$view =$this->view;
		
		return view($view, compact('title', 'Data'));
    }
	
	public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $arrStyle = [
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['argb' => '00000000']
                        ]
                    ],
                ];

                $sheet = $event->getSheet()->getDelegate();
                $highestRow = $sheet->getHighestRow();
                $highestCol = $sheet->getHighestColumn();
				
				/*
                $sheet->getStyle('A1:' . $highestCol . $highestRow)->getAlignment()
                    ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A1:' . $highestCol . $highestRow)->getAlignment()
                    ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);

                $sheet->getStyle('A1:' . $highestCol . $highestRow)
                    ->applyFromArray($arrStyle);
                */
                

                $sheet->getStyle('A1:' . $highestCol . '1')->getFont()->setBold(true);
                $sheet->getStyle('A2:' . $highestCol . '2')->getFont()->setBold(true);

                $sheet->mergeCells('A1:' . $highestCol . '1');

                $sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
            }
        ];
    }
	
}