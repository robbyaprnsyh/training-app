<?php
namespace App\Modules\Laporan\Daftarunitkerja;

use App\Bases\BaseModule;
use Illuminate\Http\Request;
use App\Modules\Laporan\Daftarunitkerja\Repository;
use App\Modules\Laporan\Daftarunitkerja\Service;
use App\Modules\Master\Tipeunitkerja\Service as TipeunitkerjaService;
use PhpOffice\PhpSpreadsheet\Reader\Html;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PDF;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo         = $repo;
        $this->module = 'laporan.daftarunitkerja';
        parent::__construct();
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageTitle);
        return $this->serveView([
            'options_tipe_unit_kerja' => TipeunitkerjaService::dropdown(),
        ]);
    }

    public function data(Request $request)
    {
        $result         = $this->repo->startProcess('data', $request);
        // dd($result);
        
        $type = $request->get('type');
        if($request->get('type') == 'export-excel'){
           libxml_use_internal_errors(true);

            $reader = new Html();
            $spreadsheet = $reader->loadFromString($this->serveView(compact('result','type')));
            $styleArray = array(
                'borders' => array(
                    'allBorders' => array(
                        'borderStyle' => Border::BORDER_THIN
                    )
                )
            );

            $spreadsheet->getActiveSheet()->getStyle(
                'A5:' . $spreadsheet->getActiveSheet()->getHighestColumn() . $spreadsheet->getActiveSheet()->getHighestRow()
            )->applyFromArray($styleArray);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');
            $filename = 'public/laporan/'.str_replace(' ','_',$this->pageTitle) .'.xlsx';
            
            $writer->save(storage_path('app/').$filename);

            return Storage::download($filename);

        }elseif($request->get('type') == 'export-pdf'){
            
            $config = [
                'format' => 'A4-L',
                'temp_dir' => storage_path('app/public/laporan')
            ];

            $pdf = PDF::loadview($this->getViewPath(),compact('result','type'),[],$config);
            
            return $pdf->stream($this->pageTitle.'.pdf');
        }else{
            return $this->serveView(compact('result','type'));
        }
    }

}
