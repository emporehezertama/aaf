<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OvertimeSheet;
use App\OvertimeSheetForm;
use App\User;
use App\SettingApproval;

class OvertimeController extends Controller
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
        $params['data'] = OvertimeSheet::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.overtime.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        $params['karyawan'] = User::where('access_id', \Auth::user()->id)->get();

        return view('karyawan.overtime.create')->with($params);
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data'] = \App\OvertimeSheet::where('id', $id)->first();

        return view('karyawan.overtime.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data               = OvertimeSheet::where('id', $id)->first();
        $form->description  = $request->description[$key];
        $form->awal         = $request->awal[$key];
        $form->akhir        = $request->akhir[$key];
        $form->total_lembur = $request->total_lembur[$key];
        $form->tanggal      = $request->tanggal;
        $form->save();

        return redirect()->route('karyawan.overtime.index')->with('message-success', 'Data berhasil disimpan');
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = OvertimeSheet::where('id', $id)->first();
        $data->delete();

        return redirect()->route('karyawan.overtima.index')->with('message-sucess', 'Data berhasi di hapus');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $data                       = new OvertimeSheet();
        $data->user_id              = \Auth::user()->id;
        $data->status               = 1;  
        $data->approved_atasan_id   = $request->atasan_user_id; 
        $data->save();

        foreach($request->tanggal as $key => $item)
        {   
            $form               = new OvertimeSheetForm();
            $form->overtime_sheet_id= $data->id;
            $form->description  = $request->description[$key];
            $form->awal         = $request->awal[$key];
            $form->akhir        = $request->akhir[$key];
            $form->total_lembur = $request->total_lembur[$key];
            $form->tanggal      = $request->tanggal[$key];
            $form->save();
        }

        $params['data']     = $data;
        $params['text']     = '<p><strong>Dear Bapak/Ibu '. $data->atasan->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' mengajukan Overtime butuh persetujuan Anda.</p>';

        \Mail::send('email.overtime-approval', $params,
            function($message) use($data) {
                $message->from('services@asiafinance.com');
                $message->to($data->user->email);
                $message->subject('PT. Arthaasia Finance - Pengajuan Overtime');
            }
        );

        return redirect()->route('karyawan.overtime.index')->with('message-success', 'Data berhasil disimpan !');
    }
}
