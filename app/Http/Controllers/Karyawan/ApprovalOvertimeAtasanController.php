<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\SettingApproval;

class ApprovalOvertimeAtasanController extends Controller
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
        $params['data'] = \App\OvertimeSheet::where('approved_atasan_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.approval-overtime-atasan.index')->with($params);
    }

    /**
     * [proses description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function proses(Request $request)
    {
        $overtime                           = \App\OvertimeSheet::where('id', $request->id)->first();
        $overtime->is_approved_atasan       = $request->status;
        $overtime->date_approved_atasan     = date('Y-m-d H:i:s');

        if($request->status == 0)
        {
            $overtime->status = 3;
        }

        $overtime->save();  
        
        if($request->status == 1){
            $data               = $overtime;
            $params['data']     = $data;
        
            $setting_approval = SettingApproval::where('jenis_form', 'overtime')->where('nama_approval', 'Manager HR')->get();
            
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

        return redirect()->route('karyawan.approval.overtime-atasan.index')->with('messages-success', 'Form Cuti Berhasil diproses !');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {   
        $params['data'] = \App\OvertimeSheet::where('id', $id)->first();

        return view('karyawan.approval-overtime-atasan.detail')->with($params);
    }
}
