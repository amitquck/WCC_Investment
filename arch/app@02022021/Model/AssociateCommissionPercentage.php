<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AssociateCommissionPercentage extends Model
{
	use SoftDeletes;
    protected $fillable = ['customer_id','associate_id','interest_amount','commission_percent','status','start_date'];

    public function customer(){
        return $this->belongsTo('App\User','customer_id');
    }

    public function associate()
    {
       return $this->belongsTo('App\User','associate_id');
    }

    // public function associatetransaction()
    // {
    //    return $this->hasMany('App\Model\AssociateTransactions','customer_id','associate_id');
    // }
    
}
