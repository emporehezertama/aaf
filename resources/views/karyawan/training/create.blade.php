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
            <form class="form-horizontal" autocomplete="off" onsubmit="return confirm('Submit Pengajuan Perjalanan Dinas / Training ?');" enctype="multipart/form-data" action="{{ route('karyawan.training.store') }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Form Perjalanan Dinas</h3>
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
                        
                        <ul class="nav customtab nav-tabs" role="tablist">
                            <li role="presentation" class="active"><a href="#kegiatan" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> Kegiatan</span></a></li>
                            <li role="presentation" class=""><a href="#pesawat" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs">Perjalanan Menggunakan Pesawat</span></a></li>
                        </ul>
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade" id="biaya">
                                <h3>Actual Bill</h3>
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
                                            <td><input placeholder="Rp" type="number" class="form-control" name="transportasi_ticket" ></td>
                                            <td><input placeholder="Rp"  type="number" class="form-control" readonly="true"></td>
                                            <td><input type="file" /></td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Taxi</td>
                                            <td><input placeholder="Rp" type="number" class="form-control" name="transportasi_taxi" ></td>
                                            <td><input placeholder="Rp" type="number" class="form-control" readonly="true"></td>
                                            <td><input type="file" /></td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Gasoline</td>
                                            <td><input placeholder="Rp"  type="number" class="form-control" name="transportasi_gasoline" ></td>
                                            <td><input placeholder="Rp" type="number" class="form-control" readonly="true"></td>
                                            <td><input type="file" /></td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true">
                                            </td>

                                        </tr>
                                        <tr>
                                            <td>Tol</td>
                                            <td><input placeholder="Rp" type="number" class="form-control" name="transportasi_tol" ></td>
                                            <td><input placeholder="Rp" type="number" class="form-control" readonly="true"></td>
                                            <td><input type="file" /></td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Parkir</td>
                                            <td><input placeholder="Rp" type="number" class="form-control" name="transportasi_parkir" ></td>
                                            <td><input placeholder="Rp" type="number" class="form-control" readonly="true"></td>
                                            <td><input type="file" /></td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true">
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Sub Total</th>
                                            <th class="total_transport"></th>
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
                                        <?php 
                                            if(!isset(\Auth::user()->organisasiposition->name))
                                                $item = plafond_perjalanan_dinas('Staff');
                                            else
                                                $item = plafond_perjalanan_dinas(\Auth::user()->organisasiposition->name);
                                        ?>
                                        @if($item)
                                        <tr>
                                            <td>Hotel</td>
                                            <td>Rp. {{ number_format($item->hotel) }}</td>
                                            <td><input type="number" class="form-control" placeholder="Rp. " ></td>
                                            <td><input type="number" class="form-control" placeholder="QTY" ></td>
                                            <td></td>
                                            <td><input type="text" readonly="true" class="form-control"></td>
                                            <td><input type="file" class="form-control"></td>
                                            <td><input type="text" class="form-control" placeholder="Catatan"></td>
                                        </tr>

                                        <tr>
                                            <td>Tunjangan Makan</td>
                                            <td>Rp. {{ number_format($item->tunjangan_makanan) }}</td>
                                            <td><input type="number" class="form-control" placeholder="Rp. " ></td>
                                            <td><input type="number" class="form-control" placeholder="QTY" ></td>
                                            <td></td>
                                            <td><input type="text" readonly="true" class="form-control"></td>
                                            <td><input type="file" class="form-control"></td>
                                            <td><input type="text" class="form-control" placeholder="Catatan"></td>
                                        </tr>
                                        <tr>
                                            <td>Tunjangan Harian</td>
                                            <td>Rp. {{ number_format($item->hotel) }}</td>
                                            <td><input type="number" class="form-control" placeholder="Rp. " ></td>
                                            <td><input type="number" class="form-control" placeholder="QTY" ></td>
                                            <td></td>
                                            <td><input type="text" readonly="true" class="form-control"></td>
                                            <td><input type="file" class="form-control"></td>
                                            <td><input type="text" class="form-control" placeholder="Catatan"></td>
                                        </tr>
                                        @endif
                                        <tr>
                                            <th>Sub Total</th>
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
                                                <input type="text" name="biaya_lainnya_label[]" class="form-control" placeholder="Biaya Lainnya" />
                                            </td>
                                            <td>
                                                <input type="text" name="biaya_lainnya_nominal[]" class="form-control" placeholder="Nominal" />
                                            </td>
                                            <td>
                                                <input type="text" name="biaya_lainnya_nominal[]" readonly="true" class="form-control" placeholder="Rp. " />
                                            </td>
                                            <td>
                                                <input type="file" placeholder="Rp." />
                                            </td>
                                            <td>
                                                <input type="text" readonly="true" name="biaya_lainnya_nominal[]" class="form-control" placeholder="Catatan" />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <input type="text" name="biaya_lainnya_label[]" class="form-control" placeholder="Biaya Lainnya" />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" placeholder="Rp. " />
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" readonly="true" placeholder="Rp." />
                                            </td>
                                            <td>
                                                <input type="file" class="form-control" />
                                            </td>
                                            <td>
                                                <input type="text" readonly="true" name="biaya_lainnya_nominal[]" class="form-control" placeholder="Catatan" />
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
                                            <th colspan="6">Total</th>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                            <div role="tabpanel" class="tab-pane fade in active" id="kegiatan">
                                <h4><b>Form Kegiatan</b></h4>
                                <hr />
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="col-md-6">Jenis Perjalanan Dinas</label>
                                        <label class="col-md-6 select-cabang" style="display: none;">Lokasi Cabang</label>
                                        <div class="clearfix"></div>
                                        <div class="col-md-6">
                                            <select name="jenis_training" required class="form-control">
                                                <option value="">Pilih Jenis Perjalanan Dinas</option>
                                                @foreach(jenis_perjalanan_dinas() as $item)
                                                <option>{{ $item }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 select-cabang" style="display: none;">
                                            <select class="form-control" name="cabang_id">
                                                <option value="">Pilih Lokasi Cabang </option>
                                                @foreach(get_cabang() as $item)
                                                <option value="{{ $item->id }}">{{ $item->name  }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-6 select-others" style="display: none;">
                                            <input type="text" class="form-control" name="others" placeholder="Others Perjalanan Dinas">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="col-md-12">Lokasi Kegiatan</label>
                                        <div class="col-md-12">
                                            <label style="font-weight: normal;margin-right: 10px;"><input type="radio" name="lokasi_kegiatan" value="Dalam Negeri"> Dalam Negeri</label>

                                            <label style="font-weight: normal;"><input type="radio" name="lokasi_kegiatan" value="Luar Negeri"> Luar Negeri</label>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Tempat Tujuan</label>
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" required name="tempat_tujuan" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Topik Kegiatan</label>
                                        <div class="col-md-12">
                                            <textarea class="form-control" required name="topik_kegiatan"></textarea>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Tanggal Kegiatan</label>
                                        <div class="col-md-6">
                                            <input type="text" name="tanggal_kegiatan_start" required class="form-control" id="from" placeholder="Dari Tanggal">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" name="tanggal_kegiatan_end" required class="form-control" id="to" placeholder="Sampai Tanggal">
                                        </div>
                                    </div>
                                    <hr />
                                    <h4><b>Pengajuan Uang Muka</b></h4>
                                    <div class="col-md-12" style="border: 1px solid #eee; padding: 15px">
                                        <div class="form-group">
                                            <label class="col-md-12">Pengambilan Uang Muka (Rp)</label>
                                            <div class="col-md-6">
                                                <input type="number" class="form-control" name="pengambilan_uang_muka" placeholder="Rp. " />
                                            </div>
                                        </div>
                                        <div class="form-group tanggal_uang_muka" style="display: none;">
                                            <label class="col-md-6">Tanggal Pengajuan</label>
                                            <label class="col-md-6">Tanggal Penyelesaian</label>
                                            <div class="col-md-6 ">
                                                <input type="text" class="form-control" readonly="true" value="{{ date('Y-m-d') }}" name="tanggal_pengajuan" />
                                            </div>
                                            <div class="col-md-6">
                                                <input type="text" class="form-control" required name="tanggal_penyelesain" value="{{ date('Y-m-d', strtotime('+10 day')) }}" readonly="true" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div><br />
                                    <hr />

                                    <h4><b>Approval</b></h4>
                                    <div class="col-md-12" style="border: 1px solid #eee; padding: 15px">
                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <input type="text" class="form-control autcomplete-atasan" required placeholder="Select Superior  / Atasan Langsung">
                                                <input type="hidden" name="approved_atasan_id" />
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
                                    <div class="form-group">
                                        <a href="{{ route('administrator.training.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                        <a href="#pesawat" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false" class="btn btn-info btn-sm next_">NEXT</a>
                                    </div>
                                </div>
                                <div class="clearfix"></div><br>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="pesawat">
                                <h4>Form Pemesanan</h4>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Pilihan Rute</label>
                                    <div class="col-md-6">
                                        <label style="font-weight: normal;"><input type="radio" name="pesawat_perjalanan" value="Sekali Jalan"> Sekali Jalan</label> &nbsp;&nbsp;
                                        <label style="font-weight: normal;"><input type="radio" name="pesawat_perjalanan" value="Pulang Pergi"> Pulang Pergi</label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Tanggal / Waktu</label>
                                    <div class="col-md-2">
                                        <input type="text" placeholder="Tanggal Berangkat" name="tanggal_berangkat" class="form-control" id="from_berangkat">
                                    </div>
                                    <div style="float: left; width: 5px;padding-top:10px;"> / </div>
                                    <div class="col-md-1">
                                        <input type="text" class="form-control time_picker" placeholder="Waktu" name="waktu_berangkat" />
                                    </div>
                                    <div style="float: left; width: 5px;padding-top:10px;"> - </div>

                                    <div class="col-md-2"><input type="text" placeholder="Tanggal Pulang" name="tanggal_pulang" readonly="true" class="form-control" id="to_berangkat">
                                    </div>
                                    <div style="float: left; width: 5px;padding-top:10px;"> / </div>
                                     <div class="col-md-1">
                                        <input type="text" class="form-control time_picker" placeholder="Waktu" readonly="true" name="waktu_pulang" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-3">Dari Bandara</label>
                                    <label class="col-md-3">Tujuan Bandara</label>
                                    <div class="clearfix"></div>
                                    <div class="col-md-3">
                                        <input type="text" name="pesawat_rute_dari" class="form-control" id="rute_dari" placeholder="Rute Dari">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="pesawat_rute_tujuan" class="form-control" id="rute_tujuan" placeholder="Rute Tujuan">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Informasi Penumpang</label>
                                    <div class="col-md-6">
                                        <table class="table table-bordered custome_table">
                                            <thead>
                                                <tr>
                                                    <th>NIK</th>
                                                    <th>NO KTP</th>
                                                    <th>NO Passport</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>NO Telepon</th>
                                                </tr>
                                            </thead>
                                            <tbody class="table-penumpang">
                                                <tr>
                                                    <td>{{ Auth::user()->name .' / '.Auth::user()->nik }}</td>
                                                    <td>{{ Auth::user()->ktp_number }}</td>
                                                    <td>{{ Auth::user()->passport_number }}</td>
                                                    <td>{{ Auth::user()->jenis_kelamin }}</td>
                                                    <td>
                                                        <input type="text" name="no_telpon" class="form-control" value="{{ Auth::user()->telepon }}" placeholder="No Telepon" required/>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-2">Kelas</label>
                                    <label class="col-md-10">Maskapai</label>
                                    <div class="col-md-2">
                                        <label style="font-weight: normal;"><input type="radio" name="pesawat_kelas" value="Ekonomi" /> Ekonomi </label> 
                                        <label style="font-weight: normal;"><input type="radio" name="pesawat_kelas" value="Bisnis" /> Bisnis </label> 
                                    </div>
                                    <div class="col-md-6"> 
                                        <input type="text" class="form-control" name="pesawat_maskapai" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Pergi Bersama</label>
                                    <div class="col-md-8"> 
                                        <input type="text" class="form-control" name="pergi_bersama" placeholder="Type here.." />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">Note</label>
                                    <div class="col-md-8"> 
                                        <input type="text" class="form-control" name="note" placeholder="Type here.." />
                                    </div>
                                </div>

                                <div class="col-md-12" style="padding-left: 0;">
                                    <a href="{{ route('karyawan.training.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                    <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10"><i class="fa fa-save"></i> Submit Kegiatan</button>
                                    <p>* Jika tidak menggunakan Pesawat silahkan langsung klik tombol submit </p>
                                    <br style="clear: both;" />
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
<style type="text/css">
    .custome_table tr th {
        padding-top: 5px !important;
        padding-bottom: 5px !important;
    }
</style>

<!-- sample modal content -->
<div id="modal_penumpang" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Tambah Data Penumpang</h4> </div>
                <div class="modal-body">
                   <div class="form-horizontal">
                       <div class="form-group">
                            <label class="col-md-3">Pilih Penumpang</label>
                            <div class="col-md-6">
                                <select class="form-control penumpang_id">
                                    <option value="">Pilih Penumpang </option>
                                    @foreach(get_karyawan() as $item)
                                    <option value="{{ $item->id }}" data-kelamin="{{ $item->jenis_kelamin }}">{{ $item->nik }} / {{ $item->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                       </div>
                   </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-sm" id="add_modal_penumpang">Proses Form Perjalanan Dinas</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('footer-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link href="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.css') }}" rel="stylesheet">
<!-- Clock Plugin JavaScript -->
<script src="{{ asset('admin-css/plugins/bower_components/clockpicker/dist/jquery-clockpicker.min.js') }}"></script>
<script type="text/javascript">
    
    $( "#from, #to" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function( selectedDate ) {
            if(this.id == 'from'){
              var dateMin = $('#from').datepicker("getDate");
              var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate()); // Min Date = Selected + 1d
              var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); // Max Date = Selected + 31d
              $('#to').datepicker("option","minDate",rMin);
              $('#to').datepicker("option","maxDate",rMax);                    
            }
        }
    });

    $( "#from_berangkat, #to_berangkat" ).datepicker({
        defaultDate: "+1w",
        changeMonth: true,
        numberOfMonths: 2,
        dateFormat: 'yy-mm-dd',
        onSelect: function( selectedDate ) {
            if(this.id == 'from_berangkat'){
              var dateMin = $('#from_berangkat').datepicker("getDate");
              var rMin = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate()); // Min Date = Selected + 1d
              var rMax = new Date(dateMin.getFullYear(), dateMin.getMonth(),dateMin.getDate() + 31); // Max Date = Selected + 31d
              $('#to_berangkat').datepicker("option","minDate",rMin);
              $('#to_berangkat').datepicker("option","maxDate",rMax);                    
            }
        }
    });

    $(".autocomplete-bersama" ).autocomplete({
        minLength:0,
        limit: 25,
        source: function( request, response ) {
            $.ajax({
              url: "{{ route('ajax.get-karyawan') }}",
              method : 'POST',
              data: {
                'name': request.term,'_token' : $("meta[name='csrf-token']").attr('content')
              },
              success: function( data ) {
                response( data );
              }
            });
        },
        select: function( event, ui ) {
            $( ".modal_finance_id" ).val(ui.item.id);
        }
    }).on('focus', function () {
            $(this).autocomplete("search", "");
    });

    $('.next_').click(function(){

        $("a[href='#pesawat']").parent().addClass('active');        

        $("a[href='#kegiatan']").parent().removeClass('active');
    });

    $("input[name='pengambilan_uang_muka']").on('input', function(){

        if($(this).val() != "")
        {
            $(".tanggal_uang_muka").show("slow");
        }
        else
        {
            $(".tanggal_uang_muka").hide("slow");
        }

    });



    var list_atasan = [];
    @foreach(get_atasan_langsung() as $item)
    list_atasan.push({id : {{ $item->id }}, value : '{{ $item->nik .' - '. $item->name.' - '. $item->job_rule }}',  });
    @endforeach
</script>
<script type="text/javascript">    
    $(".autcomplete-atasan" ).autocomplete({
        source: list_atasan,
        minLength:0, 
        select: function( event, ui ) {
            $( "input[name='approved_atasan_id']" ).val(ui.item.id);
            
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

    // Clock pickers
    $('.time_picker').clockpicker({
        placement: 'bottom',
        align: 'left',
        autoclose: true,
        'default': 'now'
    });

    $("select[name='jenis_training']").on('change', function(){
        if($(this).val() == 'Branch Visit')
        {
            $('.select-cabang').show("slow");
        }
        else
        {
            $('.select-cabang').hide("slow");
        }

        if($(this).val() == 'Others')
        {
            $('.select-others').show("slow");
        }
        else
        {
            $('.select-others').hide("slow");
        }
    });
    
    $("input[name='pesawat_perjalanan']").each(function(){

        $(this).on('click', function(){
            
            if($(this).val() == 'Sekali Jalan')
            {   
                $("input[name='tanggal_pulang']").attr('readonly', true);
                $("input[name='waktu_pulang']").attr('readonly', true);
            }
            else
            {
                $("input[name='tanggal_pulang']").removeAttr('readonly');
                $("input[name='waktu_pulang']").removeAttr('readonly');
            }

        });
    });

    $("#rute_dari" ).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "{{ route('ajax.get-airports') }}",
                method:"POST",
                data: {'word' : request.term, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType:"json",
                success:function(data)
                {
                    response(data);
                }
            })
        },
        select: function( event, ui ) {
            
        },
        showAutocompleteOnFocus: true
    });

    $("#rute_ke, #rute_tujuan" ).autocomplete({
        source: function (request, response) {
            $.ajax({
                url: "{{ route('ajax.get-airports') }}",
                method:"POST",
                data: {'word' : request.term, '_token' : $("meta[name='csrf-token']").attr('content')},
                dataType:"json",
                success:function(data)
                {
                    response(data);
                }
            });
        },
        select: function( event, ui ) {
            $("input[name='pesawat_rute_ke']").val(ui.item.id)
        },
        showAutocompleteOnFocus: true
    });

    $("#add_modal_penumpang").click(function(){

        var html_ = '<tr><td><input type="hidden" name="penumpang_id[]" value="'+ $('.penumpang_id').val() +'" />'+ $('.penumpang_id :selected').text() +'</td><td>'+ $('.penumpang_id :selected').data('kelamin') +'</td></tr>';

        $('.table-penumpang').html(html_);
    });

    $("#table_tambah_penumpang").click(function(){
        $('#modal_penumpang').modal('show');
    });

    $("#btn_plus_biaya_lainnya").click(function(){

        var el = '<tr><td><input type="text" name="biaya_lainnya_label[]" class="form-control" placeholder="Biaya Lainnya" /></td><td><input type="text" name="biaya_lainnya_nominal[]" class="form-control" placeholder="Nominal" /><a class="btn btn-xs btn-danger" onclick="hapus_el(this)"><i class="fa fa-trash"></i></a></td></tr>';

        $('.append_lain_lain').append(el);
    })

    function hapus_el(el)
    {
        $(el).parent().parent().remove();
    }

    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
    });
</script>
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
@endsection
