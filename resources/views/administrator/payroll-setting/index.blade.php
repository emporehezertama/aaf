@extends('layouts.administrator')

@section('title', 'Setting Payroll - PT. Arthaasia Finance')

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
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Setting Payroll</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">

                     <ul class="nav customtab nav-tabs" role="tablist">
                        <li role="presentation" class="active"><a href="#pph" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="true"><span class="visible-xs"><i class="ti-home"></i></span><span class="hidden-xs"> PPH</span></a></li>
                        <li role="presentation" class=""><a href="#ptkp" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs"> PTKP</span></a></li>
                        <li role="presentation" class=""><a href="#others" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false"><span class="visible-xs"><i class="ti-email"></i></span> <span class="hidden-xs"> Others</span></a></li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="pph">
                            <h3 class="box-title m-b-0">Manage TARIF PAJAK PENGHASILAN (PPh)</h3>
                            <a href="{{ route('administrator.payroll-setting.add-pph') }}" class="btn btn-info btn-sm" style="position: absolute;z-index: 99999;"><i class="fa fa-plus"></i> TAMBAH PPH SETTING</a>
                          
                            <div class="table-responsive">
                                <table id="data_table_no_copy" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>BATAS BAWAH</th>
                                            <th>BATAS ATAS</th>
                                            <th>TARIF</th>
                                            <th>PAJAK MINIMAL</th>
                                            <th>AKUMULASI PAJAK</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pph as $item)
                                        <tr>
                                            <td>{{ number_format($item->batas_bawah) }}</td>
                                            <td>{{ number_format($item->batas_atas) }}</td>
                                            <td>{{ $item->tarif }}</td>
                                            <td>{{ number_format($item->pajak_minimal) }}</td>
                                            <td>{{ number_format($item->akumulasi_pajak) }}</td>
                                            <td></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="ptkp">
                            <h3 class="box-title m-b-0">Manage PTKP</h3>
                            <div class="table-responsive">
                                <table id="data_table_no_copy2" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th>BUJANGAN / WANITA</th>
                                            <th>MENIKAH</th>
                                            <th>MENIKAH ANAK 1</th>
                                            <th>MENIKAH ANAK 2</th>
                                            <th>MENIKAH ANAK 3</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($ptkp as $item)
                                       <tr>
                                           <td>{{ number_format($item->bujangan_wanita) }}</td>
                                           <td>{{ number_format($item->menikah) }}</td>
                                           <td>{{ number_format($item->menikah_anak_1) }}</td>
                                           <td>{{ number_format($item->menikah_anak_2) }}</td>
                                           <td>{{ number_format($item->menikah_anak_3) }}</td>
                                           <td>
                                            <a href="{{ route('administrator.payroll-setting.edit-ptkp', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> edit</a>
                                           </td>
                                       </tr>
                                       @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                         <div role="tabpanel" class="tab-pane fade" id="others">
                            <h3 class="box-title m-b-0">Others Setting</h3>
                            <a href="{{ route('administrator.payroll-setting.add-others') }}" class="btn btn-info btn-sm" style="position: absolute;z-index: 99999;"><i class="fa fa-plus"></i> TAMBAH OTHERS SETTING</a>
                            <div class="table-responsive">
                                <table id="data_table_no_copy3" class="display nowrap" cellspacing="0" width="100%">
                                    <thead>
                                        <tr>
                                            <th width="70" class="text-center">#</th>
                                            <th>LABEL</th>
                                            <th>VALUE</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                       @foreach($others as $no => $item)
                                        <tr>
                                            <td>{{ $no+1 }}</td>
                                            <td>{{ $item->label }}</td>
                                            <td>{{ number_format($item->value) }}</td>
                                            <td>
                                                <a href="" class="btn btn-xs btn-info">edit</a>
                                            </td>
                                        </tr>
                                       @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
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
                <form method="POST" id="form-upload" enctype="multipart/form-data" class="form-horizontal frm-modal-education" action="{{ route('administrator.plafond-dinas.import') }}">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label class="col-md-3">Jenis Perjalanan</label>
                        <div class="col-md-9">
                            <select name="jenis_plafond" class="form-control">
                                <option>Domestik</option>
                                <option>Luar Negeri</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3">File (xls)</label>
                        <div class="col-md-9">
                            <input type="file" name="file" class="form-control" />
                        </div>
                    </div>
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

@section('footer-script')
<script type="text/javascript">
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
