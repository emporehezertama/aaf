<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CutiKaryawan;
use App\User;

class CutiController extends Controller
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
        $params['data'] = CutiKaryawan::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.cuti.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        $params['karyawan'] = User::where('access_id', 2)->get();
        $params['karyawan_backup'] = User::where('access_id', 2)->where('department_id', \Auth::user()->department_id)->get();

        return view('karyawan.cuti.create')->with($params); 
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['karyawan'] = User::where('access_id', 2)->get();
        $params['karyawan_backup'] = User::where('access_id', 2)->where('department_id', \Auth::user()->department_id)->get();
        $params['data']     = CutiKaryawan::where('id', $id)->first();

        return view('karyawan.cuti.edit')->with($params);
    }

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = CutiKaryawan::where('id', $id)->first();
        $data->delete();

        return redirect()->route('karyawan.cuti.index')->with('message-sucess', 'Data berhasi di hapus');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $data                   = new CutiKaryawan();
        $data->user_id          = \Auth::user()->id;
        $data->jenis_cuti       = $request->jenis_cuti;
        $data->tanggal_cuti_start= date('Y-m-d' , strtotime($request->tanggal_cuti_start));
        $data->tanggal_cuti_end = date('Y-m-d' , strtotime($request->tanggal_cuti_end));
        $data->keperluan        = $request->keperluan;
        $data->backup_user_id   = $request->backup_user_id;
        $data->status           = 1; 
        $data->is_personalia_id = 0;
        $data->approved_atasan_id     = $request->atasan_user_id;
        $data->jam_pulang_cepat    = $request->jam_pulang_cepat;
        $data->jam_datang_terlambat= $request->jam_datang_terlambat;
        $data->total_cuti       = $request->total_cuti;
        $atasan = \App\User::where('id', $request->atasan_user_id)->first();

        // cek cuti user
        $cuti = \App\UserCuti::where('user_id', \Auth::user()->id)->where('cuti_id', $request->jenis_cuti)->first();
        if(!$cuti)
        {   
            $cuti = \App\Cuti::where('id', $request->jenis_cuti)->first();

            if($cuti)
            {
                $user_cuti               = new \App\UserCuti();
                $user_cuti->user_id      = \Auth::user()->id;
                $user_cuti->kuota        = $cuti->kuota;
                $user_cuti->cuti_terpakai=0;
                $user_cuti->sisa_cuti    =0;
                $user_cuti->cuti_id      = $cuti->id;
                $user_cuti->save();
            }
        }

        $data->save();

        $params['data']     = $data;
        $params['text']     = '<p><strong>Dear Bapak/Ibu '. $data->atasan->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' mengajukan Cuti butuh persetujuan Anda.</p>';


        if($data->atasan->email != Null){
            \Mail::send('email.cuti-approval', $params,
                function($message) use($data) {
                    $message->from('services@asiafinance.com');
                    $message->to($data->user->email);
                    $message->subject('PT. Arthaasia Finance - Pengajuan Cuti');
                }
            );
        }

        return redirect()->route('karyawan.cuti.index')->with('message-success', 'Data berhasil disimpan !');
    }
}
