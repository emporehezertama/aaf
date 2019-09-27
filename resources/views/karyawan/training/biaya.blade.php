@extends('layouts.karyawan')

@section('title', 'Kegiatan Perjalanan Dinas - PT. Arthaasia Finance')

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
                <h4 class="page-title"></h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Kegiatan Perjalanan Dinas</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" id="form-actual-bill" enctype="multipart/form-data" action="{{ route('karyawan.training.submit-biaya') }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Form Actual Bill</h3>
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

                        <?php 
                        
                        $readonly = ''; 
                        if($data->status_actual_bill >= 2)
                        {
                            $readonly = ' readonly="true"'; 
                        }
                        ?>
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th style="background: #eee;">1. Transportasi</th>
                                    <th style="background: #eee;">Nominal</th>
                                    <th style="background: #eee;">Nominal Disetujui</th>
                                    <th style="background: #eee;">Bukti Transaki</th>
                                    <th style="background: #eee;">Catatan</th>
                                </tr>
                                <tr>
                                    <td>Ticket (KA/Pesawat/Kapal,dll)</td>
                                    <td><input placeholder="Rp" type="number" class="form-control calculate_1" {{$readonly}}  name="transportasi_ticket" value="{{ $data->transportasi_ticket }}" ></td>
                                    <td><input placeholder="Rp"  type="number" class="form-control" readonly="true"  value="{{ $data->transportasi_ticket_disetujui }}" ></td>
                                    <td>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="transportasi_ticket_file" />
                                        </div>
                                        @if(!empty($data->transportasi_ticket_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->transportasi_ticket_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" readonly="true" value="{{ $data->transportasi_ticket_catatan }}"></td>
                                </tr>
                                <tr>
                                    <td>Taxi</td>
                                    <td><input placeholder="Rp" type="number" {{$readonly}}  value="{{ $data->transportasi_taxi_disetujui }}" class="form-control calculate_1" name="transportasi_taxi" value="{{ $data->transportasi_taxi }}" ></td>
                                    <td><input placeholder="Rp" type="number" class="form-control" readonly="true" value="{{ $data->transportasi_taxi_disetujui }}"></td>
                                    <td>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="transportasi_taxi_file" />
                                        </div>
                                        @if(!empty($data->transportasi_taxi_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->transportasi_taxi_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" readonly="true" value="{{ $data->transportasi_taxi_catatan }}"></td>
                                </tr>
                                <tr>
                                    <td>Gasoline</td>
                                    <td><input placeholder="Rp"  type="number" {{$readonly}} class="form-control calculate_1" name="transportasi_gasoline" value="{{ $data->transportasi_gasoline }}" ></td>
                                    <td><input placeholder="Rp" type="number" class="form-control" readonly="true" value="{{ $data->transportasi_gasoline_disetujui }}"></td>
                                    <td>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="transportasi_gasoline_file" />
                                        </div>
                                        @if(!empty($data->transportasi_gasoline_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->transportasi_gasoline_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" readonly="true" value="{{ $data->transportasi_gasoline_catatan }}"></td>
                                </tr>
                                <tr>
                                    <td>Tol</td>
                                    <td><input placeholder="Rp" type="number" {{$readonly}} class="form-control calculate_1" name="transportasi_tol" value="{{ $data->transportasi_tol }}" ></td>
                                    <td><input placeholder="Rp" type="number" class="form-control" value="{{ $data->transportasi_tol_disetujui }}" readonly="true"></td>
                                    <td>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="transportasi_tol_file" />
                                        </div>
                                        @if(!empty($data->transportasi_tol_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->transportasi_tol_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" readonly="true" value="{{ $data->transportasi_tol_catatan }}"></td>
                                </tr>
                                <tr>
                                    <td>Parkir</td>
                                    <td><input placeholder="Rp" type="number" {{$readonly}} class="form-control calculate_1" name="transportasi_parkir" value="{{ $data->transportasi_parkir }}" ></td>
                                    <td><input placeholder="Rp" type="number" class="form-control" readonly="true" value="{{ $data->transportasi_parkir_disetujui }}"></td>
                                    <td>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="transportasi_parkir_file" />
                                        </div>
                                        @if(!empty($data->transportasi_parkir_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->transportasi_parkir_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" readonly="true" value="{{ $data->transportasi_parkir_catatan }}"></td>
                                </tr>
                                <tr>
                                    <th style="text-align: right;">Sub Total</th>
                                    <th class="total_transport" colspan="4">{{ number_format($data->sub_total_1) }}</th>
                                </tr>
                            </tbody>
                    </table>
                    <table class="table table-bordered">
                        <tbody>
                             <tr>
                                    <th style="background: #eee;">2. Hotel dan Uang Makan</th>
                                    <th style="background: #eee;">Plafond</th>
                                    <th style="background: #eee;">Nominal / Hari</th>
                                    <th style="background: #eee;">QTY (Hari)</th>
                                    <th style="background: #eee;">Total Pengajuan</th>
                                    <th style="background: #eee;">Nominal Disetujui</th>
                                    <th style="background: #eee;">Bukti Transaki</th>
                                    <th style="background: #eee;">Catatan</th>
                                </tr>
                                @if($plafond_dinas)
                                <tr>
                                    <td>Hotel</td>
                                    <td>
                                        Rp. {{ number_format($plafond_dinas->hotel) }}
                                        <input type="hidden" name="uang_hotel_plafond" value="{{ $plafond_dinas->hotel }}">
                                    </td>
                                    <td><input type="number" class="form-control calculate_2" {{$readonly}} name="uang_hotel_nominal" placeholder="Rp. " value="{{ $data->uang_hotel_nominal }}" ></td>
                                    <td><input type="number" class="form-control" {{$readonly}} placeholder="QTY" name="uang_hotel_qty"  value="{{ $data->uang_hotel_qty }}" ></td>
                                    <td class="total_pengajuan_hotel">
                                        @if(empty($data->uang_hotel_qty))
                                            {{ number_format($data->uang_hotel_nominal) }}
                                        @else
                                            {{ number_format($data->uang_hotel_nominal * $data->uang_hotel_qty) }}
                                        @endif
                                    </td>
                                    <td><input type="text" readonly="true"  class="form-control" value="{{ $data->uang_hotel_nominal_disetujui }}" ></td>
                                    <td>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="uang_hotel_file" />
                                        </div>
                                        @if(!empty($data->uang_hotel_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->uang_hotel_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" {{$readonly}} placeholder="Catatan" value="{{ $data->uang_hotel_catatan }}" readonly="true"></td>
                                </tr>
                                <tr>
                                    <td>Tunjangan Makan</td>
                                    <td>Rp. {{ number_format($plafond_dinas->tunjangan_makanan) }}</td>
                                    <td>
                                        <input type="hidden" class="form-control" name="uang_makan_plafond" value="{{ $plafond_dinas->tunjangan_makan }}" >
                                        <input type="number" class="form-control" {{$readonly}} name="uang_makan_nominal" value="{{ $data->uang_makan_nominal }}" placeholder="Rp. " >
                                    </td>
                                    <td><input type="number" class="form-control" {{$readonly}} value="{{ $data->uang_makan_qty }}" name="uang_makan_qty" placeholder="QTY" ></td>
                                    <td class="total_pengajuan_makan">
                                        @if(empty($data->uang_makan_qty))
                                            {{ number_format($data->uang_makan_nominal) }}
                                        @else
                                            {{ number_format($data->uang_makan_nominal * $data->uang_makan_qty) }}
                                        @endif
                                    </td>
                                    <td><input type="text" readonly="true" class="form-control" value="{{ $data->uang_makan_nominal_disetujui }}" ></td>
                                    <td>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="uang_makan_file" />
                                        </div>
                                        @if(!empty($data->uang_makan_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->uang_makan_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" placeholder="Catatan" readonly="true" value="{{ $data->uang_makan_catatan }}"></td>
                                </tr>
                                <tr>
                                    <td>Tunjangan Harian</td>
                                    <td>Rp. {{ number_format($plafond_dinas->hotel) }}</td>
                                    <td>
                                        <input type="hidden" class="form-control" name="uang_harian_plafond" value="{{ $plafond_dinas->hotel }}" >
                                        <input type="number" class="form-control calculate_2" {{$readonly}} value="{{ $data->uang_harian_nominal }}" name="uang_harian_nominal" placeholder="Rp. " >
                                    </td>
                                    <td><input type="number" class="form-control" {{$readonly}} name="uang_harian_qty" value="{{ $data->uang_harian_qty }}" placeholder="QTY" ></td>
                                    <td class="total_pengajuan_harian">
                                        @if(empty($data->uang_harian_qty))
                                            {{ number_format($data->uang_harian_nominal) }}
                                        @else 
                                            {{ number_format($data->uang_harian_nominal * $data->uang_harian_qty ) }}
                                        @endif
                                    </td>
                                    <td><input type="text" readonly="true" class="form-control"  value="{{ $data->uang_harian_nominal_disetujui }}" ></td>
                                    <td>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="uang_harian_file" />
                                        </div>
                                        @if(!empty($data->uang_harian_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->uang_harian_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" placeholder="Catatan" readonly="true" value="{{ $data->uang_harian_catatan }}"></td>
                                </tr>
                                @endif
                                <tr>
                                    <th colspan="2" style="text-align: right;">Sub Total</th>
                                    <th colspan="6" class="sub_total_pengajuan">{{ number_format($data->sub_total_2) }}</th>
                                </tr>
                            </tbody>
                        </table>
                        <table class="table table-bordered">
                                <tr>
                                    <th colspan="2" style="background: #eee;">3. Lain-lain </th>
                                    <th style="background: #eee;">Nominal </th>
                                    <th style="background: #eee;">Nominal Disetujui </th>
                                    <th style="background: #eee;">Bukti Transaksi </th>
                                    <th style="background: #eee;">Catatan </th>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="text" name="uang_biaya_lainnya1" {{$readonly}}  class="form-control" placeholder="Biaya Lainnya" value="{{ $data->uang_biaya_lainnya1 }}" />
                                    </td>
                                    <td>
                                        <input type="text" name="uang_biaya_lainnya1_nominal" {{$readonly}}  value="{{ $data->uang_biaya_lainnya1_nominal }}" class="form-control" placeholder="Rp. " />
                                    </td>
                                    <td>
                                        <input type="text" readonly="true" class="form-control" {{$readonly}} value="{{ $data->uang_biaya_lainnya1_nominal_disetujui }}"     placeholder="Rp. " />
                                    </td>
                                    <td>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="uang_biaya_lainnya1_file" />
                                        </div>
                                        @if(!empty($data->uang_biaya_lainnya1_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->uang_biaya_lainnya1_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>`
                                    <td>
                                        <input type="text" readonly="true" class="form-control" placeholder="Catatan" value="{{ $data->uang_biaya_lainnya1_catatan }}" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="text" name="uang_biaya_lainnya2" {{$readonly}}  value="{{ $data->uang_biaya_lainnya2 }}" class="form-control" placeholder="Biaya Lainnya" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" {{$readonly}}  value="{{ $data->uang_biaya_lainnya2_nominal }}" name="uang_biaya_lainnya2_nominal" placeholder="Rp. " />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" readonly="true" placeholder="Rp." value="{{ $data->uang_biaya_lainnya2_nominal_disetujui }}"  />
                                    </td>
                                    <td>
                                        <div class="col-md-6">
                                            <input type="file" class="form-control" name="uang_biaya_lainnya2_file" />
                                        </div>
                                        @if(!empty($data->uang_biaya_lainnya2_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->uang_biaya_lainnya2_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" readonly="true" class="form-control" placeholder="Catatan" class="form-control" placeholder="Catatan" />
                                    </td>
                                </tr>
                                <tr>
                                    <th>Sub Total</th>
                                    <th class="sub_total_nominal_lainnya">{{ number_format($data->sub_total_3) }}</th>
                                    <th style="text-align: right;">Total Disetujui</th>
                                    <th colspan="3" class="total_lain_lain_disetujui">{{ number_format($data->sub_total_3_disetujui) }}</th>
                                </tr>
                                <tr>
                                    <th>Total Actual Bill</th>
                                    <th class="total_actual_bill">
                                        {{ number_format($data->sub_total_1 + $data->sub_total_2 + $data->sub_total_3) }}
                                    </th>
                                    <th>Total Actual Bill Disetujui </th>
                                    <th colspan="3" class="total_actual_bill_disetujui">
                                         {{ number_format($data->sub_total_1_disetujui + $data->sub_total_2_disetujui + $data->sub_total_3_disetujui) }}
                                    </th>
                                </tr>
                                <tr>
                                    <th>Uang Muka</th>
                                    <th colspan="5">{{ number_format($data->pengambilan_uang_muka) }}</th>
                                </tr>
                                <tr>
                                    <th>Total Reimbursement
                                    
                                    </th>
                                    <th class="total_reimbursement">
                                        
                                        {{ number_format($data->sub_total_1 + $data->sub_total_2 + $data->sub_total_3 - $data->pengambilan_uang_muka) }}
                                    </th>
                                    <th> 
                                        <?php
                                            if($data->sub_total_1_disetujui + $data->sub_total_2_disetujui + $data->sub_total_3_disetujui - $data->pengambilan_uang_muka < 0){
                                                echo "Total Pengembalian";
                                            }else{
                                                echo "Total Reimbursement Disetujui";
                                            }
                                        ?>
                                    </th>
                                    <th colspan="3" class="total_reimbursement_disetujui">
                                        {{ number_format($data->sub_total_1_disetujui + $data->sub_total_2_disetujui + $data->sub_total_3_disetujui - $data->pengambilan_uang_muka) }}
                                    </th>
                                </tr>
                                @if($data->status_actual_bill ==1 or $data->status_actual_bill =="")
                                <tr>
                                    <th>Note</th>
                                    <td colspan="5"><input type="text" class="form-control" {{ $readonly }} placeholder="Note here.." name="noted_bill" value="{{ $data->noted_bill }}"></td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                        
                        <input type="hidden" name="id" value="{{ $data->id }}" />
                        <input type="hidden" name="status_actual_bill" value="1">
                        <input type="hidden" name="sub_total_1" value="{{ $data->sub_total_1 }}" />
                        <input type="hidden" name="sub_total_2" value="{{ $data->sub_total_2 }}" />
                        <input type="hidden" name="sub_total_3" value="{{ $data->sub_total_3 }}" />

                        <div class="col-md-12" style="padding-left: 0;">
                            <a href="{{ route('karyawan.training.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                            @if($data->status_actual_bill ==1 or $data->status_actual_bill =="")
                            <button type="submit" class="btn btn-sm btn-warning waves-effect waves-light m-r-10" id="save-as-draft-form"><i class="fa fa-save"></i> Save as Draft</button>

                            <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="submit-form"><i class="fa fa-save"></i> Submit Actual Bill</a>
                            @endif
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
<style type="text/css">
    .custome_table tr th {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
</style>
@section('footer-script')
<script type="text/javascript">

    $(".calculate_1").on('input', function(){

        var val = 0;

        $(".calculate_1").each(function(){

            if($(this).val() != "")
            {
                val += parseInt($(this).val());                
            }
        });

        $('.total_transport').html(numberWithComma(val));
        $("input[name='sub_total_1']").val(val);

        calculate_all();
    });


    $("input[name='uang_hotel_nominal'], input[name='uang_hotel_qty'], input[name='uang_makan_nominal'], input[name='uang_makan_qty'], input[name='uang_harian_nominal'], input[name='uang_harian_qty'], input[name='uang_pesawat_qty'], input[name='uang_biaya_lainnya1_nominal'], input[name='uang_biaya_lainnya2_nominal']").on('input', function(){
        calculate_all();
    });

    function calculate_all()
    {
        var val = 0;

        var hotel       = $("input[name='uang_hotel_nominal']").val();
        var hotel_qty   = $("input[name='uang_hotel_qty']").val();

        var makan       = $("input[name='uang_makan_nominal']").val();
        var makan_qty   = $("input[name='uang_makan_qty']").val();

        var harian       = $("input[name='uang_harian_nominal']").val();
        var harian_qty   = $("input[name='uang_harian_qty']").val();

        var nominal_lainnya1    = $("input[name='uang_biaya_lainnya1_nominal']").val();
        var nominal_lainnya2    = $("input[name='uang_biaya_lainnya2_nominal']").val();


        var nominal_lainnya = 0;

        if(nominal_lainnya1 != "")
        {
            nominal_lainnya += parseInt(nominal_lainnya1);
        }

        if(nominal_lainnya2 != "")
        {
            nominal_lainnya += parseInt(nominal_lainnya2);
        }

        $('.sub_total_nominal_lainnya').html(numberWithComma(nominal_lainnya));
        $("input[name='sub_total_3']").val(nominal_lainnya);



        if(hotel != "")
        {   
            if(hotel_qty != "")
            {
                hotel = parseInt(hotel) * parseInt(hotel_qty);
            }

            val += parseInt(hotel);

            $('.total_pengajuan_hotel').html(numberWithComma(hotel));
        }

        if(makan != "")
        {   
            if(makan_qty != "")
            {
                makan = parseInt(makan) * parseInt(makan_qty);
            }

            val += parseInt(makan);

            $('.total_pengajuan_makan').html(numberWithComma(makan));
        }

        if(harian != "")
        {   
            if(harian_qty != "")
            {
                harian = parseInt(harian) * parseInt(harian_qty);
            }

            val += parseInt(harian);

            $('.total_pengajuan_harian').html(numberWithComma(harian));
        }

        $('.sub_total_pengajuan').html(numberWithComma(val));
        $("input[name='sub_total_2']").val(val);




        var total_reimbursement = 0;
        var total_actual_bill = 0;

        if($("input[name='sub_total_1']").val() != "")
        {
            total_reimbursement     += parseInt($("input[name='sub_total_1']").val());   
            total_actual_bill       += parseInt($("input[name='sub_total_1']").val());
        }

        if( $("input[name='sub_total_2']").val() != "")
        {
            total_reimbursement     += parseInt($("input[name='sub_total_2']").val());
            total_actual_bill       += parseInt($("input[name='sub_total_2']").val());
        }

        if( $("input[name='sub_total_3']").val() != "")
        {
            total_reimbursement     += parseInt($("input[name='sub_total_3']").val());
            total_actual_bill       += parseInt($("input[name='sub_total_3']").val());
        }

        {{ !empty($data->pengambilan_uang_muka) ? ' total_reimbursement -='. $data->pengambilan_uang_muka .';' : '' }};


        $('.total_actual_bill').html(numberWithComma(total_actual_bill));
        $('.total_reimbursement').html(numberWithComma(total_reimbursement));
    }

    function show_img(img)
    {
        bootbox.alert(
        {
            message : '<img src="'+ img +'" style="width: 100%;" />',
            size: 'large' 
        });
    }

    $("#submit-form").click(function(){

        bootbox.confirm('Submit Actual Bill ?', function(res){
            if(res)
            {
                $("input[name='status_actual_bill']").val(2);
                $("#form-actual-bill").submit();
            }
        });     
    });
</script>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
@endsection
