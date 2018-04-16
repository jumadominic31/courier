<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function zone()
    {
        return $this->belongsTo('App\Zone', 'zone_id');
    }
}
