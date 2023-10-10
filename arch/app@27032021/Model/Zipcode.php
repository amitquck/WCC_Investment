<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Country;
use App\Model\State;
use App\Model\City;

class Zipcode extends Model
{
    use SoftDeletes;
	protected $fillable = ['name','country','state','city','created_by'];

	public function countrydetail()
	{
		return $this->belongsTo(Country::class,'country');
 	}
 	public function statedetail()
 	{
 		return $this->belongsTo(State::class,'state');
 	}
 	public function citydetail()
 	{
 		return $this->belongsTo(city::class,'city');
 	}
}
