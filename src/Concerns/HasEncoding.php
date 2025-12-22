<?php

namespace Hanafalah\ModuleEncoding\Concerns;

use Carbon\Carbon;
use Hanafalah\LaravelSupport\Concerns\Support\HasCache;
use Hanafalah\LaravelSupport\Facades\SupportCache;

trait HasEncoding
{
    use HasCache;

    private static bool $__should_reset = false;

    public static function hasEncoding(string $label, ?bool $is_update = true): mixed{
        $encoding = static::getEncodingData($label);
        if (isset($encoding)) {
          try {
            return self::generateCode($encoding['label'],$is_update);
          } catch (\Throwable $th) {
            throw new \Exception($th->getMessage() . ' : label ' . $label . ' di model ' . (new static)->getMorphClass());
          }
        }
        return null;
    }

    public static function generateCode(string $label,?bool $is_update = true): string{
        $encoding_cache = config('laravel-support.encoding_cache_data');
        $encoding_id =  SupportCache::getSavedCache('encoding_config')[$label] ?? null;
        if (!isset($encoding_id)) return '';
        $model_has_encoding_caches = SupportCache::getSavedCache('model_has_encoding_configs');
        $find_encoding_idx = array_search($encoding_id, $model_has_encoding_caches['model_has_encoding_ids']);
        if (!is_numeric($find_encoding_idx)) return '';

        $model_has_encodings = &$model_has_encoding_caches['model_has_encodings'];
        $model_has_encoding = &$model_has_encodings[$find_encoding_idx];
        if (isset($model_has_encoding) && isset($model_has_encoding->structure)) {
            $structure       = $model_has_encoding->structure;
            $separator       = $model_has_encoding->separator;
            $result          = [];
            foreach ($structure as &$part) {
                switch ($part['type']) {
                    case 'alphanumeric': static::alphanumeric($result,$part);break;
                    case 'incrementing': static::incrementing($result,$part);break;                        
                    case 'date'        : static::formatingDate($result,$part);break;                        
                    default            : throw new \Exception("Unknown type: {$part['type']}");
                }
            }
            $finalResult = '';
            $distance    = $separator['distance'] ?? 0;
            foreach ($result as $key => $result_data) {
                if ($distance > 0) {
                    if ($key > 0 && $key % $distance == 0) $finalResult .= $separator['separator'];
                }
                $finalResult .= $result_data;
            }
            $model_has_encoding->value = $finalResult;
            $model_has_encoding->setAttribute('structure', $structure);
            SupportCache::saveCache('model_has_encoding_configs', $model_has_encoding_caches);
            return $finalResult;
        }
        return '';
    }

    // public static function getEncodingModelByLabel(string $label): ?Model{
    //     return config('model-encoding.cache_model.'.$label,null) ?? app(config('database.models.ModelHasEncoding'))->with('encoding')->whereHas("encoding",fn ($query) => $query->where('label',$label))->first();
    // }

    public static function getEncodingData(string $label): ?array{
        return config()->get("module-encoding.encodings.$label") ?? null;
    }

    
    //LOGICAL SECTION
    private static function alphanumeric(&$result,&$part){
        $part['length'] = $length = strlen($part['value']);
        $result[]       = str_pad($part['value'], $length, ' ', STR_PAD_RIGHT);
    }

    private static function incrementing(&$result,&$part){
        if ($part['length'] == 0) $part['length'] = 1;
        if (static::$__should_reset){
            $part['value']  = 0;
            $part['length'] = 1;
        }else{
            $part['value'] ??= 0;
            $part['value']++;
        }
        $incrementPart = str_pad($part['value'], $part['length'], '0', STR_PAD_LEFT);

        $part['value'] = $part['value'];
        $result[]      = $incrementPart;

        if ($part['value'] >= (10 ** $part['length']) - 1) $part['length']++;
    }

    private static function formatingDate(&$result,&$part){
        $part['format'] ??= 'YYYY-MM-DD';
        $formatted_maps = [
            'YYYY'       => ['Y', 4],
            'YYYY-MM'    => ['Ym', 6],
            'YYYY-MM-DD' => ['Ymd', 8],
            'DD-MM-YYYY' => ['dmY', 8],
            'MM-YYYY'    => ['mY', 6]
        ];
        list($format, $part['length']) = $formatted_maps[$part['format']];
        $current_date = now()->format($format);
        $result[]     = $current_date;

        $part['value'] = $current_date;
        if (isset($part['resetable'])) {
            static::resetIncrementForNewPeriod($part['resetable'], $format, $part);
        }
    }

    protected static function resetIncrementForNewPeriod($resetable, $format, &$part){
        $currentDate   = now();
        $formattedDate = Carbon::createFromFormat($format, $part['value']);
        static::$__should_reset = match ($resetable) {
            'year'  => !$currentDate->isSameYear($formattedDate),
            'month' => !$currentDate->isSameMonth($formattedDate),
            'day'   => !$currentDate->isSameDay($formattedDate),
            default => false
        };
    }
    //END LOGICAL SECTION

    //EIGER SECTION
    public function modelHasEncoding(){return $this->morphOneModel('ModelHasEncoding', 'reference');}
    public function modelHasEncodings(){return $this->morphManyModel('ModelHasEncoding', 'reference');}
    public function encoding(){
        $encoding = $this->EncodingModel();
        return $this->hasOneThroughModel(
            'Encoding',
            'ModelHasEncoding',
            'reference_id',
            $encoding->getKeyName(),
            $this->getKeyName(),
            $encoding->getForeignKey()
        )->where('reference_type', $this->getMorphClass());
    }

    public function encodings(){
        return $this->belongsToManyModel(
            'Encoding',
            'ModelHasEncoding',
            'reference_id',
            $this->EncodingModel()->getForeignKey()
        )->where('reference_type', $this->getMorphClass());
    }
    //END EIGER SECTION
}
