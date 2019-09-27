<?php

namespace App\Http\Controllers\Administrator;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PayrollController extends Controller
{   

	public function __construct(\Maatwebsite\Excel\Excel $excel)
	{
	    $this->excel = $excel;
	}

    /**
     * [index description]
     * @return [type] [description]
     */
    public function index()
    {
        $params['data'] = \App\Payroll::all();

        return view('administrator.payroll.index')->with($params);
    }

    /**
     * [import description]
     * @return [type] [description]
     */
    public function import()
    {	
    	return view('administrator.payroll.import');
    }

    /**
     * [download description]
     * @return [type] [description]
     */
    public function download()
    {
        $users = \App\User::where('access_id', 2)->get();

        $params = [];

        foreach($users as $k =>  $i)
        {
            // cek data payroll
            $payroll = \App\Payroll::where('user_id', $i->user_id)->first();

            $params[$k]['NO']           = $k+1;
            $params[$k]['NIK']          = $i->nik;
            $params[$k]['Nama']         = $i->name;

            if($payroll)
            {
                $params[$k]['Salary']                           = $payroll->salary;
                $params[$k]['% JKK (Accident) + JK (Death)']    = $payroll->jkk;
                $params[$k]['Call Allowance']                   = $payroll->call_allow;
                $params[$k]['Yearly Bonus, THR or others']      = $payroll->bonus;
            }
            else
            {
                $params[$k]['Salary']                           = 0;
                $params[$k]['% JKK (Accident) + JK (Death)']    = 0;
                $params[$k]['Call Allowance']                   = 0;
                $params[$k]['Yearly Bonus, THR or others']      = 0;
            }
        }

        return \Excel::create('datapayroll',  function($excel) use($params){
              $excel->sheet('mysheet',  function($sheet) use($params){
              $sheet->fromArray($params);
              });
        })->download('xls');
    }

    /**
     * [detail description]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function detail($id)
    {
        $params['data'] = \App\Payroll::where('id', $id)->first();

        return view('administrator.payroll.detail')->with($params);
    }

    /**
     * [calculate description]
     * @return [type] [description]
     */
    public function calculate()
    {
        $data = \App\Payroll::all();

        $biaya_jabatan = \App\PayrollOthers::where('id', 1)->first()->value;
        $upah_minimum = \App\PayrollOthers::where('id', 2)->first()->value;

        foreach($data as $item)
        {
            //if($item->is_calculate == 1) continue;

            $temp = \App\Payroll::where('id', $item->id)->first();

            $jkk_result = 0;
            if(!empty($item->jkk))
            {
                $jkk_result             = ($item->salary * $item->jkk / 100);                
            }

            $gross_income = (($item->salary + $item->call_allow + $jkk_result)* 12) + $item->bonus;
            // burdern allowance
            $burden_allow = 5 * $gross_income / 100;
            if($burden_allow > $biaya_jabatan)
            {
                $burden_allow = $biaya_jabatan;
            }

            // Jamsostek Premium
            $jamsostek = 0;
            if($item->salary > $upah_minimum)
            {
                $jamsostek = ($item->salary * $item->jamsostek / 100) * 12;
            }
            else
            {
                $jamsostek = ($upah_minimum * $item->jamsostek / 100) * 12;

            }

            $total_deduction = $jamsostek + $burden_allow;

            $net_yearly_income          = $gross_income - $total_deduction;

            $untaxable_income = 0;

            $ptkp = \App\PayrollPtkp::where('id', 1)->first();
            if($item->user->marital_status == 'Bujangan/Wanita')
            {
                $untaxable_income = $ptkp->bujangan_wanita;
            }
            if($item->user->marital_status == 'Menikah')
            {
                $untaxable_income = $ptkp->menikan;
            }
            if($item->user->marital_status == 'Menikah Anak 1')
            {
                $untaxable_income = $ptkp->menikah_anak_1;
            }
            if($item->user->marital_status == 'Menikah Anak 2')
            {
                $untaxable_income = $ptkp->menikah_anak_2;
            }
            if($item->user->marital_status == 'Menikah Anak 3')
            {
                $untaxable_income = $ptkp->menikah_anak_3;
            }

            $taxable_yearly_income = $net_yearly_income - $untaxable_income;

            // Perhitungan 5 persen
            $income_tax_calculation_5 = 0;
            if($taxable_yearly_income <= 50000000)
            {
                $income_tax_calculation_5 = 0.05 * $taxable_yearly_income;
            }
            if($taxable_yearly_income >= 50000000)
            {
                $income_tax_calculation_5 = 0.05 * 50000000;
            }

            // Perhitungan 15 persen
            $income_tax_calculation_15 = 0;
            if($taxable_yearly_income >= 250000000 )
            {
                $income_tax_calculation_15 = 0.15 * (250000000 - 50000000);
            }
            if($taxable_yearly_income >= 50000000 and $taxable_yearly_income <= 250000000)
            {
                $income_tax_calculation_15 = 0.15 * ($taxable_yearly_income - 50000000);
            }

            // Perhitungan 25 persen
            $income_tax_calculation_25 = 0;
            if($taxable_yearly_income >= 500000000)
            {
                $income_tax_calculation_25 = 0.25 * (500000000 - 250000000);
            }

            if($taxable_yearly_income <= 500000000 and $taxable_yearly_income >= 250000000)
            {
                $income_tax_calculation_25 = 0.25 * ($taxable_yearly_income - 250000000);
            }

            $income_tax_calculation_30 = 0;
            if($taxable_yearly_income >= 500000000)
            {
                $income_tax_calculation_30 = 0.35 * ($taxable_yearly_income - 500000000);
            }

            $yearly_income_tax = $income_tax_calculation_5 + $income_tax_calculation_15 + $income_tax_calculation_25 + $income_tax_calculation_30;
            $monthly_income_tax = $yearly_income_tax / 12;
            $basic_salary       = $gross_income / 12;
            $less               = ($jamsostek / 12) + $jkk_result + $monthly_income_tax; 
            $thp                = $basic_salary - $less;

            $temp->jkk_result           = $jkk_result;
            $temp->gross_income         = $gross_income; 
            $temp->burden_allow         = $burden_allow;
            $temp->jamsostek_result     = $jamsostek;
            $temp->total_deduction      = $total_deduction;
            $temp->net_yearly_income    = $net_yearly_income;
            $temp->untaxable_income     = $untaxable_income;
            $temp->taxable_yearly_income        = $taxable_yearly_income;
            $temp->income_tax_calculation_5     = $income_tax_calculation_5; 
            $temp->income_tax_calculation_15    = $income_tax_calculation_15; 
            $temp->income_tax_calculation_25    = $income_tax_calculation_25; 
            $temp->income_tax_calculation_30    = $income_tax_calculation_30; 
            $temp->yearly_income_tax            = $yearly_income_tax;
            $temp->monthly_income_tax           = $monthly_income_tax;
            $temp->basic_salary                 = $basic_salary;
            $temp->less                         = $less;
            $temp->thp                          = $thp;
            $temp->is_calculate                 = 1;

            $temp->save();
        }

        return redirect()->route('administrator.payroll.index')->with('messages-success', 'Data Payroll berhasil di calculate !');
    }

    /**
     * [import description]
     * @return [type] [description]
     */
    public function tempImport(Request $request)
    {	
    	$this->validate($request, [
	        'file' => 'required',
	    ]);

    	if($request->hasFile('file'))
        {
            //$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file);
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
            foreach($rows as $key => $row)
            {
            	if($key <=4) continue;

                $nik        = $row[1];
                $salary     = $row[3]; 
                $jkk        = $row[4];
                $call_allow = $row[5];
                $bonus      = $row[6];

                // cek user 
                $user = \App\User::where('nik', $nik)->first();
                if($user)
                {   
                    // cek exit payrol user
                    $payroll = \App\Payroll::where('user_id', $user->id)->first();
                    if(!$payroll)
                    {
                        $payroll            = new \App\Payroll();
                        $payroll->user_id   = $user->id;
                    } 
                    
                    $payroll->salary        = $salary;
                    $payroll->jkk           = $jkk;
                    $payroll->call_allow    = $call_allow;
                    $payroll->bonus         = $bonus;
                    $payroll->is_calculate  = 0;
                    $payroll->save();
                }
	        }

            return redirect()->route('administrator.payroll.index')->with('messages-success', 'Data Payroll berhasil di import');
        }
    }
}
