<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Designation;

class DesignationsSeeder extends Seeder
{
    public function run()
    {
        $items = [
            ['name'=>'Incharge', 'code'=>'incharge'],
            ['name'=>'Duty Officer', 'code'=>'duty_officer'],
            ['name'=>'Data Entry Operator', 'code'=>'deo'],
            ['name'=>'Circle Officer', 'code'=>'circle_officer'],
            ['name'=>'Chief Traffic Officer', 'code'=>'cto'],
            ['name'=>'Reader', 'code'=>'reader'],
            ['name'=>'Accountant', 'code'=>'accountant'],
        ];

        foreach ($items as $it) {
            Designation::firstOrCreate(['name'=>$it['name']], $it);
        }
    }
}
