<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CustomerDetail extends Model
{
	 use SoftDeletes;
    protected $fillable = ['customer_id','dob','age','nationality','sex','father_husband_wife','address_one','address_two','city_id','state_id','country_id','zipcode','account_holder_name','bank_id','bank_name','account_number','ifsc_code','nominee_name','nominee_age','nominee_dob','nominee_relation_with_applicable','nominee_sex','nominee_address_one','nominee_country_id','nominee_address_two','nominee_city_id','nominee_state_id','nominee_zipcode','pan_no'];

    public function country(){
        return $this->belongsTo('App\Country','nominee_country_id');
    }
    public function state(){
        return $this->belongsTo('App\State','nominee_state_id');
    } 
    public function city(){
        return $this->belongsTo('App\City','nominee_city_id');
    }  
}

