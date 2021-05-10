<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();
        $limit = 10;
        for ($i = 0; $i < $limit; $i++) {

            \DB::table('recivers')->insert([
                'address_mark' =>$faker->name,
                'name' => $faker->phoneNumber,
                'phone' => $faker->phoneNumber,
            ]);
            
            \DB::table('orders')->insert([
                'client_id' => $faker->numberBetween(1,10),
                'mandub_id' => $faker->numberBetween(1,10),
                'shipping_type_id' => 1,
                'from_lang' => $faker->randomNumber,
                'to_lang' => $faker->randomNumber,
                'from_lat' => $faker->randomNumber,
                'to_lat' => $faker->randomNumber,
                'from_title' => $faker->randomNumber,
                'to_title' => $faker->randomNumber,
                'price' =>$faker->randomNumber,
                'product_price' =>  $faker->randomNumber,
                'code' => $faker->numberBetween(1,6),
                'order_date' => $faker->dateTimeBetween('now', '+1  months'),
                'order_state' => $faker->numberBetween(1,4),// [1,10]
                'reciver_id'=>1


            ]);
        }
    }
    
}
