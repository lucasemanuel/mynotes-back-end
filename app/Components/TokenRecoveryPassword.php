<?php

namespace App\Components;

use Faker\Provider\Base as Faker;

class TokenRecoveryPassword
{
    public static function generate()
    {
        return Faker::bothify('********');
    }
}

