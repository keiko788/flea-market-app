<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\User;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $seller = User::where('email', 'seller@example.com')->first();
        $otherUser = User::where('email', 'buyer@example.com')->first();

        $items = [
            [
                'user_id' => $seller->id,
                'name' => '腕時計',
                'description' => 'スタイリッシュなデザインのメンズ腕時計',
                'image_path' => 'items/watch.jpg',
                'condition' => 1,
                'price' => 15000,
                'brand_name' => 'Rolax',
            ],
            [
                'user_id' => $seller->id,
                'name' => 'HDD',
                'description' => '高速で信頼性の高いハードディスク',
                'image_path' => 'items/hdd.jpg',
                'condition' => 2,
                'price' => 5000,
                'brand_name' => '西芝',
            ],
            [
                'user_id' => $seller->id,
                'name' => '玉ねぎ3束',
                'description' => '新鮮な玉ねぎ3束のセット',
                'image_path' => 'items/onion.jpg',
                'condition' => 3,
                'price' => 300,
                'brand_name' => 'なし',
            ],
            [
                'user_id' => $seller->id,
                'name' => '革靴',
                'description' => 'クラシックなデザインの革靴',
                'image_path' => 'items/shoes.jpg',
                'condition' => 4,
                'price' => 4000,
            ],
            [
                'user_id' => $seller->id,
                'name' => 'ノートPC',
                'description' => '高性能なノートパソコン',
                'image_path' => 'items/laptop.jpg',
                'condition' => 1,
                'price' => '45000',
            ],
            [
                'user_id' => $otherUser->id,
                'name' => 'マイク',
                'description' => '高音質のレコーディング用マイク',
                'image_path' => 'items/mic.jpg',
                'condition' => 2,
                'price' => 8000,
                'brand_name' => 'なし',
            ],
            [
                'user_id' => $otherUser->id,
                'name' => 'ショルダーバッグ',
                'description' => 'おしゃれなショルダーバッグ',
                'image_path' => 'items/bag.jpg',
                'condition' => 3,
                'price' => 3500,
            ],
            [
                'user_id' => $otherUser->id,
                'name' => 'タンブラー',
                'description' => '使いやすいタンブラー',
                'image_path' => 'items/tumbler.jpg',
                'condition' => 4,
                'price' => 500,
                'brand_name' => 'なし',
            ],
            [
                'user_id' => $otherUser->id,
                'name' => 'コーヒーミル',
                'description' => '手動のコーヒーミル',
                'image_path' => 'items/grinder.jpg',
                'condition' => 1,
                'price' => 4000,
                'brand_name' => 'Starbacks',
            ],
            [
                'user_id' => $otherUser->id,
                'name' => 'メイクセット',
                'description' => '便利なメイクアップセット',
                'image_path' => 'items/makeup.jpg',
                'condition' => 2,
                'price' => 2500,
            ],
        ];

        foreach ($items as $item) {
            Item::create($item);
        }
    }
}
