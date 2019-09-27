@extends('layouts.karyawan')

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
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>DEPARTMENT / POSITION</th>
                                    <th>JENIS KEGIATAN</th>
                                    <th>TOPIK KEGIATAN</th>
                                    <th>TANGGAL KEGIATAN</th>
                                    <th>STATUS</th>
                                    <th>BILL</th>
                                    <th>CREATED</th>
                                    <th width="100">MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                 @if(isset($item->user->nik))
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>  
                                        <td>{{ $item->user->nik }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ (isset($item->user->department->name) ? $item->user->department->name : '') .' / '. (isset($item->user->organisasiposition->name) ? $item->user->organisasiposition->name : '') }}</td> 
                                        <td>{{ $item->jenis_training }}</td>
                                        <td>{{ $item->topik_kegiatan }}</td>
                                        <td>{{ date('d F Y', strtotime($item->tanggal_kegiatan_start)) }} - {{ date('d F Y', strtotime($item->tanggal_kegiatan_end)) }}</td>
                                        <td>
                                            <a onclick="status_approval_training({{ $item->id }})">
                                                @if($item->status == 1)
                                                    @if($item->is_approved_atasan == 1)

                                                        @if($item->pengambilan_uang_muka !== NULL)

                                                            @if($approval->nama_approval == 'HRD')
                                                                @if($item->approved_hrd === NULL)
                                                                    <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                                                @endif
                                                                @if($item->approved_hrd === 0)
                                                                    <label class="btn btn-danger btn-xs">Denied</label>
                                                                @endif
                                                                @if($item->approved_hrd == 1)
                                                                    <label class="btn btn-success btn-xs">Approved</label>
                                                                @endif
                                                            @endif

                                                            @if($approval->nama_approval == 'Finance')
                                                                @if($item->approved_finance === NULL)
                                                                    <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                                                @endif
                                                                @if($item->approved_finance === 0)
                                                                    <label class="btn btn-danger btn-xs">Denied</label>
                                                                @endif
                                                                @if($item->approved_finance == 1)
                                                                    <label class="btn btn-success btn-xs">Approved</label>
                                                                @endif
                                                            @endif

                                                        @else
                                                            @if($item->status == 1)
                                                                <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                                            @endif

                                                            @if($item->status == 2)
                                                                <label class="btn btn-success btn-xs">Approved</label>
                                                            @endif
                                                            @if($item->status == 3)
                                                                <label class="btn btn-danger btn-xs">Denied</label>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <!-- atasan belum approve-->
                                                        @if($item->is_approved_atasan === NULL)
                                                            <label class="btn btn-warning btn-xs">On Progress</label>
                                                        @endif
                                                        
                                                        @if($item->status == 2)
                                                            <label class="btn btn-success btn-xs">Approved</label>
                                                        @endif
                                                        @if($item->status == 3)
                                                            <label class="btn btn-danger btn-xs">Denied</label>
                                                        @endif
                                                    @endif
                                                @elseif($item->status == 2)
                                                    <label class="btn btn-success btn-xs">Approved</label>
                                                @elseif($item->status ==3)
                                                    <label class="btn btn-danger btn-xs">Denied</label>
                                                @endif
                                            </a>
                                        </td>
                                        <td>
                                            @if($item->status == 2)
                                                <a onclick="status_approval_actual_bill({{ $item->id }})"> 
                                                    @if($item->status_actual_bill == 2)

                                                        @if($item->is_approve_atasan_actual_bill == 1)
                                                            @if($approval->nama_approval == 'HRD')
                                                                @if($item->is_approve_hrd_actual_bill == "")
                                                                    <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                                                @endif
                                                                @if($item->is_approve_hrd_actual_bill === 0)
                                                                    <label class="btn btn-danger btn-xs">Denied</label>
                                                                @endif
                                                                @if($item->is_approve_hrd_actual_bill == 1)
                                                                    <label class="btn btn-success btn-xs">Approved</label>
                                                                @endif
                                                            @endif

                                                            @if($approval->nama_approval == 'Finance')
                                                                @if($item->is_approve_finance_actual_bill == "")
                                                                    <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                                                @endif
                                                                @if($item->is_approve_finance_actual_bill === 0)
                                                                    <label class="btn btn-danger btn-xs">Denied</label>
                                                                @endif
                                                                @if($item->is_approve_finance_actual_bill == 1)
                                                                    <label class="btn btn-success btn-xs">Approved</label>
                                                                @endif
                                                            @endif
                                                        @else
                                                            @if($item->is_approve_atasan_actual_bill == "")
                                                                <label class="btn btn-default btn-xs">Waiting Approval Atasan</label>
                                                            @endif
                                                            @if($item->status_actual_bill == 3)
                                                                <label class="btn btn-success btn-xs">Success</label>
                                                            @endif

                                                            @if($item->status_actual_bill == 4)
                                                                <label class="btn btn-danger btn-xs">Denied</label>
                                                            @endif
                                                        @endif
                                                        
                                                    @elseif($item->status_actual_bill == 3)
                                                        <label class="btn btn-success btn-xs">Approved</label>
                                                    @elseif($item->status_actual_bill == 3)
                                                        <label class="btn btn-danger btn-xs">Denied</label>
                                                    @endif

                                                    @if($item->status_actual_bill === null)
                                                        <label class="btn btn-default btn-xs">Open</label>
                                                    @endif
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $item->created_at }}</td>
                                        <td>
                                            @if($item->status ==2)
                                                @if($item->status_actual_bill >= 2 )

                                                    @if($item->is_approve_atasan_actual_bill == 1)
                                                        @if($approval->nama_approval == 'Finance')
                                                            @if($item->is_approve_finance_actual_bill == "")
                                                             <a href="{{ route('karyawan.approval.training.biaya', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-file"></i> Proses Actual Bill</button></a>
                                                            @endif

                                                            @if($item->is_approve_finance_actual_bill == 1 || $item->is_approve_finance_actual_bill === 0)
                                                                <a href="{{ route('karyawan.approval.training.biaya', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-file"></i> Detail Actual Bill</button></a>
                                                            @endif
                                                        @endif
                                                        @if($approval->nama_approval == 'HRD')
                                                            @if($item->is_approve_hrd_actual_bill == "")
                                                             <a href="{{ route('karyawan.approval.training.biaya', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-file"></i> Proses Actual Bill</button></a>
                                                            @endif

                                                            @if($item->is_approve_hrd_actual_bill == 1 || $item->is_approve_hrd_actual_bill === 0)
                                                                <a href="{{ route('karyawan.approval.training.biaya', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-file"></i> Detail Actual Bill</button></a>
                                                            @endif
                                                        @endif
                                                    @endif
                                                @endif
                                            @endif

                                            @if($item->status == 1)
                                                @if($item->is_approved_atasan == 1)
                                                    @if($item->approved_finance == 0)
                                                        <a href="{{ route('karyawan.approval.training.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5">proses <i class="fa fa fa-arrow-right"></i></button></a>
                                                    @else
                                                        <a href="{{ route('karyawan.approval.training.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5">detai <i class="fa fa-search-plus"></i></button></a>
                                                    @endif
                                                @endif
                                            @endif
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
@endsection
