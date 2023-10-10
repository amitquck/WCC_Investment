<?php // Code within app\Helpers\Helper.php

use App\Model\UserPermission;
use App\Model\AssociateCommissionPercentage;
use App\Model\AssociateDetail;
use App\Model\CustomerTransactions;
use App\Model\AssociateTransactions;
use App\Model\DirectAssociateCommission;
use App\Model\AssociateHeirarchy;
use App\Model\AssociateTransaction;
use App\Model\City;
use App\Model\State;
use App\Model\CompanyBank;
use App\User;


if (! function_exists('empCan')) {
    function empCan($privilegeName)
    {
        $empcan = UserPermission::whereUserId(auth()->user()->id)->whereActionName($privilegeName)->whereStatus(1)->first();
        // dd($empcan);
        if($empcan ){
            return true;
        }else{
            return false;
        }
    }
}

function directAssociateCommission($customerId){
    // dd($customerId);
  $delete_DAC_customer = DirectAssociateCommission::where('customer_id',$customerId)->delete();
  $customer = AssociateCommissionPercentage::where('customer_id',$customerId)->where('no_of_introducer',1)->orderBy('start_date')->first();
  if($customer != Null){

      $direct_associate_commission = new DirectAssociateCommission;
      $direct_associate_commission->associate_id = $customer->associate_id;
      $direct_associate_commission->customer_id = $customer->customer_id;
      $direct_associate_commission->total_investment = $customer->firstIntroducerBusinessApplicableDate($customer->start_date, $customer->customer_id);
      $direct_associate_commission->total_withdraw = $customer->firstIntroducerBusinessApplicableDateWithdraw($customer->start_date, $customer->customer_id);
      $direct_associate_commission->created_by = \Auth()->user()->id;
      $direct_associate_commission->created_at = date('Y-m-d H:i:s');
      $direct_associate_commission->save();
      return true;
  }
  return false;
}


// function createTree($conn,$associateID,&$html){
//   $children =mysqli_query($conn,"SELECT id FROM associates where sponsor_id = '".$associateID."' and type='associate' and deleted is null");
//   if(mysqli_num_rows($children) > 0){
//     $html .= '<div class="hv-item">
//             <div class="hv-item-parent">
//               <div class="person root">
//                 <img class="border-sky" src="../img/rewards/male1.png" alt="">
//                 <p class="name" style="background-color:#3c8dbc;color:white;"> '.printAssociateName($conn, $associateID).' </p>
//               </div>
//             </div>';
//     $html .= '<div class="hv-item-children">';
//     while($child = mysqli_fetch_assoc($children))
//     {
//       $html .= '<div class="hv-item-child">';
//       createTree($conn,$child['id'],$html);
//       $html .= '</div>';
//     }
//     $html .= '</div>';
//     $html .= '</div>';
//   }else{
//       $html .= '<div class="hv-item-child">';
//       $html .= '<div class="person">
//                 <img class="border-sky" src="../img/rewards/male1.png" alt="">
//                 <p class="name" style="background-color:#3c8dbc;color:white;"> '.printAssociateName($conn, $associateID).' </p>
//               </div>';
//       $html .= '</div>';
//   }
// }

function getUser($associateId){
    $associate = User::where('id',$associateId)->first();
    return $associate;
}

function getStateName($cityId){
    $city = City::where('id',$cityId)->first();
    $state = State::where('id',$city->state)->first();
    return $state->name;
}

function getUserCodeName($tableId,$tableName){
    if($tableName == 'associate_transactions'){
        $ass = AssociateTransactions::where('id',$tableId)->first();
        $user = User::where('id',$ass->associate_id)->first();
        return $user;
    }else if($tableName == 'customer_transactions'){
        $cust = CustomerTransactions::where('id',$tableId)->first();
        if($cust){
            $user = User::where('id',$cust->customer_id)->first();
            return $user;
        }

    }
}

function getBank($bId){
    $bank = CompanyBank::where('id',$bId)->first();
    return $bank->bank_name;
}


function associateHeirarchy($custId){
    if($custId){
        $hei = AssociateHeirarchy::where('customer_id',$custId)->get();
        if($hei){
            AssociateHeirarchy::where('customer_id',$custId)->delete();
            $customers = AssociateCommissionPercentage::where('status',1)->where('customer_id',$custId)->groupBy('customer_id')->get();
            foreach($customers as $key => $cust){
                $parent = 0;
                $associates = AssociateCommissionPercentage::where('customer_id',$cust->customer_id)->where('status',1)->orderByDesc('no_of_introducer')->get();
                foreach($associates as $key => $associate){
                    $assTree = new AssociateHeirarchy;
                    $assTree->customer_id = $cust->customer_id;
                    $assTree->associate_id = $associate->associate_id;
                    $assTree->parent_id = $parent;
                    $assTree->save();
                    $parent = $associate->associate_id;
                }
            }
        }
    }else{
        $customers = AssociateCommissionPercentage::where('status',1)->groupBy('customer_id')->get();
        foreach($customers as $key => $cust){
            $parent = 0;
            $associates = AssociateCommissionPercentage::where('customer_id',$cust->customer_id)->where('status',1)->orderByDesc('no_of_introducer')->get();
            foreach($associates as $key => $associate){
                $assTree = new AssociateHeirarchy;
                $assTree->customer_id = $cust->customer_id;
                $assTree->associate_id = $associate->associate_id;
                $assTree->parent_id = $parent;
                $assTree->save();
                $parent = $associate->associate_id;
            }
        }
    }

}


function getAssociate($id)
{
    $ass = User::where('id',$id)->first();
    $cn = $ass->name.' ('.$ass->code.')';
    return $cn;
}

function getAssociateImage($assId)
{
    $user = AssociateDetail::where('associate_id',$assId)->first();
    if($user->image){
        return asset('images/associate/'.$user->image);
    }else{
        return asset('images/user.png');
    }
}


function heirarchy($assId,&$html)//$conn,$associateID,&$html
{
    $route = route('associate.direct_customer',['associate_id'=>encrypt($assId)]);
    $overallBusi = getOverAllBusiness($assId);

    $totBusi = getCurrBusiness($assId);
    $children = AssociateHeirarchy::where('parent_id',$assId)->orderBy('id')->groupBy('associate_id')->get();
    foreach($children as $key => $child)
    {
        if($assId != $child->associate_id){
            $overallBusi += getOverAllBusiness($child->associate_id);
        }
    }
    if($children->count() > 0){
        $html .= '<div class="hv-item">
                <div class="hv-item-parent">
                <div class="person root">
                    <a href="#" data-toggle="tooltip" title="Over All Business - '.$overallBusi.', Current Business - '.$totBusi.'"><img class="border-sky" src="'.getAssociateImage($assId).'" alt=""></a>
                    <p class="name" style="background-color:#3c8dbc;color:white;"> '.getAssociate($assId).' </p>
                </div>
                </div>';
        $html .= '<div class="hv-item-children">';
        foreach($children as $key => $child)
        {
            $html .= '<div class="hv-item-child">';
            if($assId != $child->associate_id){
                heirarchy($child->associate_id,$html);
            }
            $html .= '</div>';
        }

        $html .= '</div>';
        $html .= '</div>';
    }else{
        $OAB = getOverAllBusiness($assId);
        $html .= '<div class="hv-item-child">';
        $html .= '<div class="person">
                    <a href="#" data-toggle="tooltip" title="Over All Business - '.$OAB.', Current Business - '.$totBusi.'"><img class="border-sky" src="'.getAssociateImage($assId).'" alt=""></a>
                    <p class="name" style="background-color:#3c8dbc;color:white;"><a href="'.$route.'" style="background-color:#3c8dbc;color:white;" data-toggle="tooltip" title="View Direct Customer"> '.getAssociate($assId).' </a></p>
                </div>';
        $html .= '</div>';
    }
}

function getOverAllBusiness($assId)
{
    $OAB = AssociateTransactions::whereAssociateId($assId)->where('cr_dr','cr')->sum('amount');
    return $OAB;
}

function getCurrBusiness($assId)
{
    $cr = AssociateTransactions::whereAssociateId($assId)->where('cr_dr','cr')->sum('amount');
    $dr = AssociateTransactions::whereAssociateId($assId)->where('cr_dr','dr')->sum('amount');
    return $cr-$dr;
}
