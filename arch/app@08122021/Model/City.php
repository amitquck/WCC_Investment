<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Country;
use App\Model\State;

class City extends Model
{
	 use SoftDeletes;
    protected $fillable = ['name','country','city','created_by'];

    public function countrydetail()
    {
    	return $this->belongsTo(Country::class,'country');
    }
    public function statedetail(){
    	return $this->belongsTo(State::class,'state');
    }

    public function customerdetails(){
        // dd($this);
       return $this->hasMany('App\Model\CustomerDetail');
    }






}
