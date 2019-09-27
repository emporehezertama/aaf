<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\SettingApproval;

class ApprovalTrainingController extends Controller
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
        $params['data']         = \App\Training::orderBy('id', 'DESC')->get();
        $params['approval']     = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','training')->first();

        if(empty($params['approval']))
        {
            return redirect()->route('karyawan.dashboard')->with('message-error', 'Access Denied');
        }

        return view('karyawan.approval-training.index')->with($params);
    }

    /**
     * [prosesBiaya description]
     * @return [type] [description]
     */
    public function prosesBiaya(Request $request)
    {
        $data = \App\Training::where('id', $request->id)->first();

        $approval = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','training')->first();

        $data->transportasi_ticket_disetujui    = $request->transportasi_ticket_disetujui;
        $data->transportasi_ticket_catatan      = $request->transportasi_ticket_catatan;
        $data->transportasi_taxi_disetujui      = $request->transportasi_taxi_disetujui;
        $data->transportasi_taxi_catatan        = $request->transportasi_taxi_catatan;
        $data->transportasi_gasoline_disetujui  = $request->transportasi_gasoline_disetujui;
        $data->transportasi_gasoline_catatan    = $request->transportasi_gasoline_catatan;
        $data->transportasi_tol_disetujui       = $request->transportasi_tol_disetujui;
        $data->transportasi_tol_catatan         = $request->transportasi_tol_catatan;
        $data->transportasi_parkir_disetujui    = $request->transportasi_parkir_disetujui;
        $data->transportasi_parkir_catatan      = $request->transportasi_parkir_catatan;
        $data->uang_hotel_nominal_disetujui     = $request->uang_hotel_nominal_disetujui;
        $data->uang_hotel_catatan               = $request->uang_hotel_catatan;
        $data->uang_makan_nominal_disetujui     = $request->uang_makan_nominal_disetujui;
        $data->uang_makan_catatan               = $request->uang_makan_catatan;
        $data->uang_harian_nominal_disetujui    = $request->uang_harian_nominal_disetujui;
        $data->uang_harian_catatan              = $request->uang_harian_catatan;
        $data->uang_pesawat_nominal_disetujui   = $request->pesawat_nominal_disetujui;
        $data->uang_pesawat_catatan             = $request->uang_pesawat_catatan;
        $data->uang_biaya_lainnya1_nominal_disetujui = $request->uang_biaya_lainnya1_nominal_disetujui;
        $data->uang_biaya_lainnya1_catatan      = $request->uang_biaya_lainnya1_catatan;
        $data->uang_biaya_lainnya2_nominal_disetujui = $request->uang_biaya_lainnya2_nominal_disetujui;
        $data->uang_biaya_lainnya2_catatan      = $request->uang_biaya_lainnya2_catatan;

        $data->sub_total_1_disetujui            = $request->sub_total_1_disetujui;
        $data->sub_total_2_disetujui            = $request->sub_total_2_disetujui;
        $data->sub_total_3_disetujui            = $request->sub_total_3_disetujui;

        if($approval->nama_approval == 'Finance')
        {
            $data->is_approve_finance_actual_bill = $request->status_actual_bill;
        }
        else // ELSE HRD
        {
            $data->is_approve_hrd_actual_bill = $request->status_actual_bill;
        }

        $data->save();

        $data = \App\Training::where('id', $request->id)->first();

        if($data->is_approve_hrd_actual_bill == 1 and $data->is_approve_finance_actual_bill == 1)
        {
            $data->status_actual_bill = 3; // approved
        }

        if($data->is_approve_hrd_actual_bill === 0 or $data->is_approve_finance_actual_bill === 0)
        {
            $data->status_actual_bill = 4; // approved
        }
        
        $data->save();

        return redirect()->route('karyawan.approval.training.index')->with('message-success', 'Form Actual Bill berhasil di proses');
    }

    /**
     * [biaya description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function biaya($id)
    {
        $params['data'] = \App\Training::where('id', $id)->first();
        $params['approval']     = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','training')->first();

        return view('karyawan.approval-training.biaya')->with($params);
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
        $status->jenis_form             = 'training';
        $status->foreign_id             = $request->id;
        $status->status                 = $request->status;
        $status->noted                  = $request->noted;
        $status->save();    

        $status = $request->status;
        $training = \App\Training::where('id', $request->id)->first();
        
        $approval = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','training')->first();
        
        if($approval->nama_approval == 'HRD')
        {
            $training->approved_hrd = $status;
            $training->approved_hrd_id = \Auth::user()->id;
            $training->approved_hrd_date = date('Y-m-d H:i:s');

            if($status == 1)
            {
                $setting_approval = SettingApproval::where('jenis_form', 'training')->where('nama_approval', 'Finance')->get();
                $data                   = $training;
                $params['data']         = $data;
                foreach($setting_approval as $item){
                    
                    $params['text']         = '<p><strong>Dear Bapak/Ibu '. User::where('id', $item->user_id)->first()->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' mengajukan Training dan Perjalanan Dinas butuh persetujuan Anda.</p>';
                    
                    \Mail::send('email.training-approval', $params,
                        function($message) use($data, $item) {
                            $message->from('services@asiafinance.com');
                            $message->to(User::where('id', $item->user_id)->first()->email);
                            $message->subject('PT. Arthaasia Finance - Pengajuan Training dan Perjalanan Dinas');
                        }
                    );  
                }
            }
        }
        if($approval->nama_approval == 'Finance')
        {
            $training->approved_finance = $status;
            $training->approved_finance_id = \Auth::user()->id;
            $training->approved_finance_date = date('Y-m-d H:i:s');
        }
        $training->save();

        $training           = \App\Training::where('id', $request->id)->first();
        $params['data']     = $training;
        // jika ada uang muka maka butuh approval di finance
        if($training->pengambilan_uang_muka !== NULL)
        {   
            if($training->approved_hrd == 1 and $training->approved_finance == 1)
            {
                $training->status = 2;
            }
        }else{

            if($status == 0)
            {
                $training->status = 3;   
            }
            else
            {
                $training->status = 2;
            }
        }

        if($status == 0)
        {
            $training->status = 3;
        }

        if($training->status == 2 || $training->status == 3)
        {
            
             if($training->status == 2)
             {
                 $params['text']     = '<p><strong>Dear Bapak/Ibu '. $training->user->name .'</strong>,</p> <p>  Pengajuan Training dan Perjalan Dinas anda <strong style="color: green;">DISETUJUI</strong>.</p>';                
             }
             else
             {
                 $params['text']     = '<p><strong>Dear Bapak/Ibu '. $training->user->name .'</strong>,</p> <p>  Pengajuan Training dan Perjalan Dinas anda <strong style="color: red;">DITOLAK</strong>.</p>';                
             }

             \Mail::send('email.training-approval', $params,
                 function($message) use($training) {
                     $message->from('services@asiafinance.com');
                     $message->to($training->user->email);
                     $message->subject('PT. Arthaasia Finance - Pengajuan Training dan Perjalanan Dinas');
                 }
             );

            
        }
        
        // cek user yang mengetahui
        $mengetahui = \App\SettingApproval::where('jenis_form', 'training_mengetahui')->get(); 
        foreach($mengetahui as $item)
        {
            //\Mail::to($item->user->email)->send(new \App\Mail\GeneralMail($objDemo));
            //\Mail::to('doni.enginer@gmail.com')->send(new \App\Mail\GeneralMail($objDemo));
        }

        $training->save();

        return redirect()->route('karyawan.approval.training.index')->with('messages-success', 'Form Cuti Berhasil diproses !');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {   
        $params['data'] = \App\Training::where('id', $id)->first();

        return view('karyawan.approval-training.detail')->with($params);
    }
}
