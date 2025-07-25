<?php
namespace App\Modules\Laporan\Daftarunitkerja;

use App\Bases\BaseService;
use App\Helpers\Common;
use DataTables;
use App\Modules\Master\Katalogrisiko\Model;
use Carbon\Carbon as Carbon;
use Illuminate\Support\Facades\DB;

class Service extends BaseService
{

    public function __construct()
    {
    }

    public function data(array $data)
    {   
        $query = DB::table('master_unit_kerja as a')
        ->select([
            'a.code',
            'a.name as unit_kerja_name',
            'b.name as tipe_unit_kerja',
        ])
        ->join('master_tipe_unit_kerja as b', function ($query) use ($data) {
            $query->on('b.id', '=', 'a.tipe_unit_kerja_id');
        })
        ->where(function ($query) use ($data) {
            if ($data['tipe_unit_kerja_id']) {
                $query->where('a.tipe_unit_kerja_id', $data['tipe_unit_kerja_id']);
            }
            $query->where('a.status',true);
            $query->whereNull('a.deleted_at');
        })
        ->get();
        
        return $this->outputResult($query);
    }

}
