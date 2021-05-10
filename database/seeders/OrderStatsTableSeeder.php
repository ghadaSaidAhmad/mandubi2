<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderStatsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //client create order 
        \DB::table('order_stats')->insert([
            'code' => 1,
            'description_ar' => 'طلب جديد',
            'description_en' => ' new order '
        ]);
        \DB::table('order_stats')->insert([
            'code' => 2,
            'description_ar' => ' المندوب وافق على الرحله',
            'description_en' => 'madoub Approved the order '
        ]);
        \DB::table('order_stats')->insert([
            'code' => 3,
            'description_ar' => ' العميل وافق على المندوب  ',
            'description_en' => 'client approved the mandoun'
        ]);
        \DB::table('order_stats')->insert([
            'code' => 4,
            'description_ar' => 'المندوب بدأ الرحله',
            'description_en' => 'mandob start trip'
        ]);
        \DB::table('order_stats')->insert([
            'code' => 5,
            'description_ar' => 'المندوب وصل مكان التسليم ',
            'description_en' => 'mandoun has arrived'
        ]);
        \DB::table('order_stats')->insert([
            'code' => 6,
            'description_ar' => 'تم تسليم الاوردر بنجاح',
            'description_en' => 'delivered order '
        ]);
        \DB::table('order_stats')->insert([
            'code' => 7,
            'description_ar' => 'تم الغاء تسليم الاورد',
            'description_en' => 'order canceled'
        ]);
        \DB::table('order_stats')->insert([
            'code' => 8,
            'description_ar' => 'المندوب فى طريق العوده للعميل',
            'description_en' => 'mandoun start back to client'
        ]);
        \DB::table('order_stats')->insert([
            'code' => 9,
            'description_ar' => 'المندوب وصلع عائدا للعميل',
            'description_en' => 'mandoub arrived to client'
        ]);
        \DB::table('order_stats')->insert([
            'code' =>10,
            'description_ar' => ' العميل حصل حق المنتج او استرد المنتج   ',
            'description_en' => 'client deliverd his mony or product'
        ]);

    }
}
