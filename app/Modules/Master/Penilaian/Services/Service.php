<?php
namespace App\Modules\Master\Penilaian;

use App\Bases\BaseService;
use App\Modules\Master\Penilaian\Model;
use DataTables;
use DB;

class Service extends BaseService
{
    public function __construct()
    {
    }

    public static function parameters(array $data)
    {
        $result = DB::table('parameter as a')->select([
            'a.*',
            'b.peringkat_id',
            'b.nilai',
            'b.id as penilaian_id',
            'b.status as penilaian_status'
        ])->leftJoin('penilaian as b', function ($query) {
            $query->on('b.parameter_id', '=', 'a.id');
        })
        ->orderBy('a.name', 'asc')
        ->get();
        return $result;
    }

    public function data(array $data)
    {
        $query = Model::with(['parameter', 'peringkat']);

        return DataTables::of($query)
            ->addColumn('id', function ($query) {
                return encrypt($query->id);
            })
            ->addColumn('parameter_name', function ($query) {
                return $query->parameter->name ?? '-';
            })
            ->addColumn('peringkat_label', function ($query) {
                return $query->peringkat->label ?? '-';
            })
            ->addColumn('peringkat_color', function ($query) {
                return '<span class="badge" style="background-color:' . ($query->peringkat->color ?? '#000') . '">' . ($query->peringkat->label ?? '-') . '</span>';
            })
            ->rawColumns(['peringkat_color'])
            ->make(true)
            ->getData(true);
    }

    // public function store(array $data)
    // {
    //     return Model::transaction(function () use ($data) {
    //         return Model::createOne([
    //             'id'                     => (string) Str::uuid(),
    //             'master_unit_kerja_name' => $data['master_unit_kerja_name'],
    //             'posisi'                 => $data['posisi'],
    //             'periode'                => $data['periode'],
    //             'parameter_id'           => $data['parameter_id'],
    //             'parameter_name'         => $data['parameter_name'],
    //             'peringkat_id'           => $data['peringkat_id'] ?? null,
    //             'peringkat_label'        => $data['peringkat_label'] ?? null,
    //             'peringkat_color'        => $data['peringkat_color'] ?? null,
    //             'status'                 => isset($data['status']) && $data['status'] == '1' ? 1 : 0,
    //         ]);
    //     });
    // }

    // public function store(array $data)
    // {
    //     return Model::transaction(function () use ($data) {
    //         $parameterId = $data['parameter_id'];
    //         $tipe        = $data['tipe_penilaian'];
    //         $nilai       = $data['nilai'] ?? null;
    //         $analisa     = $data['analisa'] ?? null;
    //         $peringkatId = $data['peringkat_id'] ?? null;

    //         // Cek peringkat otomatis jika kuantitatif
    //         if ($tipe === 'kuantitatif' && $nilai !== null) {
    //             $peringkat = \App\Modules\Master\Parameter\ModelParameterKuantitatif::where('parameter_id', $parameterId)
    //                 ->where('nilai_min', '<=', $nilai)
    //                 ->where('nilai_max', '>=', $nilai)
    //                 ->first();

    //             $peringkatId = $peringkat?->peringkat_id;
    //         }

    //         return Model::createOne([
    //             'parameter_id' => $parameterId,
    //             'nilai'        => $nilai,
    //             'analisa'      => $analisa,
    //             'peringkat_id' => $peringkatId,
    //         ]);
    //     });
    // }

    public function store(array $data)
    {
        return Model::transaction(function () use ($data) {
            $parameterId = $data['parameter_id'];
            // $nilai       = $data['nilai'] ?? null;
            $nilai       = $data['nilai'] !== '' ? $data['nilai'] : null;
            $analisa     = $data['analisa'] ?? null;
            $peringkatId = $data['peringkat_id'] ?? null;
            // $status = isset($data['status']) && $data['status'] == '1' ? 1 : 0;
            $status = $data['status'] ? 1 : 0;


            $parameter = \App\Modules\Master\Parameter\ModelParameter::findOrFail($parameterId);
            $tipe      = $parameter->tipe_penilaian;

            if ($tipe === 'KUANTITATIF' && $nilai !== null) {
                $peringkat = \App\Modules\Master\Parameter\ModelParameterKuantitatif::where('parameter_id', $parameterId)
                // ->where('nilai_min', '<=', $nilai)
                // ->where('nilai_max', '>=', $nilai)
                // ->first();
                    ->where(function ($q) use ($nilai) {
                        $q->where(function ($q2) use ($nilai) {
                            // Range normal
                            $q2->where('nilai_min', '<=', $nilai)
                                ->where('nilai_max', '>=', $nilai);
                        })
                            ->orWhere(function ($q2) use ($nilai) {
                                // Khusus untuk peringkat "tak terbatas"
                                $q2->where('operator_min', '>=')
                                    ->where('operator_max', '>=')
                                    ->where('nilai_min', '<=', $nilai)
                                    ->where('nilai_max', '<=', $nilai);
                            });
                    })
                    ->first();

                $peringkatId = $peringkat?->peringkat_id;
            }

            $peringkatData = \App\Modules\Master\Peringkat\Model::find($peringkatId);

            $existing = Model::where('parameter_id', $parameterId)->first();

            $penilaianData = [
                'parameter_id'    => $parameterId,
                'nilai'           => $nilai,
                'analisa'         => $analisa,
                'peringkat_id'    => $peringkatId,
                'peringkat_label' => $peringkatData?->label,
                'peringkat_color' => $peringkatData?->color,
                'status' => $status,
            ];

            if ($existing) {
                $existing->update($penilaianData);
                return $existing;
            }
            return Model::createOne($penilaianData);
        });
    }

    public static function get($id)
    {
        if ($id) {
            $query = Model::with(['parameter', 'peringkat'])->find($id);
            if ($query) {
                return $query;
            }
        }
        return false;
    }

    public function update(array $data)
    {
        return Model::transaction(function () use ($data) {
            $id          = $data['id'];
            $parameterId = $data['parameter_id'];
            $nilai       = $data['nilai'] !== '' ? $data['nilai'] : null;
            $analisa     = $data['analisa'] ?? null;
            $peringkatId = $data['peringkat_id'] ?? null;
            $status = isset($data['status']) && $data['status'] == '1' ? 1 : 0;

            $parameter = \App\Modules\Master\Parameter\ModelParameter::findOrFail($parameterId);
            $tipe      = $parameter->tipe_penilaian;

            if ($tipe === 'KUANTITATIF' && $nilai !== null) {
                $peringkat = \App\Modules\Master\Parameter\ModelParameterKuantitatif::where('parameter_id', $parameterId)
                    ->where(function ($q) use ($nilai) {
                        $q->where(function ($q2) use ($nilai) {
                            // Range normal
                            $q2->where('nilai_min', '<=', $nilai)
                                ->where('nilai_max', '>=', $nilai);
                        })
                            ->orWhere(function ($q2) use ($nilai) {
                                // Khusus untuk peringkat "tak terbatas"
                                $q2->where('operator_min', '>=')
                                    ->where('operator_max', '>=')
                                    ->where('nilai_min', '<=', $nilai)
                                    ->where('nilai_max', '<=', $nilai);
                            });
                    })
                    ->first();

                $peringkatId = $peringkat?->peringkat_id;
            }

            $peringkatData = \App\Modules\Master\Peringkat\Model::find($peringkatId);

            $penilaianData = [
                'parameter_id'    => $parameterId,
                'nilai'           => $nilai,
                'analisa'         => $analisa,
                'peringkat_id'    => $peringkatId,
                'peringkat_label' => $peringkatData?->label,
                'peringkat_color' => $peringkatData?->color,
                'status' => $status,

            ];

            // $existing = Model::findOrFail($id);
            // $existing->update($penilaianData);
            // return $existing;
            return Model::updateOne($id, $penilaianData);
        });
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
