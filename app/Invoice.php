<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    public function sender_company()
    {
        return $this->belongsTo('App\Company', 'sender_company_id');
    }
}
