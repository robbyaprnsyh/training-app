<?php
namespace App\Modules\Laporan\Daftarunitkerja;

use App\Bases\BaseProcessor;
use App\Modules\Laporan\Daftarunitkerja\Service;
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
                case 'data':
                    $this->output = $this->service->data($data);
                    break;
            }

            return true;
        } catch (Exception $e) {
            $this->output = $e;
            return false;
        }
    }
}
