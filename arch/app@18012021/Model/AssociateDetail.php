<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AssociateDetail extends Model
{
	 use SoftDeletes;
    protected $fillable = ['associate_id','dob','sex','father_husband_wife','mother_name','address_one','address_two','account_holder_name','account_number','zipcode','ifsc_code','bank_id','bank_name','branch','status','pan_no'];

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
