<?php

namespace Hanafalah\ModuleEncoding\Models\Encoding;

use Hanafalah\LaravelSupport\Models\Unicode\Unicode;
use Hanafalah\ModuleEncoding\Resources\Encoding\ViewEncoding;

class Encoding extends Unicode
{
    protected $table = 'unicodes';

    public function viewUsingRelation(): array{
        return [
            'modelHasEncoding' => function($query){
                $is_has_reference = isset(request()->reference_id,request()->reference_type);
                $query->when($is_has_reference,function($query){
                    $query->where('reference_id',request()->reference_id)
                          ->where('reference_type',request()->reference_type);
                })->when(!$is_has_reference,function($query){
                    $query->whereRaw('false');
                });
            }
        ];
    }

    public function showUsingRelation(): array{
        return $this->mergeArray($this->viewUsingRelation());
    }

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
