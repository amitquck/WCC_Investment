<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\Country;

class State extends Model
{
	use SoftDeletes;
	protected $fillable = ['name','country','created_by'];

    public function countrydetail()
    {
    	return $this->belongsTo(Country::class,'country');
    }
}
