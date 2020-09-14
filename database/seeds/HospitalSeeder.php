<?php

use Illuminate\Database\Seeder;
use App\Models\Hospital;
use Carbon\Carbon;

class HospitalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospitals = [
            [
                'name' => 'Dr. Fernando B. Duran Sr. Memorial Hospital',
                'address' => 'Macabog, Sorsogon City, Sorsogon, Sorsogon Diversion Rd, Sorsogon City, Sorsogon',
                'hospital_code' => 'DFBDSMH',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Donsol District Hospital',
                'address' => 'Donsol Sorsogon',
                'hospital_code' => 'DDH',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Irosin District Hospital',
                'address' => 'Irosin, Sorsogon',
                'hospital_code' => 'IDH',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Salvador R. Encinas District Hospital',
                'address' => 'Bonifacio St, Gubat, Sorsogon',
                'hospital_code' => 'SREDH',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Vicente L. Peralta Memorial District Hospital',
                'address' => 'Castilla, 4713 Sorsogon',
                'hospital_code' => 'VLPMDH',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Magallanes Medicare Community Hospital',
                'address' => 'Aguada Norte, Magallanes, Sorsogon',
                'hospital_code' => 'MagMCH',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Matnog Medicare Community Hospital',
                'address' => 'Matnog, Sorsogon',
                'hospital_code' => 'MatMCH',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Pantaleon G. Gotladera Memorial Hospital',
                'address' => 'Bulan, Sorsogon',
                'hospital_code' => 'PGGMH',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Prieto Diaz Municipal Hospital',
                'address' => 'Rizal, Prieto Diaz Sorsogon',
                'hospital_code' => 'PDMH',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];
        
        Hospital::insert($hospitals);
    }
}
