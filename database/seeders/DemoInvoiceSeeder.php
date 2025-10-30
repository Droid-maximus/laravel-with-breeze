<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoInvoiceSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            $client = Client::create([
                'type'    => 'company',
                'name'    => 'SIA Demo',
                'reg_no'  => '4010xxxxxxx',
                'vat_no'  => 'LV4010xxxxxxx',
                'email'   => 'info@demo.lv',
                'phone'   => '+37120000000',
                'address' => 'Brīvības iela 1, Rīga',
            ]);

            $inv = Invoice::create([
                'number'        => 'INV-0001',
                'client_id'     => $client->id,
                'issue_date'    => now()->toDateString(),
                'due_date'      => now()->addDays(14)->toDateString(),
                'currency'      => 'EUR',
                'status'        => 'draft',
                // snapshot no klienta
                'buyer_name'    => $client->name,
                'buyer_reg_no'  => $client->reg_no,
                'buyer_vat_no'  => $client->vat_no,
                'buyer_address' => $client->address,
            ]);

            // Rinda 1
            $qty = 1; $price = 100.00; $disc = 0.00; $vatRate = 21.00;
            $net = $qty * ($price - $disc);
            $vat = round($net * $vatRate/100, 2);
            $gross = $net + $vat;

            InvoiceItem::create([
                'invoice_id'   => $inv->id,
                'line_no'      => 1,
                'description'  => 'Logo dizains',
                'unit'         => 'service',
                'qty'          => $qty,
                'unit_price'   => $price,
                'discount'     => $disc,
                'net_amount'   => $net,
                'vat_rate'     => $vatRate,
                'vat_amount'   => $vat,
                'gross_amount' => $gross,
            ]);

            // Rinda 2
            $qty = 2; $price = 25.00; $disc = 0.00; $vatRate = 21.00;
            $net = $qty * ($price - $disc);
            $vat = round($net * $vatRate/100, 2);
            $gross = $net + $vat;

            InvoiceItem::create([
                'invoice_id'   => $inv->id,
                'line_no'      => 2,
                'description'  => 'Druka',
                'unit'         => 'pcs',
                'qty'          => $qty,
                'unit_price'   => $price,
                'discount'     => $disc,
                'net_amount'   => $net,
                'vat_rate'     => $vatRate,
                'vat_amount'   => $vat,
                'gross_amount' => $gross,
            ]);

            // pārrēķins
            $inv->load('items');
            $inv->recalcTotals();
        });
    }
}
