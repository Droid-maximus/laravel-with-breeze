<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Client;

class StoreInvoiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    // Ja gribi “sanitizēt” datus pirms validācijas:
    protected function prepareForValidation(): void
    {
        // piem., apgriežam tukšumus
        $this->merge([
            'number' => trim((string) $this->input('number')),
        ]);
    }

    public function rules(): array
    {
        $isAdmin = auth()->user()?->role === 'admin';

        return [
            'client_id' => ['required', Rule::exists('clients','id')->when(!$isAdmin, function ($rule) {
                $rule->where('user_id', auth()->id()); 
                    }),
                ],
            'number'         => ['string','max:50','unique:invoices,number'],
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

    public function attributes(): array
    {
        return [
            'items.*.description' => 'rindas apraksts',
            'items.*.qty'         => 'rindas daudzums',
            'items.*.unit_price'  => 'rindas cena',
            'items.*.vat_rate'    => 'PVN likme',
        ];
    }

    // Ja gribi dinamiski apturēt pie pirmās kļūdas:
    // public function stopOnFirstFailure(): bool { return true; }
}
