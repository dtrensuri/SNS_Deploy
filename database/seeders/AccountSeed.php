<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Account;

class AccountSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            [
                'user_id' => 1,
                'platform' => 'twitter',
                'consumer_key' => 'X3jC4zlQOLghHf6VUi638HllZ',
                'consumer_secret' => 'j3UhGocCyRXKzvZdbva28zmy2ZtiNPsoSdQ1ZTqaXjv15Njrxt',
                'bearer_token' => 'AAAAAAAAAAAAAAAAAAAAAByyqgEAAAAAotOeoQRAKexbyLBFCE8bgqSDW9Q%3D46aG7qVqAW2NxkP6HTw1hK8RFKL2NpdZSa2Ci1m5wOyJ3c9WKP',
                'access_token' => '1713798306512752640-naLgw2jXypPhDN8gKAJn68LQsCImy0',
                'refresh_token' => 'RyT9eItBSRyQEUakeuZ9EqgWN8UXDgSav4Qi0iZ7XSLNM',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 1,
                'platform' => 'facebook',
                'app_id' => '294807996829636',
                'app_secret' => 'a3b4d3c90c63839ecae759ace75ccff9',
                'access_token' => 'EAAEMIFXtj8QBO5o3FZCTLheNg0HZATHskCbVf0KZAi8EqlLyH1qJH0HFpLNTYOslCtQJYHsV2fkGn9L2DvrXxZCgwce5Tv1vENZCYE3jscKu95uRIirZCQ3ycqPo9TZBUTQ81bWrb80mOoifcZC42WDcVFoovhClleL0K4H7v8QCouXZAF4nyLA6pkeeaWQ8BHPIHNHhCkXbxelZAZB5654VTSHuuL2KwChLwo2qvVnVtnRU0iqukeZAe4LBkZCrj3w8VKRVl8WPJLaxSrZAYZD',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        foreach ($data as $record) {
            Account::create($record);
        }

    }
}
