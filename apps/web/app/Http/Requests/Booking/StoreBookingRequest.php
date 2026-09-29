<?php

namespace App\Http\Requests\Booking;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cabin_id' => ['required', 'integer', 'exists:cabins,id'],
            'check_in' => ['required', 'date', 'after_or_equal:today'],
            'check_out' => ['required', 'date', 'after:check_in'],
            'adults' => ['required', 'integer', 'min:1'],
            'children' => ['sometimes', 'integer', 'min:0'],
            'infants' => ['sometimes', 'integer', 'min:0'],
            'total_guests' => ['sometimes', 'integer', 'min:1'],
            'guest_name' => [Rule::requiredIf(fn () => $this->user() === null), 'nullable', 'string', 'max:255'],
            'guest_email' => [Rule::requiredIf(fn () => $this->user() === null), 'nullable', 'email', 'max:255'],
            'guest_phone' => ['nullable', 'string', 'max:255'],
        ];
    }
}
