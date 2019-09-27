<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaymentRequest extends Model
{
    protected $table = 'payment_request';

    /**
     * [user description]
     * @return [type] [description]
     */
    public function user()
    {
    	return $this->hasOne('App\User', 'id', 'user_id');
    }

    /**
     * [payment_request_form description]
     * @return [type] [description]
     */
    public function payment_request_form()
    {
    	return $this->hasMany('App\PaymentRequestForm', 'payment_request_id', 'id');
    }

    /**
     * [proposal_approved description]
     * @return [type] [description]
     */
    public function proposal_approval()
    {
        return $this->hasOne('App\User', 'id', 'proposal_approval_id');
    }

    /**
     * [proposal_verification description]
     * @return [type] [description]
     */
    public function proposal_verification()
    {
        return $this->hasOne('App\User', 'id', 'proposal_verification_id');
    }

    /**
     * [payment_approval description]
     * @return [type] [description]
     */
    public function payment_approval()
    {
        return $this->hasOne('App\User', 'id', 'payment_approval_id');
    }    
}