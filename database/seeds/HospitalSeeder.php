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
                'email_address' => 'dfbdsmh@gmail.com',
                'setting' =>'{"medical":0.5,"nonmedical":0.5,"pooled":0.3,"shared":0.7, "physicians":[0.1,0.1,0.1,0.1,0.3,0.2,0.1]}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Donsol District Hospital',
                'address' => 'Donsol Sorsogon',
                'hospital_code' => 'DDH',
                'email_address' => 'ddh@gmail.com',
                'setting' =>'{"medical":0.5,"nonmedical":0.5,"pooled":0.3,"shared":0.7, "physicians":[0.1,0.1,0.1,0.1,0.3,0.2,0.1]}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Irosin District Hospital',
                'address' => 'Irosin, Sorsogon',
                'hospital_code' => 'IDH',
                'email_address' => 'idh@gmail.com',
                'setting' =>'{"medical":0.5,"nonmedical":0.5,"pooled":0.3,"shared":0.7, "physicians":[0.1,0.1,0.1,0.1,0.3,0.2,0.1]}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Salvador R. Encinas District Hospital',
                'address' => 'Bonifacio St, Gubat, Sorsogon',
                'hospital_code' => 'SREDH',
                'email_address' => 'sredh@gmail.com',
                'setting' =>'{"medical":0.5,"nonmedical":0.5,"pooled":0.3,"shared":0.7, "physicians":[0.1,0.1,0.1,0.1,0.3,0.2,0.1]}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Vicente L. Peralta Memorial District Hospital',
                'address' => 'Castilla, 4713 Sorsogon',
                'hospital_code' => 'VLPMDH',
                'email_address' => 'vlpmdh@gmail.com',
                'setting' =>'{"medical":0.5,"nonmedical":0.5,"pooled":0.3,"shared":0.7, "physicians":[0.1,0.1,0.1,0.1,0.3,0.2,0.1]}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Magallanes Medicare Community Hospital',
                'address' => 'Aguada Norte, Magallanes, Sorsogon',
                'hospital_code' => 'MagMCH',
                'email_address' => 'magmch@gmail.com',
                'setting' =>'{"medical":0.5,"nonmedical":0.5,"pooled":0.3,"shared":0.7, "physicians":[0.1,0.1,0.1,0.1,0.3,0.2,0.1]}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Matnog Medicare Community Hospital',
                'address' => 'Matnog, Sorsogon',
                'hospital_code' => 'MatMCH',
                'email_address' => 'matmch@gmail.com',
                'setting' =>'{"medical":0.5,"nonmedical":0.5,"pooled":0.3,"shared":0.7, "physicians":[0.1,0.1,0.1,0.1,0.3,0.2,0.1]}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Pantaleon G. Gotladera Memorial Hospital',
                'address' => 'Bulan, Sorsogon',
                'hospital_code' => 'PGGMH',
                'email_address' => 'pggmh@gmail.com',
                'setting' =>'{"medical":0.5,"nonmedical":0.5,"pooled":0.3,"shared":0.7, "physicians":[0.1,0.1,0.1,0.1,0.3,0.2,0.1]}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'name' => 'Prieto Diaz Municipal Hospital',
                'address' => 'Rizal, Prieto Diaz Sorsogon',
                'hospital_code' => 'PDMH',
                'email_address' => 'pdmh@gmail.com',
                'setting' =>'{"medical":0.5,"nonmedical":0.5,"pooled":0.3,"shared":0.7, "physicians":[0.1,0.1,0.1,0.1,0.3,0.2,0.1]}',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ],
        ];

        Hospital::insert($hospitals);
    }
}
