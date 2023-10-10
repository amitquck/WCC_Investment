<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class DownloadsController extends Controller
{
  public function download($file_name) {
    // $file_path = public_path('uploads/prescription-image/').$file_name;
    // return response::download($file_path);
    // $filename = 'file_name';
		$tempImage = tempnam(sys_get_temp_dir(), $file_name);
		copy(public_path('uploads/prescription-image/'.$file_name),$tempImage);

		return response()->download($tempImage, $file_name);
  }
}
