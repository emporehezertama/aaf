@extends('layouts.login')

@section('content')
<!-- Preloader -->
<div class="preloader">
  <div class="cssload-speeding-wheel"></div>
</div>
<section id="wrapper" class="new-login-register">
      <div class="lg-info-panel" style="z-index: 99;">
              <div class="inner-panel">
                  <a href="javascript:void(0)" class="p-20 di"></a>
                  <div class="lg-content" style="background: #EAEAEA; padding-top: 20px;padding-bottom: 20px;">
                      <img src="{{ asset('logo.png') }}" style="width: 200px;">
                      <h2 style="color: black;">PT. Arthaasia Finance</h2>
                      <h4>Kencana Tower 5 & 6 Floor, Business Park Kebun Jeruk, Jakarta Barat.<br />
                          Phone: (021) 5890-8189, 5890-8190</h2>
                  </div>
              </div>
      </div>
      <div class="new-login-box">
          <div class="white-box">
              <h3 class="box-title m-b-0">Login System</h3>
              <small>Sign In to and Enter your details below</small>
            <form class="form-horizontal new-lg-form" method="POST" id="loginform" action="{{ route('login') }}">
              
              {{ csrf_field() }}

              @if($errors->has('email'))
                <label style="color: red;">Nomor Induk Karyawan anda atau Password anda salah, silahkan dicoba kembali</label> 
              @endif

              <div class="form-group  m-t-20">
                <div class="col-xs-12">
                  <label>Nomor Induk Karyawan</label>
                  <input class="form-control" type="text" required="" name="email" placeholder="Nomor Induk Karyawan" value="{{ old('email') }}">
                 
                </div>
              </div>
              <div class="form-group">
                <div class="col-xs-12">
                  <label>Password</label>
                  <input class="form-control" name="password" id="password" type="password" required="" placeholder="Password">
                  <span class="field-icon toggle-password fa fa-fw fa-eye"></span>
                </div>
              </div>
              <div class="form-group">
                <div class="col-md-12">
                  <div class="checkbox checkbox-info pull-left p-t-0">
                    <input id="checkbox-signup" type="checkbox"  name="remember" {{ old('remember') ? 'checked' : '' }}>
                    <label for="checkbox-signup"> Remember me </label>
                  </div>
                </div>
              </div>
              <div class="form-group text-center m-t-20">
                <div class="col-xs-12">
                  <button class="btn btn-info btn-lg btn-block btn-rounded text-uppercase waves-effect waves-light" type="submit">Log In</button>
                </div>
              </div>
            </form>
          </div>
      </div>
        <!-- 
      <img src="{{ asset('1.jpeg')}}" style="height: 80px;z-index: 9999;position: absolute;bottom: 0;right: 0px;" />          
      <img src="{{ asset('2.jpeg')}}" style="height: 80px;z-index: 9999;position: absolute;bottom: 0;right: 404px;" />          
      <img src="{{ asset('2.jpeg')}}" style="height: 80px;position: absolute;bottom: 0;right: 764px;" /> -->          
</section>
<style type="text/css">
  .field-icon {
    float: right;
    margin-right: 9px;
    margin-top: -28px;
    position: relative;
    z-index: 2;
  }
</style>
@endsection


