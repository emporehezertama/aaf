@extends('layouts.karyawan')

@section('title', 'Cuti Karyawan - PT. Arthaasia Finance')

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
                <h4 class="page-title">Form Cuti Karyawan</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Cuti Karyawan</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" id="form-cuti" onsubmit="return false;" enctype="multipart/form-data" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Form Cuti</h3>
                        <hr />
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

                        {{ csrf_field() }}
                        
                        <div class="col-md-6" style="padding-left: 0;">
                            <div class="form-group">
                                <label class="col-md-6">NIK / Nama Karyawan</label>
                                <label class="col-md-6">Telepon</label>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ Auth::user()->nik .' / '. Auth::user()->name }}" readonly="true">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" value="{{ Auth::user()->telepon }}" readonly="true" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">Jabatan</label>
                                <label class="col-md-6">Division / Departement</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control jabatan" value="{{ Auth::user()->organisasi_job_role }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control department" value="{{ isset(Auth::user()->department) ? Auth::user()->department->name : '' }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Jenis Cuti</label>
                                <div class="col-md-6">
                                    <select class="form-control" name="jenis_cuti" readonly>
                                        <option value="">Pilih Jenis Cuti</option>
                                        <?php 
                                            $kuota = 0; 
                                            $cuti_terpakai = 0;
                                        ?>
                                        @foreach(list_user_cuti() as $item)
                                        <?php 
                                            if($data->jenis_cuti == $item->id)
                                            {
                                                $kuota = $item->kuota;
                                                $cuti_terpakai = get_cuti_terpakai($item->id, \Auth::user()->id);
                                            }   
                                        ?>
                                        <option value="{{ $item->id }}" {{ $data->jenis_cuti == $item->id ? 'selected' : '' }} data-kuota="{{ $item->kuota }}" data-cutiterpakai="{{ get_cuti_terpakai($item->id, \Auth::user()->id) }}">{{ $item->jenis_cuti }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6" id="section_jenis_izin" style="display: none;">
                                    <input type="text" class="form-control" name="jenis_izin" placeholder="Jenis Izin" />
                                </div>
                            </div>
                            <div class="form-group"> 
                                <label class="col-md-4">Kuota Cuti / Ijin</label>
                                <label class="col-md-3">Cuti Terpakai</label>
                                <label class="col-md-3">Sisa Cuti</label>
                                <div class="col-md-4">
                                    <input type="text" class="form-control kuota_cuti"  value="{{ get_cuti_user($data->jenis_cuti, \Auth::user()->id, 'kuota') }}" readonly="true" />
                                </div>
                                <div class="col-md-3">
                                    <input type="text" class="form-control cuti_terpakai" readonly="true" value="{{ get_cuti_user($data->jenis_cuti, \Auth::user()->id, 'cuti_terpakai') }}"  />
                                </div>
                                <div class="col-md-3">
                                    <input type="text" readonly="true" class="form-control sisa_cuti" value="{{ get_cuti_user($data->jenis_cuti, \Auth::user()->id, 'sisa_cuti') }}">
                                </div>
                                <div class="col-md-2">
                                    <label class="btn btn-info btn-sm" id="history_cuti"><i class="fa fa-history"></i> History</label>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <br />
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12">Tanggal Cuti</label>
                                <div class="col-md-5">
                                    <input type="text" name="tanggal_cuti_start" readonly="true" value="{{ $data->tanggal_cuti_end }}" class="form-control datepicker" placeholder="Start Tanggal" />
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="tanggal_cuti_end"  readonly="true" value="{{ $data->tanggal_cuti_end }}" class="form-control datepicker" placeholder="End Tanggal">
                                </div>
                                <div class="col-md-2">
                                    <h3 class="btn btn-info total_hari_cuti" style="margin-top:0;">{{ $data->total_cuti }} Hari</h3>
                                    <h3 class="btn btn-warning btn_hari_libur" style="margin-top:0;">Hari Libur</h3>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-md-12">Keperluan</label>
                                <div class="col-md-12">
                                    <textarea class="form-control" name="keperluan" readonly="true">{{ $data->keperluan }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Selama Cuti, Backup dan Informasi pekerjaan diberikan kepada</label>
                                <div class="col-md-12">
                                    <input type="text" readonly="true" class="form-control" value="{{  $data->backup_karyawan->nik }} / {{  $data->backup_karyawan->name }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">Jabatan</label>
                                <label class="col-md-6">Division / Departement</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control jabatan_backup" value="{{ $data->backup_karyawan->organisasi_job_role }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control department_backup" value="{{ isset($data->backup_karyawan->department->name) ? $data->backup_karyawan->department->name : ''  }}">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">No Handphone</label>
                                <label class="col-md-6">Email</label>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control no_handphone" value="{{ $data->backup_karyawan->telepon }}">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" readonly="true" class="form-control email" value="{{ $data->backup_karyawan->email }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <a href="{{ route('karyawan.cuti.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                            <br style="clear: both;" />
                        </div>
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

<!-- sample modal content -->
<div id="modal_history_cuti" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">History Cuti</h4> </div>
                <div class="modal-body">
                   <div class="form-horizontal">
                    <table class="table tabl-hover">
                       <thead>
                           <tr>
                               <th width="50">NO</th>
                               <th>TANGGAL CUTI</th>
                               <th>JENIS CUTI</th>
                               <th>LAMA CUTI</th>
                               <th>KEPERLUAN</th>
                           </tr>
                       </thead> 
                       <tbody>
                        @foreach(list_cuti_user(Auth::user()->id) as $no => $item)
                        
                        @if($item->status == 1 || $item->status == 3)
                            @continue
                        @endif

                        <tr>
                           <td>{{ $no + 1 }}</td>
                           <td>{{ $item->tanggal_cuti_start }} - {{ $item->tanggal_cuti_end }}</td>
                           <td>{{ $item->jenis_cuti }}</td>
                           <td>{{ lama_hari($item->tanggal_cuti_start, $item->tanggal_cuti_end) }}</td>
                           <td>{{ $item->keperluan }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                       </table>
                   </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>


<!-- sample modal content -->
<div id="modal_hari_libur" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">HARI LIBUR</h4> </div>
                <div class="modal-body">
                   <div class="form-horizontal">
                    <table class="table tabl-hover">
                       <thead>
                           <tr>
                               <th width="50">NO</th>
                               <th>TANGGAL</th>
                               <th>KETERANGAN</th>
                           </tr>
                       </thead> 
                       <tbody>
                        @foreach(list_hari_libur() as $no => $item)
                        <tr>
                           <td>{{ $no + 1 }}</td>
                           <td>{{ $item->tanggal }}</td>
                           <td>{{ $item->keterangan }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                       </table>
                   </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('footer-script')
<link href="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script type="text/javascript">

    $(".btn_hari_libur").click(function(){

        $("#modal_hari_libur").modal('show');

    });

    $("input[name='tanggal_cuti_end'], input[name='tanggal_cuti_start']").on('change', function(){
        
        var oneDay      = 24*60*60*1000; // hours*minutes*seconds*milliseconds
        var start_date  = $("input[name='tanggal_cuti_start']").val();
        var end_date    = $("input[name='tanggal_cuti_end']").val();
        
        if(start_date == "" || end_date == "")
        {
            return false;
        }


        if(start_date == end_date)
        {
            $('.total_hari_cuti').html('1 Hari');            
        }
        else
        {
            var star_date   = new Date(start_date);
            var end_date    = new Date(end_date);

            $('.total_hari_cuti').html((Math.round(Math.round((end_date.getTime() - star_date.getTime()) / (oneDay))) + 1) +" Hari" );
        }
    });

    $("#history_cuti").click(function(){

        $("#modal_history_cuti").modal('show');

    });

    $("select[name='jenis_cuti']").on('change', function(){

        var el = $(this).find(":selected");
    
        if($(this).val() != 'Izin')
        {   
            $('.kuota_cuti').val( el.data('kuota') );
        }else{
            $('.kuota_cuti').val('0');
        }

    });

    $("select[name='jenis_cuti']").on('change', function(){

        if($(this).val() == 'Izin')
        {
            $("#section_jenis_izin").show();
        }else{
            $("#section_jenis_izin").hide();
        }

    });

    $("#btn_submit_form").click(function(){
        bootbox.confirm('Submit Form Cuti ?', function(result){
            if(result)
            {
                $("#form-cuti").submit();
            }
        });
    });

    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
    });
    
    $("select[name='backup_user_id']").on('change', function(){

        var id = $(this).val();

        $.ajax({
            type: 'POST',
            url: '{{ route('ajax.get-karyawan-by-id') }}',
            data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
            dataType: 'json',
            success: function (data) {

                $('.jabatan_backup').val(data.data.organisasi_job_role);
                $('.department_backup').val(data.data.department_name);
                $('.no_handphone').val(data.data.telepon);
                $('.email').val(data.data.email);
            }
        });

    });

    $("select[name='user_id']").on('change', function(){

        var id = $(this).val();

        $.ajax({
            type: 'POST',
            url: '{{ route('ajax.get-karyawan-by-id') }}',
            data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
            dataType: 'json',
            success: function (data) {

                $('.hak_cuti').val(data.data.hak_cuti);
                $('.jabatan').val(data.data.nama_jabatan);
                $('.department').val(data.data.department_name);
                $('.cuti_terpakai').val(data.data.cuti_yang_terpakai);

                $("select[name='backup_user_id'] option[value="+ id +"]").remove();
            }
        });
    });


    $("#add").click(function(){

        var no = $('.table-content-lembur tr').length;

        var html = '<tr>';
            html += '<td>'+ (no+1) +'</td>';
            html += '<td><textarea name="description[]" class="form-control"></textarea></td>';
            html += '<td><input type="text" name="awal[]" class="form-control" /></td>';
            html += '<td><input type="text" name="akhir[]" class="form-control" /></td>';
            html += '<td><input type="text" name="total_lembur[]" class="form-control"  /></td>';
            html += '<td><select name="employee_id" class="form-control"><option value="">Pilih Employee</option></select></td>';
            html += '<td><select name="employee_id" class="form-control"><option value="">Pilih SPV</option></select></td>';
            html += '<td><select name="employee_id" class="form-control"><option value="">Pilih Manager</option></select></td>';
            html += '</tr>';

        $('.table-content-lembur').append(html);

    });

</script>


@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
