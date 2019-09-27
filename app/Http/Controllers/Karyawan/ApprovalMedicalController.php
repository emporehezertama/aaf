<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\SettingApproval;

class ApprovalMedicalController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // cek jenis user
        $approval = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','medical')->first();
        $params['data'] = [];
        if($approval)
        {
            if($approval->nama_approval =='HR Benefit')
            {
                $params['data'] = \App\MedicalReimbursement::where('is_approved_hr_benefit', 0)->orderBy('id', 'DESC')->get();
            }

            if($approval->nama_approval =='Manager HR')
            {
                $params['data'] = \App\MedicalReimbursement::where('is_approved_manager_hr', 0)->orderBy('id', 'DESC')->get();
            }

            if($approval->nama_approval =='GM HR')
            {
                $params['data'] = \App\MedicalReimbursement::where('is_approved_atasan', 1)->where('is_approved_gm_hr', 0)->orderBy('id', 'DESC')->get();
            }
        }

        if(empty($approval))
        {
            return redirect()->route('karyawan.dashboard')->with('message-error', 'Access Denied!');
        }

        $params['approval'] = $approval;
        $params['data']     = \App\MedicalReimbursement::orderBy('id', 'DESC')->get();

        return view('karyawan.approval-medical.index')->with($params);
    }

    /**
     * [proses description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function proses(Request $request)
    {
        $status = new \App\StatusApproval;
        $status->approval_user_id       = \Auth::user()->id;
        $status->jenis_form             = 'medical';
        $status->foreign_id             = $request->id;
        $status->status                 = $request->status;
        $status->noted                  = $request->noted;
        $status->save();

        $approval   = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','medical')->first();
        $medical    = \App\MedicalReimbursement::where('id', $request->id)->first();
        if($approval)
        {
            if($approval->nama_approval =='HR Benefit')
            {
                $medical->is_approved_hr_benefit  = $request->status;
                $medical->hr_benefit_id           = \Auth::user()->id;
                $medical->hr_benefit_date         = date('Y-m-d H:i:s');

                if($request->status == 1){
                    $setting_approval = SettingApproval::where('jenis_form', 'medical')->where('nama_approval', 'Manager HR')->get();
                    $data                   = $medical;
                    $params['data']         = $data;
                    $params['approval_gm']  = '';
                    foreach($setting_approval as $item){
                        $params['text']         = '<p><strong>Dear Bapak/Ibu '. User::where('id', $item->user_id)->first()->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' mengajukan Medical Reimbursement butuh persetujuan Anda.</p>';
                        
                        \Mail::send('email.medical-approval', $params,
                            function($message) use($data, $item) {
                                $message->from('services@asiafinance.com');
                                $message->to(User::where('id', $item->user_id)->first()->email);
                                $message->subject('PT. Arthaasia Finance - Pengajuan Medical Reimbursement');
                            }
                        );
                    }
                }
            }
            if($approval->nama_approval =='Manager HR')
            {
                $medical->is_approved_manager_hr  = $request->status;
                $medical->manager_hr_id           = \Auth::user()->id;
                $medical->manager_hr_date         = date('Y-m-d H:i:s');

                if($request->status == 1){
                    $setting_approval = SettingApproval::where('jenis_form', 'medical')->where('nama_approval', 'GM HR')->get();
                    $data                   = $medical;
                    $params['data']         = $data;
                    $params['approval_gm']  = '';
                    foreach($setting_approval as $item){
                        $params['text']         = '<p><strong>Dear Bapak/Ibu '. User::where('id', $item->user_id)->first()->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' mengajukan Medical Reimbursement butuh persetujuan Anda.</p>';
                        
                        \Mail::send('email.medical-approval', $params,
                            function($message) use($data, $item) {
                                $message->from('services@asiafinance.com');
                                $message->to(User::where('id', $item->user_id)->first()->email);
                                $message->subject('PT. Arthaasia Finance - Pengajuan Medical Reimbursement');
                            }
                        );
                    }
                }
            }
            if($approval->nama_approval =='GM HR')
            {
                $medical->is_approved_gm_hr   = $request->status;
                $medical->gm_hr_id            = \Auth::user()->id;
                $medical->gm_hr_date          = date('Y-m-d H:i:s');
            }
        }
        
        foreach($request->nominal_approve as $id => $val)
        {
            $list                   = \App\MedicalReimbursementForm::where('id', $id)->first();
            $list->nominal_approve  = str_replace(',', '', $val);
            $list->save();
        }

        $skip_gm_hr             = ['Staff', 'Head','Supervisor'];
        $status                 = $request->status;
        $params['approval_gm']  = false;
        $params['data']         = $medical;

        if(isset($medical->user->organisasiposition->name))
        {
            // Dibawah Manager tidak perlu approval GM HR
            if(in_array($medical->user->organisasiposition->name, $skip_gm_hr)){

              if($medical->is_approved_hr_benefit ==1 and $medical->is_approved_manager_hr ==1)
              {
                  $medical->status = 2;
              }
            }
            else // Level Manager ke atas perlu approval GM HR
            {
              if($medical->is_approved_hr_benefit ==1 and $medical->is_approved_manager_hr ==1 and $medical->is_approved_gm_hr == 1)
              {
                    $medical->status = 2;
                    $params['approval_gm'] = true;
              }
            }
        }

        if($medical->status == 2)
        {
            $params['text']     = '<p><strong>Dear Bapak/Ibu '. $medical->user->name .'</strong>,</p> <p>  Pengajuan Medical Reimbursement anda <strong style="color: green;">DISETUJUI</strong>.</p>';
            // send email
            \Mail::send('email.medical-approval', $params,
                function($message) use($medical) {
                    $message->from('services@asiafinance.com');
                    $message->to($medical->user->email);
                    $message->subject('PT. Arthaasia Finance - Pengajuan Medical Reimbursement');
                }
            );
        }

        // Denied
        if($request->status == 0)
        {
            $params['text']     = '<p><strong>Dear Bapak/Ibu '. $medical->user->name .'</strong>,</p> <p>  Pengajuan Medical Reimbursement anda <strong style="color: red;">DITOLAK</strong>.</p>';
            // send email
            \Mail::send('email.medical-approval', $params,
                function($message) use($medical) {
                    $message->from('services@asiafinance.com');
                    $message->to($medical->user->email);
                    $message->subject('PT. Arthaasia Finance - Pengajuan Medical Reimbursement');
                }
            );   

            $medical->status == 3;
        }
        $medical->save();
 
        return redirect()->route('karyawan.approval.medical.index')->with('message-success', 'Form Medical Reimbursement Berhasil diproses !');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {
        $params['data']         = \App\MedicalReimbursement::where('id', $id)->first();
        $params['approval']     = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','medical')->first();

        return view('karyawan.approval-medical.detail')->with($params);
    }
}
