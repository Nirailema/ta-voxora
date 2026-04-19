<?php

namespace Database\Seeders;

use App\Models\Device;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        Device::create([
            'name' => 'Printer Braille Ruang Lab A',
            'endpoint_url' => 'https://edubraille-lab-a.example.com/api/send',
            'status' => 'active',
        ]);

        Device::create([
            'name' => 'Printer Braille Perpustakaan',
            'endpoint_url' => 'https://edubraille-perpustakaan.example.com/api/send',
            'status' => 'active',
        ]);

        Device::create([
            'name' => 'Printer Braille Ruang Guru',
            'endpoint_url' => 'https://edubraille-guru.example.com/api/send',
            'status' => 'inactive',
        ]);
    }
}
