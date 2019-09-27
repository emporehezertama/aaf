@extends('layouts.karyawan')

@section('title', 'Exit Interview Form - PT. Arthaasia Finance')

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
                <h4 class="page-title">Exit Interview & Exit Clearance Form</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Exit Interview & Exit Clearance Form</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('karyawan.exit-interview.store') }}" method="POST" id="exit_interview_form">
                <div class="col-md-12">
                    <div class="white-box">

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

                        <ul class="nav customtab nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#interview" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> <strong>EXIT INTERVIEW FORM</strong></span></a></li>
                            <li role="presentation"><a href="#clearance" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs"><strong>EXIT CLEARANCE FORM</strong></span></a></li>
                        </ul>

                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade" id="clearance">
                               
                                <div class="form-group">
                                    <label class="col-md-12">INVENTORY RETURN TO HRD</label>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30px;">NO</th>
                                                    <th>ITEM/JENIS</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                @foreach(list_exit_clearance_inventory_to_hrd() as $no => $item)
                                                <tr>
                                                    <td>{{ $no + 1 }}</td>
                                                    <td>{{ $item['item'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">INVENTORY RETURN TO GENERAL AFFAIR (GA)</label>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30px;">NO</th>
                                                    <th>ITEM/JENIS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach(list_exit_clearance_inventory_to_ga() as $no => $item)
                                                <tr>
                                                    <td>{{ $no + 6 }}</td>
                                                    <td>{{ $item['item'] }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                 <div class="form-group">
                                    <label class="col-md-12">INVENTORY RETURN</label>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30px;">NO</th>
                                                    <th>ITEM/JENIS</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if(Auth::user()->inventaris_mobil)
                                                <tr>
                                                    <td>12</td>
                                                    <td>
                                                        <p><strong>Mobil</strong></p>
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th>Tipe Mobil</th>
                                                                <th>Tahun</th>
                                                                <th>No Polisi</th>
                                                                <th>Status Mobil</th>
                                                            </tr>
                                                            @foreach(Auth::user()->inventaris_mobil as $item)
                                                            <input type="hidden" name="inventaris_mobil[]" value="{{ $item->id }}" />
                                                            <tr>
                                                                <td>{{ $item->tipe_mobil }}</td>
                                                                <td>{{ $item->tahun }}</td>
                                                                <td>{{ $item->no_polisi }}</td>
                                                                <td>{{ $item->status_mobil }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                                @endif

                                                @if(Auth::user()->inventaris)
                                                <tr>
                                                    <td>13</td>
                                                    <td>
                                                        <p><strong>Laptop/PC & Other IT Device</strong></p>
                                                        <table class="table table-bordered">
                                                            <tr>
                                                                <th>Jenis Inventaris</th>
                                                                <th>Keterangan</th>
                                                            </tr>
                                                            @foreach(Auth::user()->inventaris as $item)
                                                            <input type="hidden" name="inventaris[]" value="{{ $item->id }}" />
                                                            <tr>
                                                                <td>{{ $item->jenis }}</td>
                                                                <td>{{ $item->description }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </table>
                                                    </td>
                                                </tr>
                                                @endif

                                                <tr>
                                                    <td>14</td>
                                                    <td>
                                                        Password PC/Laptop <br />
                                                        <div class="col-md-4" style="padding-left:0;">
                                                            <input type="text" name="inventory_it_username_pc" class="form-control" placeholder="Username" value="{{ old('inventory_it_username_pc') }}" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" name="inventory_it_password_pc" class="form-control" placeholder="Password" value="{{ old('inventory_it_password_pc') }}" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>15</td>
                                                    <td>Other Inventory</td>
                                                </tr>
                                                <tr>
                                                    <td>16</td>
                                                    <td>
                                                        Email Address<br />
                                                        <div class="col-md-4" style="padding-left: 0;">
                                                            <input type="email" name="inventory_it_email" class="form-control" placeholder="Email" value="{{ Auth::user()->email }}" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>17</td>
                                                    <td>
                                                        Arium <br />
                                                        <div class="col-md-4" style="padding-left:0;">
                                                            <input type="text" name="inventory_it_username_arium" class="form-control" placeholder="Username" value="{{ old('inventory_it_username_arium') }}" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" name="inventory_it_password_arium" class="form-control" placeholder="Password" value="{{ old('inventory_it_password_arium') }}" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <h4><b>Approval</b></h4>
                                        <div class="col-md-12" style="border: 1px solid #eee; padding: 15px">
                                            <br />
                                            <div class="form-group">
                                                <label class="col-md-12">
                                                    Superior / Atasan Langsung
                                                </label>
                                                <div class="col-md-12">
                                                    <input type="text" class="form-control autcomplete-atasan">
                                                    <input type="hidden" name="atasan_user_id" />
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-md-6">Jabatan</label>
                                                <label class="col-md-6">Division / Departement</label>
                                                <div class="col-md-6">
                                                    <input type="text" readonly="true" class="form-control jabatan_atasan">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" readonly="true" class="form-control department_atasan">
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-md-6">No Handphone</label>
                                                <label class="col-md-6">Email</label>
                                                <div class="col-md-6">
                                                    <input type="text" readonly="true" class="form-control no_handphone_atasan">
                                                </div>
                                                <div class="col-md-6">
                                                    <input type="text" readonly="true" class="form-control email_atasan">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <br />

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <a href="{{ route('karyawan.exit-interview.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                                <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="submit_form"><i class="fa fa-save"></i> Submit Exit Interview & Exit Clearance</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div role="tabpanel" class="tab-pane fade in active" id="interview">
                                {{ csrf_field() }}
                                <div class="col-md-6" style="padding-left: 0;">
                                    <div class="form-group">
                                        <label class="col-md-12">NIK / Nama Karyawan</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" value="{{ Auth::user()->nik .' / '. Auth::user()->name }}" readonly="true">
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
                                        <label class="col-md-12">Join Date / Tanggal Masuk</label>
                                        <div class="col-md-6">
                                            <input type="text" readonly="true" class="form-control" value="{{ Auth::user()->join_date }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6">Resign Date / Tanggal Keluar</label>
                                        <label class="col-md-6">Date Last Work / Tanggal Terakhir Bekerja</label>
                                        <div class="col-md-6">
                                            <input type="text" name="resign_date" class="form-control datepicker" value="" >
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="last_work_date" class="form-control datepicker" value="">
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    <br />
                                </div>
                                <div class="clearfix"></div>
                                <div class="form-group">
                                    <label class="col-md-6">Alasan pengunduran diri / Resignation reason :</label>
                                    <div class="col-md-12">
                                        <ul class="list-group">
                                            @foreach(get_reason_interview() as $item)
                                            <li class="list-group-item"><label><input type="radio" name="exit_interview_reason" value="{{ $item->id }}" /> {{ $item->label }}</label>

                                            @if($item->id == 1)
                                            <div class="form-group perusahaan_lain" style="display: none;">
                                                <hr />
                                                <label class="col-md-12">Jika pindah ke perusahaan baru </label>
                                                <p class="col-md-6">Tujuan perusahaan baru </p>
                                                <p class="col-md-6">Jenis bidang usaha </p>
                                                <div class="col-md-6">
                                                    <textarea class="form-control" name="tujuan_perusahaan_baru">{{ old('tujuan_perusahaan_baru') }}</textarea>
                                                </div>
                                                <div class="col-md-6">
                                                    <textarea class="form-control" name="jenis_bidang_usaha">{{ old('jenis_bidang_usaha') }}</textarea>
                                                </div>
                                            </div>
                                            @endif

                                            </li>
                                            @endforeach
                                            <li class="list-group-item">
                                                <label><input type="radio" name="exit_interview_reason" value="other" /> Other (Lainnya, ditulis alasannya)</label>
                                                <textarea class="form-control" placeholder="Other Reason" name="other_reason" style="display: none;"></textarea>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Hal yang berkesan selama bekerja di Artha Asia Finance</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="hal_berkesan">{{ old('hal_berkesan') }}</textarea>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Hal yang tidak berkesan selama bekerja di Artha Asia Finance</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="hal_tidak_berkesan">{{ old('hal_tidak_berkesan') }}</textarea>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Masukan terhadap Artha Asia Finance</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="masukan">{{ old('masukan') }}</textarea>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Hal yang akan dilakukan setelah resign dari Artha Asia Finance</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="kegiatan_setelah_resign">{{ old('kegiatan_setelah_resign') }}</textarea>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-12">
                                        <a href="{{ route('karyawan.exit-interview.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                        <a href="#clearance" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false" class="btn btn-info btn-sm next_exit_clearance">NEXT <i class="fa fa-arrow-right"></i> </a>
                                    </div>
                                </div>
                            </div>
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
@section('footer-script')
<link href="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<!-- Clock Plugin JavaScript -->
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script type="text/javascript">

    $("input[name='exit_interview_reason']").click(function(){

        if($(this).val() == 1)
        {
            $('.perusahaan_lain').show("slow");
        }
        else if($(this).val() == 'other')
        {
            $("textarea[name='other_reason']").show();
        }
        else
        {
            $('.perusahaan_lain').hide("slow");
            $("textarea[name='other_reason']").hide();
        }
    });
</script>
<script type="text/javascript">

    var list_atasan = [];

    @foreach(get_atasan_langsung() as $item)
        list_atasan.push({id : {{ $item->id }}, value : '{{ $item->nik .' - '. $item->name.' - '. $item->job_rule }}',  });
    @endforeach
    
    $(".autcomplete-atasan" ).autocomplete({
        source: list_atasan,
        minLength:0,
        select: function( event, ui ) {
            $( "input[name='atasan_user_id']" ).val(ui.item.id);
            
            var id = ui.item.id;

            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.get-karyawan-by-id') }}',
                data: {'id' : id, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType: 'json',
                success: function (data) {

                    $('.jabatan_atasan').val(data.data.organisasi_job_role);
                    $('.department_atasan').val(data.data.department_name);
                    $('.no_handphone_atasan').val(data.data.telepon);
                    $('.email_atasan').val(data.data.email);
                }
            });
        }
    }).on('focus', function () {
            $(this).autocomplete("search", "");
    });

    $('#submit_form').click(function(){

        if($("input[name='atasan_user_id']").val() == "")
        {   
            bootbox.alert('Atasan Lansung ');
            return false;
        }

        bootbox.confirm("Apakah anda ingin memproses Form ini ?", function(result){
            if(result)
            {
                $("#exit_interview_form").submit()
            }
        });

    });

    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
    });

    $('.next_exit_clearance').click(function(){

        $("a[href='#clearance']").parent().addClass('active');        

        $("a[href='#interview']").parent().removeClass('active');
    });

</script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
