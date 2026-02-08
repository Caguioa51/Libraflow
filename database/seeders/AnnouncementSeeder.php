<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SystemSetting;

class AnnouncementSeeder extends Seeder
{
    public function run()
    {
        SystemSetting::set('library_announcement', 'Test announcement from seeder. Please return books on time.', 'string', 'seeded announcement');
    }
}
