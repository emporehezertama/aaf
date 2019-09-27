<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MedicalReimbursement extends Model
{
    protected $table = 'medical_reimbursement';

    /**
     * [user description]
     * @return [type] [description]
     */
    public function user()
    {
    	return $this->hasOne('App\User','id','user_id');
    }

    /**
     * [form description]
     * @return [type] [description]
     */
    public function form()
    {
    	return $this->hasMany('App\MedicalReimbursementForm', 'medical_reimbursement_id', 'id');
    }

    /**
     * [atasan description]
     * @return [type] [description]
     */
    public function atasan()
    {
        return $this->hasOne('\App\User', 'id', 'approved_atasan_id');
    }

    /**
     * [direktur description]
     * @return [type] [description]
     */
    public function direktur()
    {
        return $this->hasOne('\App\User', 'id', 'approve_direktur_id');
    }

    /**
     * [hr_benefit description]
     * @return [type] [description]
     */
    public function hr_benefit()
    {
        return $this->hasOne('\App\User', 'id', 'hr_benefit_id');
    }

    /**
     * [manager_hr description]
     * @return [type] [description]
     */
    public function manager_hr()
    {
        return $this->hasOne('\App\User', 'id', 'manager_hr_id');
    }

    /**
     * [gm_hr description]
     * @return [type] [description]
     */
    public function gm_hr()
    {
        return $this->hasOne('\App\User', 'id', 'gm_hr_id');
    }
}
