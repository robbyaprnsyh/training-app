<?php
namespace App\Modules\Master\Parameter;

use App\Bases\BaseModule;
use App\Modules\Master\Parameter\Repository;
use App\Modules\Master\Parameter\Service;
use App\Modules\Master\Peringkat\Model as Peringkat;
use Illuminate\Http\Request;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo   = $repo;
        $this->module = 'master.parameter';
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
        $peringkat = Peringkat::select('id', 'label')->orderBy('tingkat')->get();

        return $this->serveView([
            'module'    => $this->module,
            'peringkat' => $peringkat,
            'title'     => 'Tambah Parameter',
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->repo->startProcess('store', $request);
        return $this->serveJSON($result);
    }

    public function edit($id)
    {
        $data      = Service::get(decrypt($id));
        $peringkat = Peringkat::select('id', 'label')->orderBy('tingkat')->get();

        return $this->serveView([
            'module'    => $this->module,
            'data'      => $data,
            'peringkat' => $peringkat,
            'title'     => 'Edit Parameter',
        ]);
    }

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
        return $this->serveView([
            'title'  => 'Import Parameter',
            'module' => $this->module,
        ]);
    }

    public function importPost(Request $request)
    {
        $result = $this->repo->startProcess('import', $request);
        return $this->serveJSON($result);
    }
}
