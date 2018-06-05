<?php namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|min:3',
            'location' => 'required|max:255',
            'overview' => 'required|min:20',
            'objective' => 'required|min:20',
            'price' => 'numeric|min:0',
            'date_start' => 'required_unless:online_only,1',
            'date_end' => 'required_unless:online_only,1',
        ];
    }

    public function messages() {
        return [
            'date_start.required_unless' => 'Please enter the start date.',
            'date_end.required_unless' => 'Please enter the end date.',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

}
