<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Model\AssociateDetail;
// use App\Model\CustomerDetail;
use DB;
// use App\SUM;
class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'login_type','name','mobile','password', 'email','created_by','updated_by',
        'status','hold_remarks'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function associatedetail()
    {
      return  $this->hasOne(AssociateDetail::class,'associate_id');
    }
    public function customerdetails()
    {
       return $this->hasOne('App\Model\CustomerDetail','customer_id');
    }
    public function associatecommissions()
    {
       return $this->hasMany('App\Model\AssociateCommissionPercentage','customer_id');
    }
    public function associatecommission()
    {
       return $this->hasMany('App\Model\AssociateCommissionPercentage','associate_id');
    }

    public function customertransactions(){
        return $this->hasMany('App\Model\CustomerTransactions','customer_id');
    }
    public function associatetransactions(){
        return $this->hasMany('App\Model\AssociateTransactions','associate_id');
    }
    public function associaterewards(){
        return $this->hasMany('App\Model\AssociateReward','associate_id');
    }

    public function customerassociaterewards(){
        return $this->hasMany('App\Model\AssociateReward','customer_id');
    }

    public function customerrewards(){
        return $this->hasMany('App\Model\CustomerReward','customer_id');
    }

    public function customerinvestments(){
        return $this->hasMany('App\Model\CustomerInvestment','customer_id');
    }
    // public function customerinvestment(){
    //     return $this->hasOne('App\Model\CustomerInvestment','customer_id');
    // }

    public function customer_current_balance(){
        $totalcr = $this->customertransactions->where('cr_dr','cr')->sum('amount');
        $totaldr = $this->customertransactions->where('cr_dr','dr')->sum('amount');
        return $totalcr-$totaldr;
    }

    public function customerMainBalance(){
        $totalcr = $this->customertransactions->where('cr_dr','cr')->sum('amount');
        $totaldr = $this->customertransactions->where('transaction_type','withdraw')->sum('amount');
        return $totalcr-$totaldr;
    }

    public function customerLastMonthMainBalance(){
        $totalcr = $this->customertransactions->where('cr_dr','cr')->whereBetween('deposit_date',[date('Y-m-01',strtotime(date('Y-m-01').'-1 months')),date('Y-m-t',strtotime(date('Y-m-01').'-1 months'))])->sum('amount');
        $totaldr = $this->customertransactions->where('cr_dr','dr')->whereBetween('deposit_date',[date('Y-m-01',strtotime(date('Y-m-01').'-1 months')),date('Y-m-t',strtotime(date('Y-m-01').'-1 months'))])->sum('amount');
        return $totalcr-$totaldr;
    }

    public function customerThisMonthMainBalance(){
        $totalcr = $this->customertransactions->where('cr_dr','cr')->whereBetween('deposit_date',[date('Y-m-01'),date('Y-m-t')])->sum('amount');
        $totaldr = $this->customertransactions->where('cr_dr','dr')->whereBetween('deposit_date',[date('Y-m-01'),date('Y-m-t')])->sum('amount');
        return $totalcr-$totaldr;
    }

    public function customerd(){
        return $this->hasOne('App\Model\CustomerInvestment','customer_id');
    }

     public function associatebalance(){
        $totalcr = $this->associatetransactions->where('cr_dr','cr')->sum('amount');
        // dd($totalcr);
        $totaldr = $this->associatetransactions->where('cr_dr','dr')->sum('amount');

        // dd($totalcr-$totaldr);
        return $totalcr-$totaldr;
    }
     public function associateTotalCommission(){
        $totalcr = $this->associatetransactions->where('cr_dr','cr')->sum('amount');
        // dd($totalcr);


        // dd($totalcr-$totaldr);
        return $totalcr;
    }
    public function associateTotalCustomer(){
        $totalc = $this->associatetransactions->groupBy('customer_id')->count();
        return $totalc;
    }
    public function TotalDeposit(){
        $totalcr = $this->customertransactions->where('cr_dr','cr')->where('transaction_type','deposit')->sum('amount');
        return $totalcr;
    }
    public function TotalInterest(){
        $totalInterestcr = $this->customertransactions->where('transaction_type','interest')->where('cr_dr','cr')->sum('amount');
        $totalInterestdr = $this->customertransactions->where('transaction_type','interest')->where('cr_dr','dr')->sum('amount');
        return $totalInterestcr-$totalInterestdr;
    }

    public function customeractiveinterestpercent()
    {
        return $this->hasOne('App\Model\CustomerInterestPercentage' ,'customer_id')->where('active_status',1);
    }

    public function customerinterestpercents()
    {
        return $this->hasMany('App\Model\CustomerInterestPercentage' ,'customer_id');
    }

    public function permissions(){
        return $this->hasMany('App\Model\UserPermission','user_id');
    }


    public function balance($tillDate)
    {
        // $tillDate = '2020-02-29';
        $totalcr = $this->customertransactions->where('cr_dr','cr')->where('deposit_date', '<', $tillDate)->sum('amount');
        // if($this->customer_id=32){
        //     dump($tillDate);
        //     dd($totalcr);
        // }

        $totaldr = $this->customertransactions->where('cr_dr','dr')->where('deposit_date', '<=', $tillDate)->sum('amount');
        // if($this->customer_id=32){
        //     dd($totaldr);
        // }
        return $totalcr-$totaldr;
    }

    public function customersecuritycheque()
    {
        return $this->hasMany('App\Model\CustomerSecurityCheque','customer_id');
    }

    public function customer_monthly_balance($month,$year){
        $totalcr = $this->customertransactions->where('cr_dr','cr')->whereBetween('deposit_date' ,[date($year.'-'.$month.'-'.'01'),date($year.'-'.$month.'-'.'31')])->sum('amount');
        // dump($totalcr);
        $totaldr = $this->customertransactions->where('cr_dr','dr')->whereBetween('deposit_date' ,[date($year.'-'.$month.'-'.'01'),date($year.'-'.$month.'-'.'31')])->sum('amount');
        // dump($totaldr);
        // dd($totalcr-$totaldr);
        return $totalcr-$totaldr;
    }

    public function customer_monthly_balance_recalculate($month,$year){

        $totalcr = $this->customertransactions->where('cr_dr','cr')->where('deposit_date' ,'<', date($year.'-'.$month.'-'.'31'))->sum('amount');

        $totaldr = $this->customertransactions->where('cr_dr','dr')->where('deposit_date' ,'<=', date($year.'-'.$month.'-'.'31'))->sum('amount');

        return $totalcr-$totaldr;
    }

    public function thisMonthCustomerBalance()
    {
        return $this->customertransactions->where('cr_dr','cr')->whereBetween('deposit_date',[date('Y-m-01'),date('Y-m-t')])->sum('amount');
    }

}
