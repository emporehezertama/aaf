<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OvertimeSheet;
use App\OvertimeSheetForm;
use App\User;

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
        $params['data'] = OvertimeSheet::orderBy('id', 'DESC')->get();

        return view('administrator.overtime.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        $params['karyawan'] = User::where('access_id', 2)->get();

        return view('administrator.overtime.create')->with($params);
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data'] = \App\OvertimeSheet::where('id', $id)->first();

        return view('administrator.overtime.edit')->with($params);
    }

    /**
     * [batal description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function batal(Request $request)
    {   
        $data       = \App\OvertimeSheet::where('id', $request->id)->first();
        $data->status = 4;
        $data->note_pembatalan = $request->note;
        $data->save(); 

        return redirect()->route('administrator.overtime.index')->with('message-success', 'Overtime Berhasil dibatalkan');
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data       = OvertimeSheet::where('id', $id)->first();
        $data->save();

        return redirect()->route('administrator.overtime.index')->with('message-success', 'Data berhasil disimpan');
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

        return redirect()->route('administrator.overtima.index')->with('message-sucess', 'Data berhasi di hapus');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $data               = new OvertimeSheet();
        $data->user_id      = $request->user_id;
        $data->status       = 1;  
        $data->save();

        foreach($request->form as $key => $item)
        {   
            $form               = new OvertimeSheetForm();
            $form->description  = $request->description[$key];
            $form->awal         = $request->awal[$key];
            $form->akhir        = $request->akhir[$key];
            $form->employee_id  = $request->employee_id[$key];
            $form->spv_id       = $request->spv_id;
            $form->manager_id   = $request->manager_id;
            $form->save();
        }

        return redirect()->route('administrator.overtime.index')->with('message-success', 'Data berhasil disimpan !');
    }
}
