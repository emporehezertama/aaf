@extends('email.general')

@section('content')
{!! $text !!}

<table>
	<thead>
		<tr>
			<th style="text-align: left;">Tanggal Pengajuan </th>
			<th style="text-align: left;"> : {{ date('d F Y', strtotime($data->created_at)) }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Tujuan </th>
			<th style="text-align: left;"> : {{ $data->tujuan }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Jumlah </th>
			<th style="text-align: left;"> : {{ sum_payment_request_price($data->id) }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Jenis Transaksi </th>
			<th style="text-align: left;"> : {{ $data->transaction_type }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Cara Pembayaran </th>
			<th style="text-align: left;"> : {{ $data->payment_method }}</th>
		</tr>
	</thead>
</table>
<br />	

<div class="modal-body" id="modal_content_history_approval">
	@if($data->proposal_approval_id != Null)
	<div class="panel-body">
		<div class="steamline" style="position: relative; border-left: 1px solid rgba(120,130,140,.13);margin-left: 20px;">
			<div class="sl-item" style="border-bottom: 1px solid rgba(120,130,140,.13);margin: 20px 0;">
				<div class="sl-left" style="background: transparent; float: left;margin-left: -20px;z-index: 1;width: 40px;line-height: 40px;text-align: center;height: 40px;border-radius: 100%;color: #fff;margin-right: 15px;">
					@if($data->is_proposal_approved === NULL)
					<img src="{{ asset('images/info.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_proposal_approved == 1)
					<img src="{{ asset('images/oke.png') }}" style="width: 48px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_proposal_approved === 0)
					<img src="{{ asset('images/close.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
				</div>
				
				<div class="sl-right" style="padding-left: 50px;">
					<div>
						<strong>Proposal Approval</strong> <br>
						<a href="#">{{ $data->proposal_approval->nik }} - {{ $data->proposal_approval->name }}</a> 
					</div>
					@if($data->is_proposal_approved !== NULL)
						<div class="desc">{{ date('d F Y', strtotime($data->proposal_approval_date)) }}<p></p></div>
					@endif
				</div>
				
			</div>
		</div>
	</div>
	@endif


	@if($data->proposal_verification_id != Null)
	<div class="panel-body">
		<div class="steamline" style="position: relative; border-left: 1px solid rgba(120,130,140,.13);margin-left: 20px;">
			<div class="sl-item" style="border-bottom: 1px solid rgba(120,130,140,.13);margin: 20px 0;">
				<div class="sl-left" style="background: transparent; float: left;margin-left: -20px;z-index: 1;width: 40px;line-height: 40px;text-align: center;height: 40px;border-radius: 100%;color: #fff;margin-right: 15px;">
					@if($data->is_proposal_verification_approved === NULL)
					<img src="{{ asset('images/info.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_proposal_verification_approved == 1)
					<img src="{{ asset('images/oke.png') }}" style="width: 48px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_proposal_verification_approved === 0)
					<img src="{{ asset('images/close.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
				</div>
				
				<div class="sl-right" style="padding-left: 50px;">
					<div>
						<strong>Proposal Verification</strong> <br>
						<a href="#">{{ $data->proposal_verification->nik }} - {{ $data->proposal_verification->name }}</a> 
					</div>
					@if($data->is_proposal_verification_approved !== NULL)
						<div class="desc">{{ date('d F Y', strtotime($data->proposal_verification_date)) }}<p></p></div>
					@endif
				</div>
				
			</div>
		</div>
	</div>
	@endif

	@if($data->payment_approval_id != Null)
	<div class="panel-body">
		<div class="steamline" style="position: relative; border-left: 1px solid rgba(120,130,140,.13);margin-left: 20px;">
			<div class="sl-item" style="border-bottom: 1px solid rgba(120,130,140,.13);margin: 20px 0;">
				<div class="sl-left" style="background: transparent; float: left;margin-left: -20px;z-index: 1;width: 40px;line-height: 40px;text-align: center;height: 40px;border-radius: 100%;color: #fff;margin-right: 15px;">
					@if($data->is_payment_approved === NULL)
					<img src="{{ asset('images/info.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_payment_approved == 1)
					<img src="{{ asset('images/oke.png') }}" style="width: 48px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_payment_approved === 0)
					<img src="{{ asset('images/close.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
				</div>
				
				<div class="sl-right" style="padding-left: 50px;">
					<div>
						<strong>Payment Approval</strong> <br>
						<a href="#">{{ $data->payment_approval->nik }} - {{ $data->payment_approval->name }}</a> 
					</div>
					@if($data->is_payment_approved !== NULL)
						<div class="desc">{{ date('d F Y', strtotime($data->payment_approval_date)) }}<p></p></div>
					@endif
				</div>
				
			</div>
		</div>
	</div>
	@endif
@endsection