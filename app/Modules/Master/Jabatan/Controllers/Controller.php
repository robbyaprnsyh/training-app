<?php
namespace App\Modules\Master\Jabatan;

use App\Bases\BaseModule;
use App\Modules\Master\Jabatan\Repository;
use App\Modules\Master\Jabatan\Service;
use Illuminate\Http\Request;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo   = $repo;
        $this->module = 'master.jabatan';
        parent::__construct();
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageTitle);
        return $this->serveView();
    }

    public function data(Request $request)
    {
        $result = $this->repo->startProcess('data', $request);
        return $this->serveJSON($result);
    }

    public function create()
    {
        return $this->serveView();
    }

    public function store(Request $request)
    {
        $result = $this->repo->startProcess('store', $request);
        return $this->serveJSON($result);
    }

    public function edit($id)
    {
        $data = Service::get(decrypt($id));
        return $this->serveView([
            'data' => $data,
        ]);
    }

    // public function edit($id)
    // {
    //     $id = decrypt($id);

    //     $data = Service::get($id);
    //     if (! $data) {
    //         return response()->json(['message' => 'Data tidak ditemukan'], 404);
    //     }

    //     return $this->serveView([
    //         'data' => $data,
    //     ]);
    // }

    public function update(Request $request, $id)
    {
        $request->merge(['_id' => decrypt($id)]);
        $result = $this->repo->startProcess('update', $request);
        return $this->serveJSON($result);
    }

    public function destroy(Request $request, $id)
    {
        $request->merge(['_id' => decrypt($id)]);
        $result = $this->repo->startProcess('destroy', $request);
        return $this->serveJSON($result);
    }

    public function destroys(Request $request)
    {
        $result = $this->repo->startProcess('destroys', $request);
        return $this->serveJSON($result);
    }

    public function restore(Request $request, $id)
    {
        $request->merge(['_id' => decrypt($id)]);
        $result = $this->repo->startProcess('restore', $request);
        return $this->serveJSON($result);
    }

    public function download(Request $request)
    {
        return $this->repo->startProcess('download', $request);
    }

    public function import()
    {
        return $this->serveView();
    }

    public function importPost(Request $request)
    {
        $result = $this->repo->startProcess('import', $request);
        return $this->serveJSON($result);
    }
}
