<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'number','client_id','issue_date','due_date','currency','status',
        'total_net','total_vat','total_gross',
        'buyer_name','buyer_reg_no','buyer_vat_no','buyer_address',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Palīgsumu pārrēķins no rindām, (ielikt vēlāk)
    public function recalcTotals(): void
    {
        $net  = $this->items->sum('net_amount');
        $vat  = $this->items->sum('vat_amount');
        $gross= $this->items->sum('gross_amount');

        $this->update([
            'total_net'   => $net,
            'total_vat'   => $vat,
            'total_gross' => $gross,
        ]);
    }

    // čeks
        public static function generateNumber(): string
    {
        $year = date('Y');
        $last = self::whereYear('created_at', $year)
            ->orderByDesc('id')
            ->value('number');

        if ($last && preg_match('/INV-\d{4}-(\d+)/', $last, $m)) {
            $next = (int)$m[1] + 1;
        } else {
            $next = 1;
        }

        return sprintf('INV-%s-%05d', $year, $next);
    }

}
