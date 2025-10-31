<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = [
        'type','name','reg_no','vat_no','email','phone','address','user_id',
    ];

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function user() {
    return $this->belongsTo(User::class);
    }

}
