<!DOCTYPE html>
<html>
<head>
	<title>{{ $data->name }} - {{ $data->nik }}</title>
	<style type="text/css">
		table {
			border-collapse: collapse;
    		border-spacing: 0;
		}
		table tr td {
			-border-bottom: 1px solid #666666;
			padding: 5px 0;
		}

		.table-border {
			width: 100%;
		}
		.table-border tr td, .table-border tr th{
			border-bottom: 1px solid #666666;
		}
	</style>
</head>
<body>
	<h3 style="margin-bottom:0;">{{ $data->name }}</h3>
	<h4 style="margin-top:0;">{{ $data->nik }}</h4>
	
	@if(!empty($data->foto))
		<img src="{{ public_path('storage/foto/'. $data->foto) }}" style="width: 200px; position: absolute; right: 0; top: 100px;">
	@endif

	<hr>
	<table>
		<tr>
			<td>LDAP</td>
			<td>: {{ $data->ldap }}</td>
		</tr>
		<tr>
			<td>Tempat Lahir</td>
			<td>: {{ $data->tempat_lahir }}</td>
		</tr>
		<tr>
			<td>Tanggal Lahir</td>
			<td>: {{ $data->tanggal_lahir }}</td>
		</tr>
		<tr>
			<td>Jenis Kelamin</td>
			<td>: {{ $data->jenis_kelamin }}</td>
		</tr>
		<tr>
			<td>Email</td>
			<td>: {{ $data->email }}</td>
		</tr>
		<tr>
			<td>Join Date</td>
			<td>: {{ $data->join_date }}</td>
		</tr>
		<tr>
			<td>Telepon</td>
			<td>: {{ $data->telepon }}</td>
		</tr>
		<tr>
			<td>Agama</td>
			<td>: {{ $data->agama }}</td>
		</tr>
		<tr>
			<td>Handphone</td>
			<td>: {{ $data->handphone }}</td>
		</tr>
		<tr>
			<td>KTP Number</td>
			<td>: {{ $data->ktp_number }}</td>
		</tr>
		<tr>
			<td>Passport Number</td>
			<td>: {{ $data->passport_number }}</td>
		</tr>
		<tr>
			<td>KK Number</td>
			<td>: {{ $data->kk_number }}</td>
		</tr>
		<tr>
			<td>NPWM Number</td>
			<td>: {{ $data->npwp_number }}</td>
		</tr>
		<tr>
			<td>Jamsostek Number</td>
			<td>: {{ $data->jamsostek_number }}</td>
		</tr>
		<tr>
			<td>NPWM Number</td>
			<td>: {{ $data->npwp_number }}</td>
		</tr>
		<tr>
			<td>Provinsi</td>
			<td>: {{ (isset($data->provinsi->nama) ? $data->provinsi->nama : '') }}</td>
		</tr>
		<tr>
			<td>Kabupaten</td>
			<td>: {{ (isset($data->kabupaten->nama) ? $data->kabupaten->nama : '') }}</td>
		</tr>
		<tr>
			<td>Kecamatan</td>
			<td>: {{ (isset($data->kecamatan->nama) ? $data->kecamatan->nama : '') }}</td>
		</tr>
		<tr>
			<td>Kelurahan</td>
			<td>: {{ (isset($data->kelurahan->nama) ? $data->kelurahan->nama : '') }}</td>
		</tr>
		<tr>
			<td>Division</td>
			<td>: {{ (isset($data->division->name) ? $data->division->name : '') }}</td>
		</tr>
		<tr>
			<td>Department</td>
			<td>: {{ (isset($data->department->name) ? $data->department->name : '') }}</td>
		</tr>
		<tr>
			<td>Section</td>
			<td>: {{ (isset($data->section->name) ? $data->section->name : '') }}</td>
		</tr>
		<tr>
			<td>Cabang</td>
			<td>: {{ (isset($data->cabang->name) ? $data->cabang->name : '') }}</td>
		</tr>
		<tr>
			<td>ID Address</td>
			<td>: {{ (isset($data->id_address) ? $data->id_address : '') }}</td>
		</tr>
		<tr>
			<td>ID City</td>
			<td>: {{ (isset($data->id_city) ? $data->id_city : '') }}</td>
		</tr>
		<tr>
			<td>ID Zip Code</td>
			<td>: {{ (isset($data->id_zip_code) ? $data->id_zip_code : '') }}</td>
		</tr>
		<tr>
			<td>Blood Type</td>
			<td>: {{ (isset($data->blood_type) ? $data->blood_type : '') }}</td>
		</tr>
		<tr>
			<td>Branch Type</td>
			<td>: {{ (isset($data->branch_type) ? $data->branch_type : '') }}</td>
		</tr>
		<tr>
			<td>Bank</td>
			<td>: {{ (isset($data->bank->name) ? $data->bank->name : '') }}</td>
		</tr>
		<tr>
			<td>Nama Pemilik Rekening</td>
			<td>: {{ (isset($data->nama_rekening) ? $data->nama_rekening : '') }}</td>
		</tr>
		<tr>
			<td>Nomor Rekening</td>
			<td>: {{ (isset($data->nomor_rekening) ? $data->nomor_rekening : '') }}</td>
		</tr>
		<tr>
			<td>Kantor Cabang Bank</td>
			<td>: {{ (isset($data->cabang) ? $data->cabang : '') }}</td>
		</tr>
	</table>
	<br />
	<h4 style="background: #eee; padding: 10px; margin-bottom: 0; ">Dependent</h4>
	<hr>
	<br />
	<table class="table-border">
		<tr>
			<th>No</th>
			<th>Nama</th>
			<th>Hubungan</th>
			<th>Tempat Lahir</th>
			<th>Tanggal Lahir</th>
			<th>Jenjang Pendidikan</th>
			<th>Pekerjaan</th>
		</tr>
		@foreach($data->userFamily as $no => $i)
		<tr>
			<td>{{ $no + 1 }}</td>
			<td>{{ $i->nama }}</td>
			<td>{{ $i->hubungan }}</td>
			<td>{{ $i->tempat_lahir }}</td>
			<td>{{ $i->tanggal_lahir }}</td>
			<td>{{ $i->jenjang_pendidikan }}</td>
			<td>{{ $i->pekerjaan }}</td>
		</tr>
		@endforeach
	</table>

	<br />
	<h4 style="background: #eee; padding: 10px; margin-bottom: 0; ">Education</h4>
	<hr>
	<br />
	<table class="table-border">
		<tr>
			<th>No</th>
			<th>Pendidikan</th>
			<th>Tahun Awal</th>
			<th>Tahun Akhir</th>
			<th>Nama Sekolah / Fakultas</th>
			<th>Jurusan</th>
			<th>Nilai</th>
			<th>City</th>
		</tr>
		@foreach($data->userEducation as $no => $i)
		<tr>
			<td>{{ $no + 1 }}</td>
			<td>{{ $i->pendidikan }}</td>
			<td>{{ $i->tahun_awal }}</td>
			<td>{{ $i->tahun_akhir }}</td>
			<td>{{ $i->fakultas }}</td>
			<td>{{ $i->jurusan }}</td>
			<td>{{ $i->nilai }}</td>
			<td>{{ $i->kota }}</td>
		</tr>
		@endforeach
	</table>
	<br />
	<h4 style="background: #eee; padding: 10px; margin-bottom: 0; ">Inventaris Mobil</h4>
	<hr>
	<br />
	<table class="table-border">
		<tr>
			<th>#</th>
			<th>Tipe Mobil</th>
			<th>Tahun</th>
			<th>No Polisi</th>
			<th>Status Mobil</th>
		</tr>
		@foreach($data->inventaris_mobil as $no => $m)
		<tr>
			<td>{{ $no+1 }}</td>
			<td>{{ $m->tipe_mobil }}</td>
			<td>{{ $m->tahun }}</td>
			<td>{{ $m->no_polisi }}</td>
			<td>{{ $m->status_mobil }}</td>
		</tr>		
		@endforeach
	</table>

	<br />
	<h4 style="background: #eee; padding: 10px; margin-bottom: 0; ">Inventaris Lainnya</h4>
	<hr>
	<br />
	<table class="table-border">
		<tr>
			<th>#</th>
			<th>Jenis Invetaris</th>
			<th>Keterangan</th>
		</tr>
		@foreach($data->inventaris as $no => $m)
		<tr>
			<td>{{ $no+1 }}</td>
			<td>{{ $m->jenis }}</td>
			<td>{{ $m->description }}</td>
		</tr>		
		@endforeach
	</table>

	<br />
	<h4 style="background: #eee; padding: 10px; margin-bottom: 0; ">Cuti</h4>
	<hr>
	<br />
	<table class="table-border">
		<tr>
			<th>#</th>
			<th>Jenis Cuti</th>
			<th>Kuota</th>
		</tr>
		@foreach($data->cuti as $no => $m)
		<tr>
			<td>{{ $no+1 }}</td>
			<td>{{ $m->jenis_cuti }}</td>
			<td>{{ $m->kuota }}</td>
		</tr>		
		@endforeach
	</table>
</body>
</html>