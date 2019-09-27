<?php

namespace App\Http\Controllers\Administrator;

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
        $params['data'] = ExitInterview::orderBy('id', 'DESC')->get();

        return view('administrator.exit-interview.index')->with($params);
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {
        $params['data'] = ExitInterview::where('id', $id)->first();

        $approval = new \stdClass;
        $approval->nama_approval = 'HRD';
        $approval->user_id = \Auth::user()->id;
        $params['approval'] = $approval;

        $params['list_exit_clearance_document'] = \App\ExitClearanceDocument::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_hrd'] = \App\ExitClearanceInventoryHrd::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_ga'] = \App\ExitClearanceInventoryGa::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_it'] = \App\ExitClearanceInventoryIt::where('exit_interview_id', $id)->get();

        return view('administrator.exit-interview.detail')->with($params);
    }

    /**
     * [proses description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function proses(Request $request)
    {
        if(isset($request->check_dokument))
        {
            foreach($request->check_dokument as $k => $item)
            {
                if(!empty($item))
                {
                    $doc = \App\ExitClearanceDocument::where('id', $k)->first();

                    if($doc->hrd_checked == 0)
                    {
                        $doc->hrd_check_date = date('Y-m-d H:i:s');                        
                    } 

                    $doc->hrd_checked = 1;
                    $doc->hrd_note = $request->check_document_catatan[$k];
                    $doc->save();
                }
            }
        }

        if(isset($request->check_inventory_hrd))
        {
            foreach($request->check_inventory_hrd as $k => $item)
            {
                if(!empty($item))
                {
                    $doc = \App\ExitClearanceInventoryHrd::where('id', $k)->first();
                    
                    if($doc->hrd_checked == 0)
                    {
                        $doc->hrd_check_date = date('Y-m-d H:i:s');                        
                    } 

                    $doc->hrd_checked = 1;
                    $doc->hrd_note = $request->check_inventory_hrd_catatan[$k];
                    $doc->save();
                }
            }
        }

        $exit = \App\ExitInterview::where('id', $request->id)->first();

        if($exit)
        {
            $exit->is_approved_hrd = $request->is_approved_hrd;
            
            if($request->proses == 1)
            {
                if($request->is_approved_hrd == 1)
                {
                    $exit->status = 2;   
                }
                else
                {
                    $exit->status = 3;   
                }
            }

            $exit->kerugian_perusahaan_check    = $request->kerugian_perusahaan_check;
            $exit->kerugian_perusahaan_note     = $request->kerugian_perusahaan_note;

            $exit->save();
        }

        if(isset($request->inventaris_mobil))
        {
            foreach($request->inventaris_mobil as $item)
            {
                $inventaris_mobil = \App\ExitInterviewInventarisMobil::where('id', $item)->first();
                $inventaris_mobil->status = $request->check_inventaris_mobil[$item];
                $inventaris_mobil->catatan = $request->catatan_inventaris_mobil[$item];
                $inventaris_mobil->save();
            }
        }

        if(isset($request->inventaris))
        {
            foreach($request->inventaris as $item)
            {
                $inventaris_mobil = \App\ExitInterviewInventaris::where('id', $item)->first();
                $inventaris_mobil->status = $request->check_inventaris[$item];
                $inventaris_mobil->catatan = $request->catatan_inventaris[$item];
                $inventaris_mobil->save();
            }
        }

        return redirect()->route('administrator.exit-interview.index')->with('message-success', 'Form Exit Exit Interview & Exit Clearance Berhasil di update');
    }
}
