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
            <form class="form-horizontal" enctype="multipart/form-data" action="{{ route('karyawan.approval.exit.proses') }}" method="POST" id="exit_interview_form">
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
                            @if($approval->nama_approval == 'HRD')
                                <div class="form-group">
                                    <label class="col-md-12">DOCUMENT LIST/DAFTAR DOKUMEN</label>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30px;">NO</th>
                                                    <th>ITEM/JENIS</th>
                                                    <th>FORM NO (NO. FORM)</th>
                                                    <th style="width: 50px;">CHECKED</th>
                                                    <th>CATATAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($list_exit_clearance_document as $no => $item)
                                                <tr>
                                                    <td>{{ $no + 1 }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>{{ $item->form_no }}</td>
                                                    <td style="text-align: center;">
                                                        <input type="checkbox" value="1" {{ $item->hrd_checked == 1 ? 'checked' : '' }} name="check_dokument[{{ $item->id }}]">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="check_document_catatan[{{ $item->id }}]" class="form-control catatan" {{ $item->hrd_checked == 0 ? 'readonly="true"' : ''  }} value="{{ $item->hrd_note }}" />
                                                        @if($item->hrd_checked == 1)
                                                            <small>Submit Date : {{ Carbon\Carbon::parse($item->hrd_check_date)->format('d M Y H:i') }}</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-md-12">INVENTORY RETURN TO HRD</label>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30px;">NO</th>
                                                    <th>ITEM/JENIS</th>
                                                    <th style="width: 50px;">CHECKED</th>
                                                    <th>CATATAN</th>
                                                </tr>
                                            </thead>
                                            <tbody> 
                                                @foreach($list_exit_clearance_inventory_to_hrd as $no => $item)
                                                <tr>
                                                    <td>{{ $no + 1 }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td style="text-align: center;">
                                                        <input type="checkbox" {{ $item->hrd_checked == 1 ? 'checked'  : '' }} name="check_inventory_hrd[{{ $item->id }}]" value="1">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="check_inventory_hrd_catatan[{{ $item->id }}]" {{ $item->hrd_checked == 0 ? 'readonly="true"' : ''  }} class="form-control catatan" value="{{ $item->hrd_note }}" />
                                                        @if($item->hrd_checked == 1)
                                                            <small>Submit Date : {{ Carbon\Carbon::parse($item->hrd_check_date)->format('d M Y H:i') }}</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if($approval->nama_approval == 'GA')
                                <div class="form-group">
                                    <label class="col-md-12">INVENTORY RETURN TO GENERAL AFFAIR (GA)</label>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30px;">NO</th>
                                                    <th>ITEM/JENIS</th>
                                                    <th style="width:20px;">CHECKED</th>
                                                    <th>CATATAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($list_exit_clearance_inventory_to_ga as $no => $item)
                                                <tr>
                                                    <td>{{ $no + 6 }}</td>
                                                    <td>{{ $item->name }}</td>
                                                    <td style="text-align: center;">
                                                        <input type="checkbox" name="check_inventory_ga[{{ $item->id }}]" value="1" {{ $item->ga_checked == 1 ? 'checked' : '' }} />
                                                    </td>
                                                    <td>
                                                        <input type="text" name="check_inventory_ga_catatan[{{ $item->id }}]" readonly="true" class="form-control catatan" value="{{ $item->ga_note }}" />
                                                         @if($item->ga_checked == 1)
                                                            <small>Submit Date : {{ Carbon\Carbon::parse($item->ga_check_date)->format('d M Y H:i') }}</small>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                                <div class="form-group">
                                    @if($approval->nama_approval == "IT")
                                    <label class="col-md-12">INVENTORY RETURN TO IT</label>
                                    <div class="col-md-12">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 30px;">NO</th>
                                                    <th>ITEM/JENIS</th>
                                                    <th style="width:20px;">CHECKED</th>
                                                    <th>CATATAN</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>12</td>
                                                    <td>Mobil</td>
                                                    <td>
                                                        <input type="checkbox" name="check_inventory_it_mobil" value="1" />
                                                    </td>
                                                    <td>
                                                        <input type="text" name="check_inventory_it_mobil_catatan" readonly="true" class="form-control catatan" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>13</td>
                                                    <td>Laptop/PC & Other IT Device</td>
                                                </tr>
                                                <tr>
                                                    <td>14</td>
                                                    <td>
                                                        Password PC/Laptop <br />
                                                        <div class="col-md-4" style="padding-left:0;">
                                                            <input type="text" name="inventory_it_username_pc" class="form-control" placeholder="Username" readonly="true" value="{{ $data->inventory_it_username_pc }}" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" name="inventory_it_password_pc" class="form-control" placeholder="Password" readonly="true" value="{{ $data->inventory_it_password_pc }}" />
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
                                                            <input type="text" name="inventory_it_email" class="form-control" placeholder="Email" readonly="true" value="{{ $data->inventory_it_email }}" />
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>17</td>
                                                    <td>
                                                        Arium <br />
                                                        <div class="col-md-4" style="padding-left:0;">
                                                            <input type="text" name="inventory_it_username_arium" class="form-control" placeholder="Username" readonly="true" value="{{ $data->inventory_it_username_arium }}" />
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" name="inventory_it_password_arium" class="form-control" placeholder="Password" readonly="true" value="{{ $data->inventory_it_password_arium }}" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                    <div class="clearfix"></div>
                                    <br />
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <a href="{{ route('karyawan.approval.exit-atasan.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                            <button type="submit" class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="btn_approved"><i class="fa fa-save"></i> Update Form</button>
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
                                            <input type="text" class="form-control" value="{{ $data->user->nik .' / '. $data->user->name }}" readonly="true">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6">Jabatan</label>
                                        <label class="col-md-6">Division / Departement</label>
                                        <div class="col-md-6">
                                            <input type="text" readonly="true" class="form-control jabatan" value="{{ isset($data->user->organisasiposition->name) ? $data->user->organisasiposition->name : '' }}">
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" readonly="true" class="form-control department" value="{{ isset($data->user->department->name) ? $data->user->department->name : '' }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-6">Resign Date / Tanggal Berhenti</label>
                                        <label class="col-md-6">Join Data / Tanggal Masuk</label>
                                        <div class="col-md-6">
                                            <input type="text" readonly="true" name="resign_date" class="form-control datepicker" value="{{ $data->resign_date }}" >
                                        </div>
                                        <div class="col-md-6">
                                            <input type="text" readonly="true" class="form-control" value="{{ $data->user->join_date }}">
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
                                            <?php 

                                                if($data->exit_interview_reason != $item->id)
                                                {
                                                    continue;
                                                }
                                            ?>
                                            <li class="list-group-item"><label>{{ $item->label }}</label></li>
                                            @endforeach
                                            @if($item->exit_interview_reason == 'Others')
                                            <li class="list-group-item">
                                                <label><input type="radio" name="exit_interview_reason" value="other" {{ $data->exit_interview_reason == 'other' ? 'checked' : '' }} /> Other (Lainnya, ditulis alasannya)</label>
                                                <textarea class="form-control" name="other_reason">{{ $data->other_reason }}</textarea>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Hal yang berkesan selama bekerja di Artha Asia Finance</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="hal_berkesan" readonly="true">{{ $data->hal_berkesan }}</textarea>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Hal yang tidak berkesan selama bekerja di Artha Asia Finance</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="hal_tidak_berkesan" readonly="true">{{ $data->hal_tidak_berkesan }}</textarea>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Masukan terhadap Artha Asia Finance</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="masukan" readonly="true">{{ $data->masukan }}</textarea>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Hal yang akan dilakukan setelah resign dari Artha Asia Finance</label>
                                    <div class="col-md-12">
                                        <textarea class="form-control" name="kegiatan_setelah_resign" readonly="true">{{ $data->kegiatan_setelah_resign }}</textarea>
                                    </div>
                                </div>
                                <hr />
                                <div class="form-group">
                                    <label class="col-md-12">Jika pindah ke perusahaan baru </label>
                                    <p class="col-md-6">Tujuan perusahaan baru </p>
                                    <p class="col-md-6">Jenis bidang usaha </p>
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="tujuan_perusahaan_baru" readonly="true">{{ $data->tujuan_perusahaan_baru }}</textarea>
                                    </div>
                                    <div class="col-md-6">
                                        <textarea class="form-control" name="jenis_bidang_usaha" readonly="true">{{ $data->jenis_bidang_usaha }}</textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <a href="{{ route('karyawan.exit-interview.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                                            <a href="#clearance" aria-controls="messages" role="tab" data-toggle="tab" aria-expanded="false" class="btn btn-info btn-sm">NEXT <i class="fa fa-arrow-right"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>    
                <input type="hidden" name="status" value="0" />
                <input type="hidden" name="id" value="{{ $data->id }}">
            </form>                    
        </div>
        <!-- /.row -->
        <!-- ============================================================== -->
    </div>
    <!-- /.container-fluid -->
    @extends('layouts.footer')
</div>
@section('footer-script')
    <script type="text/javascript">

        $("input[type='checkbox']").each(function(){
            
            $(this).click(function(){

                if($(this).prop('checked'))
                {   
                    $(this).parent().parent().find(".catatan").removeAttr('readonly');
                }
                else
                {
                    $(this).parent().parent().find(".catatan").attr('readonly', true);
                }
            });
        });
    </script>
@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
