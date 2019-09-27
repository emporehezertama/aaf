<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ModelUser;
use Auth;
use Session;
use Illuminate\Support\Facades\Input;
use App\Directorate;
use App\Division;
use App\Department;
use App\Section;
use App\Provinsi;
use App\Kabupaten;
use App\Kecamatan;
use App\Kelurahan;
use App\User;

class AjaxController extends Controller
{
    protected $respon;

    /**
     * [__construct description]
     */
    public function __construct()
    {
        /**
         * [$this->respon description]
         * @var [type]
         */
        $this->respon = ['message' => 'error'];
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ;
    }

    /**
     * [getKaryawanApproval description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getKaryawanApproval(Request $request)
    {
        $params = [];
        if($request->ajax())
        {
            // Skip Exist User
            $approvalExistUser = \App\SettingApproval::select('user_id')->where('jenis_form', $request->jenis_form)->get()->toArray();
            
            // SKIP SUPERADMIN, ACCESS_ID 1
            $data =  \App\User::whereNotIn('id', $approvalExistUser)->where(function($table) use ($request) {

                $table->where('name', 'LIKE', "%". $request->name . "%")
                ->orWhere('nik', 'LIKE', '%'. $request->name .'%');  
            })->where('access_id', 2)->get();

            $params = [];
            foreach($data as $k => $item)
            {
                if($k >= 10) continue;

                $params[$k]['id'] = $item->id;
                $params[$k]['value'] = $item->nik .' - '. $item->name;
            }
        }
        
        return response()->json($params); 
    }

    /**
     * [getKaryawanManagerUp description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getKaryawanManagerUp(Request $request)
    {
        $params = [];
        if($request->ajax())
        {
            $approvalExistUser = \App\SettingApproval::select('user_id')->where('jenis_form', 'overtime')->get()->toArray();

            $data =  \App\User::select('users.*')->join('organisasi_position', 'organisasi_position.id', 'users.organisasi_position')->where(function($table) use ($request){
                    $table->where('users.name', 'LIKE', "%". $request->name . "%");
                    $table->orWhere('users.nik', 'LIKE', '%'. $request->name .'%');
                })
                ->whereNotIn('users.id', $approvalExistUser)
                ->where('organisasi_position.name', '<>', 'Staff')
                ->where('organisasi_position.name', '<>', 'Head')
                ->where('organisasi_position.name', '<>', 'Supervisor')
                ->where('access_id', '<>', 1)
                ->get();
            
            $params = [];
            foreach($data as $k => $item)
            {
                if($k >= 10) continue;

                $params[$k]['id'] = $item->id;
                $params[$k]['value'] = $item->nik .' - '. $item->name;
            }

            return response()->json($params);
        }
    }

    /**
     * [calculateHoursTime description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function calculateHoursTime(Request $request)
    {
        $params = ['message' => 'success'];
        
        if($request->ajax())
        {
            $params['data'] = sum_lembur_jam($request->data);
        }   
        
        return response()->json($params);
    }

    /**
     * [updateFirstPassword description]
     * @return [type] [description]
     */
    public function updatePassword(Request $request)
    {
        $params = ['message' => 'success'];
        
        if($request->ajax())
        {
            $data               = \App\User::where('id', $request->id)->first();
            $data->password     = bcrypt($request->password);
            $data->is_reset_first_password = 1; 
            $data->last_change_password = date('Y-m-d H:i:s');
            $data->save();

            \Session::flash('message-success', 'Password berhasil di rubah');
        }   
        
        return response()->json($params);
    }

    /**
     * [updateInventarisMobil description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateInventarisMobil(Request $request)
    {
        $params = ['message' => 'success'];
        
        if($request->ajax())
        {
            $data = \App\UserInventarisMobil::where('id', $request->id)->first();
            $data->tipe_mobil           = $request->tipe_mobil;
            $data->tahun                = $request->tahun;
            $data->no_polisi            = $request->no_polisi;
            $data->status_mobil         = $request->status_mobil;
            $data->save();

            \Session::flash('message-success', 'Data Cuti Berhasil di update');
        }   
        
        return response()->json($params);
    }

    /**
     * [updateInventarisLainnya description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateInventarisLainnya(Request $request)
    {
        $params = ['message' => 'success'];
        
        if($request->ajax())
        {
            $data = \App\UserInventaris::where('id', $request->id)->first();
            $data->jenis            = $request->jenis;
            $data->description      = $request->description;
            $data->save();

            \Session::flash('message-success', 'Data Inventaris Berhasil di update');
        }   
        
        return response()->json($params);
    }

    /**
     * [updateCuti description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateCuti(Request $request)
    {
        $params = ['message' => 'success'];
        
        if($request->ajax())
        {
            $data = \App\UserCuti::where('id', $request->id)->first();
            $data->cuti_id          = $request->cuti_id;
            $data->kuota            = $request->kuota;
            $data->cuti_terpakai    = $request->terpakai;
            $data->sisa_cuti        = $request->kuota - $request->terpakai;
            $data->save();

            \Session::flash('message-success', 'Data Cuti Berhasil di update');
        }   
        
        return response()->json($params);
    }

    /**
     * [updateEducation description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateEducation(Request $request)
    {
        $params = ['message' => 'success'];
        
        if($request->ajax())
        {
            $data = \App\UserEducation::where('id', $request->id)->first();
            $data->pendidikan       = $request->pendidikan;
            $data->tahun_awal       = $request->tahun_awal;
            $data->tahun_akhir      = $request->tahun_akhir;
            $data->fakultas         = $request->fakultas;
            $data->jurusan          = $request->jurusan;
            $data->nilai            = $request->nilai;
            $data->kota             = $request->kota;
            $data->save();

            \Session::flash('message-success', 'Data Education Berhasil di update');
        }   
        
        return response()->json($params);
    }

    /**
     * [updateDependent description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function updateDependent(Request $request)
    {
        $params = ['message' => 'success'];
        
        if($request->ajax())
        {
            $data = \App\UserFamily::where('id', $request->id)->first();
            $data->nama             = $request->nama;
            $data->hubungan         = $request->hubungan;
            $data->tempat_lahir     = $request->tempat_lahir;
            $data->tanggal_lahir    = $request->tanggal_lahir;
            $data->tanggal_meninggal= $request->tanggal_meninggal;
            $data->jenjang_pendidikan=$request->jenjang_pendidikan;
            $data->pekerjaan        = $request->pekerjaan;
            $data->tertanggung      = $request->tertanggung;
            $data->save();

            \Session::flash('message-success', 'Data Dependent Berhasil di update');
        }   
        
        return response()->json($params);
    }

    /**
     * [getKaryawan description]
     * @return [type] [description]
     */
    public function getKaryawan(Request $request)
    {
        $params = [];
        if($request->ajax())
        {
            $data =  \App\User::where('name', 'LIKE', "%". $request->name . "%")->orWhere('nik', 'LIKE', '%'. $request->name .'%')->get();

            $params = [];
            foreach($data as $k => $item)
            {
                if($k >= 10) continue;

                $params[$k]['id'] = $item->id;
                $params[$k]['value'] = $item->nik .' - '. $item->name;
            }
        }
        
        return response()->json($params); 
    }

    /**
     * [getAirports description]
     * @return [type] [description]
     */
    public function getAirports(Request $request)
    {
        $data = [];
        if($request->ajax())
        {
            if(strlen($request->word) >=3 ) 
            { 
                $data =  \App\Airports::where('name', 'LIKE', "%". $request->word . "%")->orWhere('cityName', 'LIKE', '%'. $request->word .'%')->orWhere('countryName', 'LIKE', '%'. strtoupper($request->word) .'%')->groupBy('code')->get();

                $params = [];
                foreach($data as $k => $item)
                {
                    if($k == 10) continue;

                    $params[$k] = $item;
                    $params[$k]['value'] = $item->name .' - '. $item->cityName;
                }
            }
        }
        
        return response()->json($params);   
    }


    /**
     * [getHistoryApprovalOvertime description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalMedical(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\MedicalReimbursement::where('id', $request->foreign_id)->first();

            $skip_gm_hr = ['Staff', 'Head','Supervisor'];
            
            if(isset($data->user->organisasiposition->name))
            {
                if(in_array($data->user->organisasiposition->name, $skip_gm_hr)){

                    $data->show_gm_hr = 'no';
                }
                else
                {
                    $data->show_gm_hr = 'yes';
                }
            }
            else
            {
                $data->show_gm_hr = 'no';
            }

            $data->hr_benefit   = $data->hr_benefit;
            $data->manager_hr   = $data->manager_hr;
            $data->gm_hr        = $data->gm_hr;

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getHistoryApprovalOvertime description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalOvertime(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\OvertimeSheet::where('id', $request->foreign_id)->first();

            $data->atasan = $data->atasan;
            $data->hr_benefit = $data->hr_benefit;
            $data->hr_manager = $data->hr_manager;

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getHistoryApprovalPaymentRequest description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function getHistoryApprovalPaymentRequest(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\PaymentRequest::where('id', $request->foreign_id)->first();

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getStatusApproval description]
     * @return [type] [description]
     */
    public function getHistoryApproval(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\StatusApproval::where('jenis_form', $request->jenis_form)->where('foreign_id', $request->foreign_id)->get();

            $obj = [];
            foreach($data as $key => $item)
            {
                $obj[$key] = $item;
                $obj[$key]['user_approval'] = $item->user_approval;
            }

            return response()->json(['message' => 'success', 'data' => $obj]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getHistoryApprovalCuti description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalCuti(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\CutiKaryawan::where('id', $request->foreign_id)->first();

            $atasan = \App\User::where('id', $data->approved_atasan_id)->first();
            
            $data->atasan = "";

            if(isset($atasan->name))
            {
                $data->atasan = $atasan->name .' / '. (isset($atasan->organisasiposition->name) ? $atasan->organisasiposition->name : '');
            }

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }


    /**
     * [getHistoryApprovalCuti description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalExit(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\ExitInterview::where('id', $request->id)->first();

            if(isset($data->atasan))
            {
                $data->nama_atasan = $data->atasan->name .' / '. $data->atasan->organisasiposition->name;
            }


            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getHistoryApprovalCuti description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalTraining(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\Training::where('id', $request->foreign_id)->first();

            $atasan = \App\User::where('id', $data->approved_atasan_id)->first();
            
            $data->atasan = "";

            if(isset($atasan))
            {
                $data->atasan = $atasan->name .' / '. $atasan->organisasiposition->name;
            }


            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getHistoryApprovalTrainingBill description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getHistoryApprovalTrainingBill(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\Training::where('id', $request->foreign_id)->first();

            $atasan = \App\User::where('id', $data->approved_atasan_id)->first();
            
            $data->atasan = "";

            if(isset($atasan))
            {
                $data->atasan = $atasan->name .' / '. $atasan->organisasiposition->name;
            }


            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeHrOperation description]
     * @param Request $request [description]
     */
    public function addSettingTrainingGaDepartment(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'training_mengetahui';
            $data->user_id      = $request->id;
            $data->nama_approval= 'GA Department';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerDepartment description]
     * @param Request $request [description]
     */
    public function addSettingOvertimeManagerDepartment(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'overtime';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Manager Department';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerDepartment description]
     * @param Request $request [description]
     */
    public function addSettingExitHRD(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'exit_clearance';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HRD';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerDepartment description]
     * @param Request $request [description]
     */
    public function addSettingExitGA(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'exit_clearance';
            $data->user_id      = $request->id;
            $data->nama_approval= 'GA';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerDepartment description]
     * @param Request $request [description]
     */
    public function addSettingExitIT(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'exit_clearance';
            $data->user_id      = $request->id;
            $data->nama_approval= 'IT';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerDepartment description]
     * @param Request $request [description]
     */
    public function addSettingExitAccounting(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'exit_clearance';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Accounting';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeHrOperation description]
     * @param Request $request [description]
     */
    public function addSettingTrainingHRD(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'training';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HRD';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

     /**
     * [addSettingOvertimeHrOperation description]
     * @param Request $request [description]
     */
    public function addSettingTrainingFinance(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'training';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Finance';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeHrOperation description]
     * @param Request $request [description]
     */
    public function addSettingOvertimeHrOperation(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'overtime';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HR Operation';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeHrOperation description]
     * @param Request $request [description]
     */
    public function addSettingExitHrDirector(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'exit';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HR Director';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingExitHRGM description]
     * @param Request $request [description]
     */
    public function addSettingExitHRGM(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'exit';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HR GM';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingExitHrManager description]
     * @param Request $request [description]
     */
    public function addSettingExitHrManager(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'exit';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HR Manager';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingOvertimeManagerHR description]
     * @param Request $request [description]
     */
    public function addSettingOvertimeManagerHR(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'overtime';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Manager HR';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingMedicalGMHR description]
     * @param Request $request [description]
     */
    public function addSettingMedicalGMHR(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'medical';
            $data->user_id      = $request->id;
            $data->nama_approval= 'GM HR';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingMedicalManagerHR description]
     * @param Request $request [description]
     */
    public function addSettingMedicalManagerHR(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'medical';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Manager HR';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addSettingMedicalHRBenefit description]
     * @param Request $request [description]
     */
    public function addSettingMedicalHRBenefit(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'medical';
            $data->user_id      = $request->id;
            $data->nama_approval= 'HR Benefit';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addInvetarisMobil description]
     * @param Request $request [description]
     */
    public function addInvetarisMobil(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\UserInvetarisMobil();
            $data->user_id      = $request->user_id;
            $data->tipe_mobil   = $request->tipe_mobil;
            $data->tahun        = $request->tahun;
            $data->no_polisi    = $request->no_polisi;
            $data->status_mobil = $request->status_mobil;
            $data->save();
            
            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addtSettingPaymentRequestApproval description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addtSettingPaymentRequestApproval(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'payment_request';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Approval';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }


    /**
     * [addtSettingPaymentRequestApproval description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addtSettingPaymentRequestVerification(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'payment_request';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Verification';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }


    /**
     * [addtSettingPaymentRequestApproval description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addtSettingPaymentRequestPayment(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'payment_request';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Payment';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addtSettingCutiPersonalia description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addtSettingCutiPersonalia(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'cuti';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Personalia';
            $data->save();

            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [addtSettingCutiAtasan description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function addtSettingCutiAtasan(Request $request)
    {
        if($request->ajax())
        {
            $data               = new \App\SettingApproval();
            $data->jenis_form   = 'cuti';
            $data->user_id      = $request->id;
            $data->nama_approval= 'Atasan';
            $data->save();
            
            Session::flash('message-success', 'User Approval berhasil di tambahkan');

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getKaryawanById description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getKaryawanById(Request $request)
    {
        if($request->ajax())
        {
            $data = User::where('id', $request->id)->first();

            $data->department_name  = isset($data->department) ? $data->department->name : '';
            $data->cabang_name      = isset($data->cabang->name) ? $data->cabang->name : '';

            $data->dependent =  \App\UserFamily::where('user_id', $data->id)->get();

            return response()->json(['message' => 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getKabupateByProvinsi description]
     * @return [type] [description]
     */
    public function getKabupatenByProvinsi(Request $request)
    {
        if($request->ajax())
        {
            $kabupaten = Kabupaten::where('id_prov', $request->id)->get();

            return response()->json(['message' => 'success', 'data' => $kabupaten]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getKecamatanByKabupaten description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getKecamatanByKabupaten(Request $request)
    {
        if($request->ajax())
        {
            $kabupaten = Kecamatan::where('id_kab', $request->id)->get();

            return response()->json(['message' => 'success', 'data' => $kabupaten]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getKelurahanByKecamatan description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getKelurahanByKecamatan(Request $request)
    {
        if($request->ajax())
        {
            $kabupaten = Kelurahan::where('id_kec', $request->id)->get();

            return response()->json(['message' => 'success', 'data' => $kabupaten]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getDivisionByDirectorate description]
     * @return [type] [description]
     */
    public function getDepartmentByDivision(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\OrganisasiDepartment::where('organisasi_division_id', $request->id)->get();
        
            return response()->json(['message'=> 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getPositionBySection description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getPositionBySection(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\OrganisasiPosition::where('organisasi_division_id', $request->organisasi_division_id)->where('organisasi_department_id', $request->organisasi_department_id)->where('organisasi_unit_id', $request->id)->get();
        
            return response()->json(['message'=> 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getDepartmentByDivision description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function getSectionByDepartment(Request $request)
    {
        if($request->ajax())
        {
            $data = \App\OrganisasiUnit::where('organisasi_department_id', $request->id)->get();
        
            return response()->json(['message'=> 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getDivisionByDirectorate description]
     * @return [type] [description]
     */
    public function getDivisionByDirectorate(Request $request)
    {
        if($request->ajax())
        {
            $data = Division::where('directorate_id', $request->id)->get();
        
            return response()->json(['message'=> 'success', 'data' => $data]);
        }

        return response()->json($this->respon);
    }

    /**
     * [getStructureBranch description]
     * @return [type] [description]
     */
    public function getStructureBranch()
    {
        foreach(\App\BranchHead::all() as $k => $item)
        {
            $data[$k]['name'] = 'Head';
            $data[$k]['title'] = $item->name;
            $data[$k]['children'] = [];

            foreach(\App\BranchStaff::where('branch_head_id', $item->id)->get() as $k2 => $i2)
            {
                $data[$k]['children'][$k2]['title'] = $i2->name;
                $data[$k]['children'][$k2]['name'] = 'Staff';
            }
        }

        return response()->json($data);
    }

    /**
     * [getStructure description]
     * @return [type] [description]
     */
    public function getStructure()
    {
        $data = [];

        $directorate = Directorate::all();
        foreach($directorate as $key_dir => $dir)
        {
            $data[$key_dir]['name'] = 'Directorate';
            $data[$key_dir]['title'] = $dir->name;
            $data[$key_dir]['children'] = [];

            $num_key_div = 0;
            foreach(get_division_by_directorate_id($dir->id) as $key_div => $div)
            {
                $data[$key_dir]['children'][$key_div]['name'] = 'Division';
                $data[$key_dir]['children'][$key_div]['title'] = $div->name;

                foreach(get_department_by_division_id($div->id) as $key_dept => $dept)
                {
                    $data[$key_dir]['children'][$key_div]['children'][$key_dept]['name'] = 'Department';
                    $data[$key_dir]['children'][$key_div]['children'][$key_dept]['title'] = $dept->name;

                    foreach(get_section_by_department_id($dept->id) as $key_sec => $sec)
                    {
                        $data[$key_dir]['children'][$key_div]['children'][$key_dept]['children'][$key_sec]['name'] = 'Section';
                        $data[$key_dir]['children'][$key_div]['children'][$key_dept]['children'][$key_sec]['title'] = $sec->name;
                    } 
                }
                $num_key_div++;
            }
        }

        return response()->json($data);
    } 
}