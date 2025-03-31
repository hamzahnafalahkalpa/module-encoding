<?php

namespace Hanafalah\ModuleEncoding\Enums\Employee;

enum EmployeeStatus: string
{
    case DRAFT          = 'Draft';
    case ACTIVE         = 'Active';
    case INACTIVE       = 'Inactive';
    case DELETED        = 'Deleted';
    case RETIRED        = 'Retired';
    case CONTRACT_ENDED = 'Contract Ended';
    case INTERN         = 'Intern';
    case PROBATION      = 'Probation';
    case DECEASED       = 'Deceased';
    case RESIGNED       = 'Resigned';
}
