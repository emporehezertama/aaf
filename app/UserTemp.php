<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTemp extends Model
{
    protected $table = 'users_temp';

    /**
     * [education description]
     * @return [type] [description]
     */ 
    public function education()
    {
    	return $this->hasMany('\App\UserEducationTemp', 'user_temp_id', 'id');
    }

    /**
     * [dependent description]
     * @return [type] [description]
     */
    public function family()
    {
    	return $this->hasMany('\App\UserFamilyTemp', 'user_temp_id', 'id');
    }

    /**
     * [department description]
     * @return [type] [description]
     */
    public function department()
    {
        return $this->hasOne('App\OrganisasiDepartment', 'id', 'organisasi_department');
    }

    /**
     * [section description]
     * @return [type] [description]
     */
    public function section()
    {
        return $this->hasOne('App\OrganisasiUnit', 'id', 'organisasi_unit');
    }

    /**
     * [position description]
     * @return [type] [description]
     */
    public function position()
    {
        return $this->hasOne('App\OrganisasiPosition', 'id', 'organisasi_position');
    }

    /**
     * [position description]
     * @return [type] [description]
     */
    public function organisasiposition()
    {
        return $this->hasOne('App\OrganisasiPosition', 'id', 'organisasi_position');
    }

    /**
     * [division description]
     * @return [type] [description]
     */
    public function division()
    {
        return $this->hasOne('App\OrganisasiDivision', 'id', 'organisasi_division');
    }

    /**
     * [cabang description]
     * @return [type] [description]
     */
    public function cabang()
    {
        return $this->hasOne('App\Cabang', 'id', 'organisasi_branch');
    }
}
