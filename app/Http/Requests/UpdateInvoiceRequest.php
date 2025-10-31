<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $invoiceId = $this->route('invoice')->id; // resource route param
        $isAdmin = auth()->user()?->role === 'admin';

        return [
            'client_id' => ['required', Rule::exists('clients','id')->when(!$isAdmin, function ($rule) {
                $rule->where('user_id', auth()->id()); 
                    }),
                ],
            'number'         => ['required','string','max:50', Rule::unique('invoices','number')->ignore($invoiceId)],
            'issue_date'     => ['required','date'],
            'due_date'       => ['nullable','date','after_or_equal:issue_date'],
            'currency'       => ['required','string','size:3'],
            'status'         => ['required', Rule::in(['draft','sent','paid','cancelled'])],

            'items'                  => ['required','array','min:1'],
            'items.*.description'    => ['required','string','max:255'],
            'items.*.qty'            => ['required','numeric','min:0.001'],
            'items.*.unit_price'     => ['required','numeric','min:0'],
            'items.*.discount'       => ['nullable','numeric','min:0'],
            'items.*.vat_rate'       => ['required','numeric','min:0'],
            'items.*.unit'           => ['nullable','string','max:20'],
        ];
    }
}
