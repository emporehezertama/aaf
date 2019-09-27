<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApprovalExitAtasanController extends Controller
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
        $params['data'] = \App\ExitInterview::where('approved_atasan_id', \Auth::user()->id)->orderBy('id', 'DESC')->get();

        return view('karyawan.approval-exit-atasan.index')->with($params);
    }

    /**
     * [proses description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function proses(Request $request)
    {
        $exit                           = \App\ExitInterview::where('id', $request->id)->first();
        $exit->is_approved_atasan       = $request->status;
        $exit->noted_atasan             = $request->noted_atasan;
        $exit->date_approved_atasan     = date('Y-m-d H:i:s');
        $exit->save();   

        return redirect()->route('karyawan.approval.exit-atasan.index')->with('messages-success', 'Form Cuti Berhasil diproses !');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {   
        $params['data']                                 = \App\ExitInterview::where('id', $id)->first();
        $params['list_exit_clearance_document']         = \App\ExitClearanceDocument::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_hrd'] = \App\ExitClearanceInventoryHrd::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_ga']  = \App\ExitClearanceInventoryGa::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_it']  = \App\ExitClearanceInventoryIt::where('exit_interview_id', $id)->get();

        return view('karyawan.approval-exit-atasan.detail')->with($params);
    }
}
