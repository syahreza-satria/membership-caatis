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
            'api_url' => 'https://pos.lakesidefnb.group/api/outlet',
            'api_token' => '92|BN2EvdcWabONwrvbSIbFgSZyPoEoFwjsRwse7li6',
            'outletId' => 'OUT-AUWXFVYRPA',
        ]);

        Branch::create([
            'name' => 'Lakeside FIT+',
            'address' => 'Pos Satpam Gate 4',
            'logo' => 'img/CabangFIT.png',
            'api_url' => 'https://pos.lakesidefnb.group/api/outlet',
            'api_token' => '92|BN2EvdcWabONwrvbSIbFgSZyPoEoFwjsRwse7li6',
            'outletId' => 'OUT-UP6VLASEJX',
        ]);

        Branch::create([
            'name' => 'Literasi Cafe',
            'address' => 'Open Library',
            'logo' => 'img/CabangLiterasiCafe.png',
            'api_url' => 'https://pos.lakesidefnb.group/api/outlet',
            'api_token' => '92|BN2EvdcWabONwrvbSIbFgSZyPoEoFwjsRwse7li6',
            'outletId' => 'OUT-GCNV7MW5YK',
        ]);
        Branch::create([
            'name' => 'Harmony cafe',
            'address' => 'Depan Rektorat',
            'logo' => 'img/Harmony Cafe.png',
            'api_url' => 'https://pos.lakesidefnb.group/api/outlet',
            'api_token' => '92|BN2EvdcWabONwrvbSIbFgSZyPoEoFwjsRwse7li6',
            'outletId' => 'OUT-I0KWK8GSNN',
        ]);
    }
}
