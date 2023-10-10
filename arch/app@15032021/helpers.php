<?php // Code within app\Helpers\Helper.php

use App\Model\UserPermission;
use App\Model\AssociateCommissionPercentage;
use App\Model\CustomerTransactions;
use App\Model\DirectAssociateCommission;


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
  $customer = AssociateCommissionPercentage::where('customer_id',$customerId)->where('no_of_introducer',1)->first();

  $delete_DAC_customer = DirectAssociateCommission::where('customer_id',$customer->customer_id)->delete();

  $direct_associate_commission = new DirectAssociateCommission;
  $direct_associate_commission->associate_id = $customer->associate_id;
  $direct_associate_commission->customer_id = $customer->customer_id;
  $direct_associate_commission->total_investment = $customer->customertransaction($customer->customer_id);
  $direct_associate_commission->created_by = \Auth()->user()->id;
  $direct_associate_commission->created_at = date('Y-m-d H:i:s');
  $direct_associate_commission->save();
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