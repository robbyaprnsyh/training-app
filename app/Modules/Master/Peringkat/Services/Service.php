<?php
namespace App\Modules\Master\Peringkat;

use App\Bases\BaseService;
use App\Modules\Master\Peringkat\Model;
use DataTables;
use Illuminate\Support\Str;

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

                if ($data['tingkat'] != '') {
                    $query->where('tingkat', 'ILIKE', '%' . $data['tingkat'] . '%');
                }

                if ($data['status'] != '') {
                    $query->where('status', $data['status']);
                }

            })
            ->addColumn('id', function ($query) {
                return encrypt($query->id);
            })
            ->make(true)
            ->getData(true);
    }

    public function store(array $data)
    {
        return Model::transaction(function () use ($data) {
            return Model::createOne([
                'id'      => (string) Str::uuid(),
                'label'   => $data['label'],
                'tingkat' => $data['tingkat'],
                'color'   => $data['color'] ?? '#000000',
                'status'  => $data['status'] ? 1 : 0,
            ]);
        });
    }

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

            return Model::updateOne($id, [
                'label'   => $data['label'],
                'tingkat' => $data['tingkat'],
                'color'   => $data['color'] ?? '#000000',
                'status'  => isset($data['status']) && $data['status'] == '1' ? 1 : 0,
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

    public static function getAvailableTingkat($excludeId = null)
    {
        $used = Model::when($excludeId, function ($q) use ($excludeId) {
            $q->where('id', '!=', $excludeId);
        })->pluck('tingkat')->map(fn($v) => (int) $v)->toArray();

        return collect(range(1, 5))->diff($used)->values();
    }
}
