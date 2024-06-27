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
            'logo' => '/img/CabangLakeside.png'
        ]);

        Branch::create([
            'name' => 'Lakeside FIT+',
            'address' => 'Pos Satpam Gate 4',
            'logo' => '/img/CabangFIT.png'
        ]);

        Branch::create([
            'name' => 'Literasi Cafe',
            'address' => 'Open Library',
            'logo' => '/img/CabangLiterasiCafe.png'
        ]);
    }
}
