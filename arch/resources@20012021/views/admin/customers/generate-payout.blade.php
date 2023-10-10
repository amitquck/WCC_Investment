@extends('layouts.admin.default')
@section('content')
<style type="text/css">
.tab-content.form-style {
    box-shadow: 0 4px 11px #eceaea;
    padding: 40px 0;
    background: #fff;
    border: 1px solid #f6f6f6;
    margin-top: 50px;
}
</style>
<nav aria-label="breadcrumb" class="main-breadcrumb">
<ol class="breadcrumb breadcrumb-style2">
    <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
    <li class="breadcrumb-item active" aria-current="page">Customer Payout</li>
</ol>
</nav>
<!-- /Breadcrumb -->
@include('flash')	
  
<div class="col-md-12" style="padding-top:0px">	     
    <div class="tab-content form-style">
        <div class="tab-pane active" id="horizontal-form">
            <form class="form-horizontal" action="{{url('admin/customer/payout-generates')}}" method="post" enctype="multipart/form-data">
            {{csrf_field()}}
                <div class="form-group row mx-auto">
                    <!-- <label for="focusedinput" class="col-sm-4 text-center control-label">Select Month <span style="color:#CC3333">*</span></label> -->
                    <div class="col-sm-5">
                    <select class="form-control" name="month">
                    <option value=""> Select Month</option>
                        @for($i=1; $i<=12; $i++)
                            <option value="{{$i}}" @if(old('month') == $i) selected="selected" @endif>{{Carbon\Carbon::parse('01-'.$i.'-'.date('Y'))->format('M')}}</option>
                        @endfor
                    </select>
                    </div>

                    <div class="col-md-5">
                        <select class="form-control" name="year">
                            <option value="">Select Year</option>
                            @for($i=date('Y'); $i>=2019; $i--)
                                <option value="{{$i}}" @if(old('year') == $i) selected="selected" @endif>{{$i}}</option>
                            @endfor
                        </select>
                    </div>
                    
                    <div class="col-sm-2">
                        <input type="submit" class="btn-primary btn" value="+ Create" onclick="return confirm('Are You Sure');">
                    </div>
                    @error('month')
                         <span class="help-block text-danger col-md-12 text-center mt-2">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror 
                </div>
            </form>
        </div>
    </div>
</div>
					


@endsection