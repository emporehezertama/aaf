@extends('layouts.administrator')

@section('title', 'Kegiatan Training & Perjalanan Dinas - PT. Arthaasia Finance')

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
                    <li class="active">Kegiatan Training & Perjalanan Dinas</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Kegiatan Training & Perjalanan Dinas</h3>
                    <br />
                    <div class="table-responsive">
                        <table id="data_table" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">#</th>
                                    <th>NIK / NAMA</th>
                                    <th>DEPARTMENT / POSITION</th>
                                    <th>JENIS TRAINING</th>
                                    <th>TOPIK KEGIATAN</th>
                                    <th>TANGGAL KEGIATAN</th>
                                    <th>STATUS</th>
                                    <th>CREATED</th>
                                    <th width="100">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>   
                                        <td><a onclick="bootbox.alert('<p>Nama : <b>{{ $item->user->name }}</b></p><p>NIK : <b>{{ $item->user->nik }}<b></p>');">{{ $item->user->name }}</a></td>
                                        <td>{{ $item->user->department->name .' / '. $item->user->organisasiposition->name }}</td>
                                        <td>{{ $item->jenis_training }}</td>
                                        <td>{{ $item->topik_kegiatan }}</td>
                                        <td>{{ date('d F Y', strtotime($item->tanggal_kegiatan_start)) }} - {{ date('d F Y', strtotime($item->tanggal_kegiatan_end)) }}</td>
                                        <td>

                                            @if($item->is_approved_atasan != 1)
                                            <label onclick="status_approval_training({{ $item->id }})" class="btn btn-default btn-xs">Waiting Approval Atasan</label>
                                            @else
                                                
                                                @if($item->status == 1)
                                                    @if(empty($item->approved_hrd))
                                                        <label onclick="status_approval_training({{ $item->id }})" class="btn btn-default btn-xs">Waiting Approval</label>
                                                    @endif
                                                    @if($item->approved_hrd == 1)
                                                        <label onclick="status_approval_training({{ $item->id }})" class="btn btn-success btn-xs">Approved</label>
                                                    @endif
                                                @endif

                                                @if($item->status == 2)
                                                    <label onclick="status_approval_training({{ $item->id }})" class="btn btn-success btn-xs">Approved</label>
                                                @endif
                                            @endif

                                            @if($item->status == 4)
                                                <label class="btn btn-danger btn-xs" onclick="bootbox.alert('<h4>Alasana Pembatalan</h4><hr /><p>{{ $item->note_pembatalan }}</p>')"><i class="fa fa-close"></i>Dibatalkan</label>
                                            @endif

                                            @if($item->status == 2)
                                                @if($item->status_actual_bill >= 2)
                                                    @if($item->status_actual_bill == 2)
                                                    <a href="{{ route('karyawan.training.biaya', $item->id) }}">
                                                    <label class="btn btn-warning btn-xs"><i class="fa fa-history"></i> Waiting Approval</label></a>
                                                    @endif

                                                    @if($item->status_actual_bill == 3)
                                                    <a href="{{ route('karyawan.training.detail-all', $item->id) }}" class="btn btn-success btn-xs"><i class="fa fa-check"></i> Actual Bill di Approve</a>
                                                    @endif

                                                    @if($item->status_actual_bill == 4)
                                                    <a  href="{{ route('karyawan.training.detail-all', $item->id) }}"class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Actual Bill di Tolak</a>
                                                    @endif
                                                    
                                                @endif
                                            @endif
                                        </td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            @if($item->status == 1)
                                            
                                                @if(empty($item->approved_hrd))
                                                    <a href="{{ route('administrator.training.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5">proses <i class="fa fa-arrow-right"></i></button></a>
                                                @endif
                                                @if($item->approved_hrd == 1)
                                                    <a href="{{ route('administrator.training.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail</button></a>
                                                @endif
                                            @else
                                                <a href="{{ route('administrator.training.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail</button></a>
                                            @endif

                                            @if($item->status == 1)
                                            <a onclick="batalkan_pengajuan('{{ $item->id }}')" class="btn btn-danger btn-xs"><i class="fa fa-close"></i> Batalkan Training</a>
                                            @endif

                                        </td> 
                                    </tr>
                                @endforeach

                                @foreach($data_biaya as $no => $item)
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>   
                                        <td>{{ $item->jenis_training }}</td>
                                        <td>{{ $item->topik_kegiatan }}</td>
                                        <td>{{ date('d F Y', strtotime($item->tanggal_kegiatan)) }}</td>
                                        <td>
                                            <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                        </td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            <a href="{{ route('administrator.training.biaya', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-file"></i> Proses Actual Bill</button></a>
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
<!-- sample modal content -->
<div id="modal_history_approval" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">History Approval</h4> </div>
                <div class="modal-body" id="modal_content_history_approval">
                    
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
<div id="modal_pembatalan" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <form class="form-horizontal" id="form-cuti" enctype="multipart/form-data" action="{{ route('administrator.training.batal') }}" method="POST">
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
                        <button type="submit" class="btn btn-info btn-sm">Proses Pembatalan <i class="fa fa-arrow-right"></i> </button>
                    </div>
                </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('footer-script')
    <script type="text/javascript">
        function batalkan_pengajuan(id)
        {   
            $('.id-pembatalan').val(id);

            $("#modal_pembatalan").modal('show');
        }
    </script>
@endsection

@endsection
