<?php

namespace Hanafalah\ModuleEncoding\Models\Encoding;

use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\ModuleEncoding\Resources\Encoding\ViewEncoding;

class Encoding extends BaseModel
{
    protected $list = ['id', 'name', 'flag'];    

    public function getViewResource(){
        return ViewEncoding::class;
    }

    public function getShowResource(){
        return $this->getViewResource();
    }

    public function modelHasEncoding(){
        return $this->hasOneModel('ModelHasEncoding');
    }
}
