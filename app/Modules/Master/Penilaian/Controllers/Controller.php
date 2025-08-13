<?php
namespace App\Modules\Master\Penilaian;

use App\Bases\BaseModule;
use App\Modules\Master\Parameter\ModelParameter as Parameter;
use App\Modules\Master\Penilaian\Repository;
use App\Modules\Master\Peringkat\Model as Peringkat;
use App\Modules\Master\Unitkerja\Model as UnitKerja;
use Illuminate\Http\Request;

class Controller extends BaseModule
{
    private Repository $repo;

    public function __construct(Repository $repo)
    {
        $this->repo   = $repo;
        $this->module = 'master.penilaian';
        parent::__construct();
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageTitle);

        $parameters = Service::parameters([]);
        $unitKerjas = UnitKerja::all();
        $peringkats = Peringkat::all();

        return $this->serveView([
            'parameters'  => $parameters,
            'unit_kerjas' => $unitKerjas,
            'peringkats'  => $peringkats,
        ]);
    }

    public function data(Request $request)
    {
        $result = $this->repo->startProcess(operation_type: 'data', request: $request);
        return $this->serveJSON($result);
    }

    public function create(Request $request)
    {
        $parameterId = $request->get('parameter_id');
        $parameter = Parameter::findOrFail($parameterId);
        $parameters = Parameter::all();
        $peringkat  = Peringkat::all();

        return $this->serveView([
            'parameters' => $parameters,
            'parameter' => $parameter,
            'peringkat'  => $peringkat,
            'module'    => $this->module
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->repo->startProcess('store', $request);
        return $this->serveJSON($result);
    }

    public function edit($id)
    {
        $id = decrypt($id);
        // $data       = $this->repo->startProcess('get', ['_id' => $id]);
        $data       = Service::get($id);
        // $parameter = Parameter::all();
        $peringkat  = Peringkat::all();

        return $this->serveView([
            'data'       => $data,
            // 'parameter' => $parameter,
            'peringkat'  => $peringkat,
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
}
