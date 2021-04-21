<?php

namespace App\Observers;

use App\Components\TokenRecoveryPassword;
use App\RecoveryPassword;

class RecoveryPasswordObserver
{
    public function creating(RecoveryPassword $recoveryPassword)
    {
        $recoveryPassword->token = TokenRecoveryPassword::generate();
    }
}
