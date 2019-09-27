@extends('layouts.administrator')

@section('title', 'Payroll Karyawan - PT. Arthaasia Finance')

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
                <h4 class="page-title">Payroll</h4> 
            </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
                <a href="{{ route('administrator.payroll.import') }}" class="btn btn-success btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-file"></i> IMPORT PAYROLL</a>
                <a href="{{ route('administrator.payroll.download') }}" class="btn btn-success btn-sm pull-right m-l-20 hidden-xs hidden-sm waves-effect waves-light"> <i class="fa fa-cloud-download"></i> DOWNLOAD PAYROLL</a>
                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Payroll</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>

        <!-- .row -->
        <div class="row">
            <div class="col-md-12">
                <div class="white-box">
                    <h3 class="box-title m-b-0" style="float: left; margin-right: 10px;">Manage Payroll</h3>


                    <label class="btn btn-warning btn-sm" id="calculate"><i class="fa fa-refresh"></i> Calculate </label>
                    
                    <br />
                    <div class="clearfix"></div>
                    <br />
                    <br />

                    <div class="table-responsive">
                        <table id="data_table" class="display nowrap" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th width="70" class="text-center">#</th>
                                    <th>NIK</th>
                                    <th>NAMA</th>
                                    <th>SALARY</th>
                                    <th>% JKK (Accident) + JK (Death)</th>
                                    <th>Call Allowance</th>
                                    <th>Yearly Bonus, THR or others</th>
                                    <th>BASIC SALLARY</th>
                                    <th>LESS: TAX, PENSION & JAMSOSTEK (MONTHLY)</th>
                                    <th>TAKE HOME PAY</th>
                                    <th>STATUS</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($data as $no => $item)
                            <tr>
                                <td>{{ $no+1 }}</td>
                                <td>{{ $item->user->nik }}</td>
                                <td>{{ $item->user->nik }}</td>
                                <td>{{ number_format($item->salary) }}</td>
                                <td>{{ $item->jkk }}</td>
                                <td>{{ number_format($item->call_allow) }}</td>
                                <td>{{ number_format($item->bonus) }}</td>
                                <td>{{ number_format($item->basic_salary) }}</td>
                                <td>{{ number_format($item->less) }}</td>
                                <td>{{ number_format($item->thp) }}</td>
                                <td>
                                    @if($item->is_calculate == 0)
                                        <label class="btn btn-warning btn-xs"><i class="fa fa-close"></i> Not Calculate</label>
                                    @else
                                        <label class="btn btn-success btn-xs"><i class="fa fa-check"></i> Calculate</label>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('administrator.payroll.detail', $item->id) }}" class="btn btn-info btn-xs"><i class="fa fa-edit"></i> detail</a>
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
@section('footer-script')
<script type="text/javascript">
    $("#calculate").click(function(){


        bootbox.confirm('Calculate Payroll ?', function(res){

            bootbox.alert('Harap menunggu beberapa saat, Mungkin butuh beberapa saat untuk melakukan Calculate ... ');

            setTimeout(function(){
                window.location = '{{ route('administrator.payroll.calculate') }}';
            }, 2000);
        });

    });
</script>
@endsection


@endsection
