<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$admins = \App\Models\User::where('role', 'admin')->get();
foreach ($admins as $a) {
    echo $a->id . ' ' . $a->email . ' ' . $a->name . PHP_EOL;
}
