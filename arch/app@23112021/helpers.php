<?php // Code within app\Helpers\Helper.php

use App\Model\UserPermission;
use App\Model\AssociateCommissionPercentage;
use App\Model\CustomerTransactions;
use App\Model\AssociateTransactions;
use App\Model\DirectAssociateCommission;
use App\Model\City;
use App\Model\State;
use App\Model\CompanyBank;
use App\User;


if (! function_exists('empCan')) {
    function empCan($privilegeName)
    {
        $empcan = UserPermission::whereUserId(\Auth::user()->id)->whereActionName($privilegeName)->whereStatus(1)->first();
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
        $user = User::where('id',$cust->customer_id)->first();
        return $user;
    }
}

function getBank($bId){
    $bank = CompanyBank::where('id',$bId)->first();
    return $bank->bank_name;
}
