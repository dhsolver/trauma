<?php

namespace App\Http\Requests\Admin;
use App\Organization;
use Illuminate\Foundation\Http\FormRequest;

class OrganizationRequest extends FormRequest
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
        if($this->segment(3)!="") {
            $organization = Organization::find($this->segment(3));
        }
        

        switch($this->method())
        {
            case 'GET':
            case 'DELETE':
            {
                return [];
            }
            case 'POST':
            {
                return [
                ];
            }
            case 'PUT':
            case 'PATCH':
            {
                return [
                    'name' => 'required|max:255|unique:organizations,name,'.$organization->id,
                    'contact_email' => 'required|email|max:255|unique:organizations,contact_email,'.$organization->id,
                    'contact_phone' => 'required|max:50',
                    'contact_name' => 'required|max:255'
                ];
            }
            default:break;
        }
    }
}
