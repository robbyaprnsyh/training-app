<?php
namespace App\Modules\Master\Parameter;

use App\Bases\BaseService;
use App\Modules\Master\Parameter\ModelParameter as Model;
use App\Modules\Master\Parameter\ModelParameterKualitatif as ModelKualitatif;
use App\Modules\Master\Parameter\ModelParameterKuantitatif as ModelKuantitatif;
use DataTables;
use Illuminate\Support\Str;

class Service extends BaseService
{
    public function __construct()
    {}

    public function data(array $data)
    {
        $query = Model::withTrashed()->data();

        return DataTables::of($query)
            ->filter(function ($query) use ($data) {
                if ($data['name'] != '') {
                    $query->where('name', 'ILIKE', '%' . $data['name'] . '%');
                }
                if ($data['status'] != '') {
                    $query->where('status', $data['status']);
                }
                if ($data['code'] != '') {
                    $query->where('code', 'ILIKE', '%' . $data['code'] . '%');
                }
                if ($data['tipe_penilaian'] != '') {
                    $query->where('tipe_penilaian', 'ILIKE', '%' . $data['tipe_penilaian'] . '%');
                }
            })
            ->addColumn('id', fn($row) => encrypt($row->id))
            ->addColumn('code', fn($row) => $row->code)
            ->addColumn('name', fn($row) => $row->name)
            ->addColumn('tipe_penilaian', fn($row) => ucfirst($row->tipe_penilaian))
            ->make(true)
            ->getData(true);
    }

    public function storeKuantitatif(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::createOne([
                'code'           => $data['code'],
                'name'           => $data['name'],
                'tipe_penilaian' => 'KUANTITATIF',
                'status'         => $data['status'] ? 1 : 0,
            ], function ($query, $event) use ($data) {
                foreach ($data['range_kuantitatif'] as $range) {
                    if (empty($range['peringkat_id'])) {
                        continue;
                    }

                    ModelKuantitatif::create([
                        'id'           => (string) Str::uuid(),
                        'parameter_id' => $event->id,
                        'operator_min' => $range['operator_min'] ?? null,
                        'nilai_min'    => $range['nilai_min'] ?? null,
                        'operator_max' => $range['operator_max'] ?? null,
                        'nilai_max'    => $range['nilai_max'] ?? null,
                        'peringkat_id' => $range['peringkat_id'],
                    ]);
                }
            });
        });
    }

    public function storeKualitatif(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::createOne([
                'code'           => $data['code'],
                'name'           => $data['name'],
                'tipe_penilaian' => 'KUALITATIF',
                'status'         => $data['status'] ? 1 : 0,
            ], function ($query, $event) use ($data) {
                foreach ($data['pilihan_kualitatif'] as $item) {
                    if (empty($item['peringkat_id'])) {
                        continue;
                    }

                    ModelKualitatif::create([
                        'id'              => (string) Str::uuid(),
                        'parameter_id'    => $event->id,
                        'analisa_default' => $item['analisa_default'] ?? null,
                        'peringkat_id'    => $item['peringkat_id'],
                    ]);
                }
            });
        });
    }

    public function updateKuantitatif(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::updateOne($data['id'], [
                'name'           => $data['name'],
                'tipe_penilaian' => 'KUANTITATIF',
                'status'         => isset($data['status']) && $data['status'] == '1' ? 1 : 0,
            ], function ($query,  $event , $cursor) use ($data) {
                $existing = ModelKuantitatif::where('parameter_id', $cursor->id)->get()->keyBy('id');
                $incoming = collect($data['range_kuantitatif'])->keyBy(function ($item) {
                    return $item['id'] ?? (string) Str::uuid();
                });
                foreach ($incoming as $key => $range) {
                    if (empty($range['peringkat_id'])) {
                        continue;
                    }

                    if ($existing->has($key)) {
                        $existing[$key]->update([
                            'operator_min' => $range['operator_min'] ?? null,
                            'nilai_min'    => $range['nilai_min'] ?? null,
                            'operator_max' => $range['operator_max'] ?? null,
                            'nilai_max'    => $range['nilai_max'] ?? null,
                            'peringkat_id' => $range['peringkat_id'],
                        ]);
                        $existing->forget($key);
                    } else {
                        ModelKuantitatif::create([
                            'id'           => (string) Str::uuid(),
                            'parameter_id' => $cursor->id,
                            'operator_min' => $range['operator_min'] ?? null,
                            'nilai_min'    => $range['nilai_min'] ?? null,
                            'operator_max' => $range['operator_max'] ?? null,
                            'nilai_max'    => $range['nilai_max'] ?? null,
                            'peringkat_id' => $range['peringkat_id'],
                        ]);
                    }
                }

                foreach ($existing as $toDelete) {
                    $toDelete->delete();
                }
            });
        });
    }

    public function updateKualitatif(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::updateOne($data['id'], [
                'name'           => $data['name'],
                'tipe_penilaian' => 'KUALITATIF',
                'status'         => isset($data['status']) && $data['status'] == '1' ? 1 : 0,
            ], function ($query, $event, $cursor) use ($data) {
                $existing = ModelKualitatif::where('parameter_id', $cursor->id)->get()->keyBy('id');
                $incoming = collect($data['pilihan_kualitatif'])->keyBy(function ($item) {
                    return $item['id'] ?? (string) Str::uuid();
                });
                foreach ($incoming as $key => $item) {
                    if (empty($item['peringkat_id'])) {
                        continue;
                    }

                    if ($existing->has($key)) {
                        $existing[$key]->update([
                            'analisa_default' => $item['analisa_default'] ?? null,
                            'peringkat_id'    => $item['peringkat_id'],
                        ]);
                        $existing->forget($key);
                    } else {
                        ModelKualitatif::create([
                            'id'              => (string) Str::uuid(),
                            'parameter_id'    => $cursor->id,
                            'analisa_default' => $item['analisa_default'] ?? null,
                            'peringkat_id'    => $item['peringkat_id'],
                        ]);
                    }
                }

                foreach ($existing as $toDelete) {
                    $toDelete->delete();
                }
            });
        });
    }

    // public static function get($id)
    // {
    //     return $id ? Model::withTrashed()->find($id) : false;
    // }

    public static function get($id)
    {
        return $id
        ? Model::withTrashed()
            ->with(['rangeKuantitatif', 'pilihanKualitatif'])
            ->find($id)
        : false;
    }

    public function destroy(array $data)
    {
        return Model::deleteOne($data['id'], function ($query, $event, $cursor) {
            $cursor->update(['status' => false]);
        });
    }

    public function restore(array $data)
    {
        return Model::restoreData($data['id'], 'id', function ($query) {
            $query->update(['status' => true]);
        });
    }

    public function destroys(array $data)
    {
        $ids = array_map('decrypt', $data['id']);

        return Model::transaction(function () use ($ids) {
            return Model::deleteBatch($ids, function ($query, $event, $cursor) {
                $cursor->update(['status' => false]);
            });
        });
    }
}
