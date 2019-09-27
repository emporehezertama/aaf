@if(Auth::user()->access_id == 1)
    <ul class="nav" id="side-menu">
        <li class="user-pro">
            <a href="javascript:void(0)" class="waves-effect"><img src="{{ asset('admin-css/plugins/images/users/varun.jpg') }}" alt="user-img" class="img-circle"> <span class="hide-menu"> {{ Auth::user()->name }}<span class="fa arrow"></span></span>
            </a>
            <ul class="nav nav-second-level collapse" aria-expanded="false" style="height: 0px;">
                <li><a href="#"><i class="ti-user"></i> <span class="hide-menu">My Profile</span></a></li>
                <li><a href="{{ url('logout') }}"><i class="fa fa-power-off"></i> <span class="hide-menu">Logout</span></a></li>
            </ul>
        </li>
        <li> <a href="{{ route('administrator.dashboard') }}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Dashboard <span class="fa arrow"></span> <span class="label label-rouded label-inverse pull-right">4</span></span></a></li>
        <li class="devider"></li>
        <li>
            <a href="{{ route('administrator.karyawan.index') }}">
                <i class="mdi mdi-account-multiple fa-fw"></i> <span class="hide-menu">Karyawan<span class="fa arrow"></span></span>
            </a>
        </li>
        <li class="mega-nav">
            <a href="javascript:void;" style="position: relative;">
                <i class="mdi mdi-account-multiple fa-fw"></i> <span class="hide-menu">Workflow Monitoring<span class="fa arrow"></span></span>
                @if(cek_count_cuti_admin() > 0 || cek_count_exit_admin() > 0 || cek_count_training_admin() > 0) 
                    <div class="notify" style="position: absolute;top: 61px;right: 10px;"> <span class="heartbit"></span> <span class="point"></span> </div>
                @endif
            </a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{{ route('administrator.cuti.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Cuti / Izin Karyawan</span>
                        @if(cek_count_cuti_admin() > 0)
                            <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ cek_count_cuti_admin() }} </label>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('administrator.payment-request.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Payment Request</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.medical.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Medical Reimbursement</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.overtime.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Overtime Sheet </span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.exit-interview.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Exit Interview & Clearance </span>
                        @if(cek_count_exit_admin() > 0)
                            <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ cek_count_exit_admin() }} </label>
                        @endif
                    </a>
                </li>
                <li>
                    <a href="{{ route('administrator.training.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Training & Perjalanan Dinas</span>
                        @if(cek_count_training_admin() > 0)
                            <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ cek_count_training_admin() }} </label>
                        @endif
                    </a>
                </li>
            </ul>
        </li>

        <li class="mega-nav">
            <a href="{{ route('administrator.structure') }}" class="waves-effect">
                <i class="mdi mdi-account-network fa-fw"></i> <span class="hide-menu">Organization Structure<span class="fa arrow"></span></span>
            </a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{{ route('administrator.directorate.index') }}"><i class="mdi mdi-account-network fa-fw"></i><span class="hide-menu">Directorate</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.division.index') }}"><i class="mdi mdi-account-network fa-fw"></i><span class="hide-menu">Divison</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.department.index') }}"><i class="mdi mdi-account-network fa-fw"></i><span class="hide-menu">Department</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.section.index') }}"><i class="mdi mdi-account-network fa-fw"></i><span class="hide-menu">Section / Unit</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.position.index') }}"><i class="mdi mdi-account-network fa-fw"></i><span class="hide-menu">Position</span></a>
                </li> 
<!--                 <li>
                    <a href="{{ route('administrator.job-rule.index') }}"><i class="mdi mdi-account-network fa-fw"></i><span class="hide-menu">Job Rule</span></a>
                </li>  -->
            </ul>
        </li>
        
        <li class="mega-nav">
            <a href="javascript:void(0)" class="waves-effect">
                <i class="mdi mdi-settings fa-fw"></i> <span class="hide-menu">Setting<span class="fa arrow"></span></span>
            </a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{{ route('administrator.setting.index') }}"><i class="mdi mdi-settings fa-fw"></i><span class="hide-menu">General</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.cabang.index') }}"><i class="mdi mdi-office fa-fw"></i><span class="hide-menu">Cabang</span></a>
                </li>
               
                <li>
                    <a href="{{ route('administrator.kabupaten.index') }}"><i class="mdi mdi-google-maps fa-fw"></i><span class="hide-menu">Kabupaten / Kota</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.bank.index') }}"><i class="mdi mdi-bank fa-fw"></i><span class="hide-menu">Bank</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.alasan-pengunduran-diri.index') }}"><i class="mdi mdi-settings fa-fw"></i><span class="hide-menu">Alasan Pengunduran Diri</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.setting-master-cuti.index') }}"><i class="mdi mdi-settings fa-fw"></i><span class="hide-menu">Cuti</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.cuti-bersama.index') }}"><i class="mdi mdi-settings fa-fw"></i><span class="hide-menu">Cuti Bersama</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.absensi.index') }}"><i class="mdi mdi-settings fa-fw"></i><span class="hide-menu">Absensi</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.libur-nasional.index') }}"><i class="mdi mdi-settings fa-fw"></i><span class="hide-menu">Libur Nasional</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.plafond-dinas.index') }}" style="width: 250px"><i class="mdi mdi-settings fa-fw"></i><span class="hide-menu">Plafond Perjalanan Dinas & Training</span></a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)" class="waves-effect">
                <i class="mdi mdi-settings fa-fw"></i> <span class="hide-menu">Setting Education<span class="fa arrow"></span></span>
            </a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{{ route('administrator.universitas.index') }}"><i class="mdi mdi-library-books fa-fw"></i><span class="hide-menu">Perguruan Tinggi</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.program-studi.index') }}"><i class="mdi mdi-library-books fa-fw"></i><span class="hide-menu">Program Studi</span></a>
                </li>
            </ul>
        </li>
        <li class="mega-nav">
            <a href="javascript:void(0)" class="waves-effect">
                <i class="mdi mdi-settings fa-fw"></i> <span class="hide-menu">Setting Approval Form<span class="fa arrow"></span></span>
            </a>
            <ul class="nav nav-second-level">
                 <li>
                    <a href="{{ route('administrator.setting-cuti.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Cuti / Izin Karyawan</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.setting-payment-request.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Payment Request</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.setting-medical.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Medical Reimbursement</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.setting-overtime.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Overtime Sheet </span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.setting-training.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Training & Perjalanan Dinas</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.setting-exit-clearance.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Exit Clearance Management</span></a>
                </li>
            </ul>
        </li>
        <li>
            <a href="javascript:void(0)">
                <i class="mdi mdi-newspaper fa-fw"></i> <span class="hide-menu">News List / Memo<span class="fa arrow"></span></span>
            </a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{{ route('administrator.news.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">News</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.internal-memo.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Internal Memo</span></a>
                </li>
                <li>
                    <a href="{{ route('administrator.peraturan-perusahaan.index') }}"><i class="mdi mdi-clipboard-text fa-fw"></i><span class="hide-menu">Product Information</span></a>
                </li>
            </ul>
        </li>
    </ul>
@else
    <ul class="nav" id="side-menu">
        <li class="user-pro">
            <a href="javascript:void(0)" class="waves-effect"><img src="{{ asset('admin-css/plugins/images/users/varun.jpg') }}" alt="user-img" class="img-circle"> <span class="hide-menu"> {{ Auth::user()->name }}<span class="fa arrow"></span></span>
            </a>
            <ul class="nav nav-second-level collapse" aria-expanded="false" style="height: 0px;">
                <li><a href="#"><i class="ti-user"></i> <span class="hide-menu">My Profile</span></a></li>
                <li><a href="{{ url('logout') }}"><i class="fa fa-power-off"></i> <span class="hide-menu">Logout</span></a></li>
            </ul>
        </li>
        <li> <a href="{{ route('karyawan.dashboard') }}" class="waves-effect"><i class="mdi mdi-av-timer fa-fw" data-icon="v"></i> <span class="hide-menu"> Dashboard <span class="fa arrow"></span> <span class="label label-rouded label-inverse pull-right">4</span></span></a></li>
        <li class="devider"></li>
        <li class="mega-nav">
            <a href="javascript:void(0)" class="waves-effect">
                <i class="mdi mdi-account-multiple fa-fw"></i> <span class="hide-menu">Management Form<span class="fa arrow"></span></span>
            </a>
            <ul class="nav nav-second-level">
                <li>
                    <a href="{{ route('karyawan.cuti.index') }}"><i class="ti-user fa-fw"></i><span class="hide-menu">Cuti / Ijin Karyawan</span></a>
                </li>
                <li>
                    <a href="{{ route('karyawan.payment-request.index') }}"><i class="ti-user fa-fw"></i><span class="hide-menu">Payment Request</span></a>
                </li>
                <li>
                    <a href="{{ route('karyawan.overtime.index') }}"><i class="ti-user fa-fw"></i><span class="hide-menu">Overtime Sheet </span></a>
                </li>
                <li>
                    <a href="{{ route('karyawan.exit-interview.index') }}"><i class="ti-user fa-fw"></i><span class="hide-menu">Exit Interview & Clearance</span></a>
                </li>
                 <li>
                    <a href="{{ route('karyawan.training.index') }}"><i class="ti-user fa-fw"></i><span class="hide-menu">Training & Perjalanan Dinas</span></a>
                </li>
                <li>
                    <a href="{{ route('karyawan.medical.index') }}"><i class="ti-user fa-fw"></i><span class="hide-menu">Medical Reimbursement</span></a>
                </li>
            </ul>
        </li>

        @if(cek_approval_user())
            <li>
                <a href="javascript:void(0)" class="waves-effect">
                    <i class="mdi mdi-account-check fa-fw"></i> <span class="hide-menu atas">Management Approval (Assign)<span class="fa arrow"></span></span>
                    @if(cek_overtime_approval_user_2() > 0 || count_approval_payment_request() > 0 || count_approval_medical_karyawan('null') > 0 || count_approval_training('null') > 0 )
                        <div class="notify" style="position: absolute;top: 61px;right: 10px;"> <span class="heartbit"></span> <span class="point"></span> </div>
                    @endif
                </a>
                <ul class="nav nav-second-level">
                @foreach(list_approval_user() as $item)
                    <?php if($item['link'] == 'training_mengetahui') { continue; } ?>
                    <li>
                        <a href="{{ route('karyawan.approval.'.  $item['link'].'.index') }}"><i class="ti-check-box fa-fw"></i><span class="hide-menu">{{ $item['nama_menu'] }}</span>
                            
                            @if($item['link'] == 'cuti')
                                <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ count_cuti_approved_personalia() }}</label>
                            @endif

                            @if($item['link'] == 'overtime')
                                <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{cek_overtime_approval_user_2()}}</label>
                            @endif
                            
                            @if($item['link'] =='payment_request')
                                <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ count_approval_payment_request() }}</label>
                            @endif

                            @if($item['link'] =='medical')
                                <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ count_approval_medical_karyawan('null') }}</label>
                            @endif

                            @if($item['link'] =='training')
                                <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{ count_approval_training('null') }}</label>
                            @endif
                        </a>
                    </li>
                @endforeach
                </ul>
            </li>
        @endif

        <li style="position: relative;">
            <a href="javascript:void(0)" class="waves-effect">
                <i class="mdi mdi-account-check fa-fw"></i> <span class="hide-menu">Management Approval (Superior)<span class="fa arrow"></span></span>
            </a>

                @if(cek_cuti_approval_user(\Auth::user()->id, 'null') > 0 ||  cek_overtime_approval_user_count(\Auth::user()->id) > 0 || count_medical_approval_atasan('null') > 0 || count_exit_approval_user(\Auth::user()->id) > 0 || count_training_approval_atasan('null') > 0)    
                    <div class="notify" style="position: absolute;top: 61px;right: 10px;"> <span class="heartbit"></span> <span class="point"></span> </div>
                @endif

            <ul class="nav nav-second-level">
                @if(cek_cuti_approval_user(\Auth::user()->id, 'null') > 0)
                <li style="position: relative;">
                    <a href="{{ route('karyawan.approval.cuti-atasan.index') }}"><i class="ti-check-box fa-fw"></i><span class="hide-menu">Cuti / Ijin</span> 
                        <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{cek_cuti_approval_user(\Auth::user()->id, 'null')}}</label>
                    </a>
                </li>
                @endif

                @if(cek_overtime_approval_user(\Auth::user()->id) > 0)
                <li style="position: relative;">
                    <a href="{{ route('karyawan.approval.overtime-atasan.index') }}"><i class="ti-check-box fa-fw"></i><span class="hide-menu">Overtime Sheet</span>
                        <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{cek_overtime_approval_user_count(\Auth::user()->id)}}</label>
                    </a>
                </li>
                @endif

                @if(count_medical_approval_atasan('all') > 0)
                <li style="position: relative;">
                    <a href="{{ route('karyawan.approval.medical-atasan.index') }}"><i class="ti-check-box fa-fw"></i><span class="hide-menu">Medical Reimbursement</span>
                        <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{count_medical_approval_atasan('null')}}</label>
                    </a>
                </li>
                @endif

                @if(cek_exit_approval_user(\Auth::user()->id) > 0)
                <li style="position: relative;">
                    <a href="{{ route('karyawan.approval.exit-atasan.index') }}"><i class="ti-check-box fa-fw"></i><span class="hide-menu">Exit Interview & Exit Clearance</span>
                        <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{count_exit_approval_user(\Auth::user()->id)}}</label>
                    </a>
                </li>
                @endif

                @if(count_training_approval_atasan('all') > 0)
                <li style="position: relative;">
                    <a href="{{ route('karyawan.approval.training-atasan.index') }}"><i class="ti-check-box fa-fw"></i><span class="hide-menu">Training & Perjanalan Dinas</span>
                        <label class="btn btn-danger btn-xs" style="position: absolute;right:10px; top: 10px;">{{count_training_approval_atasan('null')}}</label>
                    </a>
                </li>
                @endif
            </ul>
        </li>

    </ul>
@endif