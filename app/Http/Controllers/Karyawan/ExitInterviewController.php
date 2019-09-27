<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ExitInterview;

class ExitInterviewController extends Controller
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
        $params['data'] = ExitInterview::where('user_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.exit-interview.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        $params[] = '';

        return view('karyawan.exit-interview.create')->with($params);
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {
        $params['data'] = \App\ExitInterview::where('id', $id)->first();
        $params['list_exit_clearance_document'] = \App\ExitClearanceDocument::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_hrd'] = \App\ExitClearanceInventoryHrd::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_ga'] = \App\ExitClearanceInventoryGa::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_it'] = \App\ExitClearanceInventoryIt::where('exit_interview_id', $id)->get();

        return view('karyawan.exit-interview.detail')->with($params);
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params[] = '';

        return view('karyawan.exit-interview.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data       = ExitInterview::where('id', $id)->first();
        $data->save();

        return redirect()->route('karyawan.exit-interview.index')->with('message-success', 'Data berhasil disimpan');
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = ExitInterview::where('id', $id)->first();
        $data->delete();

        return redirect()->route('karyawan.exit-interview.index')->with('message-sucess', 'Data berhasi di hapus');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {

        $this->validate($request,[
            'resign_date'                       => 'required',
            'exit_interview_reason'             => 'required',
            'hal_berkesan'                      => 'required',
            'hal_tidak_berkesan'                => 'required',
            'masukan'                           => 'required',
            'kegiatan_setelah_resign'           => 'required',
            //'tujuan_perusahaan_baru'            => 'required',
            //'jenis_bidang_usaha'                => 'required',
            //'inventory_it_username_pc'          => 'required',
            //'inventory_it_password_pc'          => 'required',
            //'inventory_it_email'                => 'required',
            //'inventory_it_username_arium'       => 'required',
            //'inventory_it_password_arium'       => 'required',
        ]);

        $data       = new ExitInterview();
        $data->status               = 1;
        $data->user_id              = \Auth::user()->id; 
        $data->resign_date          = date('Y-m-d', strtotime($request->resign_date));   
        //$data->is_approved_hr_manager   = 0;
        //$data->is_approved_hr_gm        = 0;
        //$data->is_approved_hr_director  = 0;

       
        $data->approved_atasan_id     = $request->atasan_user_id; 
        $data->is_approved_atasan        = 0;

        $atasan = \App\User::where('id', $request->atasan_user_id)->first();

        if($request->exit_interview_reason == 'other')
            $data->other_reason = $request->other_reason;
        else
            $data->exit_interview_reason = $request->exit_interview_reason;

        $data->hal_berkesan             = $request->hal_berkesan;
        $data->hal_tidak_berkesan       = $request->hal_tidak_berkesan;
        $data->masukan                  = $request->masukan;
        $data->kegiatan_setelah_resign  = $request->kegiatan_setelah_resign;
        $data->tujuan_perusahaan_baru   = $request->tujuan_perusahaan_baru;
        $data->jenis_bidang_usaha       = $request->jenis_bidang_usaha;
        $data->inventory_it_username_pc   = $request->inventory_it_username_pc;
        $data->inventory_it_password_pc   = $request->inventory_it_password_pc;
        $data->inventory_it_email         = $request->inventory_it_email;
        $data->inventory_it_username_arium= $request->inventory_it_username_arium;
        $data->inventory_it_password_arium= $request->inventory_it_password_arium;
        $data->save();

        // DOCUMENT LIST
        foreach(list_exit_clearance_document() as $i)
        {
            $doc = new \App\ExitClearanceDocument();
            $doc->exit_interview_id     = $data->id;
            $doc->name                  = $i['item'];
            $doc->form_no               = $i['form_no'];   
            $doc->save();
        }

        // INVENTORY RETURN HRD
        foreach(list_exit_clearance_inventory_to_hrd() as $i)
        {
            $doc = new \App\ExitClearanceInventoryHrd();
            $doc->exit_interview_id     = $data->id;
            $doc->name                  = $i['item'];
            $doc->save();
        }
        
        // INVENTORY RETURN GA
        foreach(list_exit_clearance_inventory_to_ga() as $i)
        {
            $doc = new \App\ExitClearanceInventoryGa();
            $doc->exit_interview_id     = $data->id;
            $doc->name                  = $i['item'];
            $doc->save();
        }

        // INVENTORY RETURN GA
        foreach(list_exit_clearance_inventory_to_it() as $i)
        {
            $doc = new \App\ExitClearanceInventoryIt();
            $doc->exit_interview_id     = $data->id;
            $doc->name                  = $i['item'];
            $doc->save();
        }

        // INVENTARIS
        if(isset($request->inventaris))
        {
            foreach($request->inventaris as $item)
            {
                $new                        = new \App\ExitInterviewInventaris();
                $new->user_inventaris_id    = $item;
                $new->exit_interview_id     = $data->id; 
                $new->save();
            }
        }

        // INVENTARIS
        if(isset($request->inventaris_mobil))
        {
            foreach($request->inventaris_mobil as $item)
            {
                $new                                = new \App\ExitInterviewInventarisMobil();
                $new->user_inventaris_mobil_id      = $item;
                $new->exit_interview_id             = $data->id; 
                $new->save();
            }
        }

        return redirect()->route('karyawan.exit-interview.index')->with('message-success', 'Form Exit Interview & Exit Clearance berhasil di proses !');
    }
}
