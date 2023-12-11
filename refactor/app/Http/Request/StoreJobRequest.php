<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules= [
            'from_language_id' => 'required',
            'immediate' => 'required|in:yes,no',
            'due_date' => 'required_if:immediate,no|date_format:m/d/Y',
            'due_time' => 'required_if:immediate,no|date_format:H:i',
            'duration' => 'required',
            'job_for' => 'required|array',
            'job_for.*' => 'in:male,female,normal,certified,certified_in_law,certified_in_health',
        ];
        if ($this->input('immediate') == 'no') {
            if (!$this->input('customer_phone_type') && !$this->input('customer_physical_type')) {
                $rules['customer_phone_type'] = 'required';
            }
        }

        return $rules;
    }


    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'from_language_id.required' => 'Du måste fylla in alla fält för from_language_id.',
            'due_date.required' => 'Du måste fylla in alla fält för due_date när immediate är "no".',
            'due_time.required' => 'Du måste fylla in alla fält för due_time när immediate är "no".',
            'customer_phone_type.required' => 'Du måste göra ett val här',
            'duration.required' => 'Du måste fylla in alla fält',
            'customer_physical_type.required' => 'Du måste göra ett val här',
            'job_for.required' => 'Du måste göra ett val här',
        ];
    }
}
