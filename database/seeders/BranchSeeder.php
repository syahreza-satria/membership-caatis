<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Branch::create([
            'name' => 'Lakeside',
            'address' => 'depan FIK',
            'logo' => 'img/CabangLakeside.png',
            'api_url' => 'https://pos.lakesidefnb.group/api',
            'api_token' => 'p0s-fnb-@p1-t0k3n-2024-xnxx',
            'outletId' => 'OUT-AUWXFVYRPA',
            'order_type' => 'takeaway'
        ]);

        Branch::create([
            'name' => 'Lakeside FIT+',
            'address' => 'Pos Satpam Gate 4',
            'logo' => 'img/CabangFIT.png',
            'api_url' => 'https://pos.lakesidefnb.group/api',
            'api_token' => 'p0s-fnb-@p1-t0k3n-2024-xnxx',
            'outletId' => 'OUT-UP6VLASEJX',
            'order_type' => 'takeaway'
        ]);

        Branch::create([
            'name' => 'Literasi Cafe',
            'address' => 'Open Library',
            'logo' => 'img/CabangLiterasiCafe.png',
            'api_url' => 'https://pos.lakesidefnb.group/api',
            'api_token' => 'p0s-fnb-@p1-t0k3n-2024-xnxx',
            'outletId' => 'OUT-GCNV7MW5YK',
            'order_type' => 'takeaway'
        ]);
        Branch::create([
            'name' => 'Harmony cafe',
            'address' => 'Depan Rektorat',
            'logo' => 'img/Harmony Cafe.png',
            'api_url' => 'https://pos.lakesidefnb.group/api',
            'api_token' => 'p0s-fnb-@p1-t0k3n-2024-xnxx',
            'outletId' => 'OUT-I0KWK8GSNN',
            'order_type' => 'dinein'
        ]);
    }
}
