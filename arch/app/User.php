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
        'status'
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
        // dd($totalcr);
        $totaldr = $this->customertransactions->where('cr_dr','dr')->sum('amount');
        
        // dd($totalcr-$totaldr);
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
        $totalcr = $this->customertransactions->where('cr_dr','cr')->sum('amount');
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
        // dd($tillDate);
        $totalcr = $this->customertransactions->where('cr_dr','cr')->where('deposit_date', '<=', $tillDate)->sum('amount');
        // if($this->customer_id=16){
            // dd($totalcr);
        // }
        $totaldr = $this->customertransactions->where('cr_dr','dr')->where('deposit_date', '<=', $tillDate)->sum('amount');

        return $totalcr-$totaldr;
    }
}