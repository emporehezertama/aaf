<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\PaymentRequest;
use App\PaymentRequestForm;
use App\User;

class PaymentRequestController extends Controller
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
        $params['data'] = PaymentRequest::orderBy('id', 'DESC')->get();

        return view('administrator.payment-request.index')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {   
        $params['karyawan'] = User::where('access_id', 2)->get();

        return view('administrator.payment-request.create')->with($params);
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data']         = PaymentRequest::where('id', $id)->first();
        $params['karyawan']     = User::where('access_id', 2)->get();
        $params['form']         = PaymentRequestForm::where('payment_request_id', $id)->get();

        return view('administrator.payment-request.edit')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data       = PaymentRequest::where('id', $id)->first();
        $data->save();

        return redirect()->route('administrator.payment-request.index')->with('message-success', 'Data berhasil disimpan');
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = PaymentRequest::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.payment-request.index')->with('message-sucess', 'Data berhasi di hapus');
    } 

    /**
     * [batal description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function batal(Request $request)
    {
        $data = \App\PaymentRequest::where('id', $request)->first();
        $data->note_pembatalan = $request->note;
        $data->status = 4;
        $data->save();

        return redirect()->route('administrator.payment-request.index')->with('messages-success', 'Payment Request Berhasil di batalkan !');
    }

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        $data                       = new PaymentRequest();
        $data->user_id              = $request->from_user_id;
        $data->transaction_type     = $request->transaction_type;
        $data->payment_method       = $request->payment_method;
        $data->tujuan               = $request->tujuan;
        $data->nama_pemilik_rekening = $request->nama_pemilik_rekening;
        $data->no_rekening          = $request->no_rekening;
        $data->nama_bank            = $request->nama_bank;
        $data->nominal_pembayaran   = $request->nominal_pembayaran;
        $data->status               = 1;
        $data->save();

        $form = new PaymentRequestForm();
        foreach($request->description as $key => $item)
        {
            $form->payment_request_id   = $data->id;
            $form->description          = $item;
            $form->quantity             = $request->quantity[$key];
            $form->estimation_cost      = $request->estimation_cost[$key];
            $form->amount               = $request->amount[$key];
            $form->save();
        }

        return redirect()->route('administrator.payment-request.index')->with('message-success', 'Data berhasil disimpan !');
    }
}
