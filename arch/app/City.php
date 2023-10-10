<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class City extends Model
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
        'name', 'country_id','state_id'
    ];

    /**
     * Get the zipcodes for the city.
     */
    public function zipcodes()
    {
        return $this->hasMany('App\Zipcode');
    }

    /**
     * Get the state that owns the city.
     */
    public function state()
    {
        return $this->belongsTo('App\State');
    }

    /**
     * Get the country that owns the city.
     */
    public function country()
    {
        return $this->belongsTo('App\Country');
    }

    /**
    * Override parent boot and Call deleting event
    *
    * @return void
    */
    protected static function boot() 
    {
      parent::boot();

      static::deleting(function($cities) {
         foreach ($cities->zipcodes()->get() as $zipcode) {
            $zipcode->delete();
         }
      });
    }

    // Fetch city by state id
    public static function getStateCity($stateid=0){
        $value=City::where('state_id', $stateid)->get();
        return $value;
    }
}
