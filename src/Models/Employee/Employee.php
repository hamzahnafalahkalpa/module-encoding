<?php

namespace Hanafalah\ModuleEncoding\Models\Employee;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Hanafalah\LaravelHasProps\Concerns\HasProps;
use Hanafalah\LaravelSupport\Concerns\Support\HasProfilePhoto;
use Hanafalah\LaravelSupport\Models\BaseModel;
use Hanafalah\ModuleCardIdentity\Concerns\HasCardIdentity;
use Hanafalah\ModuleEncoding\Enums\Employee\EmployeeStatus;
use Hanafalah\ModuleEncoding\Resources\Employee\ShowEmployee;
use Hanafalah\ModuleEncoding\Resources\Employee\ViewEmployee;
use Hanafalah\ModuleUser\Concerns\UserReference\HasUserReference;
use Hanafalah\ModuleProfession\Concerns\Relation\HasProfession;

class Employee extends BaseModel
{
    use Notifiable, HasProps, HasProfession,
        HasUserReference, SoftDeletes,
        HasCardIdentity, HasProfilePhoto;

    protected $list = ['id', 'uuid', 'people_id', 'status', 'profile', 'props'];
    protected $show = ['sallary', 'profession_id'];

    protected $casts = [
        'name' => 'string',
        'uuid' => 'string'
    ]; 

    protected $prop_attributes = [
        'People' => [
            'name','first_name','last_name','nik','sex',
            'dob','pob'
        ]
    ];

    public function getPropsQuery(): array{
        return [
            'name' => 'props->prop_people->name',
            'uuid' => 'props->uuid',
        ];
    }

    protected static function booted(): void{
        parent::booted();
        static::creating(function ($query) {
            if (!isset($query->status)) $query->status = EmployeeStatus::DRAFT->value;
        });
    }

    public function getShowResource(){
        return ShowEmployee::class;
    }

    public function getViewResource(){
        return ViewEmployee::class;
    }

    public function people(){
        return $this->belongsToModel('People');
    }
    public function employeeService(){
        return $this->morphOneModel('EmployeeService', 'reference');
    }

    public function employeeServices(){
        return $this->morphManyModel('EmployeeService', 'reference');
    }
}
