<?php
namespace App\Modules\Master\Parameter;

use App\Bases\BaseProcessor;
use App\Modules\Master\Parameter\Service;
use Exception;

class Processor extends BaseProcessor
{
    private $service;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function setProcessor($operation_type, array $data)
    {
        try {
            switch ($operation_type) {
                case 'store':
                    $tipe = strtoupper($data['tipe_penilaian'] ?? '');

                    if ($tipe === 'KUANTITATIF') {
                        $this->output = $this->service->storeKuantitatif($data);
                    } elseif ($tipe === 'KUALITATIF') {
                        $this->output = $this->service->storeKualitatif($data);
                    } else {
                        throw new \Exception('Tipe penilaian tidak valid saat store.');
                    }
                    break;

                case 'update':
                    $tipe = strtoupper($data['tipe_penilaian'] ?? '');

                    if ($tipe === 'KUANTITATIF') {
                        $this->output = $this->service->updateKuantitatif($data);
                    } elseif ($tipe === 'KUALITATIF') {
                        $this->output = $this->service->updateKualitatif($data);
                    } else {
                        throw new \Exception('Tipe penilaian tidak valid saat update.');
                    }
                    break;

                case 'data':
                    $this->output = $this->service->data($data);
                    break;

                case 'get':
                    $this->output = $this->service->get($data);
                    break;

                case 'destroy':
                    $this->output = $this->service->destroy($data);
                    break;

                case 'destroys':
                    $this->output = $this->service->destroys($data);
                    break;

                case 'restore':
                    $this->output = $this->service->restore($data);
                    break;
            }

            return true;
        } catch (Exception $e) {
            $this->output = $e;
            return false;
        }
    }
}
