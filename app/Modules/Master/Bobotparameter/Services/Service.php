<?php
namespace App\Modules\Master\Bobotparameter;

use App\Bases\BaseService;
use App\Modules\Master\Bobotparameter\Model;
use DataTables;
use Illuminate\Support\Str;

class Service extends BaseService
{
    public function __construct()
    {
    }

    public function data(array $data)
    {
        $query = Model::with(['parameter'])->withTrashed();

        return DataTables::of($query)
            ->filter(function ($query) use ($data) {
                if (! empty($data['parameter_id'])) {
                    $query->where('parameter_id', $data['parameter_id']);
                }
            })
            ->addColumn('id', fn($query) => encrypt($query->id))
            ->addColumn('parameter_name', fn($query) => $query->parameter->name ?? '-')
            ->make(true)
            ->getData(true);
    }

    // public function store(array $data)
    // {
    //     return Model::transaction(function () use ($data) {
    //         return Model::createOne([
    //             'id'           => (string) Str::uuid(),
    //             'parameter_id' => $data['parameter_id'],
    //             'bobot'        => $data['bobot'],
    //         ]);
    //     });
    // }

    public function store(array $data)
    {
        return Model::transaction(function () use ($data) {
            $totalBobot = array_sum($data['bobot']);

            if (round($totalBobot, 2) != 100.00) {
                throw new \Exception('Total bobot harus 100%.');
            }

            Model::query()->delete();

            foreach ($data['bobot'] as $parameterId => $bobot) {
                $res = Model::createOne([
                    'id'           => (string) Str::uuid(),
                    'parameter_id' => $parameterId,
                    'bobot'        => $bobot,
                ]);
                $return['code'] = ($res['code'] == '200') && ($return['code'] ?? '') != '500' ? '200' : '500';
            }

            return $return;
        });
    }

    public function update(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::updateOne($data['id'], [
                'parameter_id' => $data['parameter_id'],
                'bobot'        => $data['bobot'],
            ]);
        });
    }

    public static function get($id)
    {
        return Model::find($id);
    }

    public function destroy(array $data)
    {
        return Model::deleteOne($data['id'], function ($query, $event, $cursor) {
        });
    }

    public function destroys(array $data)
    {
        $ids = array_map('decrypt', $data['id']);

        return Model::transaction(function () use ($ids) {
            return Model::deleteBatch($ids);
        });
    }

    public function restore(array $data)
    {
        return Model::restoreData($data['id'], 'id');
    }

    public static function getAvailableParameter()
    {
        return \App\Modules\Master\Parameter\Model::orderBy('nama')->get();
    }

}
