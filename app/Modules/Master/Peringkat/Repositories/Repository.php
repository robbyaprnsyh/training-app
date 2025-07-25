<?php
namespace App\Modules\Master\Peringkat;

use App\Bases\BaseRepository;
use App\Modules\Master\Peringkat\Processor;

class Repository extends BaseRepository
{
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request)
    {
        $this->data = [
            'id'      => $request('_id'),
            'label'   => $request('label'),
            'tingkat' => $request('tingkat'),
            'color'   => $request('color'),
            'status'  => $request('status'),
        ];
    }

    public function setValidationRules()
    {
        switch ($this->operation_type) {
            case 'store':
                $this->rules = [
                    [
                        'field' => 'label',
                        'label' => __('Label'),
                        'rules' => 'required',
                    ],
                    [
                        'field' => 'tingkat',
                        'label' => __('Tingkat'),
                        'rules' => 'required|integer',
                    ],
                    [
                        'field' => 'color',
                        'label' => __('Color'),
                        'rules' => 'nullable|string|max:7', // Format #RRGGBB
                    ],
                ];
                break;

            case 'update':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required|uuid',
                    ],
                    [
                        'field' => 'label',
                        'label' => __('Label'),
                        'rules' => 'required',
                    ],
                    [
                        'field' => 'tingkat',
                        'label' => __('Tingkat'),
                        'rules' => 'required|integer',
                    ],
                    [
                        'field' => 'color',
                        'label' => __('Color'),
                        'rules' => 'nullable|string|max:7', // Format #RRGGBB
                    ],
                ];
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
