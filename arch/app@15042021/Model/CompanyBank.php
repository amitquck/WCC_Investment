<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Model\CustomerTransactions;
use App\Model\AssociateTransactions;

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

   
    
}

