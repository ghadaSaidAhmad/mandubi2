<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use App\Models\ShippingType;
use App\Models\Governorate;
class MandubsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
         //api mandub
         \DB::table('mandubs')->insert([
                'name' =>'mandub',
                'email' => 'client@gmail.com',
                'phone' => '012123456789',
                'whats_number' => '010123456789',
                'verification_code' => '123456',
                'admin_agree' => 1,
                'complete_register' => 1,
                'shipping_type_id' => 1,
                'governorate_id' => 1,
               'phone_verified_at' => $faker->dateTimeBetween('now', '+1  months'),
                'password' => \Hash::make('012123456789')
        ]);

        $limit = 10;
        for ($i = 0; $i < $limit; $i++) {
            
            \DB::table('mandubs')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'phone' => $faker->phoneNumber,
                'whats_number' => $faker->phoneNumber,
                'gender' => $faker->numberBetween(1,2),
                'password' => \Hash::make('password'),
                'shipping_type_id' => $faker->numberBetween(1,2),
                'governorate_id' => $faker->numberBetween(1,27),
                'admin_agree' => 1,

            ]);
        }
    }
}
