<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ShippingTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    
        \DB::table('shipping_types')->insert([
            'name' => "car"
        ]);
        \DB::table('shipping_types')->insert([
            'name' => "Bicycle"
        ]);
        
    }
}
