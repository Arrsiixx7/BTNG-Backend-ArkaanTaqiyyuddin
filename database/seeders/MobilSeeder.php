<?php

namespace Database\Seeders;

use App\Models\Mobil;
use Illuminate\Database\Seeder;

class MobilSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mobils = [
            [
                'nama' => 'Avanza 1.5 G CVT',
                'brand' => 'Toyota',
                'harga' => 275000000,
                'tahun' => 2024,
                'transmisi' => 'CVT',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800&q=80',
            ],
            [
                'nama' => 'Civic RS Turbo',
                'brand' => 'Honda',
                'harga' => 615000000,
                'tahun' => 2024,
                'transmisi' => 'CVT',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800&q=80',
            ],
            [
                'nama' => 'Ioniq 5 Signature Long Range',
                'brand' => 'Hyundai',
                'harga' => 785000000,
                'tahun' => 2023,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Listrik',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1563720223185-11003d516935?w=800&q=80',
            ],
            [
                'nama' => 'Pajero Sport Dakar 4x2 Ultimate',
                'brand' => 'Mitsubishi',
                'harga' => 680000000,
                'tahun' => 2023,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Diesel',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800&q=80',
            ],
            [
                'nama' => 'Innova Zenix 2.0 Q Hybrid',
                'brand' => 'Toyota',
                'harga' => 625000000,
                'tahun' => 2024,
                'transmisi' => 'CVT',
                'bahan_bakar' => 'Hybrid',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800&q=80',
            ],
            [
                'nama' => 'Brio RS Urbanite Edition',
                'brand' => 'Honda',
                'harga' => 245000000,
                'tahun' => 2023,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Bensin',
                'status' => 'sold',
                'foto' => 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800&q=80',
            ],
            [
                'nama' => 'Jimny 5-Door AllGrip Pro',
                'brand' => 'Suzuki',
                'harga' => 470000000,
                'tahun' => 2024,
                'transmisi' => 'Manual',
                'bahan_bakar' => 'Bensin',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800&q=80',
            ],
            [
                'nama' => 'Air EV Long Range',
                'brand' => 'Wuling',
                'harga' => 299000000,
                'tahun' => 2023,
                'transmisi' => 'Matic',
                'bahan_bakar' => 'Listrik',
                'status' => 'tersedia',
                'foto' => 'https://images.unsplash.com/photo-1542282088-72c9c27ed0cd?w=800&q=80',
            ],
        ];

        foreach ($mobils as $mobil) {
            Mobil::firstOrCreate(
                ['nama' => $mobil['nama']],
                $mobil
            );
        }
    }
}
