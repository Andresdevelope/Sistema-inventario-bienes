<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BienIndexFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['nullable', 'string', 'max:60'],
            'estado' => ['nullable', Rule::in(['bueno', 'regular', 'malo', 'de_baja'])],
            'categoria' => ['nullable', 'string', 'max:30'],
            'ubicacion' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', Rule::in([10, 15, 25, 50, 100])],
            'page' => ['nullable', 'integer', 'min:1'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $normalize = static function ($value): mixed {
            if (! is_string($value)) {
                return $value;
            }

            $clean = trim($value);

            return $clean === '' ? null : $clean;
        };

        $this->merge([
            'search' => $normalize($this->input('search')),
            'estado' => $normalize($this->input('estado')),
            'categoria' => $normalize($this->input('categoria')),
            'ubicacion' => $normalize($this->input('ubicacion')),
            'per_page' => $normalize($this->input('per_page')),
            'page' => $normalize($this->input('page')),
        ]);
    }
}
