<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreInvoiceRequest;
use App\Http\Requests\UpdateInvoiceRequest;

class InvoicesController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('client')->orderByDesc('id')->paginate(10);
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        return view('invoices.create', [
            'invoice' => new Invoice(),
            'clients' => Client::orderBy('name')->get(),
        ]);
    }

    public function store(StoreInvoiceRequest $request)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data) {
            $client = Client::findOrFail($data['client_id']);

            $inv = Invoice::create([
                'number'        => Invoice::generateNumber(),
                'client_id'     => $client->id,
                'issue_date'    => $data['issue_date'],
                'due_date'      => $data['due_date'] ?? null,
                'currency'      => $data['currency'],
                'status'        => $data['status'],
                // snapshot no klienta
                'buyer_name'    => $client->name,
                'buyer_reg_no'  => $client->reg_no,
                'buyer_vat_no'  => $client->vat_no,
                'buyer_address' => $client->address,
            ]);

            $lineNo = 1;
            $sumNet = $sumVat = $sumGross = 0;

            foreach ($data['items'] as $it) {
                $qty = (float)$it['qty'];
                $price = (float)$it['unit_price'];
                $disc = isset($it['discount']) ? (float)$it['discount'] : 0;
                $vatRate = (float)$it['vat_rate'];

                $net = $qty * ($price - $disc);
                $vat = round($net * $vatRate/100, 2);
                $gross = $net + $vat;

                InvoiceItem::create([
                    'invoice_id'   => $inv->id,
                    'line_no'      => $lineNo++,
                    'description'  => $it['description'],
                    'unit'         => $it['unit'] ?? 'pcs',
                    'qty'          => $qty,
                    'unit_price'   => $price,
                    'discount'     => $disc,
                    'net_amount'   => $net,
                    'vat_rate'     => $vatRate,
                    'vat_amount'   => $vat,
                    'gross_amount' => $gross,
                ]);

                $sumNet += $net; $sumVat += $vat; $sumGross += $gross;
            }

            $inv->recalcTotals();
            
        });

        return redirect()->route('invoices.index')->with('status','Rēķins izveidots!');
    }

    public function edit(Invoice $invoice)
    {
        $invoice->load('items','client');
        return view('invoices.edit', [
            'invoice' => $invoice,
            'clients' => Client::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateInvoiceRequest  $request, Invoice $invoice)
    {
        $data = $request->validated();

        DB::transaction(function () use ($data, $invoice) {
            $client = Client::findOrFail($data['client_id']);

            $invoice->update([
                'number'        => $data['number'],
                'client_id'     => $client->id,
                'issue_date'    => $data['issue_date'],
                'due_date'      => $data['due_date'] ?? null,
                'currency'      => $data['currency'],
                'status'        => $data['status'],
                'buyer_name'    => $client->name,
                'buyer_reg_no'  => $client->reg_no,
                'buyer_vat_no'  => $client->vat_no,
                'buyer_address' => $client->address,
            ]);

            // pārrakstām rindas vienkāršības dēļ
            $invoice->items()->delete();

            $lineNo = 1;
            $sumNet = $sumVat = $sumGross = 0;

            foreach ($data['items'] as $it) {
                $qty = (float)$it['qty'];
                $price = (float)$it['unit_price'];
                $disc = isset($it['discount']) ? (float)$it['discount'] : 0;
                $vatRate = (float)$it['vat_rate'];

                $net = $qty * ($price - $disc);
                $vat = round($net * $vatRate/100, 2);
                $gross = $net + $vat;

                $invoice->items()->create([
                    'line_no'      => $lineNo++,
                    'description'  => $it['description'],
                    'unit'         => $it['unit'] ?? 'pcs',
                    'qty'          => $qty,
                    'unit_price'   => $price,
                    'discount'     => $disc,
                    'net_amount'   => $net,
                    'vat_rate'     => $vatRate,
                    'vat_amount'   => $vat,
                    'gross_amount' => $gross,
                ]);

               $sumNet += $net; $sumVat += $vat; $sumGross += $gross;
            }
 
            $invoice->update([
                'total_net'   => $sumNet,
                'total_vat'   => $sumVat,
                'total_gross' => $sumGross,
            ]);
        });

        return redirect()->route('invoices.index')->with('status','Rēķins atjaunināts!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return back()->with('status','Rēķins dzēsts.');
    }
}
