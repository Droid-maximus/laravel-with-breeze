<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id','line_no','description','unit','qty','unit_price','discount',
        'net_amount','vat_rate','vat_amount','gross_amount',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
