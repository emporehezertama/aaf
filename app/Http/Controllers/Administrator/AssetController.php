<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AssetController extends Controller
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
        $params['data'] = \App\Asset::orderBy('id', 'DESC')->get();

        return view('administrator.asset.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        $params['asset_type'] = \App\AssetType::all();
        
        return view('administrator.asset.create')->with($params);
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data']         = \App\Asset::where('id', $id)->first();

        return view('administrator.asset.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data       = \App\Asset::where('id', $id)->first();
        $data->asset_number     = $request->asset_number; 
        $data->asset_name       = $request->asset_name;
        $data->asset_type_id    = $request->asset_type_id;
        $data->asset_sn         = $request->assset_sn;
        $data->purchase_date    = $request->purchase_date;
        $data->asset_condition  = $request->asset_condition;
        $data->assign_to        = $request->assign_to;
        $data->user_id          = $request->user_id;
        $data->save();

        return redirect()->route('administrator.asset.index')->with('message-success', 'Data berhasil disimpan');
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = \App\Asset::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.asset.index')->with('message-sucess', 'Data berhasi di hapus');
    } 

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $data       = new \App\Asset();
        $data->asset_number     = $request->asset_number; 
        $data->asset_name       = $request->asset_name;
        $data->asset_type_id    = $request->asset_type_id;
        $data->asset_sn         = $request->assset_sn;
        $data->purchase_date    = $request->purchase_date;
        $data->asset_condition  = $request->asset_condition;
        $data->assign_to        = $request->assign_to;
        $data->user_id          = $request->user_id;
        $data->save();

        return redirect()->route('administrator.asset.index')->with('message-success', 'Data berhasil disimpan !');
    }
}
