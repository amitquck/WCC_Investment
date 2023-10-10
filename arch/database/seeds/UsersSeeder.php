<?php

use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('users')->insert([
            'name' => 'admin',
            'code' => 'admin',
            'mobile' => '1234567895',
            'email' => 'admin@gmail.com',
            'created_by' => '12',
            'login_type' => 'superadmin',
            'password' => Hash::make('123456'),
        ]);
        DB::table('users')->insert([
            'name' => 'customer',
            'code' => 'customer',
            'mobile' => '1234567890',
            'email' => 'customer@gmail.com',
            'created_by' => '1',
            'login_type' => 'customer',
            'password' => Hash::make('123456'),
        ]);
        DB::table('users')->insert([
            'name' => 'Employee',
            'code' => 'Employee',
            'mobile' => '1234567800',
            'email' => 'employee@gmail.com',
            'created_by' => '1',
            'login_type' => 'employee',
            'password' => Hash::make('123456'),
        ]);
        DB::table('users')->insert([
            'name' => 'Associate1',
            'code' => 'Associate',
            'mobile' => '1234567190',
            'email' => 'associ1ate@gmail.com',
            'created_by' => '1',
            'login_type' => 'associate',
            'password' => Hash::make('123456'),
        ]);
        DB::table('users')->insert([
            'name' => 'Associate2',
            'code' => 'Associate',
            'mobile' => '1234522790',
            'email' => 'assoc2iate@gmail.com',
            'created_by' => '1',
            'login_type' => 'associate',
            'password' => Hash::make('123456'),
        ]);
        DB::table('users')->insert([
            'name' => 'Associate3',
            'code' => 'Associate',
            'mobile' => '1234563790',
            'email' => 'associate3@gmail.com',
            'created_by' => '1',
            'login_type' => 'associate',
            'password' => Hash::make('123456'),
        ]);
        DB::table('users')->insert([
            'name' => 'Associate',
            'code' => 'Associate4',
            'mobile' => '1234564790',
            'email' => 'associat4e@gmail.com',
            'created_by' => '1',
            'login_type' => 'associate',
            'password' => Hash::make('123456'),
        ]);
        DB::table('users')->insert([
            'name' => 'Associate5',
            'code' => 'Associate',
            'mobile' => '1234567750',
            'email' => 'asso45ciate@gmail.com',
            'created_by' => '1',
            'login_type' => 'associate',
            'password' => Hash::make('123456'),
        ]);
        DB::table('users')->insert([
            'name' => 'Associate6',
            'code' => 'Associate',
            'mobile' => '1234567690',
            'email' => 'associa45te@gmail.com',
            'created_by' => '1',
            'login_type' => 'associate',
            'password' => Hash::make('123456'),
        ]);
    }
}
