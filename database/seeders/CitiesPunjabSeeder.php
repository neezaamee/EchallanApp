<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\City;
use App\Models\Province;

class CitiesPunjabSeeder extends Seeder
{
    public function run()
    {
        $province = Province::firstWhere('name', 'Punjab');
        if (! $province) return;

        $cities = [
            'Lahore','Faisalabad','Rawalpindi','Gujranwala','Multan','Sialkot','Sargodha',
            'Bahawalpur','Sahiwal','Sheikhupura','Jhang','Kasur','Okara','Gujrat',
            'Rahim Yar Khan','Dera Ghazi Khan','Pakpattan','Nankana Sahib','Attock',
            'Chiniot','Vehari','Mianwali','Bhakkar','Khushab'
        ];

        foreach ($cities as $name) {
            City::firstOrCreate([
                'province_id' => $province->id,
                'name' => $name
            ]);
        }
    }
}
