@extends('layouts.karyawan')

@section('title', 'Medical Reimbursement - PT. Arthaasia Finance')

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
                    <li class="active">Medical Reimbursement</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0">Medical Reimbursement</h3>
                    <br />
                    <div class="table-responsive">
                        <table id="data_table" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">#</th>
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>JABATAN</th>
                                    <th>DEPARTMENT</th>
                                    <th>TANGGAL PENGAJUAN</th>
                                    <th>STATUS</th>
                                    <th>MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                 @if(isset($item->user->nik))
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>
                                        <td>{{ $item->user->nik }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->organisasi_job_role }}</td>
                                        <td>{{ isset($item->user->department->name) ? $item->user->department->name : '' }}</td>
                                        <td>{{ date('d F Y', strtotime($item->tanggal_pengajuan)) }}</td>
                                        <td>
                                            <a onclick="status_approval_medical({{ $item->id }})"> 
                                            @if($item->is_approved_atasan === NULL)
                                                <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                            @endif
                                            @if($item->is_approved_atasan == 0)
                                                <label class="btn btn-danger btn-xs">Denied</label>
                                            @endif
                                            @if($item->is_approved_atasan == 1)
                                                <label class="btn btn-success btn-xs">Approved</label>
                                            @endif
                                            </a>                                        
                                        </td>
                                        <td>
                                            @if(!cek_status_approval_user(Auth::user()->id, 'medical', $item->id))
                                                <a href="{{ route('karyawan.approval.medical-atasan.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5">proses <i class="fa fa-arrow-right"></i></button></a>
                                            @else
                                                <a href="{{ route('karyawan.approval.medical-atasan.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5"><i class="fa fa-search-plus"></i> detail</button></a>
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
