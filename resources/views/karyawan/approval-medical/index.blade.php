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
                    <h3 class="box-title m-b-0">Manage Medical Reimbursement</h3>
                    <br />
                    <div class="table-responsive">
                        <table id="data_table" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">#</th>
                                    <th>NIK</th>
                                    <th>NAME</th>
                                    <th>JABATAN</th>
                                    <th>CABANG  / DEPARTMENT</th>
                                    <th>TANGGAL PENGAJUAN</th>
                                    <th>STATUS</th>
                                    <th>MANAGE</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data as $no => $item)
                                 @if(isset($item->user->nik))
                                    @if(cek_approval_gm_medical() == 'yes')
                                        @if(skip_is_down_manager($item->user->id)=='no')
                                            <?php continue; ?>
                                        @endif
                                    @endif
                                    <tr>
                                        <td class="text-center">{{ $no+1 }}</td>
                                        <td>{{ $item->user->nik }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->user->organisasi_job_role }}</td>
                                        <td>
                                            <?php
                                                if($item->user->department_id != Null){
                                                    echo $item->user->department->name;
                                                }else{
                                                    echo "";
                                                }
                                            ?>
                                        </td>
                                        <td>{{ date('d F Y', strtotime($item->tanggal_pengajuan)) }}</td>
                                        <td>
                                            <a onclick="status_approval_medical({{ $item->id }})">
                                                <?php
                                                    $skip_gm_hr  = ['Staff', 'Head','Supervisor'];
                                                ?>
                                                    @if($item->is_approved_hr_benefit == '0')
                                                        <label class="btn btn-danger btn-xs">Denied</label>
                                                    @else
                                                        @if($item->is_approved_hr_benefit == '1')
                                                            @if($item->is_approved_manager_hr == '0')
                                                                <label class="btn btn-danger btn-xs">Denied</label>
                                                            @else
                                                                @if($item->is_approved_manager_hr == '1')
                                                                    @if(in_array($item->user->organisasiposition->name, $skip_gm_hr))
                                                                        <label class="btn btn-success btn-xs">Approved</label>
                                                                    @else
                                                                        @if($item->is_approved_gm_hr === '0')
                                                                            <label class="btn btn-danger btn-xs">Denied</label>
                                                                        @else
                                                                            @if($item->is_approved_gm_hr == '1')
                                                                                <label class="btn btn-success btn-xs">Approved</label>
                                                                            @else
                                                                                <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                                                            @endif
                                                                        @endif
                                                                    @endif
                                                                @else
                                                                    <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                                                @endif
                                                            @endif
                                                        @else
                                                            <label class="btn btn-warning btn-xs">Waiting Approval</label>
                                                        @endif
                                                    @endif

                                                    
                                            <!-- OLD STATUS APPROVAL -->
                                             <!--  @if($approval->nama_approval == 'HR Benefit')
                                                    @if($item->is_approved_hr_benefit == 1)
                                                        <label class="btn btn-success btn-xs">Approved</label>
                                                    @endif
                                                    @if($item->is_approved_hr_benefit === 0)
                                                        <label class="btn btn-danger btn-xs">Denied BNFT</label>
                                                    @endif
                                                    @if($item->is_approved_hr_benefit === NULL)
                                                        <label class="btn btn-warning btn-xs">Waiting Approval BNFT</label>
                                                    @endif
                                                @endif

                                                @if($approval->nama_approval == 'Manager HR')
                                                    @if($item->is_approved_manager_hr == 1)
                                                        <label class="btn btn-success btn-xs">Approved MNGR</label>
                                                    @endif
                                                    @if($item->is_approved_manager_hr === 0)
                                                        <label class="btn btn-danger btn-xs">Denied MNGR</label>
                                                    @endif
                                                    @if($item->is_approved_manager_hr === NULL)
                                                        <label class="btn btn-warning btn-xs">Waiting Approval MNGR</label>
                                                    @endif
                                                @endif

                                                @if($approval->nama_approval == 'GM HR')
                                                    @if($item->is_approved_gm_hr == 1)
                                                        <label class="btn btn-success btn-xs">Approved GM</label>
                                                    @endif
                                                    @if($item->is_approved_gm_hr === 0)
                                                        <label class="btn btn-danger btn-xs">Denied GM</label>
                                                    @endif
                                                    @if($item->is_approved_gm_hr === NULL)
                                                        <label class="btn btn-warning btn-xs">Waiting Approval GM</label>
                                                    @endif
                                                @endif  -->
                                            </a>
                                        </td>
                                        <td>
                                            @if($approval->nama_approval == 'HR Benefit')
                                                @if($item->is_approved_hr_benefit == "")
                                                    <a href="{{ route('karyawan.approval.medical.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5">proses <i class="fa fa-arrow-right"></i></button></a>
                                                @else
                                                    <a href="{{ route('karyawan.approval.medical.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5">detail <i class="fa fa-arrow-right"></i></button></a>
                                                @endif
                                            @endif

                                            @if($approval->nama_approval == 'Manager HR')
                                                @if($item->is_approved_hr_benefit == "1" && $item->is_approved_manager_hr == "")
                                                    <a href="{{ route('karyawan.approval.medical.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5">proses <i class="fa fa-arrow-right"></i></button></a>
                                                @else
                                                    <a href="{{ route('karyawan.approval.medical.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5">detail <i class="fa fa-arrow-right"></i></button></a>
                                                @endif
                                            @endif

                                            @if($approval->nama_approval == 'GM HR')
                                                @if($item->is_approved_manager_hr == "1" && $item->is_approved_gm_hr == "")
                                                    <a href="{{ route('karyawan.approval.medical.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5">proses <i class="fa fa-arrow-right"></i></button></a>
                                                @else
                                                    <a href="{{ route('karyawan.approval.medical.detail', ['id' => $item->id]) }}"> <button class="btn btn-info btn-xs m-r-5">detail <i class="fa fa-arrow-right"></i></button></a>
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
