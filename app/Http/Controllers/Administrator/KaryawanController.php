<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Department;
use App\Provinsi;
use App\UserEducation;
use App\Kabupaten;
use App\Kecamatan;
use App\Kelurahan;
use App\Division;
use App\Section;
use PHPExcel_Worksheet_Drawing;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class KaryawanController extends Controller
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
        $params['data'] = User::where('access_id', 2)->orderBy('id', 'DESC')->get();

        return view('administrator.karyawan.index')->with($params);
    }

    /**
     * [printProfile description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function printProfile($id)
    {
        $params['data'] = \App\User::where('id', $id)->first();

        $view = view('administrator.karyawan.print')->with($params);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadHTML($view);

        return $pdf->stream();
    }

    /**
     * [importAll description]
     * @return [type] [description]
     */
    public function importAll()
    {   
        ini_set('max_execution_time', 300);

        $temp = \App\UserTemp::all();
        foreach($temp as $item)
        {
            if(empty($item->nik)) continue;

            $cekuser = \App\User::where('nik', $item->nik)->first();
            
            if($cekuser)
            {
                $user  = $cekuser;
            }
            else
            {
                $user               = new \App\User();
                $user->nik          = $item->nik;
                $user->password     = bcrypt('password'); // set default password
            }

            $user->name             = empty($item->name) ? $user->name : $item->name;
            $user->join_date        = empty($item->join_date) ? $user->join_date : $item->join_date;
            $user->jenis_kelamin    = empty($item->gender) ? $user->jenis_kelamin : $item->gender;
            $user->marital_status   = empty($item->marital_status) ? $user->marital_status : $item->marital_status;
            $user->agama            = empty($item->agama) ? $user->agama : $item->agama;
            $user->bpjs_number      = empty($item->no_bpjs_kesehatan) ? $user->bpjs_kesehatan : $item->no_bpjs_kesehatan;
            $user->tempat_lahir     = empty($item->place_of_birth) ? $user->tempat_lahir : $item->place_of_birth ;
            $user->tanggal_lahir    = empty($item->date_of_birth) ? $user->tanggal_lahir : $item->date_of_birth ;
            $user->id_address       = empty($item->id_address) ? $user->id_address : $item->id_address;
            $user->id_city          = empty($item->id_city) ? $user->id_city : $item->id_city;
            $user->id_zip_code      = empty($item->id_zip_code) ? $user->id_zip_code : $item->id_zip_code;
            $user->current_address  = empty($item->current_address) ? $user->current_address : $item->current_address;
            $user->telepon          = empty($item->telp) ? $user->telepon : $item->telp;
            $user->mobile_1         = empty($item->mobile_1) ? $user->mobile_1 : $item->mobile_1;
            $user->mobile_2         = empty($item->mobile_2) ? $user->mobile_2 : $item->mobile_2;
            $user->access_id        = 2;
            $user->status           = 1;
            $user->blood_type       = empty($item->blood_type) ? $user->blood_type : $item->blood_type;

            if($item->email != "-") $user->email            = $item->email;
            // find bank
            $bank  = \App\Bank::where('name', 'LIKE', '%'. $item->bank_1 .'%')->first();
            if($bank) $user->bank_id = $bank->id;
            $user->nama_rekening        = $item->bank_account_name_1;
            $user->nomor_rekening       = $item->bank_account_number;

            $user->sisa_cuti            = $item->cuti_sisa_cuti;
            $user->cuti_yang_terpakai   = $item->cuti_terpakai;
            $user->length_of_service    = $item->cuti_length_of_service;
            $user->cuti_status          = $item->cuti_status;
            $user->cuti_2018            = $item->cuti_cuti_2018;

            // get division
            $user->division_id      = !empty($item->organisasi_division) ? $item->organisasi_division : $user->division_id ;
            $user->department_id    = !empty($item->organisasi_department) ? $item->organisasi_department : $user->department_id ;   
            $user->section_id       = !empty($item->organisasi_unit) ? $item->organisasi_unit : $user->section_id;
            $user->organisasi_job_role       = !empty($item->organisasi_position_sub) ? $item->organisasi_position_sub : $user->organisasi_job_role;
            $user->organisasi_position       = !empty($item->organisasi_position) ? $item->organisasi_position : $user->organisasi_position ;
            $user->cabang_id            = !empty($item->organisasi_branch) ? $item->organisasi_branch : $user->cabang_id;
            $user->branch_type          = strtoupper(!empty($item->organisasi_ho_or_branch) ? $item->organisasi_ho_or_branch : $user->branch_type);
            $user->organisasi_status    = !empty($item->organisasi_status) ? $item->organisasi_status : $user->organisasi_status;
            $user->save();

            if(!empty($item->cuti_cuti_2018) || !empty($item->cuti_terpakai) || !empty($item->cuti_sisa_cuti))
            {
                // cek exist cuti
                $c = \App\UserCuti::where('user_id', $user->id)->where('cuti_id', 1)->first();
                if(!$c)
                {
                    // insert data cuti
                    $c = new \App\UserCuti();
                    $c->user_id     = $user->id;
                }

                $c->cuti_id     = 1;
                if(!empty($item->cuti_status)) 
                {
                    $c->status      = $item->cuti_status;
                }

                if(!empty($item->cuti_cuti_2018))
                {
                    $c->kuota       = $item->cuti_cuti_2018;                    
                }
                
                if(!empty($item->cuti_terpakai))
                {
                    $c->cuti_terpakai= $item->cuti_terpakai;                    
                }

                if(!empty($item->cuti_sisa_cuti))
                {
                    $c->sisa_cuti   = $item->cuti_sisa_cuti;
                }
                
                if(!empty($item->cuti_length_of_service))
                {
                    $c->length_of_service= $item->cuti_length_of_service;                    
                }

                $c->save();
            }

            // EDUCATION
            foreach(\App\UserEducationTemp::where('user_temp_id', $item->id)->get() as $edu)
            {
                if($edu->pendidikan == "") continue;

                // cek pendidikan
                $education = \App\UserEducation::where('user_id', $user->id)->where('pendidikan', $edu->pendidikan)->first();

                if(empty($education))
                {
                    $education                  = new \App\UserEducation();
                    $education->user_id         = $user->id;
                }

                $education->pendidikan      = !empty($edu->pendidikan) ? $edu->pendidikan : $education->pendidikan;
                $education->tahun_awal      = !empty($edu->tahun_awal) ? $edu->tahun_awal : $education->tahun_awal;
                $education->tahun_akhir     = !empty($edu->tahun_akhir) ? $edu->tahun_akhir : $education->tahun_akhir;
                
                $education->fakultas        = !empty($edu->fakultas) ? $edu->fakultas : $education->fakultas;
                $education->jurusan         = !empty($edu->jurusan) ? $edu->jurusan : $education->jurusan;
                $education->nilai           = !empty($edu->nilai) ? $edu->nilai : $education->nilai;
                $education->kota            = $edu->kota;
                $education->save();
            }

            // FAMILY
            foreach(\App\UserFamilyTemp::where('user_temp_id', $item->id)->get() as $fa)
            {
                if($fa->nama == "") continue;

                $family     = \App\UserFamily::where('user_id', $user->id)->where('hubungan', $fa->hubungan)->first(); 
                
                if(empty($family))
                {
                    $family                 = new \App\UserFamily();
                    $family->user_id        = $user->id;
                }

                $family->nama           = !empty($fa->nama) ? $fa->nama : $family->nama;
                $family->hubungan       = !empty($fa->hubungan) ? $fa->hubungan : $family->hubungan;
                $family->tempat_lahir   = !empty($fa->tempat_lahir) ? $fa->tempat_lahir : $family->tempat_lahir;
                $family->tanggal_lahir  = !empty($fa->tanggal_lahir) ? $fa->tanggal_lahir : $family->tanggal_lahir;
                $family->jenjang_pendidikan= !empty($fa->jenjang_pendidikan) ? $fa->jenjang_pendidikan : $family->jenjang_pendidikan;
                $family->pekerjaan      = !empty($fa->pekerjaan) ? $fa->pekerjaan : $family->pekerjaan;
                $family->save();       
            }
        }

        // delete all table temp
        \App\UserTemp::truncate();
        \App\UserEducationTemp::truncate();
        \App\UserFamilyTemp::truncate();

        return redirect()->route('administrator.karyawan.index')->with('message-success', 'Data Karyawan berhasil di import');
    }

    /**
     * [import description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function importData(Request $request)
    {
        if($request->hasFile('file'))
        {
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
        //    $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = [];
            foreach ($worksheet->getRowIterator() AS $row) {
                $cellIterator = $row->getCellIterator();
                $cellIterator->setIterateOnlyExistingCells(FALSE); // This loops through all cells,
                $cells = [];
                foreach ($cellIterator as $cell) {
                    $cells[] = $cell->getValue();
                }
                $rows[] = $cells;
            }

            // delete all table temp
            \App\UserTemp::truncate();
            \App\UserEducationTemp::truncate();
            \App\UserFamilyTemp::truncate();

            foreach($rows as $key => $item)
            {
                if($key >= 3)
                {
                    $user = new \App\UserTemp();

                    if(empty($item[2])) continue;

                    /**
                     * FIND USER
                     *
                     */
                    $find_user = \App\User::where('nik', $item[2])->first();
                    if($find_user)
                    {
                        $user->user_id = $find_user->id;
                    }

                    $user->absensi_number   = $item[0];
                    $user->employee_number  = $item[1];
                    $user->nik              = $item[2];
                    $user->name             = strtoupper($item[3]);
                    $user->join_date        = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[4]);

                    if($item[5] == 'Male' || $item[5] == 'male' || $item[5] == 'Laki-laki' || $item[5]=='laki-laki')
                    {
                        $user->gender           = 'Laki-laki';
                    }

                    if($item[5] == 'Female' || $item[5] == 'female' || $item[5] == 'Perempuan' || $item[5] == 'perempuan')
                    {
                        $user->gender           = 'Perempuan';
                    }
                    
                    $user->marital_status   = $item[6];
                    $user->agama            = $item[7];
                    $user->ktp_number       = $item[8];
                    $user->passport_number  = $item[9];
                    $user->kk_number        = $item[10];
                    $user->npwp_number      = $item[11];
                    $user->no_bpjs_kesehatan= $item[12];
                    $user->place_of_birth   = strtoupper($item[13]);
                    $user->date_of_birth    = !empty($item[14]) ?  \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[14]) : '';
                    $user->id_address       = strtoupper($item[15]);
                    
                    if(!empty($item[16]))
                    {
                        // find city
                        $kota = \App\Kabupaten::where('nama', 'LIKE', '%' . $item[16] .'%')->first();
                        
                        if(isset($kota))
                            $user->id_city          = $kota->id_kab;
                        else
                            $user->id_city          = $item[16];
                    }

                    $user->id_zip_code      = $item[17];
                    $user->current_address  = strtoupper($item[18]);
                    $user->telp             = $item[19];
                    $user->ext              = $item[20];
                    $user->ldap              = $item[21];
                    $user->mobile_1         = $item[22];
                    $user->mobile_2         = $item[23];
                    $user->email            = $item[24];
                    $user->blood_type       = $item[25];
                    $user->bank_1           = $item[26];
                    $user->bank_account_name_1= $item[27];
                    $user->bank_account_number= $item[28];

                    $dir = \App\OrganisasiDirectorate::where('name', $item[29])->first();
                    if($dir)
                    {
                        $user->directorate_id     = $dir->id;
                    }    
                    else
                    {
                        if(!empty($item[29]))
                        {
                            // $dir = new \App\OrganisasiDirectorate();
                            // $dir->name = $item[29];
                            // $dir->save();
                            // $user->directorate_id     = $dir->id;    
                        }
                    }

                    if(!empty($item[30]))
                    {
                        # find Division
                        $organisasi_division = \App\OrganisasiDivision::where('name', $item[30])->first();
                        if($organisasi_division)
                        {
                            $user->organisasi_division= $organisasi_division->id;
                        }else{
                            // $organisasi_division = new \App\OrganisasiDivision();
                            // $organisasi_division->name = $item[30];
                            // $organisasi_division->organisasi_directorate_id = isset($dir->id) ? $dir->id  : 0;
                            // $organisasi_division->save();

                            // $user->organisasi_division= $organisasi_division->id;
                        } 
                    } 

                    if(!empty($item[31]))
                    {
                        $item[31] = str_replace('(', '', $item[31]);
                        $item[31] = str_replace(')', '', $item[31]);

                        if($organisasi_division)
                        {
                            # find Department
                            $organisasi_department = \App\OrganisasiDepartment::where('name', $item[31])->where('organisasi_division_id', $organisasi_division->id)->first();
                            if($organisasi_department)
                            {
                                $user->organisasi_department= $organisasi_department->id;
                            }else{
                                // $organisasi_department                          = new \App\OrganisasiDepartment();
                                // $organisasi_department->organisasi_division_id  = $organisasi_division->id;
                                // $organisasi_department->name                      = $item[31];
                                // $organisasi_department->save();

                                // $user->organisasi_department = $organisasi_department->id;
                            }
                        }
                    }

                    if(!empty($item[32]))
                    {
                        if($organisasi_division and $organisasi_department)
                        {
                            # find Unit
                            $organisasi_unit = \App\OrganisasiUnit::where('name', $item[32])->where('organisasi_division_id', $organisasi_division->id)->where('organisasi_department_id', $organisasi_department->id)->first();
                            if($organisasi_unit)
                            {
                                $user->organisasi_unit= $organisasi_unit->id;
                            }else{
                                // $organisasi_unit                          = new \App\OrganisasiUnit();
                                // $organisasi_unit->organisasi_division_id  = $organisasi_division->id;
                                // $organisasi_unit->organisasi_department_id= $organisasi_department->id;
                                // $organisasi_unit->name                    = $item[32];
                                // $organisasi_unit->save();

                                // $user->organisasi_unit = $organisasi_unit->id;
                            }
                        }
                    }

                    if(!empty($item[33]))
                    {
                        # find Position
                        $organisasi_position = \App\OrganisasiPosition::where('name', $item[33])->first();
                        if($organisasi_position)
                        {
                            $user->organisasi_position = $organisasi_position->id;
                        }else{
                            // $organisasi_position                            = new \App\OrganisasiPosition();
                            // $organisasi_position->name                      = $item[33];
                            // $organisasi_position->save();

                            // $user->organisasi_position= $organisasi_position->id;
                        }
                    }

                    $user->organisasi_position_sub       = $item[34];
                        
                    $cabang_string = strtoupper($item[35]); 
                    $cabang_string =  str_replace('BRANCH', '', $cabang_string);
                    $cabang_string =  str_replace(' ', '', $cabang_string);
                    $cabang_string =  str_replace('Branch', '', $cabang_string);
                    $cabang_string =  str_replace('branch', '', $cabang_string);
                    $cabang_string =  str_replace('JOGJAKARTA', 'YOGYAKARTA', $cabang_string);
                    $cabang_string =  str_replace('Jogjakarta', 'YOGYAKARTA', $cabang_string);
                    $cabang_string =  str_replace('JOGJA', 'YOGYAKARTA', $cabang_string);
                    $cabang_string =  str_replace('SURAKARTA', 'SOLO', $cabang_string);
                    $cabang_string =  str_replace('Jogja', 'YOGYAKARTA', $cabang_string);

                    $cabang = \App\Cabang::where('name', 'LIKE', "%". strtoupper($cabang_string) ."%")->first();

                    if($cabang)
                    {
                        $user->organisasi_branch    = $cabang->id;
                    }
                    else
                    {
                        // $cabang = new \App\Cabang();
                        // $cabang->name = $cabang_string;
                        // $cabang->save();

                        // $user->organisasi_branch    = $cabang->id;
                    }
        
                    $user->organisasi_ho_or_branch= $item[36];
                    $user->organisasi_status    = $item[37];
                    $user->cuti_length_of_service = $item[38];
                    $user->cuti_cuti_2018       = $item[39];
                    $user->cuti_terpakai        = $item[40];
                    $user->cuti_sisa_cuti       = $item[41];
                    $user->save();

                    // SD
                    $education                  = new \App\UserEducationTemp();
                    $education->user_temp_id    = $user->id;
                    $education->pendidikan      = strtoupper($item[42]);
                    $education->tahun_awal      = $item[43];
                    $education->tahun_akhir     = $item[44];
                    $education->fakultas        = strtoupper($item[45]);
                    $education->kota            = strtoupper($item[46]); // CITY
                    $education->jurusan         = strtoupper($item[47]); // MAJOR
                    $education->nilai           = $item[48]; // GPA
                    $education->certificate     = $item[49]; 
                    $education->note            = strtoupper($item[50]); 
                    $education->save();

                    // SD KE DUA
                    if(!empty($item[51]))
                    {
                        $education                  = new \App\UserEducationTemp();
                        $education->user_temp_id    = $user->id;
                        $education->pendidikan      = strtoupper($item[51]);
                        $education->tahun_awal      = $item[52];
                        $education->tahun_akhir     = $item[53];
                        $education->fakultas        = strtoupper($item[54]);
                        $education->kota            = strtoupper($item[55]); // CITY
                        $education->jurusan         = strtoupper($item[56]); // MAJOR
                        $education->nilai           = $item[57]; // GPA
                        $education->certificate     = $item[58]; 
                        $education->note            = strtoupper($item[59]); 
                        $education->save();
                    }

                    // SMP
                    $education                  = new \App\UserEducationTemp();
                    $education->user_temp_id    = $user->id;
                    $education->pendidikan      = strtoupper($item[60]);
                    $education->tahun_awal      = $item[61];
                    $education->tahun_akhir     = $item[62];
                    $education->fakultas        = strtoupper($item[63]);
                    $education->kota            = strtoupper($item[64]); // CITY
                    $education->jurusan         = strtoupper($item[65]); // MAJOR
                    $education->nilai           = $item[66]; // GPA
                    $education->certificate     = $item[67]; 
                    $education->note            = strtoupper($item[68]); 
                    $education->save();

                    if(!empty($item[69]))
                    {
                        // SMP  KE 2
                        $education                  = new \App\UserEducationTemp();
                        $education->user_temp_id    = $user->id;
                        $education->pendidikan      = strtoupper($item[69]);
                        $education->tahun_awal      = $item[70];
                        $education->tahun_akhir     = $item[71];
                        $education->fakultas        = strtoupper($item[72]);
                        $education->kota            = strtoupper($item[73]); // CITY
                        $education->jurusan         = strtoupper($item[74]); // MAJOR
                        $education->nilai           = $item[75]; // GPA
                        $education->certificate     = $item[76]; 
                        $education->note            = strtoupper($item[77]); 
                        $education->save();
                    }

                    // SMA
                    $education                  = new \App\UserEducationTemp();
                    $education->user_temp_id    = $user->id;
                    $education->pendidikan      = strtoupper($item[78]);
                    $education->tahun_awal      = $item[79];
                    $education->tahun_akhir     = $item[80];
                    $education->fakultas        = strtoupper($item[81]);
                    $education->kota            = strtoupper($item[82]); // CITY
                    $education->jurusan         = strtoupper($item[83]); // MAJOR
                    $education->nilai           = $item[84]; // GPA
                    $education->certificate     = $item[85]; 
                    $education->note            = strtoupper($item[86]); 
                    $education->save();

                    // SMA KE 2
                    if(!empty($item[87]))
                    {
                        $education                  = new \App\UserEducationTemp();
                        $education->user_temp_id    = $user->id;
                        $education->pendidikan      = strtoupper($item[87]);
                        $education->tahun_awal      = $item[88];
                        $education->tahun_akhir     = $item[89];
                        $education->fakultas        = strtoupper($item[90]);
                        $education->kota            = strtoupper($item[91]); // CITY
                        $education->jurusan         = strtoupper($item[92]); // MAJOR
                        $education->nilai           = $item[93]; // GPA
                        $education->certificate     = $item[94]; 
                        $education->note            = strtoupper($item[95]); 
                        $education->save();
                    }

                    $education                  = new \App\UserEducationTemp();
                    $education->user_temp_id    = $user->id;
                    $education->pendidikan      = strtoupper($item[96]);
                    $education->tahun_awal      = $item[97];
                    $education->tahun_akhir     = $item[98];
                    $education->fakultas        = strtoupper($item[99]);
                    $education->kota            = strtoupper($item[100]); // CITY
                    $education->jurusan         = strtoupper($item[101]); // MAJOR
                    $education->nilai           = $item[102]; // GPA
                    $education->certificate     = $item[103]; 
                    $education->note            = strtoupper($item[104]); 
                    $education->save();

                    $education                  = new \App\UserEducationTemp();
                    $education->user_temp_id    = $user->id;
                    $education->pendidikan      = strtoupper($item[105]);
                    $education->tahun_awal      = $item[106];
                    $education->tahun_akhir     = $item[107];
                    $education->fakultas        = strtoupper($item[108]);
                    $education->kota            = strtoupper($item[109]); // CITY
                    $education->jurusan         = strtoupper($item[110]); // MAJOR
                    $education->nilai           = $item[111]; // GPA
                    $education->certificate     = $item[112]; 
                    $education->note            = strtoupper($item[113]); 
                    $education->save();

                    $education                  = new \App\UserEducationTemp();
                    $education->user_temp_id    = $user->id;
                    $education->pendidikan      = strtoupper($item[114]);
                    $education->tahun_awal      = $item[115];
                    $education->tahun_akhir     = $item[116];
                    $education->fakultas        = strtoupper($item[117]);
                    $education->kota            = strtoupper($item[118]); // CITY
                    $education->jurusan         = strtoupper($item[119]); // MAJOR
                    $education->nilai           = $item[120]; // GPA
                    $education->certificate     = $item[121]; 
                    $education->note            = strtoupper($item[122]); 
                    $education->save();

                    $education                  = new \App\UserEducationTemp();
                    $education->user_temp_id    = $user->id;
                    $education->pendidikan      = strtoupper($item[123]);
                    $education->tahun_awal      = $item[124];
                    $education->tahun_akhir     = $item[125];
                    $education->fakultas        = strtoupper($item[126]);
                    $education->kota            = strtoupper($item[127]); // CITY
                    $education->jurusan         = strtoupper($item[128]); // MAJOR
                    $education->nilai           = $item[129]; // GPA
                    $education->certificate     = $item[130]; 
                    $education->note            = strtoupper($item[131]); 
                    $education->save();

                    if(!empty($item[132]))
                    {
                        $education                  = new \App\UserEducationTemp();
                        $education->user_temp_id    = $user->id;
                        $education->pendidikan      = strtoupper($item[132]);
                        $education->tahun_awal      = $item[133];
                        $education->tahun_akhir     = $item[134];
                        $education->fakultas        = strtoupper($item[135]);
                        $education->kota            = strtoupper($item[136]); // CITY
                        $education->jurusan         = strtoupper($item[137]); // MAJOR
                        $education->nilai           = $item[138]; // GPA
                        $education->certificate     = $item[139]; 
                        $education->note            = strtoupper($item[140]); 
                        $education->save();
                    }

                    $education                  = new \App\UserEducationTemp();
                    $education->user_temp_id    = $user->id;
                    $education->pendidikan      = strtoupper($item[141]);
                    $education->tahun_awal      = $item[142];
                    $education->tahun_akhir     = $item[143];
                    $education->fakultas        = strtoupper($item[144]);
                    $education->kota            = strtoupper($item[145]); // CITY
                    $education->jurusan         = strtoupper($item[146]); // MAJOR
                    $education->nilai           = $item[147]; // GPA
                    $education->certificate     = $item[148]; 
                    $education->note            = strtoupper($item[149]); 
                    $education->save();
                    
                    // ISTRI 1
                    $family                     = new \App\UserFamilyTemp();
                    $family->user_temp_id       = $user->id;
                    $family->hubungan           = empty($item[150]) ? 'ISTRI' : $item[150];
                    $family->nama               = strtoupper($item[151]);
                    $family->gender             = $item[152];
                    $family->tempat_lahir       = strtoupper($item[153]);
                    $family->tanggal_lahir      = !empty($item[154]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[154]) : null;
                    $family->pekerjaan          = strtoupper($item[155]);
                    $family->note               = strtoupper($item[156]);
                    $family->save();

                    // ISTRI KE 2
                    if(!empty($item[157]))
                    {
                        $family                     = new \App\UserFamilyTemp();
                        $family->user_temp_id       = $user->id;
                        $family->hubungan           = strtoupper($item[157]);
                        $family->nama               = strtoupper($item[158]);
                        $family->gender             = $item[159];
                        $family->tempat_lahir       = strtoupper($item[160]);
                        $family->tanggal_lahir      = !empty($item[154]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[161]) : null;
                        $family->pekerjaan          = strtoupper($item[162]);
                        $family->note               = strtoupper($item[163]);
                        $family->save();
                    }

                    // SUAMI
                    if(!empty($item[164]))
                    {
                        $family                     = new \App\UserFamilyTemp();
                        $family->user_temp_id       = $user->id;
                        $family->hubungan           = strtoupper($item[164]);
                        $family->nama               = strtoupper($item[165]);
                        $family->gender             = $item[166];
                        $family->tempat_lahir       = strtoupper($item[167]);
                        $family->tanggal_lahir      = !empty($item[154]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[168]) : null;
                        $family->pekerjaan          = strtoupper($item[169]);
                        $family->note               = strtoupper($item[170]);
                        $family->save();
                    }
                    
                    // ANAK 1
                    if(!empty($item[171]))
                    {
                        $family                     = new \App\UserFamilyTemp();
                        $family->user_temp_id       = $user->id;
                        $family->hubungan           = strtoupper($item[171]);
                        $family->nama               = strtoupper($item[172]);
                        $family->gender             = $item[173];
                        $family->tempat_lahir       = strtoupper($item[174]);
                        $family->tanggal_lahir      = !empty($item[154]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[175]) : null;
                        $family->pekerjaan          = strtoupper($item[176]);
                        $family->note               = strtoupper($item[177]);
                        $family->save();
                    }

                    // ANAK 2
                    if(!empty($item[178]))
                    {
                        $family                     = new \App\UserFamilyTemp();
                        $family->user_temp_id       = $user->id;
                        $family->hubungan           = strtoupper($item[178]);
                        $family->nama               = strtoupper($item[179]);
                        $family->gender             = $item[180];
                        $family->tempat_lahir       = strtoupper($item[181]);
                        $family->tanggal_lahir      = !empty($item[154]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[182]) : null;
                        $family->pekerjaan          = strtoupper($item[183]);
                        $family->note               = strtoupper($item[184]);
                        $family->save();
                    }

                    // ANAK 3
                    if(!empty($item[185]))
                    {
                        $family                     = new \App\UserFamilyTemp();
                        $family->user_temp_id       = $user->id;
                        $family->hubungan           = strtoupper($item[185]);
                        $family->nama               = strtoupper($item[186]);
                        $family->gender             = $item[187];
                        $family->tempat_lahir       = strtoupper($item[188]);
                        $family->tanggal_lahir      = !empty($item[154]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[189]) : null;
                        $family->pekerjaan          = strtoupper($item[190]);
                        $family->note               = strtoupper($item[191]);
                        $family->save();
                    }

                    // ANAK 4
                    if(!empty($item[192]))
                    {
                        $family                     = new \App\UserFamilyTemp();
                        $family->user_temp_id       = $user->id;
                        $family->hubungan           = strtoupper($item[192]);
                        $family->nama               = strtoupper($item[193]);
                        $family->gender             = $item[194];
                        $family->tempat_lahir       = strtoupper($item[195]);
                        $family->tanggal_lahir      = !empty($item[154]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[196]) : null;
                        $family->pekerjaan          = strtoupper($item[197]);
                        $family->note               = strtoupper($item[198]);
                        $family->save();
                    }

                    // ANAK 5
                    if(!empty($item[199]))
                    {
                        $family                     = new \App\UserFamilyTemp();
                        $family->user_temp_id       = $user->id;
                        $family->hubungan           = strtoupper($item[199]);
                        $family->nama               = strtoupper($item[200]);
                        $family->gender             = $item[201];
                        $family->tempat_lahir       = strtoupper($item[202]);
                        $family->tanggal_lahir      = !empty($item[154]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[203]) : null;
                        $family->pekerjaan          = strtoupper($item[204]);
                        $family->note               = strtoupper($item[205]);
                        $family->save();
                    }
                }
            }

            return redirect()->route('administrator.karyawan.preview-import')->with('message-success', 'Data berhasil di import');
        }
    }   

    /**
     * [previewImport description]
     * @return [type] [description]
     */
    public function previewImport()
    {
        $params['data'] = \App\UserTemp::all();

        return view('administrator.karyawan.preview-import')->with($params);
    }

    /**
     * [deleteTemp description]
     * @return [type] [description]
     */
    public function deleteTemp($id)
    {
        $data = \App\UserTemp::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.karyawan.preview-import')->with('message-success', 'Data Temporary berhasil di hapus');
    }

    /**
     * [edit description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function edit($id)
    {
        $params['data'] = User::where('id', $id)->first();
        $params['department']       = Department::where('division_id', $params['data']['division_id'])->get();
        $params['provinces']        = Provinsi::all();
        $params['dependent']        = \App\UserFamily::where('user_id', $id)->first();
        $params['education']        = UserEducation::where('user_id', $id)->first();
        $params['kabupaten']        = Kabupaten::where('id_prov', $params['data']['provinsi_id'])->get();
        $params['kecamatan']        = Kecamatan::where('id_kab', $params['data']['kabupaten_id'])->get();
        $params['kelurahan']        = Kelurahan::where('id_kec', $params['data']['kecamatan_id'])->get();
        $params['division']         = Division::all();
        $params['section']          = Section::where('division_id', $params['data']['division_id'])->get();

        return view('administrator.karyawan.edit')->with($params);
    }

    /**
     * [create description]
     * @return [type] [description]
     */
    public function create()
    {
        $params['department']   = Department::all();
        $params['provinces']    = Provinsi::all();
        $params['division']     = Division::all();
        $params['department']   = Department::all();
        $params['section']      = Section::all();

        return view('administrator.karyawan.create')->with($params);
    }

    /**
     * [update description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function update(Request $request, $id)
    {
        $data       = User::where('id', $id)->first();

        if(!empty($request->password))
        {
            $this->validate($request,[
                'confirmation'      => 'same:password',
            ]);

            $data->password             = bcrypt($request->password);  
        }
        
        $data->name         = strtoupper($request->name);
        $data->nik          = $request->nik;
        $data->ldap         = $request->ldap;
        $data->jenis_kelamin= $request->jenis_kelamin;
        $data->email        = $request->email;
        // $data->provinsi_id  = $request->provinsi_id;
        // $data->kabupaten_id = $request->kabupaten_id;
        // $data->kecamatan_id = $request->kecamatan_id;
        // $data->kelurahan_id = $request->kelurahan_id;

        $data->telepon      = $request->telepon;
        $data->agama        = $request->agama;
        $data->alamat       = $request->alamat;
        $data->access_id    = 2; //
        $data->division_id  = $request->division_id;
        $data->department_id= $request->department_id;
        $data->section_id   = $request->section_id;
        $data->type_jabatan = $request->type_jabatan;
        $data->nama_jabatan = $request->nama_jabatan;
        $data->hak_cuti     = 12;
        $data->cuti_yang_terpakai = 0;
        $data->cabang_id    =$request->cabang_id;
        $data->nama_rekening    = $request->nama_rekening;
        $data->nomor_rekening   = $request->nomor_rekening;
        $data->bank_id          = $request->bank_id;
        // $data->cabang           = $request->cabang;
        $data->join_date        = $request->join_date;
        $data->tempat_lahir     = $request->tempat_lahir;
        $data->tanggal_lahir    = $request->tanggal_lahir;
        
        $data->absensi_number       = $request->absensi_number;
        $data->employee_number      = $request->employee_number;
        $data->ktp_number           = $request->ktp_number;
        $data->passport_number      = $request->passport_number;
        $data->kk_number            = $request->kk_number;
        $data->npwp_number          = $request->npwp_number;
        $data->bpjs_number          = $request->no_bpjs_number;

        $data->organisasi_position     = $request->organisasi_position;
        $data->organisasi_job_role     = $request->organisasi_job_role;
        $data->section_id              = $request->section_id;

        $data->branch_type          = $request->branch_type; 
        $data->ext                  = $request->ext; 
        $data->is_pic_cabang        = isset($request->is_pic_cabang) ? $request->is_pic_cabang : 0;
        $data->branch_staff_id      = $request->branch_staff_id;
        $data->branch_head_id       = $request->branch_head_id;
        $data->blood_type           = $request->blood_type; 
        $data->status               = $request->status;
        
        if ($request->hasFile('foto'))
        {
            $file = $request->file('foto');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/foto/');
            $file->move($destinationPath, $fileName);

            $data->foto = $fileName;
        }

        $data->save();

        if(isset($request->dependent))
        {
            foreach($request->dependent['nama'] as $key => $item)
            {
                $dep = new \App\UserFamily();
                $dep->user_id           = $data->id;
                $dep->nama          = $request->dependent['nama'][$key];
                $dep->hubungan      = $request->dependent['hubungan'][$key];
                $dep->tempat_lahir  = $request->dependent['tempat_lahir'][$key];
                $dep->tanggal_lahir = $request->dependent['tanggal_lahir'][$key];
                $dep->tanggal_meninggal = $request->dependent['tanggal_meninggal'][$key];
                $dep->jenjang_pendidikan = $request->dependent['jenjang_pendidikan'][$key];
                $dep->pekerjaan = $request->dependent['pekerjaan'][$key];
                $dep->tertanggung = $request->dependent['tertanggung'][$key];
                $dep->save();
            }
        }

        if(isset($request->inventaris_mobil))
        {
            foreach($request->inventaris_mobil['tipe_mobil'] as $k => $item)
            {
                $inventaris                 = new \App\UserInventarisMobil();
                $inventaris->user_id        = $data->id;
                $inventaris->tipe_mobil     = $request->inventaris_mobil['tipe_mobil'][$k];
                $inventaris->tahun          = $request->inventaris_mobil['tahun'][$k];
                $inventaris->no_polisi      = $request->inventaris_mobil['no_polisi'][$k];
                $inventaris->status_mobil   = $request->inventaris_mobil['status_mobil'][$k];
                $inventaris->save();
            }
        }

        if(isset($request->education))
        {
            foreach($request->education['pendidikan'] as $key => $item)
            {
                $edu = new UserEducation();
                $edu->user_id = $data->id;
                $edu->pendidikan    = $request->education['pendidikan'][$key];
                $edu->tahun_awal    = $request->education['tahun_awal'][$key];
                $edu->tahun_akhir   = $request->education['tahun_akhir'][$key];
                $edu->fakultas      = $request->education['fakultas'][$key];
                $edu->jurusan       = $request->education['jurusan'][$key];
                $edu->nilai         = $request->education['nilai'][$key];
                $edu->kota          = $request->education['kota'][$key];
                $edu->save();
            }
        }

        if(isset($request->cuti))
        {
            // user Education
            foreach($request->cuti['cuti_id'] as $key => $item)
            {
                $c = new \App\UserCuti();
                $c->user_id = $data->id;
                $c->cuti_id    = $request->cuti['cuti_id'][$key];
                $c->kuota    = $request->cuti['kuota'][$key];
                $c->save();
            }
        }

        if(isset($request->inventaris_lainnya['jenis']))
        {
            foreach($request->inventaris_lainnya['jenis'] as $k => $i)
            {
                $i              = new \App\UserInventaris();
                $i->user_id     = $data->id;
                $i->jenis       = $request->inventaris_lainnya['jenis'][$k];
                $i->description = $request->inventaris_lainnya['description'][$k];
                $i->save();
            }
        }

        return redirect()->route('administrator.karyawan.edit', $data->id)->with('message-success', 'Data berhasil disimpan');
    }   

    /**
     * [store description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {   
        $data               = new User();

        $this->validate($request,[
            'nik'               => 'required|unique:users'
        ]);

        $data->password             = bcrypt($request->password);
    
        $data->name         = $request->name;
        $data->nik          = $request->nik;
        $data->ldap         = $request->ldap;
        $data->jenis_kelamin= $request->jenis_kelamin;
        $data->email        = $request->email;
        $data->telepon      = $request->telepon;
        $data->agama        = $request->agama;
        $data->alamat       = $request->alamat;
        $data->access_id    = 2; 
        $data->jabatan_cabang= $request->jabatan_cabang;
        $data->division_id  = $request->division_id;
        $data->department_id= $request->department_id;
        $data->section_id   = $request->section_id;
        $data->type_jabatan = $request->type_jabatan;
        $data->nama_jabatan = $request->nama_jabatan;
        $data->hak_cuti     = 12;
        $data->cuti_yang_terpakai = 0;
        $data->cabang_id            = $request->cabang_id;
        $data->nama_rekening        = $request->nama_rekening;
        $data->nomor_rekening       = $request->nomor_rekening;
        $data->bank_id              = $request->bank_id;
        $data->join_date            = $request->join_date;
        $data->tempat_lahir         = $request->tempat_lahir;
        $data->tanggal_lahir        = $request->tanggal_lahir;
        $data->absensi_number       = $request->absensi_number;
        $data->employee_number      = $request->employee_number;
        $data->ktp_number           = $request->ktp_number;
        $data->passport_number      = $request->passport_number;
        $data->kk_number            = $request->kk_number;
        $data->npwp_number          = $request->npwp_number;
        $data->bpjs_number          = $request->no_bpjs_number;
        $data->organisasi_position  = $request->organisasi_position;
        $data->organisasi_job_role  = $request->organisasi_job_role;
        $data->section_id           = $request->section_id;
        $data->organisasi_status    = $request->organisasi_status;
        $data->branch_type          = $request->branch_type; 
        $data->ext                  = $request->ext; 
        $data->is_pic_cabang        = isset($request->is_pic_cabang) ? $request->is_pic_cabang : 0;  
        $data->blood_type           = $request->blood_type;  
        $data->marital_status       = $request->marital_status; 
        $data->mobile_1             = $request->mobile_1;  
        $data->mobile_2             = $request->mobile_2;
        $data->id_address           = $request->id_address;
        $data->id_city              = $request->id_city;
        $data->status               = $request->status;

        if (request()->hasFile('foto'))
        {
            $file = $request->file('foto');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();

            $destinationPath = public_path('/storage/foto/');
            $file->move($destinationPath, $fileName);

            $data->foto = $fileName;
        }

        $data->save();

        // user Dependent
        if(isset($request->dependent))
        {
            foreach($request->dependent['nama'] as $key => $item)
            {
                $dep = new \App\UserFamily();
                $dep->user_id           = $data->id;
                $dep->nama          = $request->dependent['nama'][$key];
                $dep->hubungan      = $request->dependent['hubungan'][$key];
                $dep->tempat_lahir  = $request->dependent['tempat_lahir'][$key];
                $dep->tanggal_lahir = $request->dependent['tanggal_lahir'][$key];
                $dep->tanggal_meninggal = $request->dependent['tanggal_meninggal'][$key];
                $dep->jenjang_pendidikan = $request->dependent['jenjang_pendidikan'][$key];
                $dep->pekerjaan = $request->dependent['pekerjaan'][$key];
                $dep->tertanggung = $request->dependent['tertanggung'][$key];
                $dep->save();
            }
        }

        if(isset($request->inventaris_mobil))
        {
            foreach($request->inventaris_mobil['tipe_mobil'] as $k => $item)
            {
                $inventaris                 = new \App\UserInventarisMobil();
                $inventaris->user_id        = $data->id;
                $inventaris->tipe_mobil     = $request->inventaris_mobil['tipe_mobil'][$k];
                $inventaris->tahun          = $request->inventaris_mobil['tahun'][$k];
                $inventaris->no_polisi      = $request->inventaris_mobil['no_polisi'][$k];
                $inventaris->status_mobil   = $request->inventaris_mobil['status_mobil'][$k];
                $inventaris->save();
            }
        }

        if(isset($request->education))
        {
            // user Education
            foreach($request->education['pendidikan'] as $key => $item)
            {
                $edu = new UserEducation();
                $edu->user_id = $data->id;
                $edu->pendidikan    = $request->education['pendidikan'][$key];
                $edu->tahun_awal    = $request->education['tahun_awal'][$key];
                $edu->tahun_akhir   = $request->education['tahun_akhir'][$key];
                $edu->fakultas      = $request->education['fakultas'][$key];
                $edu->jurusan       = $request->education['jurusan'][$key];
                $edu->nilai         = $request->education['nilai'][$key];
                $edu->kota          = $request->education['kota'][$key];
                $edu->save();
            }
        }

        if(isset($request->cuti))
        {
            // user Education
            foreach($request->cuti['cuti_id'] as $key => $item)
            {
                $c = new \App\UserCuti();
                $c->user_id = $data->id;
                $c->cuti_id    = $request->cuti['cuti_id'][$key];
                $c->kuota    = $request->cuti['kuota'][$key];
                $c->save();
            }
        }

        if(isset($request->inventaris_lainnya['jenis']))
        {
            foreach($request->inventaris_lainnya['jenis'] as $k => $i)
            {
                $i              = new \App\UserInventaris();
                $i->user_id     = $data->id;
                $i->jenis       = $request->inventaris_lainnya['jenis'][$key];
                $i->description = $request->inventaris_lainnya['description'][$key];
                $i->save();
            }
        }

        return redirect()->route('administrator.karyawan.index')->with('message-success', 'Data berhasil disimpan !');
    }

    /**
     * [DeleteCuti description]
     * @param [type] $id [description]
     */
    public function DeleteCuti($id)
    {
        $data = UserCuti::where('id', $id)->first();
        $user_id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $user_id)->with('message-success', 'Data Cuti berhasil dihapus');
    }

    /**
     * [deleteOldUser description]
     * @return [type] [description]
     */
    public function deleteOldUser($id)
    {
        $data = User::where('id', $id)->first();
        $data->delete();

        return redirect()->route('administrator.karyawan.preview-import')->with('message-success', 'Data lama berhasil di hapus');
    }   

    /**
     * [desctroy description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function destroy($id)
    {
        $data = User::where('id', $id)->first();
        $data->delete();

        \App\UserFamily::where('user_id', $id)->delete();
        
        UserEducation::where('user_id', $id)->delete();

        return redirect()->route('administrator.karyawan.index')->with('message-sucess', 'Data berhasi di hapus');
    } 

    /**
     * [deleteDependent description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteDependent($id)
    {
        $data = \App\UserFamily::where('id', $id)->first();
        $id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $id)->with('message-success', 'Data Dependent Berhasil dihapus !');
    }
    /**
     * [deleteInvetarisMobil description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteInvetarisMobil($id)
    {
        $data = \App\UserInventarisMobil::where('id', $id)->first();
        $id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $id)->with('message-success', 'Data Invetaris Berhasil dihapus !');
    }

    /**
     * [deleteInvetaris description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteInventarisLainnya($id)
    {
        $data = \App\UserInventaris::where('id', $id)->first();
        $id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $id)->with('message-success', 'Data Invetaris Berhasil dihapus !');
    }

    /**
     * [deleteEducation description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function deleteEducation($id)
    {
        $data = \App\UserEducation::where('id', $id)->first();
        $id = $data->user_id;
        $data->delete();

        return redirect()->route('administrator.karyawan.edit', $id)->with('message-success', 'Data Educatuin Berhasil dihapus !');
    }

    /**
     * [changePasswordKaryawan description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function changeStatusKaryawan(Request $request)
    {
        $user = \App\User::where('id', $request->id)->first();

        if($user)
        {
            $user->status = $request->status;
            $user->save();

            return redirect()->route('administrator.karyawan.index')->with('message-success', 'Status Karyawan Berhasil dirubah !');
        }
    }

    /**
     * [changePasswordKaryawan description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function changePasswordKaryawan(Request $request)
    {
        $user = \App\User::where('id', $request->id)->first();

        if($user)
        {
            $user->password             = bcrypt($request->password);
            $user->last_change_password = date('Y-m-d H:i:s');
            $user->save();

            return redirect()->route('administrator.karyawan.index')->with('message-success', 'Password Karyawan Berhasil dirubah !');
        }
    }

    /**
     * [autologin description]
     * @return [type] [description]
     */
    public function autologin($id)
    {   
        $user = \App\User::where('id', $id)->first();

        if($user)
        {
            \Auth::loginUsingId($user->id);
            \Session::put('is_login_administrator', true);

            return redirect()->route('karyawan.dashboard');
        }
        else
        {
            return redirect()->route('administrator.karyawan.index')->with('message-error', 'Error, Autologin gagal !');
        }
    }

    public function downloadExcel()
    {
        $data       = User::where('access_id', 2)->orderBy('id', 'DESC')->get();
        $params = [];
        
        foreach($data as $k =>  $item)
        {
            
            $params[$k]['No']                   = $k+1;
            $params[$k]['Employee Number ']     = $item->employee_number;
            $params[$k]['Absensi Number']       = $item->absensi_number;
            $params[$k]['NIK']                  = $item->nik;
            $params[$k]['Name']                 = $item->name;
            $params[$k]['Join Date']            = $item->join_date;
            $params[$k]['Gender']               = $item->jenis_kelamin;
            $params[$k]['Maritial Status']      = $item->marital_status;
            $params[$k]['Religion']             = $item->agama;
            $params[$k]['KTP Number']           = $item->ktp_number;
            $params[$k]['Passport Number']      = $item->passport_number;
            $params[$k]['KK Number']            = $item->kk_number;
            $params[$k]['NPWP Number']          = $item->npwp_number;
            $params[$k]['No BPJS Kesehatan']    = $item->jamsostek_number;
            $params[$k]['No BPJS Ketenagakerjaan']  = $item->bpjs_number;
            $params[$k]['Place of Birth']       = $item->tempat_lahir;
            $params[$k]['Date of Birth']        = $item->tanggal_lahir;
            $params[$k]['ID Address']           = $item->id_address;
            //$params[$k]['ID City']              = isset($item->kota->nama) ? $item->kota->nama : '';
            //$params[$k]['ID Zip Code']          = $item->id_zip_code;
            $params[$k]['Current Address']      = $item->current_address;
            $params[$k]['Telp']                 = $item->telepon;
            $params[$k]['Ext']                  = $item->ext;
            $params[$k]['Mobile 1']             = $item->mobile_1;
            $params[$k]['Mobile 2']             = $item->mobile_2;
            $params[$k]['Email']                = $item->email;
            $params[$k]['Blood Type']           = $item->blood_type;

            if(!empty($item->bank_id)) {
                $params[$k]['Bank ']                = $item->bank->name;
            }elseif (empty($item->bank_id)) {
                $params[$k]['Bank ']                ="";
            }

            $params[$k]['Bank Account Name']    = $item->nama_rekening;
            $params[$k]['Bank Account Number']  = $item->nomor_rekening;

            $pos ="";

            if(!empty($item->empore_organisasi_staff_id)){
                $pos= "Staff";
            }elseif (empty($item->empore_organisasi_staff_id) and !empty($item->empore_organisasi_manager_id)) {
                 $pos= "Manager";
            }elseif (empty($item->empore_organisasi_staff_id) and empty($item->empore_organisasi_manager_id) and !empty($item->empore_organisasi_direktur)) {
                $pos= "Direktur";
            }

            $params[$k]['Position']             = $pos;

            $jobrule ="";
            
            if(!empty($item->empore_organisasi_staff_id)){
                $jobrule = isset($item->empore_staff->name) ? $item->empore_staff->name : '';
            }elseif (empty($item->empore_organisasi_staff_id) and !empty($item->empore_organisasi_manager_id)) {
                $jobrule = isset($item->empore_manager->name) ? $item->empore_manager->name : '';
            }
                                    
            $params[$k]['Job Rule']             = $jobrule;
            
            $params[$k]['status']               = $item->organisasi_status;

            $sd = UserEducation::where('user_id', $item->id)->where('pendidikan','SD')->first();

            if(!empty($sd)) {
                    $params[$k]['Education SD']           = $sd ->pendidikan;
                    $params[$k]['Start Year SD']          = $sd->tahun_awal;
                    $params[$k]['End Year SD']            = $sd->tahun_akhir;
                    $params[$k]['Institution SD']         = $sd->fakultas;
                    $params[$k]['City Education SD']      = $sd->kota;
                    $params[$k]['Major SD']               = $sd->jurusan;
                    $params[$k]['GPA SD']                 = $sd->nilai;
            } else
            {
                    $params[$k]['Education SD']           = "-";
                    $params[$k]['Start Year SD']          = "-";
                    $params[$k]['End Year SD']            = "-";
                    $params[$k]['Institution SD']         = "-";
                    $params[$k]['City Education SD']      = "-";
                    $params[$k]['Major SD']               = "-";
                    $params[$k]['GPA SD']                 = "-";
            }
            $smp = UserEducation::where('user_id', $item->id)->where('pendidikan','SMP')->first();
            if(!empty($smp)) {
                    $params[$k]['Education SMP']           = $smp ->pendidikan;
                    $params[$k]['Start Year SMP']          = $smp->tahun_awal;
                    $params[$k]['End Year SMP']            = $smp->tahun_akhir;
                    $params[$k]['Institution SMP']         = $smp->fakultas;
                    $params[$k]['City Education SMP']      = $smp->kota;
                    $params[$k]['Major SMP']               = $smp->jurusan;
                    $params[$k]['GPA SMP']                 = $smp->nilai;
            } else
            {
                    $params[$k]['Education SMP']           = "-";
                    $params[$k]['Start Year SMP']          = "-";
                    $params[$k]['End Year SMP']            = "-";
                    $params[$k]['Institution SMP']         = "-";
                    $params[$k]['City Education SMP']      = "-";
                    $params[$k]['Major SMP']               = "-";
                    $params[$k]['GPA SMP']                 = "-";
            }

            $sma = UserEducation::where('user_id', $item->id)->where('pendidikan','SMA/SMK')->first();
            if(!empty($sma)) {
                    $params[$k]['Education SMA/SMK']           = $sma ->pendidikan;
                    $params[$k]['Start Year SMA/SMK']          = $sma->tahun_awal;
                    $params[$k]['End Year SMA/SMK']            = $sma->tahun_akhir;
                    $params[$k]['Institution SMA/SMK']         = $sma->fakultas;
                    $params[$k]['City Education SMA/SMK']      = $sma->kota;
                    $params[$k]['Major SMA/SMK']               = $sma->jurusan;
                    $params[$k]['GPA SMA/SMK']                 = $sma->nilai;
            } else
            {
                    $params[$k]['Education SMA/SMK']           = "-";
                    $params[$k]['Start Year SMA/SMK']          = "-";
                    $params[$k]['End Year SMA/SMK']            = "-";
                    $params[$k]['Institution SMA/SMK']         = "-";
                    $params[$k]['City Education SMA/SMK']      = "-";
                    $params[$k]['Major SMA/SMK']               = "-";
                    $params[$k]['GPA SMA/SMK']                 = "-";
            }

            $diploma = UserEducation::where('user_id', $item->id)->where('pendidikan','D1')->first();
            if(!empty($diploma)) {
                    $params[$k]['Education D1']           = $diploma ->pendidikan;
                    $params[$k]['Start Year D1']          = $diploma->tahun_awal;
                    $params[$k]['End Year D1']            = $diploma->tahun_akhir;
                    $params[$k]['Institution D1']         = $diploma->fakultas;
                    $params[$k]['City Education D1']      = $diploma->kota;
                    $params[$k]['Major D1']               = $diploma->jurusan;
                    $params[$k]['GPA D1']                 = $diploma->nilai;
            } else
            {
                    $params[$k]['Education D1']           = "-";
                    $params[$k]['Start Year D1']          = "-";
                    $params[$k]['End Year D1']            = "-";
                    $params[$k]['Institution D1']         = "-";
                    $params[$k]['City Education D1']      = "-";
                    $params[$k]['Major D1']               = "-";
                    $params[$k]['GPA D1']                 = "-";
            }

            $diploma2 = UserEducation::where('user_id', $item->id)->where('pendidikan','D2')->first();
            if(!empty($diploma2)) {
                    $params[$k]['Education D2']           = $diploma2 ->pendidikan;
                    $params[$k]['Start Year D2']          = $diploma2->tahun_awal;
                    $params[$k]['End Year D2']            = $diploma2->tahun_akhir;
                    $params[$k]['Institution D2']         = $diploma2->fakultas;
                    $params[$k]['City Education D2']      = $diploma2->kota;
                    $params[$k]['Major D2']               = $diploma2->jurusan;
                    $params[$k]['GPA D2']                 = $diploma2->nilai;
            } else
            {
                    $params[$k]['Education D2']           = "-";
                    $params[$k]['Start Year D2']          = "-";
                    $params[$k]['End Year D2']            = "-";
                    $params[$k]['Institution D2']         = "-";
                    $params[$k]['City Education D2']      = "-";
                    $params[$k]['Major D2']               = "-";
                    $params[$k]['GPA D2']                 = "-";
            }

            $diploma3 = UserEducation::where('user_id', $item->id)->where('pendidikan','D3')->first();
            if(!empty($diploma3)) {
                    $params[$k]['Education D3']           = $diploma3 ->pendidikan;
                    $params[$k]['Start Year D3']          = $diploma3->tahun_awal;
                    $params[$k]['End Year D3']            = $diploma3->tahun_akhir;
                    $params[$k]['Institution D3']         = $diploma3->fakultas;
                    $params[$k]['City Education D3']      = $diploma3->kota;
                    $params[$k]['Major D3']               = $diploma3->jurusan;
                    $params[$k]['GPA D3']                 = $diploma3->nilai;
            } else
            {
                    $params[$k]['Education D3']           = "-";
                    $params[$k]['Start Year D3']          = "-";
                    $params[$k]['End Year D3']            = "-";
                    $params[$k]['Institution D3']         = "-";
                    $params[$k]['City Education D3']      = "-";
                    $params[$k]['Major D3']               = "-";
                    $params[$k]['GPA D3']                 = "-";
            }

            $s1 = UserEducation::where('user_id', $item->id)->where('pendidikan','S1')->first();
            if(!empty($s1)) {
                    $params[$k]['Education S1']           = $s1 ->pendidikan;
                    $params[$k]['Start Year S1']          = $s1->tahun_awal;
                    $params[$k]['End Year S1']            = $s1->tahun_akhir;
                    $params[$k]['Institution S1']         = $s1->fakultas;
                    $params[$k]['City Education S1']      = $s1->kota;
                    $params[$k]['Major S1']               = $s1->jurusan;
                    $params[$k]['GPA S1']                 = $s1->nilai;
            } else
            {
                    $params[$k]['Education S1']           = "-";
                    $params[$k]['Start Year S1']          = "-";
                    $params[$k]['End Year S1']            = "-";
                    $params[$k]['Institution S1']         = "-";
                    $params[$k]['City Education S1']      = "-";
                    $params[$k]['Major S1']               = "-";
                    $params[$k]['GPA S1']                 = "-";
            }

            $s2 = UserEducation::where('user_id', $item->id)->where('pendidikan','S2')->first();
            if(!empty($s2)) {
                    $params[$k]['Education S2']           = $s2 ->pendidikan;
                    $params[$k]['Start Year S2']          = $s2->tahun_awal;
                    $params[$k]['End Year S2']            = $s2->tahun_akhir;
                    $params[$k]['Institution S2']         = $s2->fakultas;
                    $params[$k]['City Education S2']      = $s2->kota;
                    $params[$k]['Major S2']               = $s2->jurusan;
                    $params[$k]['GPA S2']                 = $s2->nilai;
            } else
            {
                    $params[$k]['Education S2']           = "-";
                    $params[$k]['Start Year S2']          = "-";
                    $params[$k]['End Year S2']            = "-";
                    $params[$k]['Institution S2']         = "-";
                    $params[$k]['City Education S2']      = "-";
                    $params[$k]['Major S2']               = "-";
                    $params[$k]['GPA S2']                 = "-";
            }

            $s3 = UserEducation::where('user_id', $item->id)->where('pendidikan','S3')->first();
            if(!empty($s3)) {
                    $params[$k]['Education S3']           = $s3 ->pendidikan;
                    $params[$k]['Start Year S3']          = $s3->tahun_awal;
                    $params[$k]['End Year S3']            = $s3->tahun_akhir;
                    $params[$k]['Institution S3']         = $s3->fakultas;
                    $params[$k]['City Education S3']      = $s3->kota;
                    $params[$k]['Major S3']               = $s3->jurusan;
                    $params[$k]['GPA S3']                 = $s3->nilai;
            } else
            {
                    $params[$k]['Education S3']           = "-";
                    $params[$k]['Start Year S3']          = "-";
                    $params[$k]['End Year S3']            = "-";
                    $params[$k]['Institution S3']         = "-";
                    $params[$k]['City Education S3']      = "-";
                    $params[$k]['Major S3']               = "-";
                    $params[$k]['GPA S3']                 = "-";
            }

            $ayah = UserFamily::where('user_id', $item->id)->where('hubungan','Ayah Kandung')->first();
            if(!empty($ayah)) {
                    $params[$k]['Relative Name Ayah Kandung']           = $ayah ->nama;
                    $params[$k]['Place of birth Ayah Kandung']          = $ayah->tempat_lahir;
                    $params[$k]['Date of birth Ayah Kandung']           = $ayah->tanggal_lahir;
                    $params[$k]['Education level Ayah Kandung']         = $ayah->jenjang_pendidikan;
                    $params[$k]['Occupation Ayah Kandung']              = $ayah->pekerjaan;
            } else
            {       
                    $params[$k]['Relative Name Ayah Kandung']           = "-";
                    $params[$k]['Place of birth Ayah Kandung']          = "-";
                    $params[$k]['Date of birth Ayah Kandung']           = "-";
                    $params[$k]['Education level Ayah Kandung']         = "-";
                    $params[$k]['Occupation Ayah Kandung']              = "-";
            }
            $ibu = UserFamily::where('user_id', $item->id)->where('hubungan','Ibu Kandung')->first();
            if(!empty($ibu)) {
                    $params[$k]['Relative Name Ibu Kandung']           = $ibu ->nama;
                    $params[$k]['Place of birth Ibu Kandung']          = $ibu->tempat_lahir;
                    $params[$k]['Date of birth Ibu Kandung']           = $ibu->tanggal_lahir;
                    $params[$k]['Education level Ibu Kandung']         = $ibu->jenjang_pendidikan;
                    $params[$k]['Occupation Ibu Kandung']              = $ibu->pekerjaan;
            } else
            {       
                    $params[$k]['Relative Name Ibu Kandung']           = "-";
                    $params[$k]['Place of birth Ibu Kandung']          = "-";
                    $params[$k]['Date of birth Ibu Kandung']           = "-";
                    $params[$k]['Education level Ibu Kandung']         = "-";
                    $params[$k]['Occupation Ibu Kandung']              = "-";
            }
            
            $istri = UserFamily::where('user_id', $item->id)->where('hubungan','Istri')->first();
            if(!empty($istri)) {
                    $params[$k]['Relative Name Istri']           = $istri ->nama;
                    $params[$k]['Place of birth Istri']          = $istri->tempat_lahir;
                    $params[$k]['Date of birth Istri']           = $istri->tanggal_lahir;
                    $params[$k]['Education level Istri']         = $istri->jenjang_pendidikan;
                    $params[$k]['Occupation Istri']              = $istri->pekerjaan;
            } else
            {       
                    $params[$k]['Relative Name Istri']           = "-";
                    $params[$k]['Place of birth Istri']          = "-";
                    $params[$k]['Date of birth Istri']           = "-";
                    $params[$k]['Education level Istri']         = "-";
                    $params[$k]['Occupation Istri']              = "-";
            }

            $suami = UserFamily::where('user_id', $item->id)->where('hubungan','Suami')->first();
            if(!empty($suami)) {
                    $params[$k]['Relative Name Suami']           = $suami ->nama;
                    $params[$k]['Place of birth Suami']          = $suami->tempat_lahir;
                    $params[$k]['Date of birth Suami']           = $suami->tanggal_lahir;
                    $params[$k]['Education level Suami']         = $suami->jenjang_pendidikan;
                    $params[$k]['Occupation Suami']              = $suami->pekerjaan;
            } else
            {       
                    $params[$k]['Relative Name Suami']           = "-";
                    $params[$k]['Place of birth Suami']          = "-";
                    $params[$k]['Date of birth Suami']           = "-";
                    $params[$k]['Education level Suami']         = "-";
                    $params[$k]['Occupation Suami']              = "-";
            }

            $anak1 = UserFamily::where('user_id', $item->id)->where('hubungan','Anak 1')->first();
            if(!empty($anak1)) {
                    $params[$k]['Relative Name Anak 1']           = $anak1 ->nama;
                    $params[$k]['Place of birth Anak 1']          = $anak1->tempat_lahir;
                    $params[$k]['Date of birth Anak 1']           = $anak1->tanggal_lahir;
                    $params[$k]['Education level Anak 1']         = $anak1->jenjang_pendidikan;
                    $params[$k]['Occupation Anak 1']              = $anak1->pekerjaan;
            } else
            {       
                    $params[$k]['Relative Name Anak 1']           = "-";
                    $params[$k]['Place of birth Anak 1']          = "-";
                    $params[$k]['Date of birth Anak 1']           = "-";
                    $params[$k]['Education level Anak 1']         = "-";
                    $params[$k]['Occupation Anak 1']              = "-";
            }

            $anak2 = UserFamily::where('user_id', $item->id)->where('hubungan','Anak 2')->first();
            if(!empty($anak2)) {
                    $params[$k]['Relative Name Anak 2']           = $anak2 ->nama;
                    $params[$k]['Place of birth Anak 2']          = $anak2->tempat_lahir;
                    $params[$k]['Date of birth Anak 2']           = $anak2->tanggal_lahir;
                    $params[$k]['Education level Anak 2']         = $anak2->jenjang_pendidikan;
                    $params[$k]['Occupation Anak 2']              = $anak2->pekerjaan;
            } else
            {       
                    $params[$k]['Relative Name Anak 2']           = "-";
                    $params[$k]['Place of birth Anak 2']          = "-";
                    $params[$k]['Date of birth Anak 2']           = "-";
                    $params[$k]['Education level Anak 2']         = "-";
                    $params[$k]['Occupation Anak 2']              = "-";
            }

            $anak3 = UserFamily::where('user_id', $item->id)->where('hubungan','Anak 3')->first();
            if(!empty($anak3)) {
                    $params[$k]['Relative Name Anak 3']           = $anak3 ->nama;
                    $params[$k]['Place of birth Anak 3']          = $anak3->tempat_lahir;
                    $params[$k]['Date of birth Anak 3']           = $anak3->tanggal_lahir;
                    $params[$k]['Education level Anak 3']         = $anak3->jenjang_pendidikan;
                    $params[$k]['Occupation Anak 3']              = $anak3->pekerjaan;
            } else
            {       
                    $params[$k]['Relative Name Anak 3']           = "-";
                    $params[$k]['Place of birth Anak 3']          = "-";
                    $params[$k]['Date of birth Anak 3']           = "-";
                    $params[$k]['Education level Anak 3']         = "-";
                    $params[$k]['Occupation Anak 3']              = "-";
            }

            $anak4 = UserFamily::where('user_id', $item->id)->where('hubungan','Anak 4')->first();
            if(!empty($anak4)) {
                    $params[$k]['Relative Name Anak 4']           = $anak4 ->nama;
                    $params[$k]['Place of birth Anak 4']          = $anak4->tempat_lahir;
                    $params[$k]['Date of birth Anak 4']           = $anak4->tanggal_lahir;
                    $params[$k]['Education level Anak 4']         = $anak4->jenjang_pendidikan;
                    $params[$k]['Occupation Anak 4']              = $anak4->pekerjaan;
            } else
            {       
                    $params[$k]['Relative Name Anak 4']           = "-";
                    $params[$k]['Place of birth Anak 4']          = "-";
                    $params[$k]['Date of birth Anak 4']           = "-";
                    $params[$k]['Education level Anak 4']         = "-";
                    $params[$k]['Occupation Anak 4']              = "-";
            }

            $anak5 = UserFamily::where('user_id', $item->id)->where('hubungan','Anak 5')->first();
            if(!empty($anak5)) {
                    $params[$k]['Relative Name Anak 5']           = $anak5 ->nama;
                    $params[$k]['Place of birth Anak 5']          = $anak5->tempat_lahir;
                    $params[$k]['Date of birth Anak 5']           = $anak5->tanggal_lahir;
                    $params[$k]['Education level Anak 5']         = $anak5->jenjang_pendidikan;
                    $params[$k]['Occupation Anak 5']              = $anak5->pekerjaan;
            } else
            {       
                    $params[$k]['Relative Name Anak 5']           = "-";
                    $params[$k]['Place of birth Anak 5']          = "-";
                    $params[$k]['Date of birth Anak 5']           = "-";
                    $params[$k]['Education level Anak 5']         = "-";
                    $params[$k]['Occupation Anak 5']              = "-";
            }
        }

        return (new \App\KaryawanExport($params, 'Report Employee '. date('d F Y') ))->download('EM-HR.Report-Employee-'.date('d-m-Y') .'.xlsx');
    }
}
