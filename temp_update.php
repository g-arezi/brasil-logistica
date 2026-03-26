<?php
require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$user = App\Models\User::where('email', 'admin@demo.com')->first();
$user->password = \Illuminate\Support\Facades\Hash::make('ASDKASKD1q23easDASD12@@!#%ç');
$user->save();
