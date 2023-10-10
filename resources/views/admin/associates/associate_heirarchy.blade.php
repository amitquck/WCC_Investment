@extends('layouts.admin.default')
@section('content')

  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="main-breadcrumb">
    <ol class="breadcrumb breadcrumb-style2">
      <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
      <!-- <li class="breadcrumb-item"><a href="javascript:void(0)">Dashboard</a></li> -->
      <li class="breadcrumb-item active" aria-current="page">Tree View</li>
    </ol>
  </nav>
  <!-- /Breadcrumb -->
  @include('flash')

    <div class="card card-style-1 mt-3">
        <div class="box-body no-padding">
            <div class="management-hierarchy" style="padding-top:60px;">
                <div class="hv-container" style="overflow: scroll;">
                    <div class="hv-wrapper">
                    <?php
                        //associateHeirarchy($custId = false);
                        //echo "done"; die;
                        $html = '';
                        heirarchy(auth()->user()->id,$html,[]);
                        echo $html;
                    ?>
                        <!-- Key component -->

                    </div>
                </div>
            </div>

        </div>
    </div>


@endsection


@section('page_js')

@endsection







