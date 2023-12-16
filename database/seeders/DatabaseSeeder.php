<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Size;
use App\Models\Unit;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Period;
use App\Models\Setting;
use App\Models\Village;
use App\Models\Category;
use App\Models\Customer;
use App\Models\AdminLevel;
use App\Models\SubDistrict;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        Brand::create([
            'id' => '1',
            'name' => 'ABC',
        ]);

        Unit::create([
            'id' => '1',
            'initial' => 'Pcs',
            'name' => 'Pieces',
        ]);
        
        Size::create([
            'id' => '1',
            'initial' => 'S',
            'name' => 'Small',
        ]);
        Size::create([
            'id' => '2',
            'initial' => 'M',
            'name' => 'Medium',
        ]);
        Size::create([
            'id' => '3',
            'initial' => 'L',
            'name' => 'Large',
        ]);
        Size::create([
            'id' => '4',
            'initial' => 'XL',
            'name' => 'Extra Large',
        ]);
        
        Category::create([
            'id' => '1',
            'name' => 'Pakaian Pria',
        ]);
        
        Category::create([
            'id' => '2',
            'name' => 'Pakaian Wanita',
        ]);

        AdminLevel::create([
            'id' => '1',
            'name' => 'Kepala Toko',
        ]);
        
        AdminLevel::create([
            'id' => '2',
            'name' => 'Admin Staf',
        ]);

        Admin::create([
            'code' => 'ADM'.date('Ymd').'1',
            'fullname' => 'Admin Cahaya Baru',
            'username' => 'admin_123',
            'gender' => 'M',
            'phone' => '08986564321',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('111111'),
            'level_id' => 1,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 'admin_123',
        ]);

        Customer::create([
            'code' => 'CCB'.date('Ymd').'0001',
            'fullname' => 'User Test',
            'username' => 'user_test',
            'gender' => 'M',
            'phone' => '08986564321',
            'email' => 'test@gmail.com',
            'password' => Hash::make('111111'),
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
        ]);

    }
}
