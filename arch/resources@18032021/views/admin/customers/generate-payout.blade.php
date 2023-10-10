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
                    <div class="col-sm-3">
                        <label for="">Select Month<sup class="text-danger">*</sup></label>
                    <select class="form-control" name="month">
                    <option value=""> --</option>
                        @for($i=01; $i<=12; $i++)
                            <option value="{{$i}}" @if(old('month') == $i) selected="selected" @endif>{{Carbon\Carbon::parse('01-'.$i.'-'.date('Y'))->format('M')}}</option>
                        @endfor
                    </select>
                    @if($errors->has('month'))
                        <p style="color:red;">{{ $errors->first('month') }}</p>
                    @endif
                    </div>

                    <div class="col-md-3">
                        <label for="">Select Year<sup class="text-danger">*</sup></label>
                        <select class="form-control" name="year">
                            <option value="">--</option>
                            @for($i=date('Y'); $i>=2019; $i--)
                                <option value="{{$i}}" @if(old('year') == $i) selected="selected" @endif>{{$i}}</option>
                            @endfor
                        </select>
                    @if($errors->has('year'))
                        <p style="color:red;">{{ $errors->first('year') }}</p>
                    @endif
                    </div>
                    
                    <div class="col-sm-3">
                        <label for="posting_date">Posting Date <sup class="text-danger">*</sup></label>
                        <input type="date" name="posting_date" class="form-control" >
                        @if($errors->has('posting_date'))
                         <p style="color:red;">{{ $errors->first('posting_date') }}</p>
                        @endif
                    </div>
                    
                    <div class="col-sm-2">
                        <label style="padding-top: 45px"></label>
                        <input type="submit" class="btn-primary btn" value="+ Create" onclick="return confirm('Are You Sure');">
                    </div>
                    <!-- @error('month')
                         <span class="help-block text-danger col-md-12 text-center mt-2">
                              <strong>{{$message}}</strong>
                              </span>
                     @enderror  -->
                </div>
            </form>
        </div>
    </div>
</div>
					


@endsection