<?php
namespace App\Modules\Master\Penilaian;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;

class Model extends BaseModel
{
    use SoftDeletes;
    protected static $logFillable = true;
    protected $table              = 'penilaian';
    protected $primaryKey         = 'id';
    public $incrementing          = false;
    protected $keyType            = 'string';

    protected $fillable = [
        'id',
        'parameter_id',
        'peringkat_id',
        'nilai',
        'analisa',
        'status',
    ];
    
    public function parameter()
    {
        return $this->belongsTo(\App\Modules\Master\Parameter\ModelParameter::class, 'parameter_id');
    }

    public function peringkat()
    {
        return $this->belongsTo(\App\Modules\Master\Peringkat\Model::class, 'peringkat_id');
    }
}
