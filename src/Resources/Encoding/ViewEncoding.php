<?php

namespace Hanafalah\ModuleEncoding\Resources\Encoding;

use Hanafalah\LaravelSupport\Resources\ApiResource;
use Hanafalah\ModuleEncoding\Resources\ModelHasEncoding\ShowModelHasEncoding;

class ViewEncoding extends ApiResource
{
  /**
   * Transform the resource into an array.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
   */
  public function toArray(\Illuminate\Http\Request $request): array
  {
    $arr = [
      'id'                   => $this->id,
      'name'                 => $this->name,
      'label'                 => $this->label,
      'model_has_encoding'   => $this->relationValidation('modelHasEncoding', function () {
        return $this->modelHasEncoding->toShowApi()->resolve();
      }),
      'created_at'           => $this->created_at,
      'updated_at'           => $this->updated_at
    ];

    return $arr;
  }
}
