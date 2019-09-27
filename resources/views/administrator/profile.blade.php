@extends('layouts.administrator')

@section('title', 'Dashboard - PT. Arthaasia Finance')

@section('sidebar')

@endsection

@section('content')
<!-- ============================================================== -->
<!-- Page Content -->
<!-- ============================================================== -->
<div id="page-wrapper">
    <div class="container-fluid">
        <div class="row bg-title">
            <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
                <h4 class="page-title">HOME</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Home</a></li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <div class="row">
            <div class="col-md-12 col-sm-12 col-lg-12">
                <div class="panel">
                    <div class="p-30">
                        <div class="row">
                            <div class="col-xs-2 col-sm-2">
                                @if(empty(Auth::user()->foto))
                                <img src="{{ asset('admin-css/images/user.png') }}" alt="varun" class="img-circle img-responsive">
                                @else
                                <img src="{{ asset('storage/foto/'. Auth::user()->foto) }}" alt="varun" class="img-circle img-responsive">
                                @endif
                            </div>
                            <div class="col-xs-12 col-sm-8">
                                <h2 class="m-b-0">{{ Auth::user()->name }}</h2>
                                <h4>{{ get_department_name(Auth::user()->department_id) }}</h4>
                                <a class="btn btn-info btn-xs" id="change_password">Change Password <i class="fa fa-key"></i></a>
                                @if(Auth::user()->last_change_password !== null) 
                                    <p>Last Update :  {{ date('d F Y H:i', strtotime(Auth::user()->last_change_password)) }}</p>
                                @endif
                            </div>
                        </div>
                        <hr />
                    </div>

                     <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('administrator.profile.update') }}" method="POST">
                        <div class="col-md-6">
                            <div class="white-box">
                                {{ csrf_field() }}
                                <div class="form-group">
                                    <label class="col-md-2">Name</label>
                                    <div class="col-md-6">
                                        <input type="text" name="name" class="form-control" value="{{ \Auth::user()->name }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2">Email</label>
                                    <div class="col-md-6">
                                        <input type="text" name="email" class="form-control" value="{{ \Auth::user()->email }}">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2">Telepon</label>
                                    <div class="col-md-6">
                                        <input type="text" name="telepon" class="form-control" value="{{ \Auth::user()->telepon }}">
                                    </div>
                                </div>
                                <hr />
                                <button type="submit" class="btn btn-info btn-sm"><i class="fa fa-save"></i> Update Profile</button>
                            </div>
                        </div>
                    </form>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>

    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
</div>
<style type="text/css">
    .col-in h3 {
        font-size: 20px;
    }
</style>

@section('footer-script')
     <div class="modal fade" id="modal_reset_password" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
              <form>
                
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="exampleModalLabel1">Reset Password !</h4> 
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Password:</label>
                        <input type="password" name="password"class="form-control" placeholder="Password"> 
                    </div>

                    <div class="form-group">
                        <label for="recipient-name" class="control-label">Konfirmasi Password:</label>
                        <input type="password" name="confirm"class="form-control" placeholder="Konfirmasi Password"> 
                    </div>
                </div>
                <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-info btn-sm" id="submit_password">Submit Password <i class="fa fa-arrow-right"></i></button>
                </div>
              </form>
            </div>
        </div>
    </div> 

<script type="text/javascript">

    $("#change_password").click(function(){

        $("#modal_reset_password").modal("show");

    });

    $("#submit_password").click(function(){

        var password    = $("input[name='password']").val();
        var confirm     = $("input[name='confirm']").val();

        if(password == "" || confirm == "")
        {
            bootbox.alert('Password atau Konfirmasi Password harus diisi !');
            return false;
        }

        if(password != confirm)
        {
            bootbox.alert('Password tidak sama');
        }
        else
        {
             $.ajax({
                type: 'POST',
                url: '{{ route('ajax.update-first-password') }}',
                data: {'id' : {{ Auth::user()->id }}, 'password' : password, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {
                    location.reload();
                }
            });
        }
    });
</script>
@endsection

@endsection
