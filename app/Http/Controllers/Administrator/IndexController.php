<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use App\Directorate;

class IndexController extends Controller
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
        $params[] = '';

        return view('administrator.index')->with($params);
    }

    /**
     * [structure description]
     * @return [type] [description]
     */
    public function structure()
    {
        $params['directorate'] = Directorate::all();

        return view('administrator.structure')->with($params);
    }

    /**
     * [profile description]
     * @return [type] [description]
     */
    public function profile()
    {
        return view('administrator.profile')->with(['data' => \Auth::user()]);
    }

    /**
     * [profileUpdate description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function profileUpdate(Request $request)
    {
        $data           = \App\User::where('id', \Auth::user()->id)->first();
        $data->name     = $request->name;
        $data->email    = $request->email;
        $data->telepon  = $request->telepon;
        $data->save();

        return redirect()->route('administrator.profile')->with('message-success', 'Update profile berhasil ');
    }
}
