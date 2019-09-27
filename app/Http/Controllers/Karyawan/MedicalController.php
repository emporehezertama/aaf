<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\MedicalReimbursement;
use App\MedicalReimbursementForm;
use App\User;
use App\SettingApproval;

class MedicalController extends Controller
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
        $params['data'] = MedicalReimbursement::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.medical.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        $params['karyawan'] = User::where('id', \Auth::user()->id)->where('access_id', 2)->get();

        return view('karyawan.medical.create')->with($params);
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data'] = MedicalReimbursement::where('id', $id)->first();;
        $params['form'] = MedicalReimbursementForm::where('medical_reimbursement_id', $id)->get();
        $params['karyawan'] = User::where('access_id', 2)->get();

        return view('karyawan.medical.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data       = MedicalReimbursement::where('id', $id)->first();
        $data->tanggal_pengajuan = $request->tanggal_pengajuan;
        $data->status       = 1;
        $data->save();

        MedicalReimbursementForm::where('medical_reimbursement_id', $id)->delete();

        foreach($request->tanggal_kwitansi as $key => $item)
        {
            $form                           = new MedicalReimbursementForm();
            $form->medical_reimbursement_id = $data->id;
            $form->tanggal_kwitansi         = $request->tanggal_kwitansi[$key];
            $form->user_family_id              = $request->user_family_id[$key];
            $form->jenis_klaim              = $request->jenis_klaim[$key];
            $form->jumlah                   = str_replace(',', '', $request->jumlah[$key]);
            $form->save();
        }

        return redirect()->route('karyawan.medical.index')->with('message-success', 'Data berhasil disimpan');
    }

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = MedicalReimbursement::where('id', $id)->first();
        $data->delete();

        MedicalReimbursementForm::where('medical_reimbursement_id', $id)->delete();

        return redirect()->route('karyawan.medical.index')->with('message-sucess', 'Data berhasi di hapus');
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $data                       = new MedicalReimbursement();
        $data->user_id              = \Auth::user()->id;
        $data->tanggal_pengajuan    = $request->tanggal_pengajuan;
        $data->status               = 1;
        $data->approved_atasan_id   = $request->atasan_user_id;
        $data->save();

        $params['data']         = $data;
        $params['approval_gm']  = '';
        

        $setting_approval = SettingApproval::where('jenis_form', 'medical')->where('nama_approval', 'HR Benefit')->get();
        
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

        // $params['text']     = '<p><strong>Dear Bapak/Ibu '. $data->atasan->name .'</strong>,</p> <p> '. $data->user->name .'  / '.  $data->user->nik .' mengajukan Medical Reimbursement butuh persetujuan Anda.</p>';
            
        // \Mail::send('email.medical-approval', $params,
        //     function($message) use($data) {
        //         $message->from('services@asiafinance.com');
        //         $message->to($data->user->email);
        //         $message->subject('PT. Arthaasia Finance - Pengajuan Medical Reimbursement');
        //     }
        // );

        foreach($request->tanggal_kwitansi as $key => $item)
        {
            $form                           = new MedicalReimbursementForm();
            $form->medical_reimbursement_id = $data->id;
            $form->tanggal_kwitansi         = $request->tanggal_kwitansi[$key];
            $form->user_family_id           = $request->user_family_id[$key];
            $form->jenis_klaim              = $request->jenis_klaim[$key];
            $form->jumlah                   = str_replace(',', '', $request->jumlah[$key]);
            
            if (request()->hasFile('file_bukti_transaksi'))
            {
                $file = $request->file('file_bukti_transaksi');

                foreach($file as $k => $f)
                {
                    if($k == $key)
                    {
                        $fname = md5($f->getClientOriginalName() . time()) . "." . $f->getClientOriginalExtension();

                        $destinationPath = public_path('/storage/file-medical/');
                        $f->move($destinationPath, $fname);
                        $form->file_bukti_transaksi = $fname;
                    }
                }
            }
            $form->save();
        }

        return redirect()->route('karyawan.medical.index')->with('message-success', 'Anda berhasil mengajukan Medical Reimbursement !');
    }
}
