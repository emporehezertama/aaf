@extends('layouts.administrator')

@section('title', 'Overtime Sheet - PT. Arthaasia Finance')

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
                <h4 class="page-title">Form Overtime Sheet</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Overtime Sheet</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" method="POST" action="{{ route('administrator.overtime.update', $data->id) }}">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Form Overtime Sheet</h3>
                        <hr />
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <strong>Whoops!</strong> There were some problems with your input.<br><br>
                                <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                                </ul>
                            </div>
                        @endif

                        {{ csrf_field() }}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-6">NIK / Nama Karyawan</label>
                                <label class="col-md-6">Jabatan</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ $data->user->nik .' - '. $data->user->name  }}" readonly="true" />
                                </div>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control jabatan" value="{{ isset($data->user->organisasiposition->name) ? $data->user->organisasiposition->name : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">Department</label>
                                <label class="col-md-6">Job Rule</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control department" value="{{ isset($data->user->department->name) ? $data->user->department->name : '' }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control" value="{{ $data->user->organisasi_job_role }}" >
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-bordered manage-u-table">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>TANGGAL</th>
                                        <th>DESCRIPTION</th>
                                        <th>AWAL</th>
                                        <th>AKHIR</th>
                                        <th>TOTAL LEMBUR (JAM)</th>
                                    </tr>
                                </thead>
                                <tbody class="table-content-lembur">
                                    @foreach($data->overtime_form as $no => $item)
                                    <tr>
                                        <td>{{ $no+1 }}</td>
                                        <td><input type="text" readonly="true" value="{{ $item->tanggal }}" class="form-control"></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->description }}"></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->awal }}" /></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->akhir }}" /></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->total_lembur }}" /></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <input type="hidden" name="status" value="0" />
                            <input type="hidden" name="id" value="{{ $data->id }}">
                        </div>
                        <hr />
                        <div class="clearfix"></div>
                        <br />
                        <a href="{{ route('administrator.overtime.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                        <a class="btn btn-sm btn-danger waves-effect waves-light m-r-10" id="btn_tolak"><i class="fa fa-close"></i> Denied sebagai Atasan</a>
                        <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_approved"><i class="fa fa-save"></i> Approve sebagai Atasan</a>
                        <br style="clear: both;" />
                        <div class="clearfix"></div>
                    </div>
                </div>    
            </form>                    
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @extends('layouts.footer')
</div>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->

@section('footer-script')
<script type="text/javascript">
    $("#btn_approved").click(function(){
        bootbox.confirm('Approve Overtime Karyawan ?', function(result){

            $("input[name='status']").val(1);
            if(result)
            {
                $('#form-overtime').submit();
            }

        });
    });

    $("#btn_tolak").click(function(){
        bootbox.confirm('Tolak Overtime Karyawan ?', function(result){
            $("input[name='status']").val(0);
            if(result)
            {
                $('#form-overtime').submit();
            }

        });
    });
</script>
@endsection

@endsection
