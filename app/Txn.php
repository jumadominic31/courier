<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Txn extends Model
{
    public function origin()
    {
        return $this->belongsTo('App\Station', 'origin_id');
    }

    public function dest()
    {
        return $this->belongsTo('App\Station', 'dest_id');
    }

    public function clerk()
    {
        return $this->belongsTo('App\User', 'clerk_id');
    }

    public function rider()
    {
        return $this->belongsTo('App\User', 'driver_id');
    }

    public function driver()
    {
        return $this->belongsTo('App\User', 'driver_id');
    }

    public function vehicle()
    {
        return $this->belongsTo('App\Vehicle', 'vehicle_id');
    }

    public function parcel_status()
    {
        return $this->belongsTo('App\ParcelStatus', 'parcel_status_id');
    }

    public function parcel_type()
    {
        return $this->belongsTo('App\ParcelType', 'parcel_type_id');
    }

    public function zone_origin()
    {
        return $this->belongsTo('App\Zone', 'origin_id');
    }

    public function zone_dest()
    {
        return $this->belongsTo('App\Zone', 'dest_id');
    }

    public function sender_company()
    {
        return $this->belongsTo('App\Company', 'sender_company_id');
    }
}
