<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Hospital;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new User;
        $admin->username = 'admin';
        $admin->password = Hash::make('secret');
        $admin->hospital_id = null;
        $admin->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $admin->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $admin->assignRole('admin');
        $admin->save();

        $observer = new User;
        $observer->username = 'observer';
        $observer->password = Hash::make('secret');
        $observer->hospital_id = null;
        $observer->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $observer->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $observer->assignRole('observer');
        $observer->save();


        $usernames = [
            'DFBDSMH_usr1', 'DFBDSMH_usr2', 'DFBDSMH_usr3',
        ];

        $hospitals = Hospital::get();
        for($index = 0; $index < count($usernames); $index++) {
            $hospital_admin = new User;
            $hospital_admin->username = $usernames[$index];
            $hospital_admin->password = Hash::make('secret');
            $hospital_admin->created_at = Carbon::now()->format('Y-m-d H:i:s');
            $hospital_admin->updated_at = Carbon::now()->format('Y-m-d H:i:s');
            $hospital_admin->hospital()->associate(Hospital::find(1)->id);
            $hospital_admin->assignRole('user');
            $hospital_admin->save();
        }

        $hospital_admin = new User;
        $hospital_admin->username = 'test_user';
        $hospital_admin->password = Hash::make('secret');
        $hospital_admin->created_at = Carbon::now()->format('Y-m-d H:i:s');
        $hospital_admin->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        $hospital_admin->hospital()->associate(Hospital::find(2)->id);
        $hospital_admin->assignRole('user');
        $hospital_admin->save();

        // Use for differenet hospitals
        // $hospitals = Hospital::get();
        // for($index = 0; $index < $hospitals->count(); $index++) {
        //     $hospital_admin = new User;
        //     $hospital_admin->username = $usernames[$index];
        //     $hospital_admin->password = Hash::make('secret');
        //     $hospital_admin->created_at = Carbon::now()->format('Y-m-d H:i:s');
        //     $hospital_admin->updated_at = Carbon::now()->format('Y-m-d H:i:s');
        //     $hospital_admin->hospital()->associate(Hospital::find($index+1)->id);
        //     $hospital_admin->assignRole('user');
        //     $hospital_admin->save();
        // }

    }
}
