<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Destinationpagenumber;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::create([
            'company_name' => 'Ishidaprint',
            'name' => "Manager",
            'email' => "testmanager@gmail.com",
            'furigana_name' => "マネージャー",
            'password' => "123456789",
            'user_role' => '1',
            'phone_number' => "81122456789",
            'post_code' => "100-0005",
            'location' => "東京都千代田区丸の内",
            'street_adress' => "1-2-3",
            'building_name' => "1",
        ]);
        $user_id1 = $user1->id;
        $num1 = array(
            'user_id'=> $user_id1,
            'rowNumber'=> 10 
        );
        Destinationpagenumber::create($num1);
        $user2 = User::create([
            'company_name' => 'Ishidaprint',
            'name' => "InventoryManager",
            'email' => "testinventorymanager@gmail.com",
            'furigana_name' => "在庫マネージャー",
            'password' => "123456789",
            'user_role' => '2',
            'phone_number' => "81123256790",
            'post_code' => "100-0005",
            'location' => "東京都千代田区丸の内",
            'street_adress' => "1-2-5",
            'building_name' => "12",
        ]);
        $user_id2 = $user2->id;
        $num2 = array(
            'user_id'=> $user_id2,
            'rowNumber'=> 10 
        );
        Destinationpagenumber::create($num2);
    }
}
