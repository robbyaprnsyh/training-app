<?php
namespace App\Modules\Master\Jabatan;

use App\Bases\BaseRepository;
use App\Modules\Master\Jabatan\Processor;

class Repository extends BaseRepository
{
    public function __construct(Processor $processor)
    {
        $this->processor = $processor;
    }

    public function getInput($request)
    {
        $this->data = [
            'id'     => $request('_id'),
            'name'   => $request('name'),
            'code'   => $request('code'),
            'status' => $request('status'),
            'files'  => $request('files'),
        ];
    }

    public function setValidationRules()
    {
        switch ($this->operation_type) {
            case 'store':
                $this->rules = [
                    [
                        'field' => 'name',
                        'label' => __('Nama'),
                        'rules' => 'required',
                    ],
                    [
                        'field' => 'code',
                        'label' => __('Kode'),
                        'rules' => 'required|unique:master_jabatan',
                    ],
                ];
                break;

            case 'update':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required',
                    ],
                    [
                        'field' => 'name',
                        'label' => __('Nama'),
                        'rules' => 'required',
                    ],
                    [
                        'field' => 'code',
                        'label' => __('Kode'),
                        'rules' => 'nullable',
                    ],
                ];
                break;

            case 'destroy':
                $this->rules = [
                    [
                        'field' => 'id',
                        'label' => __('ID'),
                        'rules' => 'required',
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
                        'rules' => 'required',
                    ],
                ];
                break;

            case 'import':
                $this->rules = [
                    [
                        'field' => 'files',
                        'label' => __('File'),
                        'rules' => 'required|mimes:xlsx|max:5120',
                    ],
                ];
                break;

            default:
                $this->rules = [];
        }
    }
}
