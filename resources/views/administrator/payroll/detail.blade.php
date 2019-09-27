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
                <h4 class="page-title">Form Payroll Karyawan</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Payroll Karyawan</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" enctype="multipart/form-data" action="" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Payroll Karyawan</h3>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3">NIK</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ $data->user->nik }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">NAMA</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ $data->user->name }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Salary</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->salary) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">JKK (Accident) + JK (Death)</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ $data->jkk }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Call Allowance</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->call_allow) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Yearly Bonus, THR or others     </label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->bonus) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Gross Income Per Year </label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->gross_income) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Burden Allowance    </label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->burden_allow) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Jamsostek Premium Paid by Employee (JHT dan pension) {{ !empty($date->jamsostek) ? $data->jamsostek .'%' : '' }}   </label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->jamsostek_result) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Total Deduction ( 3 + 4 )</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->total_deduction) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">NET Yearly Income  ( 2 - 5 )    </label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->net_yearly_income) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Untaxable Income </label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->untaxable_income) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3">Taxable Yearly Income  ( 6 - 7)</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->taxable_yearly_income) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">5%    ( 0-50 million)</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->income_tax_calculation_5) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">15%  ( 50 - 250 million)</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->income_tax_calculation_15) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">25%  ( 250-500 million)</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->income_tax_calculation_25) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">30%  ( > 500 million)</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->income_tax_calculation_30) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Yearly Income Tax</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->yearly_income_tax) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Monthly Income Tax  </label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->monthly_income_tax) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Basic Salary </label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->basic_salary) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Less : Tax, Pension & Jamsostek (Monthly)</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->less) }}" class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3">Take Home Pay</label>
                                <div class="col-md-6">
                                   <input type="text" readonly="true" value="{{ number_format($data->thp) }}" class="form-control">
                                </div>
                            </div>
                        </div>
                        
                        
                         
                        <div class="clearfix"></div>
                        <br />
                        <div class="form-group">
                            <div class="col-md-12">
                                <a href="{{ route('administrator.payroll.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Back</a>
                                <br style="clear: both;" />
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
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
