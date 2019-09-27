@extends('layouts.karyawan')

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
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('karyawan.approval.overtime.proses') }}" id="form-overtime" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Data Overtime Sheet</h3>
                        <br />
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
                        
                        @php($readonly="")
                        @if($approval->nama_approval == 'HR Operation')
                            @if($data->is_hr_benefit_approved !== NULL)
                                @php($readonly='readonly="true"')
                            @endif
                        @endif
                        @if($approval->nama_approval == 'Manager HR')
                            @if($data->is_hr_manager === NULL)
                                @php($readonly='readonly="true"')
                            @endif
                        @endif
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label class="col-md-6">NIK / Nama Karyawan</label>
                            <label class="col-md-6">Department</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" value="{{ $data->user->nik .' - '. $data->user->name  }}" readonly="true" />
                            </div>
                            <div class="col-md-6">
                                <input type="text" readonly="true" class="form-control" value="{{ isset($data->user->department->name) ? $data->user->department->name : '' }}">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Jabatan</label>
                            <div class="col-md-6">
                                <input type="text" readonly="true" class="form-control" value="{{ $data->user->organisasi_job_role }}">
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-hover manage-u-table">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>TANGGAL</th>
                                        <th>DESCRIPTION</th>
                                        <th>AWAL</th>
                                        <th>AKHIR</th>
                                        <th>TOTAL LEMBUR (JAM)</th>
                                        @if($approval->nama_approval != 'Manager HR')
                                        <th>TOTAL APPROVAL</th>
                                        <th>TOTAL MEAL</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="table-content-lembur">
                                    @php($total_lembur=[])
                                    @foreach($data->overtime_form as $no => $item)
                                    <tr>
                                        <td>{{ $no+1 }}</td>
                                        <td><input type="text" readonly="true" value="{{ $item->tanggal }}" class="form-control"></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->description }}"></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->awal }}" /></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->akhir }}" /></td>
                                        <td><input type="text" readonly="true" class="form-control" value="{{ $item->total_lembur }}" /></td>
                                        @if($approval->nama_approval != 'Manager HR')
                                        <td><input type="text" class="form-control  input_approval_jam" {{ $readonly }} name="total_approval[{{ $item->id }}]" value="{{ $item->total_approval }}" placeholder="Total Approval" ></td>
                                        <td><input type="text" class="form-control input_total_meal" {{ $readonly }} name="total_meal[{{ $item->id }}]" value="{{ $item->total_meal }}" placeholder="Total Meal" ></td>
                                        @endif
                                    </tr>
                                    @php($total_lembur[]=$item->total_lembur)
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="5" style="text-align: right;">Total : </th>
                                        <th>{{ sum_lembur_jam($total_lembur) }}</th>
                                        @if($approval->nama_approval != 'Manager HR')
                                        <th class="total_approve_jam"></th>
                                        <th class="total_meal"></th>
                                        @endif
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <input type="hidden" name="status" value="0" />
                        <input type="hidden" name="id" value="{{ $data->id }}">
                        <input type="hidden" name="total_approval_all">
                        <input type="hidden" name="total_meal_all">
                        <div class="clearfix"></div>
                        <br />
                        <hr />
                        <a href="{{ route('karyawan.approval.overtime.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                        @if($data->is_approved_atasan == 1)
                            @if($approval->nama_approval == 'Manager HR')
                                @if($data->is_hr_manager === NULL)
                                    <a class="btn btn-sm btn-danger waves-effect waves-light m-r-10" id="btn_tolak"><i class="fa fa-close"></i> Denied</a>
                                    <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_approved"><i class="fa fa-save"></i> Approve</a>
                                @endif
                            @endif

                            @if($approval->nama_approval == 'HR Operation')
                                @if($data->is_hr_benefit_approved === NULL)
                                    <a class="btn btn-sm btn-danger waves-effect waves-light m-r-10" id="btn_tolak"><i class="fa fa-close"></i> Denied</a>
                                    <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_approved"><i class="fa fa-save"></i> Approve</a>
                                @endif
                            @endif
                        @endif
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
@section('footer-script')
<link rel="stylesheet" href="{{ asset('js/wickedpicker/dist/wickedpicker.min.css') }}" type="text/css" />
<script type="text/javascript" src="{{ asset('js/wickedpicker/dist/wickedpicker.min.js') }}"></script>
<script type="text/javascript">
    
    calculate_total_meal();

    $('.input_total_meal').on('input', function(){
        calculate_total_meal();
    })
    
    function calculate_total_meal()
    {   
        var total = 0;
        $('.input_total_meal').each(function(){
            if($(this).val() != "")
            {
                total += parseInt($(this).val());
            }
        });

        $("input[name='total_meal_all']").val(total);
        $('.total_meal').html(numberWithComma(total));
    }


    $(".input_approval_jam").each(function(){
        
        $(this).on('change', function(){
            var split = $(this).val().split(':');
            
            if(split[0] >= 2 )
            {
                if($(this).parent().parent().find('.input_total_meal').val() == "")
                {
                    $(this).parent().parent().find('.input_total_meal').val(20000); 
                }
            }
            else
            {
                if($(this).parent().parent().find('.input_total_meal').val() == 20000)
                {
                    $(this).parent().parent().find('.input_total_meal').val(""); 
                }
            }
            
            calculate_total_meal();       
            calculate_total_jam_approve();
        }); 
    })

    function calculate_total_jam_approve()
    {
        var params = [];

        $(".input_approval_jam").each(function(){
            if($(this).val() != '') { params.push($(this).val()); }
        });

        $.ajax({
            type: 'POST',
            url: '{{ route('ajax.calculate-hours-time') }}',
            data: {'data' : params, '_token' : $("meta[name='csrf-token']").attr('content')},
            dataType: 'json',
            success: function (data) {
                $('.total_approve_jam').html( data.data  );
                $("input[name='total_approval_all']").val(data.data);
            }
        });
    }

    $('.input_approval_jam').each(function(){

        var this_now  = $(this).val();
        if(this_now == "")
        {
            this_now = "00 : 00";
        }

        $(this).wickedpicker({
            now : this_now,
            title: 'Total Approval',
            twentyFour: true,
        });
    })

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
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
