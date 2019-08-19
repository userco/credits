<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditInvest extends Model
{
    protected $table = 'invest_credit';
	protected $fillable = [
        'id','user_id', 'credit_id', 'investment'
    ];
	protected $primaryKey = 'id';
	public $timestamps=true;
}