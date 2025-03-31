<?php

namespace Hanafalah\ModuleEncoding\Enums\Employee;

enum CardIdentity: string
{
    case SIP = 'SIP';
    case SIK = 'SIK';
    case NIP = 'NIP';
    case STR = 'STR';
    case BPJS_KETENAGAKERJAAN = 'bpjs_ketenagakerjaan';
}
