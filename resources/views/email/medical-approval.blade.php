@extends('email.general')

@section('content')
{!! $text !!}

<table>
	<thead>
		<tr>
			<th style="text-align: left;">Tanggal Pengajuan </th>
			<th style="text-align: left;"> : {{ date('d F Y', strtotime($data->tanggal_pengajuan)) }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Jenis Klaim </th>
			<th style="text-align: left;"> : {{ medical_jenis_claim_string($data->id) }}</th>
		</tr>
		<tr>
			<th style="text-align: left;">Jumlah</th>
			<th style="text-align: left;"> : {{ number_format(total_medical_nominal($data->id)) }}</th>
		</tr>
	</thead>
</table>
<br />	
<div class="modal-body" id="modal_content_history_approval">

	@if($data->hr_benefit_id != Null)
	<div class="panel-body">
		<div class="steamline" style="position: relative; border-left: 1px solid rgba(120,130,140,.13);margin-left: 20px;">
			<div class="sl-item" style="border-bottom: 1px solid rgba(120,130,140,.13);margin: 20px 0;">
				<div class="sl-left" style="background: transparent; float: left;margin-left: -20px;z-index: 1;width: 40px;line-height: 40px;text-align: center;height: 40px;border-radius: 100%;color: #fff;margin-right: 15px;">
					@if($data->is_approved_hr_benefit === NULL)
					<img src="{{ asset('images/info.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_approved_hr_benefit == 1)
					<img src="{{ asset('images/oke.png') }}" style="width: 48px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_approved_hr_benefit === 0)
					<img src="{{ asset('images/close.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
				</div>
				<div class="sl-right" style="padding-left: 50px;">
					<div>
						<strong>HR Benefit</strong> <br>
						<a href="#"> {{ $data->hr_benefit->nik }}  -  {{ $data->hr_benefit->name }}</a> 
					</div>
					@if($data->is_approved_hr_benefit !== NULL)
						<div class="desc">{{ date('d F Y', strtotime($data->hr_benefit_date)) }}<p></p></div>
					@endif
				</div>
			</div>
		</div>
	</div>
	@endif

	@if($data->manager_hr_id != Null)
	<div class="panel-body">
		<div class="steamline" style="position: relative; border-left: 1px solid rgba(120,130,140,.13);margin-left: 20px;">
			<div class="sl-item" style="border-bottom: 1px solid rgba(120,130,140,.13);margin: 20px 0;">
				<div class="sl-left" style="background: transparent; float: left;margin-left: -20px;z-index: 1;width: 40px;line-height: 40px;text-align: center;height: 40px;border-radius: 100%;color: #fff;margin-right: 15px;">
					@if($data->is_approved_manager_hr === NULL)
					<img src="{{ asset('images/info.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_approved_manager_hr == 1)
					<img src="{{ asset('images/oke.png') }}" style="width: 48px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_approved_manager_hr === 0)
					<img src="{{ asset('images/close.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
				</div>
				<div class="sl-right" style="padding-left: 50px;">
					<div>
						<strong>Manager HR OPR</strong> <br>
						<a href="#"> {{ $data->manager_hr->nik }} - {{ $data->manager_hr->name }}</a> 
					</div>
					@if($data->is_approved_manager_hr !== NULL)
						<div class="desc">{{ date('d F Y', strtotime($data->manager_hr_date)) }}<p></p></div>
					@endif
				</div>
			</div>
		</div>
	</div>
	@endif
	
	@if($approval_gm)
	<div class="panel-body">
		<div class="steamline" style="position: relative; border-left: 1px solid rgba(120,130,140,.13);margin-left: 20px;">
			<div class="sl-item" style="border-bottom: 1px solid rgba(120,130,140,.13);margin: 20px 0;">
				<div class="sl-left" style="background: transparent; float: left;margin-left: -20px;z-index: 1;width: 40px;line-height: 40px;text-align: center;height: 40px;border-radius: 100%;color: #fff;margin-right: 15px;">
					@if($data->is_approved_gm_hr === NULL)
					<img src="{{ asset('images/info.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_approved_gm_hr == 1)
					<img src="{{ asset('images/oke.png') }}" style="width: 48px;margin-left: -4px;margin-top: -12px;" />
					@endif
					@if($data->is_approved_gm_hr === 0)
					<img src="{{ asset('images/close.png') }}" style="width: 33px;margin-left: -4px;margin-top: -12px;" />
					@endif
				</div>
				<div class="sl-right" style="padding-left: 50px;">
					<div>
						<strong>GM HR</strong> <br>
						<a href="#"> $data->gm_hr->nik  -  $data->gm_hr->name </a> 
					</div>
					@if($data->is_approved_gm_hr !== NULL)
						<div class="desc">{{ date('d F Y', strtotime($data->gm_hr_date)) }}<p></p></div>
					@endif
				</div>
			</div>
		</div>
	</div>
	@endif
</div>
@endsection