<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(UsersTableSeeder::class);
        $this->call(ClientsTableSeeder::class);
        $this->call(GovernoratesTableSeeder::class);
        $this->call(CitiesTableSeeder::class);
        $this->call(ShippingTypesTableSeeder::class);
        $this->call(MandubsTableSeeder::class);
        $this->call(OrdersTableSeeder::class);
        $this->call(OrderStatsTableSeeder::class);
        $this->call(ShipptingspecificationTable::class);

        
    }
}
