<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectAssociateCommission extends Model
{
	use SoftDeletes;
    protected $fillable = ['associate_id','customer_id','total_investment'];
    
   
    public function associate(){
		return $this->belongsTo('App\User','associate_id');
	}  

	public function getAssociateWiseBusinessAttribute()
    {
    	return $this->where('associate_id', $this->associate_id)->sum('total_investment');
    } 

	public function getAssociateWiseThisMonthBusinessAttribute()
    {
    	return $this->where('associate_id', $this->associate_id)->whereBetween('created_at',[date('Y-m-01 00:00:01'),date('Y-m-t 23:59:59')])->sum('total_investment');
    }

	public function getAssociateWiseLastMonthBusinessAttribute()
    {
    	return $this->where('associate_id', $this->associate_id)->whereBetween('created_at',[date('Y-m-01 00:00:01',strtotime(date('Y-m-01').'-1 months')),date('Y-m-t 23:59:59',strtotime(date('Y-m-01').'-1 months'))])->sum('total_investment');
    }
}
