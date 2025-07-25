<?php
namespace App\Modules\Master\Parameter;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModelParameter extends BaseModel
{
    use SoftDeletes;
    protected static $logFillable = true;
    protected $table      = 'parameter';
    protected $primaryKey = 'id';
    public $incrementing  = false;
    protected $keyType    = 'string';

    protected $fillable = ['code', 'name', 'tipe_penilaian', 'status'];

    public function rangeKuantitatif()
    {
        return $this->hasMany(ModelParameterKuantitatif::class, 'parameter_id', 'id');
    }

    public function pilihanKualitatif()
    {
        return $this->hasMany(ModelParameterKualitatif::class, 'parameter_id', 'id');
    }
}

class ModelParameterKuantitatif extends BaseModel
{
    use SoftDeletes;
    protected $table = 'parameter_kuantitatif';

    protected $fillable = [
        'parameter_id',
        'operator_min',
        'operator_max',
        'nilai_min',
        'nilai_max',
        'peringkat_id',
    ];

    public function peringkat()
    {
        return $this->belongsTo(\App\Modules\Master\Peringkat\Model::class, 'peringkat_id');
    }
}

class ModelParameterKualitatif extends BaseModel
{
    use SoftDeletes;
    protected $table = 'parameter_kualitatif';

    protected $fillable = [
        'parameter_id',
        'analisa_default',
        'peringkat_id',
    ];

    public function peringkat()
    {
        return $this->belongsTo(\App\Modules\Master\Peringkat\Model::class, 'peringkat_id');
    }
}
