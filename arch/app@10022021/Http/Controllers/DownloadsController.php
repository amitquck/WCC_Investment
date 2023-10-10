<?php

namespace App\Http\Controllers;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;


class DownloadsController extends Controller
{
  public function download(Request $request) {
  	// $request->filename->getMimeType();
  	// $mimecheck = 'mimes:jpeg,png,jpg,pdf|max:2000';
  	// $name = $request->filename;
  	// $dd = File::mimeType($name);

  	if($request->has('filename')){
	    $file_path = public_path('uploads/prescription-image/').$request->filename;
	    // $mime_type = ['image/jpeg','image/jpg','image/png','application/pdf'];
	   $ext = File::mimeType($file_path);
	   if($ext == 'image/jpeg' ||  'image/jpg' || 'image/png' || 'application/pdf'){
	    return response::download($file_path);
	  	}else{
	  		return 0;
	  	}
	  }
	  return 0;
    
  }
}
