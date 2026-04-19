<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $demoUser = User::firstOrCreate(
            ['email' => 'demo@voxara.local'],
            [
                'name' => 'Pengguna Demo',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'user',
            ]
        );

        $documents = [
            [
                'title' => 'Bangun Datar Segitiga',
                'original_filename' => 'segitiga_matematika.pdf',
                'preview_text' => 'Segitiga adalah bangun datar yang memiliki tiga sisi dan tiga sudut. Rumus luas segitiga adalah 1/2 x alas x tinggi. Keliling segitiga adalah jumlah dari ketiga sisinya.',
                'status' => 'ready',
                'collection_id' => 'doc_seed_001',
            ],
            [
                'title' => 'Persamaan Linear Satu Variabel',
                'original_filename' => 'persamaan_linear.pdf',
                'preview_text' => 'Persamaan linear satu variabel adalah kalimat terbuka yang dihubungkan oleh tanda sama dengan (=) dan hanya memiliki satu variabel. Bentuk umum: ax + b = c, dengan a ≠ 0.',
                'status' => 'ready',
                'collection_id' => 'doc_seed_002',
            ],
            [
                'title' => 'Teorema Pythagoras',
                'original_filename' => 'pythagoras_smp.pdf',
                'preview_text' => 'Teorema Pythagoras menyatakan bahwa pada segitiga siku-siku, kuadrat sisi miring (hipotenusa) sama dengan jumlah kuadrat kedua sisi lainnya. a² + b² = c².',
                'status' => 'ready',
                'collection_id' => 'doc_seed_003',
            ],
            [
                'title' => 'Himpunan dan Operasi Himpunan',
                'original_filename' => 'himpunan_sma.pdf',
                'preview_text' => 'Himpunan adalah kumpulan objek yang memiliki sifat sama. Operasi himpunan meliputi: union (∪), irisan (∩), selisih (−), dan komplemen (Aᶜ).',
                'status' => 'ready',
                'collection_id' => 'doc_seed_004',
            ],
            [
                'title' => 'Trigonometri Dasar',
                'original_filename' => 'trigonometri_dasar.pdf',
                'preview_text' => 'Trigonometri mempelajari hubungan antara sudut dan sisi segitiga. Tiga fungsi utama: sin θ = depan/miring, cos θ = samping/miring, tan θ = depan/samping.',
                'status' => 'ready',
                'collection_id' => 'doc_seed_005',
            ],
        ];

        foreach ($documents as $doc) {
            Document::updateOrCreate(
                ['title' => $doc['title'], 'user_id' => $demoUser->id],
                [
                    'original_filename' => $doc['original_filename'],
                    'file_path' => 'documents/seed/' . $doc['original_filename'],
                    'preview_text' => $doc['preview_text'],
                    'status' => $doc['status'],
                    'collection_id' => $doc['collection_id'],
                ]
            );
        }
    }
}
