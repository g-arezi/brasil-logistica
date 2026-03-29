<?php

use App\Models\User;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Support\Facades\Hash;

require 'vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Kernel::class);
$kernel->bootstrap();
$user = User::where('email', 'admin@demo.com')->first();
$user->password = Hash::make('ASDKASKD1q23easDASD12@@!#%ç');
$user->save();
