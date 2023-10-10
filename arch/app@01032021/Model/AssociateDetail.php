<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AssociateDetail extends Model
{
	 use SoftDeletes;
    protected $fillable = ['associate_id','dob','sex','father_husband_wife','mother_name','address_one','address_two','account_holder_name','account_number','zipcode','ifsc_code','bank_id','bank_name','branch','status','pan_no','nominee_name','nominee_age','nominee_dob','nominee_relation_with_applicable','nominee_sex','nominee_address_one','nominee_country_id','nominee_address_two','nominee_city_id','nominee_state_id','nominee_zipcode'];

    public function country(){
        return $this->belongsTo('App\Country');
    }
    public function state(){
        return $this->belongsTo('App\State');
    } 
    public function city(){
        return $this->belongsTo('App\City');
    } 
}
