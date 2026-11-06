<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with clean generic starter data.
     */
    public function run(): void
    {
        // 1. Akun demo untuk admin, guru, dan siswa
        User::updateOrCreate(['email' => 'admin@gmail.com'], [
            'name' => 'Admin Sarpras',
            'role' => 'admin',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
        ]);

        User::updateOrCreate(['email' => 'guru@gmail.com'], [
            'name' => 'Bapak Guru Pembina',
            'role' => 'guru',
            'nis' => '198501012010011001',
            'phone' => '081234567892',
            'password' => Hash::make('password123'),
        ]);

        User::updateOrCreate(['email' => 'siswa@gmail.com'], [
            'name' => 'Siswa Demo',
            'role' => 'siswa',
            'nis' => '12345678',
            'phone' => '081234567891',
            'password' => Hash::make('password123'),
        ]);

        // 2. Kategori Sarana & Fasilitas Umum
        $categories = [
            [
                'name' => 'Furnitur & Mebel (Meja, Kursi, Lemari)',
                'icon' => 'bi-door-closed',
                'description' => 'Meja kelas, kursi siswa/guru, lemari kelas, papan tulis, dan rak buku.',
            ],
            [
                'name' => 'Sarana Elektronik & Proyektor',
                'icon' => 'bi-projector',
                'description' => 'LCD Proyektor, layar proyektor, speaker ruang, mic, dan TV display.',
            ],
            [
                'name' => 'Sanitasi, Toilet & Kran Air',
                'icon' => 'bi-droplet-half',
                'description' => 'Kran air, wastafel cuci tangan, kloset, pintu toilet, dan saluran air.',
            ],
            [
                'name' => 'Kelistrikan, Lampu & AC',
                'icon' => 'bi-lightning-charge',
                'description' => 'Unit AC, saklar, stop kontak, lampu penerangan, dan instalasi kabel.',
            ],
            [
                'name' => 'Fasilitas Lab & Jaringan Komputer',
                'icon' => 'bi-display',
                'description' => 'Komputer PC Lab, Monitor, Keyboard, Mouse, Switch Hub, dan Kabel LAN.',
            ],
            [
                'name' => 'Bangunan, Pintu, Jendela & Plafon',
                'icon' => 'bi-building',
                'description' => 'Engsel pintu, kaca jendela, kunci pintu, plafon bocor, dan lantai keramik.',
            ],
            [
                'name' => 'Fasilitas Olahraga & Lapangan',
                'icon' => 'bi-trophy',
                'description' => 'Ring basket, gawang futsal, net voli, net badminton, dan sarana olahraga.',
            ],
            [
                'name' => 'Fasilitas Umum / Lain-lain',
                'icon' => 'bi-tag',
                'description' => 'Fasilitas dan sarana umum sekolah lainnya.',
            ],
        ];

        foreach ($categories as $catData) {
            Category::firstOrCreate(
                ['name' => $catData['name']],
                ['icon' => $catData['icon'], 'description' => $catData['description']]
            );
        }

        // 3. Lokasi & Ruang Sekolah
        foreach ([
            'Lab RPL 1',
            'Lab RPL 2',
            'Lab RPL 3',
            'Ruang Kelas Axioo 1',
            'Ruang Kelas Axioo 2',
            'Ruang Kelas Axioo 3',
            'Lab ASM 1',
            'Lab ASM 2',
            'Lab ASM 3',
            'Ruang Kelas ASM Teori 1',
            'Ruang Kelas ASM Teori 2',
            'Toilet Siswa',
            'Toilet Guru',
            'Ruang Guru RPL',
        ] as $locationName) {
            Location::firstOrCreate(['name' => $locationName]);
        }

    }
}
