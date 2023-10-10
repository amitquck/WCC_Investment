<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\User;

class CustomerBankDetail extends Model
{
    use SoftDeletes;
    protected $fillable = ['bank_id','bank_name','account_holder_name','account_number','ifsc_code','pan_no'];

    public function customer()
    {
        return $this->belongsTo(User::class,'customer_id');
    }
}
