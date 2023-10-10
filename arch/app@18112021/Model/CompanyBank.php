<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\CustomerTransactions;
use App\Model\AssociateTransactions;
use App\Model\BankTransaction;
use DB;

class CompanyBank extends Model
{
	use SoftDeletes;
    protected $fillable = ['bank_name','amount'];

    public function customertransactions()
    {
        return $this->hasMany('App\Model\CustomerTransactions','customer_id');
    }

    public function associatetransactions()
    {
        return $this->hasMany('App\Model\AssociateTransactions','associate_id');
    }

    public function getBankCurrentBalanceAttribute(){

    	// $cust_total_cr = $this->customertransactions->where('cr_dr','cr')->where('bank_id',$this->id)->sum('amount');
    	$cust_total_cr = CustomerTransactions::where('cr_dr','cr')->where('bank_id',$this->id)->sum('amount');
    	// dump($cust_total_cr);
    	$cust_total_dr = CustomerTransactions::where('cr_dr','dr')->where('bank_id',$this->id)->sum('amount');

    	$asso_total_cr = AssociateTransactions::where('cr_dr','cr')->where('bank_id',$this->id)->sum('amount');
    	$asso_total_dr = AssociateTransactions::where('cr_dr','dr')->where('bank_id',$this->id)->sum('amount');

    	$total = ($cust_total_cr-$cust_total_dr) + ($asso_total_cr-$asso_total_dr);
    	return $total;
    }

    public function banktransaction()
    {
        return $this->hasMany('App\Model\BankTransaction','bank_id');
    }

    public function getBalanceAttribute()
    {
        $balance = BankTransaction::select(DB::raw('SUM((amount) * ( CASE WHEN cr_dr = "cr" THEN 1 WHEN cr_dr = "dr" THEN -1 END )) AS balance'))->whereBankId($this->id)->first();
        return $balance;
    }


}

