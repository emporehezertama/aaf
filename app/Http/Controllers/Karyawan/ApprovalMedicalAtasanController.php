<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\SettingApproval;

class ApprovalMedicalAtasanController extends Controller
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
        $params['data'] = \App\MedicalReimbursement::where('approved_atasan_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.approval-medical-atasan.index')->with($params);
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

        $medical = \App\MedicalReimbursement::where('id', $request->id)->first();
        $medical->is_approved_atasan = 1;
        $medical->date_approved_atasan = date('Y-m-d H:i:s');
        $medical->save();

        $setting_approval = SettingApproval::where('jenis_form', 'medical')->where('nama_approval', 'HR Benefit')->get();
        $data                   = $medical;
        $params['data']         = $data;
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

        return redirect()->route('karyawan.approval.medical-atasan.index')->with('message-success', 'Form Medical Reimbursement Berhasil diproses !');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {   
        $params['data'] = \App\MedicalReimbursement::where('id', $id)->first();

        return view('karyawan.approval-medical-atasan.detail')->with($params);
    }
}
