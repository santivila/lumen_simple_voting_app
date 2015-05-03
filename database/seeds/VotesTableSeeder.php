<?php

use App\Vote;  
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class VotesTableSeeder extends Seeder  
{
    public function run()
    {
        // Using "Faker" library to generate some dummy data
        // https://github.com/fzaninotto/Faker
        $faker = Faker::create();
        for ($i=0; $i < 100; $i++)
        {
            Vote::create([
                'target_id' => $faker->numberBetween($min = 1, $max = 20),
                'target_name' => $faker->sentence($nbWords = 4),
                'voter_email' => $faker->email
            ]);
        }
    }
}