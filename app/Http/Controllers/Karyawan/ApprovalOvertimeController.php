<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\SettingApproval;

class ApprovalOvertimeController extends Controller
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
        $approval = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','overtime')->first();
        
        if(empty($approval))
        {
            return redirect()->route('karyawan.dashboard')->with('message-error', 'Access denied');
        }
        $params['approval'] = $approval;
        $params['data'] = \App\OvertimeSheet::orderBy('id', 'DESC')->get();

        return view('karyawan.approval-overtime.index')->with($params);
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
        $status->jenis_form             = 'overtime';
        $status->foreign_id             = $request->id;
        $status->status                 = $request->status;
        $status->noted                  = $request->noted;
        $status->save();    
    
        $overtime = \App\OvertimeSheet::where('id', $request->id)->first();
        $approval = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','overtime')->first();
        
        if($approval)
        {
            if($approval->nama_approval =='Manager HR')
            {
                $overtime->is_hr_manager    = $request->status;
                $overtime->hr_manager_date  = date('Y-m-d H:i:s');
                $overtime->hr_manager_id    = \Auth::user()->id;

                if($request->status == 1){
                    $data               = $overtime;
                    $params['data']     = $data;
                
                    $setting_approval = SettingApproval::where('jenis_form', 'overtime')->where('nama_approval', 'HR Operation')->get();
                    
                    foreach($setting_approval as $item){
                        $params['text']     = '<p><strong>Dear Bapak/Ibu '. User::where('id', $item->user_id)->first()->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' mengajukan Overtime butuh persetujuan Anda.</p>';
        
                        \Mail::send('email.overtime-approval', $params,
                            function($message) use($data, $item) {
                                $message->from('services@asiafinance.com');
                                $message->to(User::where('id', $item->user_id)->first()->email);
                                $message->subject('PT. Arthaasia Finance - Pengajuan Overtime');
                            }
                        );
                    }
                }
            }

            if($approval->nama_approval =='HR Operation')
            {
                $overtime->is_hr_benefit_approved   = $request->status;
                $overtime->hr_benefit_date          = date('Y-m-d H:i:s');
                $overtime->hr_benefit_id            = \Auth::user()->id;
            }
        }

        if($overtime->is_hr_benefit_approved ==1 and $overtime->is_hr_manager ==1)
        {
            $params['data']     = $overtime;
            $params['text']     = '<p><strong>Dear Bapak/Ibu '. $overtime->user->name .'</strong>,</p> <p>  Pengajuan Overtime anda <strong style="color: green;">DISETUJUI</strong>.</p>';

            \Mail::send('email.overtime-approval', $params,
                function($message) use($overtime) {
                    $message->from('services@asiafinance.com');
                    $message->to($overtime->user->email);
                    $message->subject('PT. Arthaasia Finance - Pengajuan Overtime');
                }
            );

            $overtime->status = 2;
        }

        if($request->status == 0)
        {
            $params['data']     = $overtime;
            $params['text']     = '<p><strong>Dear Bapak/Ibu '. $overtime->user->name .'</strong>,</p> <p>  Pengajuan Overtime anda <strong style="color: red;">DITOLAK</strong>.</p>';

            \Mail::send('email.overtime-approval', $params,
                function($message) use($overtime) {
                    $message->from('services@asiafinance.com');
                    $message->to($overtime->user->email);
                    $message->subject('PT. Arthaasia Finance - Pengajuan Overtime');
                }
            );

            $overtime->status = 3; // Reject
        }
        $overtime->total_approval_all   = $request->total_approval_all;
        $overtime->total_meal_all       = $request->total_meal_all;
        $overtime->save();

        # form
        if(isset($request->total_approval))
        {
            foreach($request->total_approval as $id => $val)
            {
                $data                   = \App\OvertimeSheetForm::where('id', $id)->first();
                $data->total_approval   = $val;
                $data->total_meal       = $request->total_meal[$id];
                $data->save();
            }
        }

        return redirect()->route('karyawan.approval.overtime.index')->with('messages-success', 'Form Cuti Berhasil diproses !');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {   
        $params['data']         = \App\OvertimeSheet::where('id', $id)->first();
        $params['approval']     = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','overtime')->first();

        return view('karyawan.approval-overtime.detail')->with($params);
    }
}
