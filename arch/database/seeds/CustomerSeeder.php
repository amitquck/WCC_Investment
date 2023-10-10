<?php

use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       
        DB::table('customer_details')->insert([
            'customer_id' => 2,
            'sex' => 'male',
            'dob' => date('Y-m-d'),
            'age' => 25,
            'nationality' => 'indian',
            'father_husband_wife' => 'pkm',
            'address_one' => 'as',
            'city_id' =>4827,
            'state_id' =>38,
            'country_id' =>101,
            'zipcode' =>222131,
            'account_holder_name' =>'ashd',
            'bank_name' =>'ashd',
            'account_number' =>'ashd',
            'ifsc_code' =>'ashd',
            'nominee_name' =>'ashd',
            'nominee_age' =>'ashd',
            'nominee_dob' =>date('Y-m-d'),
            'nominee_relation_with_applicable' =>'ashd',
            'nominee_address_one' =>'male',
            'nominee_address_two' =>'ashd',
            'nominee_city_id' =>4827,
            'nominee_state_id' =>38,
            'nominee_country_id' =>101,
            'nominee_zipcode' =>222135,
            // 'nominee_zipcode' =>date('Y-m-d'),
        ]);
       

        

        
        
    }
}
