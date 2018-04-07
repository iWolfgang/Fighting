<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
<<<<<<< HEAD
        'SmsCode/*', 'CheckCode/*'
       
=======
        'SmsCode/*',
        'User/*',
>>>>>>> 8c15cd60fec62b8110f19c6902d4c126d056ca94
    ];
}
