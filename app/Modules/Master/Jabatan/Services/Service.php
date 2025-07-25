<?php
namespace App\Modules\Master\Jabatan;

use App\Bases\BaseService;
use App\Modules\Master\Jabatan\Model;
use DataTables;

class Service extends BaseService
{
    public function __construct()
    {
    }

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

            })
            ->addColumn('id', function ($query) {
                return encrypt($query->code);
            })
            ->make(true)
            ->getData(true);
    }

    public function store(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::createOne([
                'name'   => $data['name'],
                'code'   => $data['code'],
                'status' => $data['status'] ? 1 : 0,
            ]);
        });
    }

    // public static function get($id)
    // {
    //     return Model::find($id);
    // }

    // public function update(array $data)
    // {
    //     return Model::transaction(function () use ($data) {
    //         return Model::updateOne($data['id'], [
    //             'name'   => $data['name'],
    //             'code'   => $data['code'],
    //             'status' => $data['status'] ? 1 : 0,
    //         ]);
    //     });
    // }

    public static function get($id)
    {
        if ($id) {
            $query = Model::find($id);
            if ($query) {
                return $query;
            }
        }

        return false;
    }

    public function update(array $data)
    {
        return Model::transaction(function () use ($data) {
            $id = $data['id'] ?? null;

            if (! $id) {
                throw new \Exception('ID tidak ditemukan');
            }

            \Log::info('Update jabatan data:', $data);

            return Model::updateOne($id, [
                'name'   => $data['name'],
                'status' => isset($data['status']) && $data['status'] == '1' ? 1 : 0,
            ]);
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
        return Model::restoreData($data['id'], 'code', function ($query) {
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

    public static function dropdown($default = true)
    {
        $results = [];
        if ($default) {
            $results[''] = __('-- Pilih Jabatan --');
        }

        $cursors = Model::isActive()->orderBy('name', 'asc')->get();

        foreach ($cursors as $cursor) {
            $results[$cursor->code] = $cursor->name;
        }

        return $results;
    }
}
