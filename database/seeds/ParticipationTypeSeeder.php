<?php

use Illuminate\Database\Seeder;
use App\Models\ParticipationType;

class ParticipationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'requesting_physician',
                'weight' => '0.1',
            ],
            [
                'name' => 'surgeon_physician',
                'weight' => '0.1',
            ],
            [
                'name' => 'health_care_physician',
                'weight' => '0.1',
            ],
            [
                'name' => 'er_physician',
                'weight' => '0.1',
            ],
            [
                'name' => 'anesthesiologist',
                'weight' => '0.3',
            ],
            [
                'name' => 'co_management',
                'weight' => '0.2',
            ],
            [
                'name' => 'admitting',
                'weight' => '0.10',
            ],
        ];

        ParticipationType::insert($data);
    }
}
