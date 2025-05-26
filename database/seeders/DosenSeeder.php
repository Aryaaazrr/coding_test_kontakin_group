<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('dosen')->insert([
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Dr. Denny Trias Utomo, S.Si, M.T',
                'keahlian' => json_encode(['Software Engineering', 'Machine Learning']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Nugroho Setyo Wibowo, ST. MT',
                'keahlian' => json_encode(['Network Security', 'Cloud Computing']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Prawidya Destarianto, S.Kom, M.T',
                'keahlian' => json_encode(['Database Systems', 'Big Data']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Syamsul Arifin, S.Kom, M.Cs',
                'keahlian' => json_encode(['Web Development', 'Frontend Frameworks']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Moh. Munih Dian Widianta, S.Kom, M.T',
                'keahlian' => json_encode(['Embedded Systems', 'IoT']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Didit Rahmat Hartadi, S.Kom, MT',
                'keahlian' => json_encode(['Cybersecurity', 'Penetration Testing']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Elly Antika, ST, M.Kom',
                'keahlian' => json_encode(['Software Testing', 'Quality Assurance']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Hermawan Arief Putranto, ST, MT',
                'keahlian' => json_encode(['Mobile Development', 'Android']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Aji Seto Arifianto, S.ST., M.T.',
                'keahlian' => json_encode(['Cloud Infrastructure', 'DevOps']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Ratih Ayuninghemi, S.ST, M.Kom',
                'keahlian' => json_encode(['UI/UX Design', 'Graphic Design']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Bety Etikasari, S.Pd, M.Pd',
                'keahlian' => json_encode(['Educational Technology', 'Instructional Design']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Trismayanti Dwi P, S.Kom, M.Cs',
                'keahlian' => json_encode(['Artificial Intelligence', 'Data Mining']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Zilvanhisna Emka Fitri, ST. MT',
                'keahlian' => json_encode(['Network Engineering', 'Telecommunications']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Ery Setiyawan Jullev Atmadji, S.Kom, M.Cs',
                'keahlian' => json_encode(['Artificial Intelligence', 'Computer Vision']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Mukhamad Angga Gumilang, S. Pd., M. Eng.',
                'keahlian' => json_encode(['Software Project Management', 'Agile']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Dia Bitari Mei Yuana, S.ST., M.Tr.Kom.',
                'keahlian' => json_encode(['Network Security', 'Ethical Hacking']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Choirul Huda, S.Kom., M.Kom.',
                'keahlian' => json_encode(['Database Administration', 'SQL Optimization']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Arvita Agus Kurniasari, S.ST.,M.Tr.Kom',
                'keahlian' => json_encode(['Telecommunication Systems', 'Wireless Networks']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'David Juli Ariyadi, S.Kom., M.Kom.',
                'keahlian' => json_encode(['Cloud Computing', 'Virtualization']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id_dosen' => Str::uuid(),
                'nama_lengkap' => 'Fatimatuzzahra, S.Kom., M.Kom',
                'keahlian' => json_encode(['Web Development', 'JavaScript Frameworks']),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
