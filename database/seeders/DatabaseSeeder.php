<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Setting;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'name'=>'Admin',
            'email'=>'admin@admin.com',
            'password'=>Hash::make('password'),
            'admin'=>1,
            'address'=>'Batangas City',
            'last_online_at' => now()
        ]);
        User::create([
            'name'=>'Guest User',
            'email'=>'user@user.com',
            'password'=>Hash::make('password'),
            'admin'=>0,
            'mobile_verified_at'=>now(),
            'phone_no'=>'098342545785',
            'address'=>'Batangas City',
            'last_online_at' => now()
        ]);

        Setting::create([
            'invoice_no' => 1,
            'deal_desc' => '<div class="text-lime-600 text-[20px] italic">
                    Best Price For You
                </div>
                <div class="font-semibold text-[40px] mt-1">
                    Deal of the day
                </div>
                <div class="text-gray-500 mt-4">
                    The wise bunny knows the carrot will not to hop to him
                </div>
                <div class="text-lime-600 text-[35px] italic mt-2">Carrot</div>
                <div class="text-gray-500 text-[20px] mt-1">
                    ₱150 <span class="text-lime-600">now ₱100 only</span>
                </div>',
            'deal_date_until' => now()->addDays(1),
            'number' => '09345647385',
            'quote_image' => 'person.jpg',
            'quote_title' => 'Lance Smith',
            'quote_body' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus itaque, autem necessitatibus voluptate quod mollitia delectus aut, sunt placeat nam vero culpa sapiente consectetur similique, inventore eos fugit cupiditate numquam!',
        ]);
         
        Category::insert([
            [
                'name' => 'Main Budget',
            ],
            [
                'name' => 'Rabbit',
            ],
            [
                'name' => 'Food',
            ],
            [
                'name' => 'Accesories',
            ],
            [
                'name' => 'Bundle Package',
            ],
        ]);
    }
}
