<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rank;

class RanksSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['name'=>'Data Entry Operator','code'=>'deo'],
            ['name'=>'Traffic Warden','code'=>'tw'],
            ['name'=>'Senior Traffic Warden','code'=>'stw'],
            ['name'=>'Deputy Superintendent','code'=>'ds'],
            ['name'=>'Superintendent','code'=>'sp'],
            ['name'=>'Deputy Inspector General', 'code'=>'dig'],
            ['name'=>'Additional Inspector General','code'=>'addl_ig'],
            ['name'=>'Inspector General','code'=>'ig'],
        ];

        foreach ($items as $it) {
            Rank::firstOrCreate(['name'=>$it['name']], $it);
        }
    }
}
