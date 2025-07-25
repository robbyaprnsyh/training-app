<?php
namespace App\Modules\Master\Parameter;

use App\Bases\BaseRepository;
use App\Modules\Master\Parameter\Processor;

class Repository extends BaseRepository
{
    public function __construct(Processor $processor)
    {
        $this->processor        = $processor;
        $this->allowed_from_xss = ['operator_min', 'operator_max'];
    }

    public function getInput($request)
    {
        $this->data = [
            'id'                => $request('_id'),
            'code'              => $request('code'),
            'name'              => $request('name'),
            'tipe_penilaian'    => $request('tipe_penilaian'),
            'status'            => $request('status'),
            'range_kuantitatif' => $request('range_kuantitatif', []),
            'pilihan_kualitatif'  => $request('pilihan_kualitatif', []),
        ];
    }

    public function setValidationRules()
    {
        switch ($this->operation_type) {
            case 'store':
                $this->rules = [
                    [
                        'field' => 'code',
                        'label' => __('Kode'),
                        'rules' => 'required|string|unique:parameter,code',
                    ],
                    [
                        'field' => 'name',
                        'label' => __('Nama'),
                        'rules' => 'required|string',
                    ],
                    [
                        'field' => 'tipe_penilaian',
                        'label' => __('Tipe Penilaian'),
                        'rules' => 'required|in:KUANTITATIF,KUALITATIF',
                    ],
                ];
                break;

            case 'update':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required|exists:parameter,id',
                    ],
                    [
                        'field' => 'code',
                        'label' => __('Kode'),
                        'rules' => 'required|string|exists:parameter,code',
                    ],
                    [
                        'field' => 'name',
                        'label' => __('Nama'),
                        'rules' => 'required|string',
                    ],
                    [
                        'field' => 'tipe_penilaian',
                        'label' => __('Tipe Penilaian'),
                        'rules' => 'nullable|in:KUANTITATIF,KUALITATIF',
                    ],
                ];
                break;

            case 'destroy':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required|exists:parameter,id',
                    ],
                ];
                break;

            case 'destroys':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('IDs'),
                        'rules' => 'required|array',
                    ],
                    [
                        'field' => 'id.*',
                        'label' => __('ID'),
                        'rules' => 'required|uuid|exists:parameter,id',
                    ],
                ];
                break;

            default:
                $this->rules = [];
        }
    }
}
