<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Document;
use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ConversationSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();
        $documents = Document::where('status', 'ready')->get();

        $conversationData = [
            [
                'user' => 'demo@voxara.local',
                'document' => 'Teorema Pythagoras',
                'messages' => [
                    ['role' => 'user', 'content' => 'Apa itu teorema Pythagoras?'],
                    ['role' => 'assistant', 'content' => 'Teorema Pythagoras menyatakan bahwa pada segitiga siku-siku, kuadrat sisi miring (hipotenusa) sama dengan jumlah kuadrat kedua sisi lainnya. Rumusnya: a² + b² = c², di mana c adalah sisi miring.'],
                    ['role' => 'user', 'content' => 'Beri contoh soal sederhana'],
                    ['role' => 'assistant', 'content' => 'Tentu! Soal: Sebuah segitiga siku-siku memiliki sisi tegak 3 cm dan 4 cm. Berapa panjang sisi miringnya? Penyelesaian: a² + b² = c² → 3² + 4² = c² → 9 + 16 = c² → c² = 25 → c = 5 cm.'],
                ],
            ],
            [
                'user' => 'demo@voxara.local',
                'document' => 'Persamaan Linear Satu Variabel',
                'messages' => [
                    ['role' => 'user', 'content' => 'Jelaskan cara menyelesaikan 2x + 5 = 13'],
                    ['role' => 'assistant', 'content' => 'Langkah-langkah: 1) Kurangi kedua ruas dengan 5 → 2x + 5 - 5 = 13 - 5 → 2x = 8. 2) Bagi kedua ruas dengan 2 → 2x / 2 = 8 / 2 → x = 4. Jadi nilai x adalah 4.'],
                ],
            ],
            [
                'user' => 'demo@voxara.local',
                'document' => 'Trigonometri Dasar',
                'messages' => [
                    ['role' => 'user', 'content' => 'Apa itu sin, cos, dan tan?'],
                    ['role' => 'assistant', 'content' => 'Ketiganya adalah perbandingan sisi segitiga siku-siku: sin θ = sisi depan / sisi miring; cos θ = sisi samping / sisi miring; tan θ = sisi depan / sisi samping.'],
                ],
            ],
        ];

        foreach ($conversationData as $convData) {
            $user = $users->firstWhere('email', $convData['user']);
            $document = $documents->firstWhere('title', $convData['document']);

            if (!$user || !$document) {
                continue;
            }

            $conversation = Conversation::create([
                'user_id' => $user->id,
                'document_id' => $document->id,
            ]);

            foreach ($convData['messages'] as $msg) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'role' => $msg['role'],
                    'content' => $msg['content'],
                ]);
            }
        }
    }
}
