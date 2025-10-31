<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateClientRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
{
    $this->merge([
        'type'   => $this->string('type')->trim()->toString(),
        'name'   => $this->string('name')->trim()->toString(),
        'email'  => $this->string('email')->trim()->toString(),
        'phone'  => $this->string('phone')->trim()->toString(),
        'reg_no' => $this->string('reg_no')->trim()->toString(),
        // normalizē PVN: noņem tukšumus, lielie burti
        'vat_no' => strtoupper(preg_replace('/\s+/', '', (string)$this->input('vat_no'))),
    ]);
}


    public function rules(): array
    {
        $type = $this->input('type');
        $isAdmin = auth()->user()?->role === 'admin';

        $rules = [
            'type'    => ['bail','required', Rule::in(['person','company'])],
            'name'    => ['bail','required','string','max:255'],
            'email'   => ['bail','nullable','email'],
            'phone'   => ['nullable','string','max:100'],
            'address' => ['nullable','string','max:500'],
            'vat_no'  => ['nullable','string','max:100'],
            'reg_no'  => ['nullable','string','max:100'],
        ];

        $rules['user_id'] = $isAdmin
        ? ['sometimes', 'required', Rule::exists('users','id')]
        : ['prohibited'];


        if ($type === 'person') {
            $rules['reg_no'] = ['required','string','max:100','regex:/^(\d{6}-\d{5}|\d{11})$/'];
            $rules['vat_no'] = ['nullable','string','max:100'];
        }

        if ($type === 'company') {
            $rules['reg_no'] = ['required','string','max:100','regex:/^\d{11}$/'];
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
        'type.in'        => 'Tips drīkst būt tikai “person” vai “company”.',
        'reg_no.required'=> 'Lauks :attribute ir obligāts.',
        'vat_no.required'=> 'PVN numurs ir obligāts juridiskām personām.',
        'reg_no.regex'   => 'Lauks :attribute nav pareizā formātā.',
        'vat_no.regex'   => 'PVN numuram jābūt formātā “LV12345678901”.',
    ];
}
}
