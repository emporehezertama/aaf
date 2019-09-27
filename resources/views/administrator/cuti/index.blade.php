@extends('layouts.administrator')

@section('title', 'Cuti / Ijin Karyawan - PT. Arthaasia Finance')

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
                    <li class="active">Cuti / Ijin Karyawan</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Manage Cuti / Ijin Karyawan</h3>
                    <br />
                    <div class="table-responsive">
                        <table id="data_table" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">#</th>
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>TANGGAL CUTI / IJIN</th>
                                    <th>JENIS CUTI / IJIN</th>
                                    <th>LAMA CUTI / IJIN</th>
                                    <th>KEPERLUAN</th>
                                    <th>STATUS</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    @if($item->user->nik)
                                        <tr>
                                            <td class="text-center">{{ $no+1 }}</td>    
                                            <td>{{ $item->user->nik }}</td>
                                            <td>{{ $item->user->name }}</td>
                                            <td>{{ date('d F Y', strtotime($item->tanggal_cuti_start)) }} - {{ date('d F Y', strtotime($item->tanggal_cuti_end)) }}</td>
                                            <td>{{ isset($item->cuti) ? $item->cuti->jenis_cuti : '' }}</td>
                                            <td>{{ $item->total_cuti }}</td>
                                            <td>{{ $item->keperluan }}</td>
                                            <td>
                                                <a onclick="detail_approval_cuti('cuti', {{ $item->id }})"> 
                                                    @if($item->is_approved_atasan === NULL)
                                                        <label class="btn btn-default btn-xs">Waiting Approval Atasan</label>
                                                    @else
                                                        @if($item->is_approved_personalia == "" and $item->is_approved_atasan == 1 and $item->status != 4)
                                                            <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                                        @endif

                                                        @if($item->is_approved_personalia == 1)
                                                            <label class="btn btn-success btn-xs">Approved</label>
                                                        @endif
                                                    @endif
                                                </a>
                                                @if($item->status == 4)
                                                    <label class="btn btn-danger btn-xs" onclick="bootbox.alert('<h4>Alasana Pembatalan</h4><hr /><p>{{ $item->note_pembatalan }}</p>')"><i class="fa fa-close"></i>Dibatalkan</label>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->status == 1)
                                                <a onclick="batalkan_pengajuan('{{ $item->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Batalkan Cuti / Ijin</a>
                                                @endif

                                                @if($item->is_approved_atasan == 1 and $item->status == 1 )
                                                <a href="{{ route('administrator.cuti.proses', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-arrow-right"></i> Proses Cuti / Ijin</a>
                                                @endif

                                                <a href="{{ route('administrator.cuti.delete', $item->id) }}" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i> Delete</a>
                                            </td>
                                        </tr>
                                    @endif
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

<!-- sample modal content -->
<div id="modal_pembatalan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <form class="form-horizontal" id="form-pembatalan" enctype="multipart/form-data" action="{{ route('administrator.cuti.batal') }}" method="POST">
                    {{ csrf_field() }}
                    <h4 class="modal-title" id="myModalLabel">Pembatalan Form</h4> </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3">Alasan Pembatalan</label>
                            <div class="col-md-8">
                                <textarea class="form-control" name="note"></textarea>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                        <input type="hidden" class="id-pembatalan" name="id" />
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal"> <i class="fa fa-close"></i> Close</button>
                        <button type="button" class="btn btn-info btn-sm" id="btn_pembatalan">Proses Pembatalan <i class="fa fa-arrow-right"></i> </button>
                    </div>
                </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('footer-script')
    <script type="text/javascript">

        $("#btn_pembatalan").click(function(){

            if($("textarea[name='note']").val() == "")
            {
                bootbox.alert('Alasan pembatalan harus diisi ');
                return false;
            }

            $("#form-pembatalan").submit();
        });

        function batalkan_pengajuan(id)
        { 
            $('.id-pembatalan').val(id);

            $("#modal_pembatalan").modal('show');
        }

    </script>
@endsection

@endsection
