<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TrainingController extends Controller
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
        $params['data'] = \App\Training::orderBy('id', 'DESC')->get();
        $params['data_biaya'] = \App\Training::where('is_approve_atasan_actual_bill', 1)->where('status_actual_bill', 2)->orderBy('id', 'DESC')->get();

        return view('administrator.training.index')->with($params);
    }

    /**
     * [batal description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function batal(Request $request)
    {   
        $data       = \App\Training::where('id', $request->id)->first();
        $data->status = 4;
        $data->note_pembatalan = $request->note;
        $data->save(); 

        return redirect()->route('administrator.training.index')->with('message-success', 'Cuti Berhasil dibatalkan');
    }

    /**
     * [prosesBiaya description]
     * @return [type] [description]
     */
    public function prosesBiaya(Request $request)
    {
        $data = \App\Training::where('id', $request->id)->first();

        $data->transportasi_ticket_disetujui    = $request->transportasi_ticket_disetujui;
        $data->transportasi_ticket_catatan      = $request->transportasi_ticket_catatan;
        $data->transportasi_taxi_disetujui      = $request->transportasi_taxi_disetujui;
        $data->transportasi_taxi_catatan        = $request->transportasi_taxi_catatan;
        $data->transportasi_gasoline_disetujui  = $request->transportasi_gasolin_disetujui;
        $data->transportasi_gasoline_catatan    = $request->transportasi_gasolin_catatan;
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
        $data->is_approve_hrd_actual_bill = 1;
        $data->sub_total_1_disetujui            = $request->sub_total_1_disetujui;
        $data->sub_total_2_disetujui            = $request->sub_total_2_disetujui;
        $data->sub_total_3_disetujui            = $request->sub_total_3_disetujui;

        // jika tidak ada uang muka maka tidak perlu approval finance
        if(empty($training->pengambilan_uang_muka))
        {   
            if($request->status_actual_bill == 1)
            {
                $data->status_actual_bill = 3; // approved
            }
            else
            {
                $data->status_actual_bill = 4; // reject
            }
        }

        $data->save();

        return redirect()->route('administrator.training.index')->with('message-success', 'Form Actual Bill berhasil di proses');
    }

    /**
     * [biaya description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function biaya($id)
    {
        $params['data'] = \App\Training::where('id', $id)->first();

        return view('administrator.training.biaya')->with($params);
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

        $training->approved_hrd = $status;
        $training->approved_hrd_id = \Auth::user()->id;
        $training->approved_hrd_date = date('Y-m-d H:i:s');

        // jika ada uang muka maka butuh approval di finance
        if(empty($training->pengambilan_uang_muka))
        {   
            if($status ==0)
            {
                $training->status = 3;

                // send email atasan
                $objDemo = new \stdClass();
                $objDemo->content = '<p>Dear '. $training->user->name .'</p><p> Pengajuan Training dan Perjalanan Dinas  anda ditolak.</p>' ;    
            }
            else
            {
                $training->status = 2;
                // send email atasan
                $objDemo = new \stdClass();
                $objDemo->content = '<p>Dear '. $training->user->name .'</p><p> Pengajuan Training dan Perjalanan Dinas  anda disetujui.</p>' ; 
            }
        }

        // cek user yang mengetahui
        $mengetahui = \App\SettingApproval::where('jenis_form', 'training_mengetahui')->get(); 
        foreach($mengetahui as $item)
        {
            //\Mail::to($item->user->email)->send(new \App\Mail\GeneralMail($objDemo));
            //\Mail::to('doni.enginer@gmail.com')->send(new \App\Mail\GeneralMail($objDemo));
        }

        //\Mail::to($overtime->user->)->send(new \App\Mail\GeneralMail($objDemo));
        //\Mail::to('doni.enginer@gmail.com')->send(new \App\Mail\GeneralMail($objDemo));
        $training->save();

        return redirect()->route('administrator.training.index')->with('message-success', 'Form Training Berhasil diproses !');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {   
        $params['data'] = \App\Training::where('id', $id)->first();

        return view('administrator.training.detail')->with($params);
    }
}
