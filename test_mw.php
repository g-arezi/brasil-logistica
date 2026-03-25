<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
// simulate what Laravel does:
$mw = new \App\Http\Middleware\EnsureUserProfile();
// If route specifies profile:admin,company ... how are they passed?
