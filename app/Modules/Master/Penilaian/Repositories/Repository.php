<?php
namespace App\Modules\Master\Penilaian;

use App\Bases\BaseRepository;
use App\Modules\Master\Penilaian\Processor;

class Repository extends BaseRepository
{
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request)
    {
        $this->data = [
            'id'           => $request('_id'),
            'parameter_id' => $request('parameter_id'),
            'peringkat_id' => $request('peringkat_id'),
            'nilai'        => $request('nilai'),
            'analisa'      => $request('analisa'),
            'status'       => $request('status'),
        ];
    }

    public function setValidationRules()
    {
        switch ($this->operation_type) {
            case 'store':
                $this->rules = [
                    [
                        'field' => 'parameter_id',
                        'label' => __('Parameter'),
                        'rules' => 'required|exists:parameter,id',
                    ],
                    [
                        'field' => 'analisa',
                        'label' => __('Analisa'),
                        'rules' => 'required',
                    ],
                ];
                break;

            case 'update':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required|exists:penilaian,id',
                    ],
                    [
                        'field' => 'analisa',
                        'label' => __('Analisa'),
                        'rules' => 'required',
                    ],
                ];
                break;

            case 'destroy':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required|exists:penilaian,id',
                    ],
                ];
                break;

            case 'destroys':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required|array',
                    ],
                    [
                        'field' => 'id.*',
                        'label' => __('ID'),
                        'rules' => 'required|exists:penilaian,id',
                    ],
                ];
                break;

            default:
                $this->rules = [];
        }
    }

}
