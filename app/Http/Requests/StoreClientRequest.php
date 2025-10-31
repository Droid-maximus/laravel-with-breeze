<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreClientRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $type = $this->input('type');
        $isAdmin = auth()->user()?->role === 'admin';

        $rules = [
            'type'    => ['required', Rule::in(['person','company'])],
            'name'    => ['required','string','max:255'],
            'email'   => ['nullable','email'],
            'phone'   => ['nullable','string','max:100'],
            'address' => ['nullable','string','max:500'],
            'vat_no'  => ['nullable','string','max:100'],
            'reg_no'  => ['nullable','string','max:100'],

           'user_id' => $isAdmin ? ['required', Rule::exists('users','id')] : ['prohibited'], // parasts lietotājs nedrīkst sūtīt user_id
        ];


        if ($type === 'person') {
            // personas kods obligāts + formāts 
            $rules['reg_no'] = ['required','string','max:100','regex:/^(\d{6}-\d{5}|\d{11})$/'];
            // PVN parasti nav personai → nav obligāts
            // PS: nav input laukums create skatā )=
            $rules['vat_no'] = ['nullable','string','max:100'];
        }

        if ($type === 'company') {
            // reģ. nr. 11 cipari 
            $rules['reg_no'] = ['required','string','max:100','regex:/^\d{11}$/'];
            // PVN obligāts: LV + 11 cipari 
            $rules['vat_no'] = ['required','string','max:100','regex:/^LV\d{11}$/i'];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'reg_no' => 'reģistrācijas numurs / personas kods',
            'vat_no' => 'PVN numurs',
        ];
    }

    public function messages(): array
    {
        return [
            'reg_no.regex' => 'Lauks :attribute nav pareizā formātā.',
            'vat_no.regex' => 'PVN numuram jābūt formātā “LV12345678901”.',
        ];
    }
}
