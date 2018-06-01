<?php namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseDocumentRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'fileNames' => 'required',
            'fileKeys' => 'required',
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
