<?php

namespace App\Http\Controllers\Karyawan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApprovalExitController extends Controller
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
        // cek jenis user
        $approval = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','exit_clearance')->first();
        $params['data'] = [];

        if($approval)
        {
            if($approval->nama_approval =='HRD')
            {
                $params['data'] = \App\ExitInterview::where('status', 1)->where('is_approved_atasan', 1)->orderBy('id', 'DESC')->get();
            }

            if($approval->nama_approval =='GA')
            {
                $params['data'] = \App\ExitInterview::where('status', 1)->where('is_approved_atasan', 1)->orderBy('id', 'DESC')->get();
            }

            if($approval->nama_approval =='IT')
            {
                $params['data'] = \App\ExitInterview::where('status', 1)->where('is_approved_atasan', 1)->orderBy('id', 'DESC')->get();
            }
        }

        return view('karyawan.approval-exit.index')->with($params);
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
        $status->jenis_form             = 'exit';
        $status->foreign_id             = $request->id;
        $status->status                 = $request->status;
        $status->noted                  = $request->noted;

        $approval = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','exit')->first();
        
        $exit = \App\ExitInterview::where('id', $request->id)->first();        
        if($approval)
        {
            if($approval->nama_approval =='HRD')
            {
                $exit->is_approved_hrd = 1;
            }

            if($approval->nama_approval =='GA')
            {
                $exit->is_approved_ga = 1;
            }

            if($approval->nama_approval =='IT')
            {
                $exit->is_approved_it = 1;
            }   
        }
        $exit->save();    

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

        if(isset($request->check_inventory_ga))
        {
            foreach($request->check_inventory_ga as $k => $item)
            {
                if(!empty($item))
                {
                    $doc = \App\ExitClearanceInventoryGa::where('id', $k)->first();
                    
                    if($doc->ga_checked == 0)
                    {
                        $doc->ga_check_date = date('Y-m-d H:i:s');                        
                    } 

                    $doc->ga_checked = 1;
                    $doc->ga_note = $request->check_inventory_ga_catatan[$k];
                    $doc->save();
                }
            }
        }

        // $exit = \App\ExitInterview::where('id', $request->id)->first();
        // if($exit->is_approved_hr_manager ==1 and $exit->is_approved_hr_gm ==1 and $exit->is_approved_hr_director == 1)
        // {
        //     // cek semua approval
        //     $status = \App\StatusApproval::where('jenis_form', 'exit')
        //                                     ->where('foreign_id', $request->id)
        //                                     ->where('status', 0)
        //                                     ->count();

        //     $exit = \App\ExitInterview::where('id', $request->id)->first();
        //     if($status >=1)
        //     {
        //         $status = 3;

        //         // send email atasan
        //         $objDemo = new \stdClass();
        //         $objDemo->content = '<p>Dear '. $exit->user->name .'</p><p> Pengajuan Exit Interview dan Exit Clearance anda ditolak.</p>' ;
        //     }
        //     else
        //     {
        //         // send email atasan
        //         $objDemo = new \stdClass();
        //         $objDemo->content = '<p>Dear '. $exit->user->name .'</p><p> Pengajuan Exit Interview dan Exit Clearance anda disetujui.</p>' ;

        //         $status = 2;
        //     }

        //     //\Mail::to('doni.enginer@gmail.com')->send(new \App\Mail\GeneralMail($objDemo));

        //     $exit->status = $status;
        //     $exit->save();
        // }

        return redirect()->route('karyawan.approval.exit.index')->with('message-success', 'Form Berhasil diproses !');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {   
        $params['data'] = \App\ExitInterview::where('id', $id)->first();
        $params['approval'] = \App\SettingApproval::where('user_id', \Auth::user()->id)->where('jenis_form','exit_clearance')->first();

        $params['list_exit_clearance_document'] = \App\ExitClearanceDocument::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_hrd'] = \App\ExitClearanceInventoryHrd::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_ga'] = \App\ExitClearanceInventoryGa::where('exit_interview_id', $id)->get();
        $params['list_exit_clearance_inventory_to_it'] = \App\ExitClearanceInventoryIt::where('exit_interview_id', $id)->get();

        return view('karyawan.approval-exit.detail')->with($params);
    }
}
