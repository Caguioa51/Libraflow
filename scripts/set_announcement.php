<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
// Boot framework to allow Eloquent usage
$app->make(Illuminate\Contracts\Console\Kernel::class);
use App\Models\SystemSetting;
SystemSetting::set('library_announcement', 'Test announcement inserted by dev script. Please return books on time.', 'string', 'dev script');
echo "Announcement set\n";
