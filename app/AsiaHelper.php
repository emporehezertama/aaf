<?php


/**
 * [jenis_claim_medical description]
 * @param  string $key [description]
 * @return [type]      [description]
 */
function jenis_claim_medical($key="")
{
	$arr = ['RJ' => 'RJ (Rawat Jalan)', 'RI' => 'RI (Rawat Inap)', 'MA' => 'MA (Melahirkan)','Frame' => 'Frame', 'Glasses' => 'Glasses'];
	if(!empty($key))
	{
		return $arr[$key];
	}
	else
	{
		return $arr;
	}
}

/**
 * [total_medical_nominal description]
 * @return [type] [description]
 */
function total_medical_nominal($id)
{
	$data = \App\MedicalReimbursementForm::where('medical_reimbursement_id', $id)->get();
	$nominal = 0;

	foreach($data as $item)
	{
		$nominal  += $item->jumlah;
	}

	return $nominal;
}

/**
 * [jenis_claim_strint description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function medical_jenis_claim_string($id)
{
	$data = \App\MedicalReimbursementForm::where('medical_reimbursement_id', $id)->get();
	$string = "";

	foreach($data as $item)
	{
		$string  .= jenis_claim_medical($item->jenis_klaim) .' ,';
	}

	return substr($string, 0, -1);
}

/**
 * [cuti_personalia description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function count_cuti_approved_personalia()
{
	$data = \App\CutiKaryawan::whereNull('is_approved_personalia')->whereNotNull('is_approved_atasan')->where('status', 1)->count();

	return $data;
}

/**
 * [is_image description]
 * @return boolean [description]
 */
function is_image($url)
{
	$allowedMimeTypes = ['image/jpeg','image/gif','image/png','image/bmp','image/svg+xml'];
	$contentType = mime_content_type($url);
	
	if(in_array($contentType, $allowedMimeTypes))
	{
	  return true;
	}
	else
	{
		return false;
	}
}

/**
 * [sum_lembur_jam description]
 * @param  [type] $hours [description]
 * @return [type]        [description]
 */
function sum_lembur_jam($hours)
{
	$i = 0;
    foreach ($hours as $time) {
    	
    	$time = str_replace(' ', '', $time);

        sscanf($time, '%d:%d', $hour, $min);
        $i += $hour * 60 + $min;
    }
    if ($h = floor($i / 60)) {
        $i %= 60;
    }
    return sprintf('%02d:%02d', $h, $i);
}

/**
 * [cek_is_up_manager description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function skip_is_down_manager($id)
{
	$skip_gm_hr = ['Staff', 'Head','Supervisor'];
     
	$data = \App\User::where('id', $id)->first();

    if(isset($data->organisasiposition->name))
    {
        if(in_array($data->organisasiposition->name, $skip_gm_hr)){

            return  'no';
        }
        else
        {
        	return 'yes';
        }
    }
    else
    {
    	return 'no';
    }
}

/**
 * [count_approval_training description]
 * @param  string $status [description]
 * @return [type]         [description]
 */
function count_approval_training($status='null')
{
	// cek jenis user
    $approval = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','training')->first();
        
    if($approval)
    {
    	if($status == 'null')
    	{
		    if($approval->nama_approval == 'HRD')
		    {
		    	return \App\Training::where('is_approved_atasan', 1)->whereNull('approved_hrd')->count();
		    }
		    if($approval->nama_approval == 'Finance')
		    {
		    	return \App\Training::where('approved_hrd', 1)->whereNull('approved_finance')->count();
	    	}
	    }

	    if($status == 'all')
    	{
		    return \App\Training::count();
	    }
    }
}

/**
 * [count_approval_medical description]
 * @param  string $status [description]
 * @return [type]         [description]
 */
function count_approval_medical_karyawan($status='all')
{
	// cek jenis user
    $approval = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','medical')->first();
    if($approval)
    {
    	if($status == 'null')
    	{
	        if($approval->nama_approval =='HR Benefit')
	        {
	            return \App\MedicalReimbursement::whereNull('is_approved_hr_benefit')->count();
	        }

	        if($approval->nama_approval =='Manager HR')
	        {
				return \App\MedicalReimbursement::where('is_approved_hr_benefit', '1')
													->whereNull('is_approved_manager_hr')->count();
	        }

	         if($approval->nama_approval =='GM HR')
	        {
				return \App\MedicalReimbursement::join('users', 'medical_reimbursement.user_id', '=', 'users.id')
													->join('organisasi_position', 'users.organisasi_position', '=', 'organisasi_position.id')
													->whereNotIn('organisasi_position.name', ['Staff', 'Supervisor', 'Head'])
													->where('medical_reimbursement.is_approved_manager_hr', '1')
													->whereNull('medical_reimbursement.is_approved_gm_hr')->count();
	        }
	    }

	    if($status == 'all')
    	{
	        if($approval->nama_approval =='HR Benefit')
	        {
	            return \App\MedicalReimbursement::count();
	        }

	        if($approval->nama_approval =='Manager HR')
	        {
	            return \App\MedicalReimbursement::count();
	        }

	         if($approval->nama_approval =='GM HR')
	        {
	            return \App\MedicalReimbursement::count();
	        }
	    }

    }
}

/**
 * [status_approval_payment_request description]
 * @param  string $status [description]
 * @return [type]         [description]
 */
function count_approval_payment_request($status='all')
{
	// cek approval
   	$approval = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','payment_request')->first();

   	if($approval)
   	{
   		if($approval->nama_approval =='Approval')
        {
            return \App\PaymentRequest::whereNull('is_proposal_approved')->count();
        }
        
        if($approval->nama_approval =='Verification')
        {
            return \App\PaymentRequest::whereNull('is_proposal_verification_approved')->count();
        }

        if($approval->nama_approval =='Payment')
        {
            return \App\PaymentRequest::whereNull('is_payment_approved')->count();
        }
	}
} 

/**
 * [get_cuti_terpakai description]
 * @param  [type] $cuti_id [description]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function get_kuota_cuti($cuti_id, $user_id)
{ 
	$cuti = \App\UserCuti::where('user_id', $user_id)->where('cuti_id', $cuti_id)->first();

	if($cuti)
		return $cuti->kuota;
	else
	{
		$cuti = \App\Cuti::where('id', $cuti_id)->first();

		return $cuti->kuota;
	}
}

/**
 * @param  [type]
 * @param  [type]
 * @param  [type]
 * @return [type]
 */
function get_cuti_user($cuti_id, $user_id, $field)
{
	$cuti = \App\UserCuti::where('user_id', $user_id)->where('cuti_id', $cuti_id)->first();

	if($cuti){
		if(isset($cuti->$field))
		{
			return $cuti->$field;
		}
	}
	else
		return 0;
}

/**
 * [cek_count_exit_admin description]
 * @return [type] [description]
 */
function cek_count_training_admin()
{
	$total = \App\Training::where('status', 1)->orWhere('status_actual_bill', 2)->count();

	return $total;
}

/**
 * [cek_count_exit_admin description]
 * @return [type] [description]
 */
function cek_count_exit_admin()
{
	$total = \App\ExitInterview::where('status', 1)->count();

	return $total;
}

/**
 * [hari_libur description]
 * @return [type] [description]
 */
function hari_libur()
{
	return \App\LiburNasional::get();
}

/**
 * [cek_count_cuti_admin description]
 * @return [type] [description]
 */
function cek_count_cuti_admin()
{
	$total = \App\CutiKaryawan::where('status', 1)->count();

	return $total;
}

/**
 * [total_payment_request description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function sum_payment_request_price($id)
{
	$payment = \App\PaymentRequestForm::where('payment_request_id', $id)->get();
	$total  = 0 ;

	foreach($payment as $i)
	{
		$total += !empty($i->amount) ? $i->amount : $i->estimation_cost;
	}	

	return $total;
}

/**
 * [get_cuti_terpakai description]
 * @param  [type] $cuti_id [description]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function get_cuti_terpakai($cuti_id, $user_id)
{
	$cuti = \App\UserCuti::where('user_id', $user_id)->where('cuti_id', $cuti_id)->first();

	if($cuti)
		return $cuti->cuti_terpakai;
	else
		return 0;
}

/**
 * [plafond_perjalanan_dinas description]
 * @return [type] [description]
 */
function plafond_perjalanan_dinas($name)
{
	return \App\PlafondDinas::where('organisasi_position_text', 'LIKE', '%'. strtoupper($name) .'%')->first();
}

/**
 * [get_backup_cuti description]
 * @return [type] [description]
 */
function get_backup_cuti()
{
	$user = \Auth::user();

	if($user->branch_type == 'BRANCH')
	{
		$karyawan = \App\User::where('cabang_id', $user->cabang_id)->where('id', '<>', $user->id)->get();
	}
	else
	{
		$karyawan = \App\User::where('division_id', $user->division_id)->where('id', '<>', $user->id)->get();
	}

	return $karyawan;
}

/**
 * [list_user_cuti description]
 * @return [type] [description]
 */
function list_user_cuti()
{
	return \App\Cuti::orderBy('jenis_cuti','ASC')->get();
}

/**
 * [jenis_perjalanan_dinas description]
 * @return [type] [description]
 */
function jenis_perjalanan_dinas()
{
	return ['Training', 'Management Meeting','Hearing Meeting','Regional/Division Meeting','Assessment','Branch Visit', 'Others'];
}

/**
 * [cek_training_approval_user description]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function cek_training_approval_user($user_id)
{
	$count =  \App\Training::where('approved_atasan_id', $user_id)->count();

	return $count;
}

/**
 * [cek_training_approval_user description]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function count_training_approval_atasan($type='all')
{
	$count = 0;

	if($type == 'all')
	{
		$count =  \App\Training::where('approved_atasan_id', \Auth::user()->id)->count();
	}

	if($type =='null')
	{
		$count =  \App\Training::where('approved_atasan_id', \Auth::user()->id)->whereNull('is_approved_atasan')->count();

		$count +=  \App\Training::where('approved_atasan_id', \Auth::user()->id)->where('status_actual_bill', 2)->whereNull('is_approve_atasan_actual_bill')->count();
	}

	return $count;
}

/**
 * [cek_cuti_approval_user description]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function cek_exit_approval_user($user_id)
{
	return \App\ExitInterview::where('approved_atasan_id', $user_id)->count();
}


/**
 * [cek_cuti_approval_user description]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function count_exit_approval_user($user_id)
{
	return \App\ExitInterview::where('approved_atasan_id', $user_id)->where('is_approved_atasan', '<>', 1)->count();
}

/**
 * [cek_medical_approval_atasan description]
 * @param  [type] $user_id [description]
 * @param  string $type    [description]
 * @return [type]          [description]
 */
function count_medical_approval_atasan($type ='all')
{	
	if($type == 'all')
	{
		return \App\MedicalReimbursement::where('approved_atasan_id', \Auth::user()->id)->count();		
	}

	if($type == 'null')
	{
		return \App\MedicalReimbursement::where('approved_atasan_id', \Auth::user()->id)->whereNull('is_approved_atasan')->count();	
	}	
}

/**
 * [cek_cuti_approval_user description]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function cek_cuti_approval_user($user_id, $status='all')
{
	if($status == 'all')
	{
		return \App\CutiKaryawan::where('approved_atasan_id', $user_id)->count();
	}

	if($status == 'null')
	{
		return \App\CutiKaryawan::where('approved_atasan_id', $user_id)->whereNull('is_approved_atasan')->count();
	}
}

/**
 * [cek_cuti_approval_user description]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function cek_overtime_approval_user_count($user_id)
{
	$data =  \App\OvertimeSheet::where('approved_atasan_id', $user_id)->get();
	$count = 0;
	foreach($data as $i)
	{	
		if($i->is_approved_atasan == "") 
		{
			$count++;
		}
	}
	
	return $count;
}

/**
 * [cek_overtime_approval_user_count description]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function cek_overtime_approval_user_2()
{
    $approval = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','overtime')->first();
    $data = \App\OvertimeSheet::orderBy('id', 'DESC')->get();

    $count = 0;
    foreach($data as $item)
    {
    	if($approval)
    	{
	    	if($approval->nama_approval == 'Manager HR')
	    	{
			    if($item->is_hr_manager == Null && $item->is_hr_benefit_approved == "1")
			    {
					$count++;
			    }
			}

			if($approval->nama_approval == 'HR Operation')
			{
			    if($item->is_hr_benefit_approved == Null && $item->is_approved_atasan == "1")
		    	{
		    		$count++;
		    	}
		    }
		}
    }

    return $count;
}

/**
 * [cek_cuti_approval_user description]
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
function cek_overtime_approval_user($user_id)
{
	return \App\OvertimeSheet::where('approved_atasan_id', $user_id)->count();
}

/**
 * [get_atasan description]
 * @return [type] [description]
 */
function get_atasan_langsung()
{
	// cek sebagai branch / tidak
	$user = \Auth::user();
	$karyawan = [];

	if(strtoupper($user->branch_type) == 'BRANCH')
	{
		if(isset($user->organisasiposition->name))
		{
			if($user->organisasiposition->name == 'Staff')
			{
				$res = \App\User::where('department_id', $user->department_id)
								->join('organisasi_position', function($join){
									$join->on('organisasi_position.id', '=', 'users.organisasi_position');
								})
								->where('cabang_id', $user->cabang_id)
								->where('users.id', '<>', $user->id)
								->where(function($query){
									$query->where('organisasi_position.name', 'LIKE', '%Head%')
										->orWhere('organisasi_position.name', 'LIKE', '%Branch Manager%');
								})
								->select('users.*', 'organisasi_position.name as job_rule')
								->get();
				$karyawan = new stdClass; $no=0;
				foreach($res as $k => $item)
				{
					$karyawan->$k = $item;
					$no++;
				}

				if($no==0)
				{
					$res = \App\User::join('organisasi_position', function($join){
									$join->on('organisasi_position.id', '=', 'users.organisasi_position');
								})
								->where('cabang_id', $user->cabang_id)
								->where('users.id', '<>', $user->id)
								->where(function($query){
									$query->where('organisasi_position.name', 'LIKE', '%Head%')
										->orWhere('organisasi_position.name', 'LIKE', '%Branch Manager%');
								})
								->select('users.*', 'organisasi_position.name as job_rule')
								->get();
					$karyawan = new stdClass;
					foreach($res as $k => $item)
					{
						$karyawan->$k = $item;
					}
				}
			}

			// jika sabagai Head
			if($user->organisasiposition->name  == 'Head')
			{
				$karyawan = \App\User::join('organisasi_position', function($join){
									$join->on('organisasi_position.id', '=', 'users.organisasi_position');
								})
								->where('cabang_id', $user->cabang_id)
								->where('users.id', '<>', $user->id)
								->where('organisasi_position.name', 'LIKE', '%Branch Manager%')
								->select('users.nik', 'users.id', 'users.name','organisasi_position.name as job_rule')
								->get();
			}
			// jika yang mengajukan Branch Manager
			if($user->organisasiposition->name  == 'Branch Manager')
			{
				$karyawan = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
								//->where('users.division_id', $user->division_id)
								->where('users.id', '<>', $user->id)
								->where(function($query){
									$query->where('organisasi_position.name', 'LIKE', '%General Manager%')
											->orWhere('organisasi_position.name', 'LIKE', '%Area Manager%');
								})
								->select('users.*', 'organisasi_position.name as job_rule')
								->get();
			}

			/**
			 * Jika sebagai pic cabang maka role approval sama dengan Branch Manager
			 * 
			 */
			if($user->is_pic_cabang == 1)
			{
				$karyawan = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
								//->where('users.division_id', $user->division_id)
								->where('users.id', '<>', $user->id)
								->where(function($query){
									$query->where('organisasi_position.name', 'LIKE', '%General Manager%')
											->orWhere('organisasi_position.name', 'LIKE', '%Area Manager%');
								})
								->select('users.*', 'organisasi_position.name as job_rule')
								->get();
			}

			// jika yang mengajukan Branch Manager
			if($user->organisasiposition->name  == 'Head' and $user->organisasi_job_role == 'Manager Outlet')
			{
				$karyawan = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
								//->where('users.division_id', $user->division_id)
								->where('users.id', '<>', $user->id)
								->where(function($query){
									$query->where('organisasi_position.name', 'LIKE', '%General Manager%')
											->orWhere('organisasi_position.name', 'LIKE', '%Area Manager%');
								})
								->select('users.*', 'organisasi_position.name as job_rule')
								->get();
			}

			// jika yang mengajukan Branch Manager
			if($user->organisasiposition->name  == 'General Manager')
			{
				$karyawan = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
								//->where('users.division_id', $user->division_id)
								->where('users.id', '<>', $user->id)
								->where(function($query){
									$query->where('organisasi_position.name','LIKE', '%Director%')
											->orWhere('organisasi_position.name', 'LIKE', '%Expatriat%');
								})
								->select('users.*', 'organisasi_position.name as job_rule')
								->get()
								;
			}
		}
	}
	else
	{
		if(isset($user->organisasiposition->name))
		{
			if($user->organisasiposition->name == 'Staff')
			{
				// mencari di department
				$res = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
								->where('users.id', '<>', $user->id)
								->where(function($query){
									$query->where('organisasi_position.name', 'LIKE', '%Supervisor%')
											->orWhere('organisasi_position.name', 'LIKE', '%Manager%')
											->orWhere('organisasi_position.name', 'LIKE', '%Senior Manager%');
								})
								->where('users.department_id', $user->department_id)
								->select('users.*', 'organisasi_position.name as job_rule')
								->get();

				$karyawan = new stdClass; $no=0;
				foreach($res as $k => $item)
				{
					$karyawan->$k = $item; $no++;
				}


				if($no == 0)
				{
					// mencari di division
					$res = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
								->where('users.id', '<>', $user->id)
								->where(function($query){
									$query->where('organisasi_position.name', 'LIKE', '%Supervisor%')
											->orWhere('organisasi_position.name','LIKE', '%Manager%')
											->orWhere('organisasi_position.name', 'LIKE', '%Senior Manager%');
								})
								->where('users.division_id', $user->division_id)
								->select('users.*', 'organisasi_position.name as job_rule')
								->get();

					$karyawan = new stdClass; $no=0;
					foreach($res as $k => $item)
					{
						$karyawan->$k = $item; $no++;
					}
				}

				// mencari bukan berdasarkan divisi atau department
				if($no == 0)
				{
					// mencari di division
					$res = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
								->where('users.id', '<>', $user->id)
								->where('organisasi_position.name', '<>', 'Staff')
								->select('users.*', 'organisasi_position.name as job_rule')
								->get();

					$karyawan = new stdClass; $no=0;
					foreach($res as $k => $item)
					{
						$karyawan->$k = $item; $no++;
					}

				}
			}

			// Supervisor / Head
			if($user->organisasiposition->name == 'Supervisor' || $user->organisasiposition->name == 'Head')
			{
				// mencari di division
				$res = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
							->where('users.id', '<>', $user->id)
							->where(function($query){
								$query->orWhere('organisasi_position.name', 'LIKE', '%Manager')
										->orWhere('organisasi_position.name', 'LIKE', '%Senior Manager%');
							})
							->where('users.division_id', $user->division_id)
							->select('users.*', 'organisasi_position.name as job_rule')
							->get();

				$karyawan = new stdClass; $no=0;
				foreach($res as $k => $item)
				{
					$karyawan->$k = $item; $no++;
				}
			}

			// Jika manager
			if($user->organisasiposition->name == 'Manager')
			{
				$res = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
							->where('users.id', '<>', $user->id)
							->where(function($query){
								$query->orWhere('organisasi_position.name', 'LIKE', '%Director%')
										->orWhere('organisasi_position.name', 'LIKE', '%Expatriat%')
										->orWhere('organisasi_position.name', 'LIKE', '%General Manager%')
										->orWhere('organisasi_position.name', 'LIKE', '%Senior Manager%')
										;
							})
							->select('users.*', 'organisasi_position.name as job_rule')
							->get();

				$karyawan = new stdClass; $no=0;
				foreach($res as $k => $item)
				{
					$karyawan->$k = $item; $no++;
				}
			}

			// Manager, Sales Manager, Area Manager
			if($user->organisasiposition->name == 'Area Manager' || $user->organisasiposition->name == 'Sales Manager' )
			{
				
				$res = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
								->where('users.id', '<>', $user->id)
								->where('users.department_id', $user->department_id)
								->where('organisasi_position.name', 'LIKE', '%Senior Manager%')
								->select('users.*', 'organisasi_position.name as job_rule')
								->get();
								
				$karyawan = new stdClass;$no=0;
				foreach($res as $k => $item)
				{
					$no++;
					$karyawan->$k = $item;
				}

				if($no ==0)
				{
					$res = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
									->where('users.id', '<>', $user->id)
									->where('users.division_id', $user->division_id)
									->where(function($query){
											$query->orWhere('organisasi_position.name', 'LIKE', '%General Manager%')
													->orWhere('organisasi_position.name', 'LIKE', '%Senior Manager%');
										})
									->select('users.*', 'organisasi_position.name as job_rule')
									->get();
									
					$karyawan = new stdClass;$no=0;
					foreach($res as $k => $item)
					{
						$no++;
						$karyawan->$k = $item;
					}
				}
			}

			// General Manager / Senior Manager
			if($user->organisasiposition->name =='Senior Manager')
			{
				$res = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
								->where(function($query){
									$query->orWhere('organisasi_position.name', 'LIKE', '%General Manager%')
											->orWhere('organisasi_position.name', 'LIKE', '%Director%')
											->orWhere('organisasi_position.name', 'LIKE', '%Expatriat%');
								})
								->select('users.*', 'organisasi_position.name as job_rule')
								->get();
				$karyawan = new stdClass; $no=0;

				foreach($res as $k => $item)
				{
					$karyawan->$k = $item;
					$no++;
				}
			}

			// General Manager / Senior Manager
			if($user->organisasiposition->name == 'General Manager')
			{
				$res = \App\User::join('organisasi_position', 'organisasi_position.id', '=', 'users.organisasi_position')
								->where(function($query){
									$query->orWhere('organisasi_position.name', 'LIKE', '%Director%')
											->orWhere('organisasi_position.name', 'LIKE', '%Expatriat%');
								})
								->select('users.*', 'organisasi_position.name as job_rule')
								->get();
				$karyawan = new stdClass; $no=0;

				foreach($res as $k => $item)
				{
					$karyawan->$k = $item;
					$no++;
				}
			}
		}
	}

	return $karyawan;
}

/**
 * [tree_organisasi description]
 * @return [type] [description]
 */
function tree_atasan_organisasi()
{
	return ['Head', 'Supervisor','Manager', 'Branch Manager', 'Senior Advisor', 'Senior Manager', 'General Manager', 'Director'];
}


/**
 * [get_organisasi_position_group description]
 * @return [type] [description]
 */
function get_organisasi_position_group()
{
	return \App\OrganisasiPosition::groupBy('name')->get();
}

/**
 * [get_organisasi_position description]
 * @param  string $unit_id [description]
 * @return [type]          [description]
 */
function get_organisasi_position($unit_id = "")
{
	if($unit_id != "")
		return \App\OrganisasiPosition::where('organisasi_unit_id', $unit_id)->orderBy('name', 'ASC')->get();
	else
		return \App\OrganisasiPosition::orderBy('name', 'ASC')->get();

}
/**
 * [get_organisasi_unit description]
 * @param  string $department_id [description]
 * @return [type]                [description]
 */
function get_organisasi_unit($department_id = "")
{
	if(!empty($department_id))
		return \App\OrganisasiUnit::where('organisasi_department_id', $department_id)->get();
	else
		return \App\OrganisasiUnit::all();
}

/**
 * [get_organisasi_division description]
 * @return [type] [description]
 */
function get_organisasi_department($division_id = 0)
{
	if(empty($division_id))
		return \App\OrganisasiDepartment::orderBy('name', 'ASC')->get();
	else
		return \App\OrganisasiDepartment::where('organisasi_division_id', $division_id)->orderBy('name', 'ASC')->get();
}

/**
 * [get_organisasi_division description]
 * @return [type] [description]
 */
function get_organisasi_division()
{
	return \App\OrganisasiDivision::orderBy('name', 'ASC')->get();
}

/**
 * [list_hari_libur description]
 * @return [type] [description]
 */
function list_hari_libur()
{
	return \App\LiburNasional::all();
}

/**
 * [get_head_branch description]
 * @return [type] [description]
 */
function get_head_branch()
{
	return \App\BranchHead::all();
}

/**
 * [get_head_branch description]
 * @return [type] [description]
 */
function get_staff_branch()
{
	return \App\BranchStaff::all();
}

/**
 * [cek_approval description]
 * @param  [type] $table [description]
 * @return [type]        [description]
 */
function cek_approval($table)
{
	$cek = DB::table($table)->where('status', 1)->where('user_id', \Auth::user()->id)->count();

	if($cek >= 1)
		return false;
	else
		return true;
}

/**
 * [get_master_cuti description]
 * @return [type] [description]
 */
function get_master_cuti()
{
	return \App\Cuti::all();
}

/**
 * [position_karyawan description]
 * @return [type] [description]
 */
function position_structure()
{
	return ['Staff', 'SPV', 'Head', 'General Manager', 'Manager'];
}

if (! function_exists('d')) {
    /**
     * Dump the passed variables.
     *
     * @param  mixed
     * @return void
     */
    function d($var)
    {
		return yii\helpers\VarDumper::dump($var);
    }
}

/**
 * [total_training description]
 * @return [type] [description]
 */
function total_training()
{
	return \App\Training::count();
}

/**
 * [total_exit_interview description]
 * @return [type] [description]
 */
function total_exit_interview()
{
	return \App\ExitInterview::count();
}

/**
 * [total_overtime description]
 * @return [type] [description]
 */
function total_overtime()
{
	return \App\OvertimeSheet::count();
}

/**
 * [total_medical description]
 * @return [type] [description]
 */
function total_medical()
{
	return \App\MedicalReimbursement::count();
}

/**
 * [total_payment_request description]
 * @return [type] [description]
 */
function total_payment_request()
{
	return \App\PaymentRequest::count();
}

/**
 * [total_karyawan description]
 * @return [type] [description]
 */
function total_karyawan()
{
	return \App\User::where('access_id', 2)->count();
}

/**
 * [total_cuti_karyawan description]
 * @return [type] [description]
 */
function total_cuti_karyawan()
{
	return \App\CutiKaryawan::count();
}

/**
 * [list_cuti_user description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function list_cuti_user($id)
{
	return \App\CutiKaryawan::where('user_id', $id)->get();
}

/**
 * [data_overtime_user description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function data_overtime_user($id)
{
	$total = \App\OvertimeSheet::where('user_id', $id)->where('status', 2)->count();
	
	if($total == 0)
		return false;
	else
		return \App\OvertimeSheet::where('user_id', $id)->where('status', 2)->get();
}

/**
 * [get_airports description]
 * @return [type] [description]
 */
function get_airports()
{
	return \App\Airports::orderBy('code', 'ASC')->get();
}

/**
 * [cek_status_approval_user description]
 * @param  [type] $id         [description]
 * @param  [type] $jenis_form [description]
 * @param  [type] $foreign_id [description]
 * @return [type]             [description]
 */
function cek_status_approval_user($user_id, $jenis_form, $foreign_id)
{
	// cek approval
	$approval = \App\StatusApproval::where('approval_user_id', $user_id)->where('jenis_form', $jenis_form)->where('foreign_id', $foreign_id)->first();

	if($approval)
		return true;
	else
		return false;
}

/**
 * [cek_approval_user description]
 * @return [type] [description]
 */
function cek_approval_user()
{
	$user = \Auth::user();

	// cek approval
	$approval = \App\SettingApproval::where('user_id', $user->id)->first();

	if($approval)
		return true;
	else
		return false;
}

/**
 * [cek_approval_user description]
 * @return [type] [description]
 */
function list_approval_user()
{
	$user = \Auth::user();

	// cek approval
	$approval = \App\SettingApproval::where('user_id', $user->id)->groupBy('jenis_form')->get();

	$list = [];
	foreach($approval as $k => $item)
	{
		$list[$k]['name'] = $item->nama_approval;
		$list[$k]['link'] = $item->jenis_form;

		switch($item->jenis_form)
		{
			case 'cuti':
				$list[$k]['nama_menu'] = 'Cuti / Izin Karyawan (Assign HRD)';
			break;
			case 'payment_request':
				$list[$k]['nama_menu'] = 'Payment Request';
			break;
			case 'medical':
				$list[$k]['nama_menu'] = 'Medical Reimbursement';
			break;
			case 'exit_clearance':
				$list[$k]['nama_menu'] = 'Exit Interview & Clearance';
			break;
			case 'exit':
				$list[$k]['nama_menu'] = 'Exit Interview & Clearance';
			break;
			case 'training':
				$list[$k]['nama_menu'] = 'Training & Perjalanan Dinas';
			break;
			case 'overtime':
				$list[$k]['nama_menu'] = 'Overtime Sheet';
			break;
			default:
				$list[$k]['nama_menu'] = '';
			break;
		}
	}	

	return $list;
}

/**
 * [get_karyawan description]
 * @return [type] [description]
 */
function get_karyawan()
{
	return \App\User::where('access_id', 2)->get();
}

/**
 * [list_exit_clearance_accounting_finance_note description]
 * @return [type] [description]
 */
function list_exit_clearance_accounting_finance_note()
{	
	$list[0]['item'] = 'Employee Loan / Pinjaman Karyawan';
	$list[1]['item'] = 'Advance Payment / Realisasi Uang Muka';
	$list[2]['item'] = 'Early Term COP / Pelunasan ';

	return $list;
}

/**
 * [list_exit_clearance_inventory_to_it description]
 * @return [type] [description]
 */
function list_exit_clearance_inventory_to_it()
{
	$list[0]['item'] = 'Laptop/PC & Other IT Device';
	$list[1]['item'] = 'Password PC/Laptop';
	$list[2]['item'] = 'Email Address';
	$list[3]['item'] = 'Arium';

	return $list;
}
/**
 * [list_exit_clearance_inventory_to_ga description]
 * @return [type] [description]
 */
function list_exit_clearance_inventory_to_ga()
{
	$list[0]['item'] = 'Parking Card/Kartu Parkir';
	$list[1]['item'] = 'Vehicle/Kendaraan Operasional';
	$list[2]['item'] = 'Vehicle Registration Number Letter / STNK';
	$list[3]['item'] = 'Drawer Lock/Kunci Laci';
	$list[4]['item'] = 'Camera/Kamera';
	$list[5]['item'] = 'Handphone';

	return $list;
}

/**
 * [list_exit_clearance_inventory_to_hrd description]
 * @return [type] [description]
 */
function list_exit_clearance_inventory_to_hrd()
{
	$list[0]['item'] = 'ID Card/Kartu Identitas Perusahaan';
	$list[1]['item'] = 'Business  Card/Kartu Nama';
	$list[2]['item'] = 'Stamp/Stempel atau Cap';
	$list[3]['item'] = 'Company Regulation Book/Buku Peraturan Perusahaan';
	$list[4]['item'] = 'Seragam ( HO: 1 buah, Cabang: 2 buah )';
	
	return $list;
}

/**
 * [list_exit_clearance_document description]
 * @return [type] [description]
 */
function list_exit_clearance_document()
{
	$list[0]['item'] 	= 'Exit Interview/Formulir Wawancara Karyawan Keluar';
	$list[0]['form_no'] 	= 'HR/P 14';

	$list[1]['item'] 	= 'Surat Mengundurkan Diri/Resignation Form';
	$list[1]['form_no'] 	= '';

	return $list;
}

/**
 * [status_exit_interview description]
 * @param  [type] $status [description]
 * @return [type]         [description]
 */
function status_exit_interview($status)
{
	$html = '';
	switch ($status) {
		case 1:
			$html = '<label class="btn btn-warning btn-xs">Waiting Approval</label>';
			break;
		case 2:
			$html = '<label class="btn btn-success btn-xs"><i class="fa fa-chceck"></i>Disetujui</label>';
		break;
		case 3:
			$html = '<label class="btn btn-danger btn-xs"><i class="fa fa-close"></i>Ditolak</label>';
		break;
		case 4:
			$html = '<label class="btn btn-danger btn-xs"><i class="fa fa-close"></i>Dibatalkan</label>';
		break;
		default:
			break;
	}

	return $html;
}

/**
 * [get_reason_interview description]
 * @return [type] [description]
 */
function get_reason_interview()
{
	return \App\ExitInterviewReason::all();
}

/**
 * [get_bank description]
 * @return [type] [description]
 */
function get_bank()
{
	return \App\Bank::all();
}

/**
 * [get_lembur_detail description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function get_lembur_detail($id)
{
	$data = \App\OvertimeSheetForm::where('overtime_sheet_id', $id)->get();

	return $data;
}

/**
 * [status_overtime description]
 * @param  [type] $status [description]
 * @return [type]         [description]
 */
function status_overtime($status)
{
	$html = '';
	switch ($status) {
		case 1:
			$html = '<label class="btn btn-warning btn-xs">Waiting Approval</label>';
			break;
		case 2:
			$html = '<label class="btn btn-success btn-xs"><i class="fa fa-chceck"></i>Disetujui</label>';
		break;
		case 3:
			$html = '<label class="btn btn-danger btn-xs"><i class="fa fa-close"></i>Ditolak</label>';
		break;
		case 4:
			$html = '<label class="btn btn-danger btn-xs"><i class="fa fa-close"></i>Dibatalkan</label>';
		break;
		
		default:
			break;
	}

	return $html;
}

/**
 * [get_department_name description]
 * @param  [type] $id [description]
 * @return [type]     [description]
 */
function get_department_name($id)
{
	$data = \App\Department::where('id', $id)->first();

	if($data)
		return $data->name;
	else
		return '';
}

/**
 * [get_kabupten description]
 * @param  integer $id_prov [description]
 * @return [type]           [description]
 */
function get_kabupaten($id_prov = 0)
{
	if($id_prov == 0)
	{
		$data = \App\Kabupaten::all();
	}
	else
	{
		$data = \App\Kabupaten::where('id_prov', $id_prov)->get();
	}

	return $data;
}

/**
 * [get_provinsi description]
 * @return [type] [description]
 */
function get_provinsi()
{
	return \App\Provinsi::orderBy('nama', 'ASC')->get();;
}

/**
 * [get_sekolah description]
 * @return [type] [description]
 */
function get_sekolah()
{
	return \App\Sekolah::orderBy('name', 'ASC')->get();
}

/**
 * [get_cabang description]
 * @return [type] [description]
 */
function get_cabang()
{
	return \App\Cabang::orderBy('name', 'ASC')->get();
}

/**
 * [get_program_studi description]
 * @return [type] [description]
 */
function get_program_studi()
{
	return \App\ProgramStudi::orderBy('name', 'ASC')->get();
}

/**
 * [get_universitas description]
 * @return [type] [description]
 */
function get_jurusan()
{
	return \App\Jurusan::orderBy('name', 'ASC')->get();
}

/**
 * [get_universitas description]
 * @return [type] [description]
 */
function get_universitas()
{
	return \App\Universitas::orderBy('name', 'ASC')->get();
}

/**
 * [status_medical description]
 * @param  [type] $status [description]
 * @return [type]         [description]
 */
function status_medical($status)
{
	$html = '';
	switch ($status) {
		case 1:
			$html = '<label class="btn btn-warning btn-xs">Waiting Approval</label>';
			break;
		case 2:
			$html = '<label class="btn btn-success btn-xs"><i class="fa fa-chceck"></i>Disetujui</label>';
		break;
		case 3:
			$html = '<label class="btn btn-danger btn-xs"><i class="fa fa-close"></i>Ditolak</label>';
		break;
		case 4:
			$html = '<label class="btn btn-danger btn-xs"><i class="fa fa-close"></i>Dibatalkan</label>';
		break;
		default:
			break;
	}

	return $html;
}

/**
 * [status_payment_request description]
 * @param  [type] $status [description]
 * @return [type]         [description]
 */
function status_payment_request($status)
{
	$html = '';
	switch ($status) {
		case 1:
			$html = '<label class="btn btn-warning btn-xs">Waiting Approval</label>';
			break;
		case 2:
			$html = '<label class="btn btn-success btn-xs"><i class="fa fa-chceck"></i>Disetujui</label>';
		break;
		case 3:
			$html = '<label class="btn btn-danger btn-xs"><i class="fa fa-close"></i>Ditolak</label>';
		break;
		case 4:
			$html = '<label class="btn btn-danger btn-xs"><i class="fa fa-close"></i>Dibatalkan</label>';
		break;
		default:
			break;
	}

	return $html;
}

/**
 * [lama_hari description]
 * @param  [type] $start [description]
 * @param  [type] $end   [description]
 * @return [type]        [description]
 */
function lama_hari($start, $end)
{
	$start_date = new DateTime($start);
	$end_date = new DateTime($end);
	$interval = $start_date->diff($end_date);
		
	// jika hari sama maka dihitung 1 hari
	if($start_date == $end_date)  return "1";

	$hari = $interval->days + 1;

	return "$hari "; // hasil : 217 hari

}

/**
 * [status_cuti description]
 * @param  [type] $status [description]
 * @return [type]         [description]
 */
function status_cuti($status)
{
	$html = '';
	switch ($status) {
		case 1:
			$html = '<label class="btn btn-warning btn-xs">Waiting Approval</label>';
			break;
		case 2:
			$html = '<label class="btn btn-success btn-xs"><i class="fa fa-chceck"></i>Disetujui</label>';
		break;
		case 3:
			$html = '<label class="btn btn-danger btn-xs"><i class="fa fa-close"></i>Ditolak</label>';
		break;
		case 4:
			$html = '<label class="btn btn-danger btn-xs"><i class="fa fa-close"></i>Dibatalkan</label>';
		break;
		default:
			break;
	}

	return $html;
}


/**
 * [get_department_by_section_id description]
 * @param  [type] $department_id [description]
 * @param  string $type          [description]
 * @return [type]                [description]
 */
function get_section_by_department_id($department_id, $type='array')
{
	if($type == 'array')
		$data = \App\Section::where('department_id', $department_id)->get();
	else
		$data = \App\Section::where('department_id', $department_id)->first();
	
	return $data;	
}

/**
 * [get_department_by_division_id description]
 * @param  [type] $division_id [description]
 * @return [type]              [description]
 */
function get_department_by_division_id($division_id, $type='array')
{
	if($type == 'array')
		//$data = \App\Department::where('division_id', $division_id)->get();
		$data = \App\OrganisasiDepartment::where('organisasi_division_id', $division_id)->get();
	else
		//$data = \App\Department::where('division_id', $division_id)->get();
		$data = \App\OrganisasiDepartment::where('organisasi_division_id', $division_id)->first();
	
	return $data;	
}
/**
 * [get_division_by_directorate_id description]
 * @param  [type] $directorate_id [description]
 * @return [type]                 [description]
 */
function get_division_by_directorate_id($directorate_id, $type = 'array')
{
	if($type == 'array')
		//$data = \App\Division::where('directorate_id', $directorate_id)->get();
		$data = \App\OrganisasiDivision::where('organisasi_directorate_id', $directorate_id)->get();
	else
		//$data = \App\Division::where('directorate_id', $directorate_id)->first();
		$data = \App\OrganisasiDivision::where('organisasi_directorate_id', $directorate_id)->first();
	
	return $data;		
}

/**
 * [agama description]
 * @return [type] [description]
 */
function agama()
{
	return ['Muslim', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu'];
}


function cek_approval_gm_medical(){
	$data = \App\SettingApproval::where('user_id', \Auth::user()->id)
									->where('jenis_form','medical')
									->where('nama_approval', 'GM HR')
									->first();
	if($data){
		return 'yes';
	}else{
		return 'no';
	}
}


?>