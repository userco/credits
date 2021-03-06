<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    protected $table = 'credit';
	protected $fillable = [
        'id','external_id', 'period', 'total', 'invested_amount', 'type'
    ];
	protected $primaryKey = 'id';
	public $timestamps=true;
}