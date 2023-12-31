<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Size;
use App\Models\Unit;
use App\Models\Admin;
use App\Models\Brand;
use App\Models\Period;
use App\Models\Vendor;
use App\Models\Setting;
use App\Models\Village;
use App\Models\Category;
use App\Models\Customer;
use App\Models\AdminLevel;
use App\Models\SubDistrict;
use App\Models\DeliveryType;
use App\Models\PaymentMethod;
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
        
        AdminLevel::create([
            'id' => '3',
            'name' => 'Driver',
        ]);
        
        Vendor::create([
            'code' => '1',
            'name' => 'Vendor A',
            'address' => 'Tangerang',
        ]);
        
        DeliveryType::create([
            'name' => 'Lokal',
            'description' => 'Pengiriman lokal digunakan untuk pengiriman area Jakarta.',
            'charge' => 5000
        ]);

        DeliveryType::create([
            'name' => 'Ekspedisi',
            'description' => 'Pengiriman menggunakan ekspedisi digunakan untuk pengiriman area luar Jakarta.',
            'charge' => 13000
        ]);

        Admin::create([
            'code' => 'ADM'.date('Ymd').'01',
            'fullname' => 'User Cahaya Baru',
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

        Admin::create([
            'code' => 'ADM'.date('Ymd').'02',
            'fullname' => 'Staf Cahaya Baru',
            'username' => 'staf_toko',
            'gender' => 'M',
            'phone' => '08986564321',
            'email' => 'staf@gmail.com',
            'password' => Hash::make('111111'),
            'level_id' => 2,
            'is_active' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => 'admin_123',
        ]);
        
        Admin::create([
            'code' => 'ADM'.date('Ymd').'03',
            'fullname' => 'Driver Cahaya Baru',
            'username' => 'driver_toko',
            'gender' => 'M',
            'phone' => '08986564321',
            'email' => 'driver@gmail.com',
            'password' => Hash::make('111111'),
            'level_id' => 3,
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

        
        PaymentMethod::create([
            'bank_name' => 'BCA',
            'account_number' => '9390309390'
        ]);
    }
}
