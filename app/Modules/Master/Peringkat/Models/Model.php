<?php
namespace App\Modules\Master\Peringkat;

use App\Bases\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\LogOptions;

class Model extends BaseModel
{
    use SoftDeletes;

    protected static $logFillable = true;

    protected $table      = 'peringkat';
    protected $primaryKey = 'id';
    public $incrementing  = false;
    protected $keyType    = 'string'; // karena UUID

    protected $fillable = [
        'label',
        'tingkat',
        'color',
        'status',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable()->useLogName('Master Peringkat');
    }
}
