<?php
namespace App\Modules\Master\Bobotparameter;

use App\Bases\BaseRepository;

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
            'bobot'        => $request('bobot'), // array of bobot [parameter_id => value]
        ];
    }

    public function setValidationRules()
    {
        switch ($this->operation_type) {
            case 'store':
                $this->rules = [
                    [
                        'field' => 'bobot',
                        'label' => __('Bobot'),
                        'rules' => 'required|array|min:1',
                    ],
                    [
                        'field' => 'bobot.*',
                        'label' => __('Nilai Bobot'),
                        'rules' => 'required|numeric|min:0|max:100',
                    ],
                ];
                break;

            case 'update':
                $this->rules = [
                    [
                        'field' => 'bobot',
                        'label' => __('Bobot'),
                        'rules' => 'required|array|min:1',
                    ],
                    [
                        'field' => 'bobot.*',
                        'label' => __('Nilai Bobot'),
                        'rules' => 'required|numeric|min:0|max:100',
                    ],
                ];

                if ($this->operation_type === 'update') {
                    $this->rules[] = [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required|uuid',
                    ];
                }
                break;

            case 'destroy':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required|uuid',
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
                        'rules' => 'required|uuid',
                    ],
                ];
                break;

            default:
                $this->rules = [];
        }
    }
}
