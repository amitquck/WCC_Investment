<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\State;

class Country extends Model
{
    use SoftDeletes;
    protected $fillable = ['name','sortname','code','created_by'];
    public function states(){
        return $this->HasMany(State::class,'country');
    }
}
