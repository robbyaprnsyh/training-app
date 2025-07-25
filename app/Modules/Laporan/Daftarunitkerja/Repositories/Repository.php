<?php
namespace App\Modules\Laporan\Daftarunitkerja;

use App\Bases\BaseRepository;
use Illuminate\Validation\Rule;
use App\Modules\Penilaian\Led\Validations\CheckReviewer;
use App\Modules\Laporan\Daftarunitkerja\Processor;

class Repository extends BaseRepository
{

    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request) {
        $this->data = [
            'id'          => $request('_id'),
            'name'        => $request('name'),
            'code'        => $request('code'),
            'tipe_unit_kerja_id' => $request('tipe_unit_kerja_id'),
            'tipe_unit_kerja_code' => $request('tipe_unit_kerja_code'),
            'unit_kerja' => $request('unit_kerja'),
            'status'      => $request('status'),
        ];

    }

    public function setValidationRules() {
        switch ($this->operation_type) {
        default:
            $this->rules = [];
        }

    }

}
