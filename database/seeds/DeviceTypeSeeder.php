<?php

use App\Models\DeviceType;
use Illuminate\Database\Seeder;

class DeviceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DeviceType::create(['name' => 'Desktop']);
        DeviceType::create(['name' => 'Tablet']);
        DeviceType::create(['name' => 'Mobile']);
        DeviceType::create(['name' => 'unkown']);
    }
}
