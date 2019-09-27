<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\SettingApproval;

class ApprovalCutiAtasanController extends Controller
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
        $params['data'] = \App\CutiKaryawan::where('approved_atasan_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.approval-cuti-atasan.index')->with($params);
    }

    /**
     * [proses description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function proses(Request $request)
    {
        $cuti                           = \App\CutiKaryawan::where('id', $request->id)->first();
        $cuti->is_approved_atasan       = $request->status;
        $cuti->catatan_atasan           = $request->noted;
        $cuti->date_approved_atasan     = date('Y-m-d H:i:s');

        if($request->status == 0)
        {
            $cuti->status =3 ; // reject
        }

        $cuti->save();
 
        if($request->status == 1)
        {
            $setting_approval = SettingApproval::where('jenis_form', 'cuti')->where('nama_approval', 'Personalia')->get();
        
            foreach($setting_approval as $item){
                $data               =   $cuti;
                $params['data']     = $data;
                $params['text']     = '<p><strong>Dear Bapak/Ibu '. User::where('id', $item->user_id)->first()->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' mengajukan Cuti butuh persetujuan Anda.</p>';

                \Mail::send('email.cuti-approval', $params,
                    function($message) use($data, $item) {
                        $message->from('services@asiafinance.com');
                        $message->to(User::where('id', $item->user_id)->first()->email);
                        $message->subject('PT. Arthaasia Finance - Pengajuan Cuti / Izin');
                    }
                );
            }
        }
        
        return redirect()->route('karyawan.approval.cuti-atasan.index')->with('messages-success', 'Form Cuti Berhasil diproses !');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {   
        $params['data'] = \App\CutiKaryawan::where('id', $id)->first();

        return view('karyawan.approval-cuti-atasan.detail')->with($params);
    }
}
