<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DepartmentController extends Controller
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
        $params['data'] = \App\OrganisasiDepartment::all();

        return view('administrator.department.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
    //    $params['directorate']  = \App\OrganisasiDirectorate::all();
        $params['directorate']  = \App\Directorate::all();
        $params['division']     = \App\OrganisasiDivision::all();

        return view('administrator.department.create')->with($params);
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
    //    $params['directorate']  = \App\OrganisasiDirectorate::all();
        $params['directorate']  = \App\Directorate::all();
        $params['division']     = \App\OrganisasiDivision::all();
        $params['data']         = \App\OrganisasiDepartment::where('id', $id)->first();

        return view('administrator.department.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
    //    $data       = \App\Department::where('id', $id)->first();
        $data       = \App\OrganisasiDepartment::where('id', $id)->first();
        //$data->directorate_id   = $request->directorate_id; 
        $data->organisasi_division_id      = $request->division_id;
        $data->name             = $request->name;
        $data->save();

        return redirect()->route('administrator.department.index')->with('message-success', 'Data berhasil disimpan');
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        //$data = \App\OrganisasiDivision::where('id', $id)->first();
        $data = \App\OrganisasiDepartment::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.department.index')->with('message-sucess', 'Data berhasi di hapus');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        //$data       = new Department();
        $data       = new \App\OrganisasiDepartment();
        //$data->organisasi_directorate_id   = $request->directorate_id; 
        $data->organisasi_division_id      = $request->division_id;
        $data->name             = $request->name;
        $data->save();



        // $params['text']     = '<p><strong>Dear Bapak/Ibu </strong>,</p> <p>  Pengajuan Cuti / Ijin anda <strong style="color: red;">DITOLAK</strong>.</p>';
        //     // send email
        //     \Mail::send('email.test-email', $params,
        //         function($message) use($request) {
        //             $message->from('emporeht@gmail.com');
        //             $message->to('farros@empore.co.id');
        //             $message->subject('PT. Arthaasia Finance - Pengajuan Cuti / Izin');
        //         }
        //     ); 

        return redirect()->route('administrator.department.index')->with('message-success', 'Data berhasil disimpan !');
    }
}
