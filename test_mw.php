<?php

use App\Http\Middleware\EnsureUserProfile;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();
// simulate what Laravel does:
$mw = new EnsureUserProfile;
// If route specifies profile:admin,company ... how are they passed?
