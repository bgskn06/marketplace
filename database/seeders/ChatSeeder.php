<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conversation;
use App\Models\Message;
use Faker\Factory as Faker;

class ChatSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create('id_ID');

        // === BAGIAN INI DIUBAH ===
        // Masukkan ID User Seller Anda yang sudah ada di database.
        // Misal ID Anda adalah 1.
        $myUserId = 3; 

        $seller = User::find($myUserId);

        if (!$seller) {
            $this->command->error("User dengan ID $myUserId tidak ditemukan! Cek database Anda.");
            return;
        }

        $this->command->info("Menambahkan chat dummy untuk user: " . $seller->name);

        // 2. BUAT 5 USER LAWAN BICARA (Pembeli Dummy)
        // Kita tetap perlu membuat user palsu agar Anda punya teman ngobrol
        $buyers = User::factory(5)->create([
            'seller_status' => 0,
            'status' => 1
        ]);

        // 3. GENERATE PERCAKAPAN
        foreach ($buyers as $buyer) {
            
            // Buat Room Chat
            $conversation = Conversation::create([
                'sender_id' => $seller->id,
                'receiver_id' => $buyer->id,
                'last_message_at' => now(), 
            ]);

            // Buat 10-15 Pesan Balas-balasan
            for ($i = 0; $i < rand(10, 15); $i++) {
                // Random siapa yang kirim (Anda atau Si Buyer)
                $isMeSender = rand(0, 1); 
                $senderId = $isMeSender ? $seller->id : $buyer->id;

                // Waktu mundur biar urut
                $time = now()->subHours(rand(1, 48))->addMinutes($i * 10);

                // Update last_message_at di conversation untuk pesan terakhir
                if ($i == 10) { 
                    $conversation->update(['last_message_at' => $time]);
                }

                Message::create([
                    'conversation_id' => $conversation->id,
                    'user_id' => $senderId,
                    'body' => $faker->realText(rand(20, 80)), // Teks random
                    'is_read' => true,
                    'created_at' => $time,
                    'updated_at' => $time,
                ]);
            }
        }
        
        $this->command->info('Sukses! Silakan refresh halaman chat Anda.');
    }
}