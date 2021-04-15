<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use App\Models\Usuario;
class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        "/api/login",
        "/api/auditor/audits",
        "/api/auditor/saveTracing",
        "/api/auditor/deleteTracing"
    ];
}
