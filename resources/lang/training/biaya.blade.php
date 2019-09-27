@extends('layouts.administrator')

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
            <form class="form-horizontal" id="form-actual-bill" enctype="multipart/form-data" action="{{ route('administrator.training.proses-biaya') }}" method="POST">
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

                       $total_transportasi_nominal = $data->transportasi_ticket + $data->transportasi_taxi + $data->transportasi_gasoline + $data->transportasi_tol + $data->transportasi_parkir;

                        $total_hotel_nominal = $data->uang_hotel_nominal + $data->uang_makan_nominal + $data->uang_harian_nominal + $data->uang_pesawat_nominal;
                        $total_hotel_qty  = $data->uang_hotel_qty + $data->uang_makan_qty + $data->uang_harian_qty + $data->uang_pesawat_qty;
                        
                        $total_lainnya = $data->uang_biaya_lainnya1 + $data->uang_biaya_lainnya2; 
                        $total_hotel_nominal_dan_qty = ($data->uang_hotel_nominal * $data->uang_hotel_qty) + ($data->uang_makan_nominal * $data->uang_makan_qty) + ($data->uang_harian_nominal * $data->uang_harian_qty) + ($data->uang_pesawat_nominal * $data->uang_pesawat_qty) ;;
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
                                    <td><input placeholder="Rp" type="number" class="form-control" readonly="true" name="transportasi_ticket" value="{{ $data->transportasi_ticket }}" ></td>
                                    <td><input placeholder="Rp"  type="number" class="form-control" name="transportasi_ticket_disetujui" value="{{ $data->transportasi_ticket_disetujui }}"></td>
                                    <td>
                                        @if(!empty($data->transportasi_ticket_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->transportasi_ticket_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" name="transportasi_ticket_catatan" value="{{ $data->transportasi_ticket_catatan }}"></td>
                                </tr>
                                <tr>
                                    <td>Taxi</td>
                                    <td><input placeholder="Rp" type="number" class="form-control" readonly="true" name="transportasi_taxi" value="{{ $data->transportasi_taxi }}" ></td>
                                    <td><input placeholder="Rp" type="number" class="form-control" name="transportasi_taxi_disetujui" value="{{ $data->transportasi_taxi_disetujui }}"></td>
                                    <td>
                                        @if(!empty($data->transportasi_taxi_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->transportasi_taxi_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" name="transportasi_taxi_catatan" value="{{ $data->transportasi_taxi_catatan }}" ></td>
                                </tr>
                                <tr>
                                    <td>Gasoline</td>
                                    <td><input placeholder="Rp"  type="number" class="form-control" name="transportasi_gasoline" value="{{ $data->transportasi_gasoline }}" readonly="true" ></td>
                                    <td><input placeholder="Rp" type="number" class="form-control" name="transportasi_gasoline_disetujui"  value="{{ $data->transportasi_gasoline_disetujui }}"></td>
                                    <td>
                                        @if(!empty($data->transportasi_gasoline_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->transportasi_gasoline_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" name="transportasi_gasoline_catatan" class="form-control" /></td>
                                </tr>
                                <tr>
                                    <td>Tol</td>
                                    <td><input placeholder="Rp" type="number" class="form-control" name="transportasi_tol" value="{{ $data->transportasi_tol }}" readonly="true" ></td>
                                    <td><input placeholder="Rp" type="number" class="form-control" name="transportasi_tol_disetujui" value="{{ $data->transportasi_tol_disetujui }}" ></td>
                                    <td>
                                        @if(!empty($data->transportasi_tol_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->transportasi_tol_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" name="transportasi_tol_catatan" value="{{ $data->tranportasi_tol_catatan }}"></td>
                                </tr>
                                <tr>
                                    <td>Parkir</td>
                                    <td><input placeholder="Rp" type="number" class="form-control" name="transportasi_parkir" value="{{ $data->transportasi_parkir }}" readonly="" ></td>
                                    <td><input placeholder="Rp" type="number" class="form-control" name="transportasi_parkir_disetujui" value="{{ $data->transportasi_parkir_disetujui }}"></td>
                                    <td>
                                        @if(!empty($data->transportasi_parkir_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->transportasi_parkir_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" name="transportasi_parkir_catatan" value="{{ $data->transportasi_parkir_catatan }}"></td>
                                </tr>
                                <tr>
                                    <th>Sub Total</th>
                                    <th class="total_transport">Rp. {{ $total_transportasi_nominal }}</th>
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
                                @foreach(plafond_perjalanan_dinas(\Auth::user()->organisasiposition->name) as $item)
                                <tr>
                                    <td>Hotel</td>
                                    <td>
                                        Rp. {{ number_format($item->hotel) }}
                                        <input type="hidden" name="uang_hotel_plafond" value="{{ $item->hotel }}">
                                    </td>
                                    <td><input type="number" class="form-control" name="uang_hotel_nominal" placeholder="Rp. " value="{{ $data->uang_hotel_nominal }}" readonly="true" ></td>
                                    <td><input type="number" class="form-control" placeholder="QTY" name="uang_hotel_qty"  value="{{ $data->uang_hotel_qty }}" readonly="true"></td>
                                    <td>{{ $data->uang_hotel_nominal * $data->uang_hotel_qty }}</td>
                                    <td><input type="text" name="uang_hotel_nominal_disetujui" value="{{ $data->uang_hotel_nominal_disetujui }}" class="form-control"></td>
                                    <td>
                                        @if(!empty($data->uang_hotel_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->uang_hotel_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" placeholder="Catatan" name="uang_hotel_catatan" value="{{ $data->uang_hotel_catatan }}"></td>
                                </tr>

                                <tr>
                                    <td>Tunjangan Makan</td>
                                    <td>Rp. {{ number_format($item->tunjangan_makanan) }}</td>
                                    <td>
                                        <input type="hidden" class="form-control" name="uang_makan_plafond" value="{{ $item->tunjangan_makan }}" >
                                        <input type="number" class="form-control" name="uang_makan_nominal" value="{{ $data->uang_makan_nominal }}" placeholder="Rp. " readonly="" >
                                    </td>
                                    <td><input type="number" class="form-control" value="{{ $data->uang_makan_qty }}" name="uang_makan_qty" readonly="true" placeholder="QTY" ></td>
                                    <td>{{ $data->uang_makan_nominal * $data->uang_makan_qty }}</td>
                                    <td><input type="text" name="uang_makan_nominal_disetujui" value="{{ $data->uang_makan_nominal_disetujui }}" class="form-control"></td>
                                    <td>
                                        @if(!empty($data->uang_makan_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->uang_makan_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" placeholder="Catatan" name="uang_makan_catatan" value="{{ $data->uang_makan_catatan }}"></td>
                                </tr>
                                <tr>
                                    <td>Tunjangan Harian</td>
                                    <td>Rp. {{ number_format($item->hotel) }}</td>
                                    <td>
                                        <input type="hidden" class="form-control" name="uang_harian_plafond" value="{{ $item->hotel }}" >
                                        <input type="number" class="form-control" value="{{ $data->uang_harian_nominal }}" name="uang_harian_nominal" placeholder="Rp. " readonly="true" >
                                    </td>
                                    <td><input type="number" class="form-control" name="uang_harian_qty" value="{{ $data->uang_harian_qty }}" placeholder="QTY" readonly="true" ></td>
                                    <td>{{ $data->uang_harian_nominal * $data->uang_harian_qty }}</td>
                                    <td><input type="text" class="form-control" name="uang_harian_nominal_disetujui"></td>
                                    <td>
                                        @if(!empty($data->uang_harian_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->uang_harian_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" placeholder="Catatan" name="uang_harian_catatan" value="{{ $data->uang_harian_catatan }}"></td>
                                </tr>
                                <tr>
                                    <td>Pesawat</td>
                                    <td>{{ $item->pesawat }}</td>
                                    <td>
                                        <input type="hidden" class="form-control" name="uang_pesawat_plafond" value="{{ $item->pesawat }}" >
                                        <input type="number" class="form-control" value="{{ $data->uang_pesawat_nominal }}"å name="uang_pesawat_nominal" placeholder="Rp. " readonly="true" >
                                    </td>
                                    <td><input type="number" class="form-control" name="uang_pesawat_qty" value="{{ $data->uang_pesawat_qty }}" placeholder="QTY" readonly="true" ></td>
                                    <td>{{ $data->uang_pesawat_nominal * $data->uang_pesawat_qty }}</td>
                                    <td><input type="text" name="uang_pesawat_nominal_disetujui" class="form-control"></td>
                                    <td>
                                        @if(!empty($data->uang_pesawat_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->uang_pesawat_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td><input type="text" class="form-control" placeholder="Catatan" name="uang_pesawat_catatan" value="{{ $data->uang_pesawat_catatan }}"></td>
                                </tr>
                                @endforeach
                                <tr>
                                    <th>Sub Total</th>
                                    <th></th>
                                    <th>{{ $total_hotel_nominal }}</th>
                                    <th>{{ $total_hotel_qty }}</th>
                                    <th>{{ $total_hotel_nominal_dan_qty }}</th>
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
                                        <input type="text" name="uang_biaya_lainnya1" class="form-control" placeholder="Biaya Lainnya" value="{{ $data->uang_biaya_lainnya1 }}" readonly="true" />
                                    </td>
                                    <td>
                                        <input type="text" name="uang_biaya_lainnya1_nominal" value="{{ $data->uang_biaya_lainnya1_nominal }}" class="form-control" placeholder="Rp. " readonly="true" />
                                    </td>
                                    <td>
                                        <input type="text" name="uang_biaya_lainnya1_nominal_disetujui" class="form-control" placeholder="Rp. " value="{{ $data->uang_biaya_lainnya1_nominal_disetujui }}" />
                                    </td>
                                    <td>
                                        @if(!empty($data->uang_biaya_lainnya1_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->uang_biaya_lainnya1_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="uang_biaya_lainnya1_catatan" value="{{ $data->uang_biaya_lainnya1_catatan }}" placeholder="Catatan" />
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2">
                                        <input type="text" name="uang_biaya_lainnya2" value="{{ $data->uang_biaya_lainnya2 }}" class="form-control" placeholder="Biaya Lainnya" readonly="true" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" value="{{ $data->uang_biaya_lainnya2_nominal }}" name="uang_biaya_lainnya2_nominal" placeholder="Rp. " readonly="true" />
                                    </td>
                                    <td>
                                        <input type="text" class="form-control" name="uang_biaya_lainnya2_nominal_disetujui" value="{{ $data->uang_biaya_lainnya2_nominal_disetujui }}" placeholder="Rp." />
                                    </td>
                                    <td>
                                        @if(!empty($data->uang_biaya_lainnya2_file))
                                        <label onclick="show_img('{{ asset('storage/file-training/'. $data->uang_biaya_lainnya2_file)  }}')" class="btn btn-info btn-xs"><i class="fa fa-image"></i> view</label>
                                        @endif
                                    </td>
                                    <td>
                                        <input type="text" name="uang_biaya_lainnya2_catatan" value="{{ $data->uang_biaya_lainnya2_catatan }}" class="form-control" placeholder="Catatan" />
                                    </td>
                                </tr>
                                <tr>
                                    <th colspan="6">Sub Total</th>
                                </tr>

                                <tr>
                                    <th colspan="6">Total Actual Bill</th>
                                </t>
                                <tr>
                                    <th colspan="6">Uang Muka</th>
                                </tr>
                                <tr>
                                    <th colspan="6">Total Reimbursement</th>
                                </tr>
                            </tbody>
                        </table>
                        
                        <input type="hidden" name="id" value="{{ $data->id }}" />
                        <input type="hidden" name="status_actual_bill" value="0">

                        <div class="col-md-12" style="padding-left: 0;">
                            <a href="{{ route('administrator.training.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>

                            <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_approved"><i class="fa fa-check"></i> Approved Perjalanan Dinas</a>
                            <a class="btn btn-sm btn-danger waves-effect waves-light m-r-10" id="btn_tolak"><i class="fa fa-close"></i> Tolak Perjalanan Dinas</a>

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
    function show_img(img)
    {
        bootbox.alert(
        {
            message : '<img src="'+ img +'" style="width: 100%;" />',
            size: 'large' 
        });
    }

    $("#btn_approved").click(function(){
        bootbox.confirm('Approve Actual Bill Perjalanan Dinas Karyawan ?', function(result){

            $("input[name='status_actual_bill']").val(1);
            if(result)
            {
                $('#form-actual-bill').submit();
            }

        });
    });

    $("#btn_tolak").click(function(){
        bootbox.confirm('Tolak Actual Bill Perjalanan Dinas Karyawan ?', function(result){

            if(result)
            {
                $('#form-actual-bill').submit();
            }

        });
    });
</script>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
@endsection
