<?php // Code within app\Helpers\Helper.php

use App\Model\UserPermission;
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