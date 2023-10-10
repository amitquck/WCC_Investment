<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Country;
use App\Model\CustomerTransactions;
use App\Model\CustomerReward;
use App\Model\AssociateReward;
use DB;

class State extends Model
{
	use SoftDeletes;
	protected $fillable = ['name','country','created_by'];

    public function countrydetail()
    {
    	return $this->belongsTo(Country::class,'country');
    }
    public function customerdetails(){
        // dd($this);
       return $this->hasMany('App\Model\CustomerDetail');
    }

}
