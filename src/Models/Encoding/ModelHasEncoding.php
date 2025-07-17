<?php

namespace Hanafalah\ModuleEncoding\Models\Encoding;

use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\ModuleEncoding\Resources\ModelHasEncoding\ViewModelHasEncoding;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class ModelHasEncoding extends BaseModel
{
    use HasProps, HasUlids;

    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    protected $list = [
        'id', 'reference_id', 'reference_type', 'encoding_id', 'value', 'props'
    ];

    public function getViewResource(){
        return ViewModelHasEncoding::class;
    }

    public function getShowResource(){
        return $this->getViewResource();
    }

    public function reference(){return $this->morphTo();}
    public function encoding(){return $this->belongsToModel('Encoding');}
}
