<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\User;

class AssociateHeirarchy extends Model
{
	use SoftDeletes;
    protected $fillable = ['associate_id','customer_id','parent_id'];
    protected $table = 'associate_heirarchies';

    public function associate()
    {
        return $this->belongsTo(User::class,'associate_id');
    }
}
