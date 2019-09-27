<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OvertimeSheet extends Model
{
    protected $table = 'overtime_sheet';

    /**
     * [user description]
     * @return [type] [description]
     */
    public function user()
    {
    	return $this->hasOne('App\User', 'id', 'user_id');
    }

    /**
     * [overtime_form description]
     * @return [type] [description]
     */
    public function overtime_form()
    {
    	return $this->hasMany('App\OvertimeSheetForm', 'overtime_sheet_id', 'id');
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
     * [hr_benefit description]
     * @return [type] [description]
     */
    public function hr_benefit()
    {
        return $this->hasOne('\App\User', 'id', 'hr_benefit_id');
    }

    /**
     * [hr_manager description]
     * @return [type] [description]
     */
    public function hr_manager()
    {
        return $this->hasOne('\App\User', 'id', 'hr_manager_id');
    }
}
