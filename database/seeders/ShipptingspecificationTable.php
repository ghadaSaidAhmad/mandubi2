<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ShipptingspecificationTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('Shipping_specifications')->insert([
            'name' => "clothes"
        ]);
        \DB::table('shipping_types')->insert([
            'name' => "mobiles"
        ]);
    }
}
