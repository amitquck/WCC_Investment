@extends('layouts.admin.front')

@section('content')
<div class="container pt-5">
    <div class="row justify-content-center">
      <div class="col-md-auto d-flex justify-content-center">
        <div class="card card-style-1">
          <div class="card-body p-4">

            <!-- LOG IN FORM -->
            <h4 class="card-title text-center mb-0">LOG IN</h4>
            <div class="text-center text-muted font-italic">to your account</div>
            <hr>
            <form action="{{ route('login') }}" name="login-frm" id="login-frm" method="post">
                       @csrf
              <div class="form-group">
                <div class="floating-label input-icon">
                  <i class="material-icons">person_outline</i>
                  <input type="text" name="email"  id="email" class="form-control @error('email') is-invalid @enderror" placeholder="" value="{{ old('email') || old('code') }}"/>
            @if($errors->has('email') || $errors->has('code'))
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $errors->first('email') || $errors->first('code') }}</strong>
                  </span>
              
              @endif
                  <label for="username">Code Or Email</label>
     
                </div>
              </div>
              <div class="form-group">
                <div class="floating-label input-icon">
                  <i class="material-icons">lock_open</i>
                  <input type="password"  class="form-control @error('password') is-invalid @enderror" name="password"  placeholder="Password"/>
            @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                  <label for="password">Password</label>
                
                
                </div>
              </div>
              <?php /*<div class="form-group d-flex justify-content-between align-items-center">
                <div class="custom-control custom-checkbox">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }} >
                  <label class="custom-control-label" for="remember">Remember me</label>
                </div>
                <a href="#" class="text-primary text-decoration-underline small">Forgot password ?</a>
              </div>*/ ?>
              <button type="submit" class="btn btn-primary btn-block btn-flat" name="sign-in">Sign In</button>
            </form>
            <hr>
            <?php /*<div class="small">
              Don't have an account ?
              <a href="#" class="text-decoration-underline">Register</a>
            </div>*/ ?>
            <!-- /LOG IN FORM -->

          </div>
        </div>
      </div>
    </div>
  </div>

@endsection
