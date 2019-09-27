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
                <h4 class="page-title">Dashboard</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="{{ route('administrator.karyawan.create') }}" class="btn btn-success btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-plus"></i> TAMBAH KARYAWAN</a>
                <!--a href="{{ route('administrator.karyawan.create') }}" class="btn btn-success btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-download"></i> DOWNLOAD</a-->

                <a class="btn btn-info btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light" id="add-import-karyawan"> <i class="fa fa-upload"></i> IMPORT</a>
                
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Karyawan</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Manage Karyawan</h3>
                    <br />
                    <div class="table-responsive">
                        <table id="data_table" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">#</th>
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>JENIS KELAMIN</th>
                                    <th>TELEPON</th>
                                    <th>EMAIL</th>
                                    <th>DEPARTEMENT</th>
                                    <th>POSITION</th>
                                    <th>JOB RULE</th>
                                    <th>STATUS LOGIN</th>
                                    <th>MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>
                                        <td>{{ $item->nik }}</td>
                                        <td>{{ strtoupper($item->name) }}</td>
                                        <td>{{ $item->jenis_kelamin }}</td>
                                        <td>{{ $item->telepon }}</td>
                                        <td>{{ $item->email }}</td>
                                        <td>{{ isset($item->department->name) ? $item->department->name : '' }}</td>
                                        <td>{{ isset($item->organisasiposition->name) ? $item->organisasiposition->name : '' }}</td>
                                        <td>{{ $item->organisasi_job_role }}</td>
                                        <td>
                                            <a onclick="status_karyawan('{{ $item->name .' - '. $item->nik }}',  {{ $item->id }}, {{ $item->status }})"> 
                                            @if($item->status == 1)
                                                <label class="btn btn-success btn-xs"><i class="fa fa-check"></i> Active</label>
                                            @else
                                                <label class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Inactive</label>
                                            @endif
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('administrator.karyawan.edit', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail</button></a>
                                            <div class="clearfix"></div>
                                            <a class="btn btn-default btn-xs" onclick="change_password('{{ $item->name .' - '. $item->nik }}', {{ $item->id }})"><i class="fa fa-key"></i> Change Password </a><br />
                                            <a href="{{ route('administrator.karyawan.print-profile', $item->id) }}" target="_blank" class="btn btn-default btn-xs"><i class="fa fa-print"></i> print</a>
                                            <a onclick="confirm_loginas('{{ $item->name }}','{{ route('administrator.karyawan.autologin', $item->id) }}')"  class="btn btn-warning btn-xs"><i class="fa fa-key"></i> Autologin</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @include('layouts.footer')
</div>
<!-- modal content education  -->
<div id="modal_import" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="myModalLabel">Import Data</h4> </div>
                    <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('administrator.karyawan.import') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">File (xls)</label>
                            <div class="col-md-9">
                                <input type="file" name="file" class="form-control" />
                            </div>
                        </div>
                        <a href="{{ asset('storage/sample/Sample-Karyawan.xlsx') }}"><i class="fa fa-download"></i> Download Sample Excel</a>
                    </div>
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                        <label class="btn btn-info btn-sm" id="btn_import">Import</label>
                    </div>
                </form>
                <div style="text-align: center;display: none;" class="div-proses-upload">
                    <h3>Proses upload harap menunggu !</h3>
                    <h1 class=""><i class="fa fa-spin fa-spinner"></i></h1>
                </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- modal content education  -->
<div id="modal_status_karyawan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title nama_karyawan">Status Karyawan</h4> </div>
                    <form method="POST" id="form-status-karyawan" class="form-horizontal frm-modal-education" action="{{ route('administrator.karyawan.change-status-karyawan') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">Status</label>
                            <div class="col-md-9">
                                <select class="form-control" name="status">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>
                        <input type="hidden" name="id" />
                    </div>
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info btn-sm">Change Status</button>
                    </div>
                </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- modal content education  -->
<div id="modal_change_password" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title nama_karyawan">Change Password Karyawan</h4> </div>
                    <form method="POST" id="form-changen-password-karyawan" class="form-horizontal frm-modal-education" action="{{ route('administrator.karyawan.change-password-karyawan') }}">
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">Password</label>
                            <div class="col-md-9">
                              <input type="password" name="password" class="form-control modal-input-change-password" />
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-md-3">Confirm Password</label>
                            <div class="col-md-9">
                              <input type="password" name="confirm" class="form-control modal-input-change-confirm" />
                            </div>
                        </div>
                        <input type="hidden" name="id" />
                    </div>
                    <div class="modal-footer"> 
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Cancel</button>
                        <label class="btn btn-info btn-sm" id="submit_change_password_karyawan">Change Password</label>
                    </div>
                </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
@section('footer-script')
<script type="text/javascript">
    
    function confirm_loginas(name, url)
    {
        bootbox.confirm("Login sebagai "+ name +" ? ", function(result){
            if(result)
            {
                window.location = url;
            }
        });
    }

    $("#submit_change_password_karyawan").click(function(){

        var password    = $('.modal-input-change-password').val();
        var confirm     = $('.modal-input-change-confirm').val();

        if(password == confirm)
        {
            $("#form-changen-password-karyawan").submit();            
        }
        else
        {
            alert('Konfirmasi password tidak sama !');
        }
    });
    
    var change_password = function(name, id){
        $("#modal_change_password input[name='id']").val(id);
        $("#modal_change_password").modal("show");
    }

    var status_karyawan = function(name, id, status){
        $("#modal_status_karyawan").modal("show");
        $("#modal_status_karyawan input[name='id']").val(id);
        $("#modal_status_karyawan select[name='status']").val(status);
        $("#modal_status_karyawan .nama_karyawan").html(name);
    }

    $("#btn_import").click(function(){

        $("#form-upload").submit();
        $("#form-upload").hide();
        $('.div-proses-upload').show();

    });

    $("#add-import-karyawan").click(function(){
        $("#modal_import").modal("show");
        $('.div-proses-upload').hide();
        $("#form-upload").show();
    })
</script>
@endsection

@endsection