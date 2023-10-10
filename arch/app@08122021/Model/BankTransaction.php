<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use DB;

class BankTransaction extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'user_id',
        'amount',
        'payment_type',
        'cr_dr',
        'remarks',
        'status',
        'cheque_dd_number',
        'cheque_dd_date',
        'transaction_date',
        'created_by',
    ];
}
