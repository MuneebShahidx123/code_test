<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDistanceFeedRequest extends FormRequest
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
        return [
            'distance' => 'required',
            'time' => 'required',
            'jobid' => 'required',
            'session_time' => 'required',
            'flagged' => 'required|boolean',
            'admincomment' => $this->input('flagged') === 'true' ? 'required_if:flagged,true' : 'nullable',
            'manually_handled' => 'required|boolean',
            'by_admin' => 'required|boolean',
        ];
    }
}
