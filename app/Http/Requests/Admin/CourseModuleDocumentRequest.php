<?php namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class CourseModuleDocumentRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type' => 'in:url,file',
            'url' => 'required_if:type,url',
            'fileNames' => 'required_if:type,file',
            'fileKeys' => 'required_if:type,file',
        ];
    }

    public function messages() {
        return [
            'url.required_if' => 'Please enter the url.',
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
