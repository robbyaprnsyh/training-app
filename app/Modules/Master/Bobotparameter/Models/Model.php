<?php
namespace App\Modules\Master\Bobotparameter;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Model extends BaseModel
{
    use SoftDeletes;
    protected static $logFillable = true;
    protected $primaryKey         = 'id';
    protected $table              = 'bobot_parameter';
    public $incrementing          = false;
    protected $keyType            = 'string';
    protected $fillable           = [
        'parameter_id',
        'bobot',
    ];

    public function parameter()
    {
        return $this->belongsTo(\App\Modules\Master\Parameter\ModelParameter::class, 'parameter_id');
    }
}
