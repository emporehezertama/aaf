@extends('layouts.administrator')

@section('title', 'Payment Request - PT. Arthaasia Finance')

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
                <h4 class="page-title">Form Payment Request</h4> </div>
            <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">

                <ol class="breadcrumb">
                    <li><a href="javascript:void(0)">Dashboard</a></li>
                    <li class="active">Payment Request</li>
                </ol>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- .row -->
        <div class="row">
            <form class="form-horizontal" id="form_payment" enctype="multipart/form-data" action="{{ route('karyawan.payment-request.store') }}" method="POST">
                <div class="col-md-12">
                    <div class="white-box">
                        <h3 class="box-title m-b-0">Data Payment Request</h3>
                        <br />
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
                        <div class="col-md-6" style="padding-left:0;">
                            <div class="form-group">
                                <label class="col-md-12">From</label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" value="{{ Auth::user()->nik .' / '. Auth::user()->name  }}" readonly="true">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-6">To : Accounting Department</label>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Tujuan / Purpose</label>
                                <div class="col-md-10">
                                    <textarea class="form-control" name="tujuan"></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Jenis Transaksi / Trancation Type</label>
                                <div class="col-md-12">
                                    <label style="font-weight: normal;"><input type="radio" name="transaction_type" value="Advance" /> Advance</label> &nbsp;&nbsp;
                                    <label style="font-weight: normal;"><input type="radio" name="transaction_type" value="Payment" /> Payment</label>
                                </div>
                            </div>
                            <hr />
                            <div class="form-group">
                                <label class="col-md-12">Cara Pembayaran / Payment Method</label>
                                <div class="col-md-12">
                                    <label style="font-weight: normal;"><input type="radio" name="payment_method" value="Cash" /> Cash</label> &nbsp;&nbsp;
                                    <label style="font-weight: normal;"><input type="radio" name="payment_method" value="Bank Transfer" /> Bank Transfer</label>

                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-12">Nama Pemilik Rekening / Name of Account</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" readonly="true" value="{{ Auth::user()->nama_rekening }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">No Rekening / Account Number</label>
                                <div class="col-md-12">
                                    <input type="number" class="form-control" readonly="true" value="{{ Auth::user()->nomor_rekening }}" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-12">Nama Bank / Name Of Bank</label>
                                <div class="col-md-12">
                                    <input type="text" class="form-control" readonly="true" value="{{ isset(Auth::user()->bank) ? Auth::user()->bank->name : '' }}" />
                                </div>
                            </div>
                        </div>

                        <div class="clearfix"></div>
                        <div class="table-responsive">
                            <table class="table table-hover manage-u-table">
                                <thead>
                                    <tr>
                                        <th>NO</th>
                                        <th>TYPE</th>
                                        <th>DESCRIPTION</th>
                                        <th>QUANTITY</th>
                                        <th>ESTIMATION COST</th>
                                        <th>AMOUNT</th>
                                        <th>AMOUNT APPROVED</th>
                                        <th>BUKTI TRANSAKSI</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody class="table-content-lembur">
                                    <tr>
                                        <td>1</td>
                                        <td>
                                            <div class="col-md-10" style="padding-left:0;">
                                                <select name="type[]" class="form-control input">
                                                    <option value=""> - none - </option>
                                                    <option>Parkir</option>
                                                    <option>Bensin</option>
                                                    <option>Tol</option>
                                                    <option>Overtime Transport</option>
                                                    <option>Others</option>
                                                </select>
                                            </div>
                                            <div class="content_bensin"></div>
                                            <div class="content_overtime"></div>
                                        </td>
                                        <td>
                                            <input type="text" class="form-control input" name="description[]">
                                        </td>
                                        <td>
                                            <input type="number" name="quantity[]" class="form-control input">
                                        </td>
                                        <td>
                                            <input type="number" name="estimation_cost[]" class="form-control estimation input">
                                        </td>
                                        <td>
                                            <input type="number" name="amount[]" class="form-control amount input">
                                        </td>
                                        <td>
                                            <input type="number" name="amount_approved[]" class="form-control" readonly="true">
                                        </td>
                                        <td>
                                            <input type="file" name="file_struk[] input" class="form-control">
                                        </td>
                                        <td></td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" style="text-align: right;">TOTAL NOMINAL PEMBAYARAN / PAYMENT AMOUNT</th>
                                        <th class="total"></th>
                                    </tr>
                                </tfoot>
                            </table>
                            <a class="btn btn-info btn-xs pull-right" id="add"><i class="fa fa-plus"></i> Tambah</a>
                        </div>
                        <hr />
                        <div class="clearfix"></div>
                        <br />
                        <a href="{{ route('karyawan.payment-request.index') }}" class="btn btn-sm btn-default waves-effect waves-light m-r-10"><i class="fa fa-arrow-left"></i> Cancel</a>
                        <a class="btn btn-sm btn-success waves-effect waves-light m-r-10" id="submit_payment"><i class="fa fa-save"></i> Submit Payment Request</a>
                        <br style="clear: both;" />
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

<!-- sample modal content -->
<div id="modal_overtime" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Overtime</h4> </div>
                <div class="modal-body">
                   <div class="form-horizontal modal-form-overtime">
                    @if(!data_overtime_user(Auth::user()->id))
                        <p><i>Tidak ada Overtime</i></p>
                    @endif

                    @if(data_overtime_user(Auth::user()->id))
                    <table class="table tabl-hover">
                       <thead>
                           <tr>
                               <th width="50">NO</th>
                               <th>TANGGAL</th>
                           </tr>
                       </thead>
                       <tbody>
                        @foreach(data_overtime_user(Auth::user()->id) as $item)
                        <?php if($item->is_payment_request != ""){ continue; } ?>
                        <tr>
                           <td><input type="checkbox" name="overtime_item" value="{{ $item->id }}"></td>
                           <td>{{ $item->created_at }}</td>
                        </tr>
                        @endforeach
                        </tbody>
                       </table>
                    @endif
                   </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-sm" id="add_overtime">Tambah</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- sample modal content -->
<div id="modal_bensin" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title" id="myModalLabel">Data Bensin</h4> </div>
                <div class="modal-body">
                   <form class="form-horizontal" id="form_modal_bensin">
                        <div class="form-group">
                            <label class="col-md-12">Tanggal struk pembelian bensin</label>
                            <div class="col-md-12">
                                <input type="text" class="form-control datepicker modal_tanggal_struk_bensin" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-12">Odometer (KM)</label>
                            <div class="col-md-6">
                                <input type="number" class="form-control modal_odo_from" placeholder="From Odo Meter" />
                            </div>
                            <div class="col-md-6">
                                <input type="number" class="form-control modal_odo_to" placeholder="To Odo Meter" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Liter</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control modal_liter" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-12">Cost</label>
                            <div class="col-md-12">
                                <input type="number" class="form-control modal_cost" />
                            </div>
                        </div>
                   </form>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default waves-effect btn-sm btn_close_bensin" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info btn-sm" id="add_modal_bensin">Tambah</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@section('footer-script')
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<!-- <link href="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.css') }}" rel="stylesheet" type="text/css" />
<script src="{{ asset('admin-css/plugins/bower_components/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script> -->
<script type="text/javascript">
    
    var list_atasan = [];

    @foreach(get_atasan_langsung() as $item)
        list_atasan.push({id : {{ $item->id }}, value : '{{ $item->nik .' - '. $item->name.' - '. $item->job_rule }}',  });
    @endforeach
</script>
<script type="text/javascript">
    
    var validate_form = true;

    $(".autcomplete-atasan" ).autocomplete({
        source: list_atasan,
        minLength:0,
        select: function( event, ui ) {
            $( "input[name='atasan_user_id']" ).val(ui.item.id);
            
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

    general_function();

    jQuery('.datepicker').datepicker({
        format: 'yyyy-mm-dd',
    });
    
    $("#add_overtime").click(function(){

        var el = "";
        
        $("input[name=overtime_item]:checked").each(function(){
            el += '<input type="hidden" name="overtime[]" value="'+ $(this).val() +'" />';
        });

       //el += '<a class="btn btn-info btn-xs" onclick="info_overtime(this)"><i class="fa fa-info"></i></a>';

        general_el.parent().parent().find('.content_overtime').html(el);

        $("#modal_overtime").modal('hide');
    });

    $("#add_modal_bensin").click(function(){

        var cost = $('.modal-cost').val();

        general_el.parent().find("input[name='amount[]']").val(cost);   

        var tanggal     = $('.modal_tanggal_struk_bensin').val();
        var odo_from    = $('.modal_odo_from').val();
        var odo_to      = $('.modal_odo_to').val();
        var liter       = $('.modal_liter').val();
        var cost        = $('.modal_cost').val();

        var el = '<div class="bensin"><a class="btn btn-info btn-xs" onclick="info_bensin(this)"><i class="fa fa-info"></i></a><input type="hidden" name="bensin[tanggal][]" value="'+ tanggal +'" />';
            el += '<input type="hidden" name="bensin[odo_from][]" value="'+ odo_from +'" />';
            el += '<input type="hidden" name="bensin[odo_to][]" value="'+ odo_to +'" />';
            el += '<input type="hidden" name="bensin[liter][]" value="'+ liter +'" />';
            el += '<input type="hidden" name="bensin[cost][]" value="'+ cost +'" /></div>';

        general_el.parent().parent().find('.content_bensin').html(el);
        general_el.parent().parent().parent().parent().find("input[name='description[]']").val('Bensin');
        general_el.parent().parent().parent().parent().find("input[name='quantity[]']").val(liter);
        general_el.parent().parent().parent().parent().find("input[name='amount[]']").val(cost);

        $("#form_modal_bensin").trigger('reset');
        $("#modal_bensin").modal("hide");
    });

    function info_bensin(el)
    {
        $("#modal_bensin").modal('show');

        var el = $(el).parent();

        var tanggal = el.find("input[name='bensin[tanggal][]']").val();
        var odo_from = el.find("input[name='bensin[odo_from][]']").val();
        var odo_to = el.find("input[name='bensin[odo_to][]']").val();
        var liter = el.find("input[name='bensin[liter][]']").val();
        var cost = el.find("input[name='bensin[cost][]']").val();

        $('.modal_tanggal_struk_bensin').val(tanggal);
        $('.modal_odo_from').val(odo_from);
        $('.modal_odo_to').val(odo_to);
        $('.modal_liter').val(liter);
        $('.modal_cost').val(cost);

        general_el = el.parent().parent().parent().find("select[name='type[]']");
    }

    $('#submit_payment').click(function(){

        cek_form();
        
        if(!validate_form)
        {
            bootbox.alert('Form belum lengkap !');

            return false;
        }

        if($('.table-content-lembur tr').length == 0)
        {
            return false;
        }

        bootbox.confirm("Apakah anda ingin Proses Payment Request ini ?", function(result) {
            if(result)
            {
                $("#form_payment").submit();
            }
        }); 
    });

    var general_el;

    function general_function()
    {
        $("select[name='type[]']").on('change', function(){

            if($(this).val() == 'Overtime Transport')
            {
                $("#modal_overtime").modal("show");
            }else if($(this).val() == 'Bensin')
            {
                $("#modal_bensin").modal("show");
            }else {
                $(this).parent().parent().find('.bensin').remove();
            }
            
            general_el = $(this);

        });
    }

    $("#add").click(function(){

        var no = $('.table-content-lembur tr').length;

        var html = '<tr>';
            html += '<td>'+ (no+1) +'</td>';
            html += '<td><select name="type[]" class="form-control input"><option value=""> - none - </option><option>Parkir</option><option>Bensin</option><option>Tol</option><option>Overtime Transport</option><option>Others</option></select></td>';
            html += '<td class="description_td"><input type="text" class="form-control input" name="description[]"></td>';
            html += '<td><input type="number" name="quantity[]" class="form-control input" /></td>';
            html += '<td><input type="number" name="estimation_cost[]" class="form-control estimation input" /></td>';
            html += '<td><input type="number" name="amount[]" class="form-control amount input" /></td>';
            html += '<td><input type="number" name="amount_approved[]" class="form-control input" readonly="true" /></td>';
            html += '<td><input type="file" name="file_struk[]" class="form-control input input" /></td>';
            html += '<td><a class="btn btn-danger btn-xs" onclick="hapus_item(this)"><i class="fa fa-trash"></i> hapus</a></td>';
            html += '</tr>';

        $('.table-content-lembur').append(html);

        $('.estimation').on('input', function(){
            
            var total = 0;

            $('.estimation').each(function(){

                if($(this).val() != "")
                {
                    total += parseInt($(this).val());
                }
            });

            $('.total').html('Rp. '+ numberWithComma(total));
        });

        general_function();
    });

    function cek_form()
    {
        validate_form = true;
        $(".input").each(function(){
            if($(this).val() == "")
            {
                validate_form = false;
            }
        });   
    }

    function hapus_item(el)
    {   
        bootbox.confirm("Hapus data ini ?", function(res){
            if(res)
            {
                $(el).parent().parent().remove();
            }
        })
    }

</script>


@endsection
<!-- ============================================================== -->
<!-- End Page Content -->
<!-- ============================================================== -->
@endsection
