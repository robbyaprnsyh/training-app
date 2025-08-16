<?php
namespace App\Modules\Master\Bobotparameter;

use App\Bases\BaseModule;
use App\Modules\Master\Bobotparameter\Repository;
use App\Modules\Master\Bobotparameter\Service;
use Illuminate\Http\Request;
use App\Modules\Master\Bobotparameter\Model as ModelBobotparameter;

class Controller extends BaseModule
{
    private $repo;

    public function __construct(Repository $repo)
    {
        $this->repo   = $repo;
        $this->module = 'master.bobotparameter';
        parent::__construct();
    }

    public function index()
    {
        activity('Akses menu')->log('Akses menu ' . $this->pageTitle);
        $parameters = \App\Modules\Master\Parameter\ModelParameter::all();
        $bobot = ModelBobotparameter::pluck('bobot', 'parameter_id')->toArray();

        return $this->serveView([
            'parameters' => $parameters,
            'bobot' => $bobot,
        ]);
    }

    public function data(Request $request)
    {
        $result = $this->repo->startProcess('data', $request);
        return $this->serveJSON($result);
    }

    public function create()
    {
        // Ambil semua parameter sebagai komponen bobot
        $availableParameter = Service::getAvailableParameter();

        return $this->serveView([
            'availableParameter' => $availableParameter,
        ]);
    }

    public function store(Request $request)
    {
        $result = $this->repo->startProcess('store', $request);
        return $this->serveJSON($result);
    }

    public function edit($id)
    {
        $id   = decrypt($id);
        $data = Service::get($id);

        return $this->serveView([
            'data' => $data,
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
