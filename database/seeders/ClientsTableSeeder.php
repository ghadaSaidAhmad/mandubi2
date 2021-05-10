<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        //api client
        \DB::table('clients')->insert([
                'name' =>'client',
                'email' => 'client@gmail.com',
                'phone' => '010123456789',
                'verification_code' => '123456',
                'admin_agree' => 1,
                'complete_register' => 1,
                'phone_verified_at' => $faker->dateTimeBetween('now', '+1  months'),
                'password' => \Hash::make('010123456789')
        ]);

        $limit = 10;
        for ($i = 0; $i < $limit; $i++) {
            \DB::table('clients')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->email,
                'phone' => $faker->phoneNumber,
                'verification_code' => $faker->numberBetween(1,6),
                'admin_agree' => 1,
                'password' => \Hash::make('password')
            ]);
        }
    }
}
