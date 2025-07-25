<?php
namespace App\Modules\Master\Jabatan;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;

class Model extends BaseModel
{
    use SoftDeletes;
    protected static $logFillable = true;
    protected $table      = 'master_jabatan';
    protected $primaryKey = 'code';
     public $incrementing = false; // karena primary key bukan integer
    protected $keyType    = 'string';
    
    protected $fillable = [
        'name', 'code', 'status',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogname('Master Jabatan');
    }
}
