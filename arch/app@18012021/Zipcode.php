<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Zipcode extends Model
{
    use SoftDeletes;
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'zipcode', 'country','state','city'
    ];

    /**
     * Get the city that owns the zipcode.
     */
    public function city()
    {
        return $this->belongsTo('App\City','city_id');
    }

    /**
     * Get the state that owns the zipcode.
     */
    public function state()
    {
        return $this->belongsTo('App\State','state_id');
    }

    /**
     * Get the country that owns the zipcode.
     */
    public function country()
    {
        return $this->belongsTo('App\Country','country_id');
    }

    // Fetch country, state, city by zipcode
    public static function getCSC($zipcode=0){
        $value=Zipcode::where('zipcode', $zipcode)->first();
        return $value;
    }
}
