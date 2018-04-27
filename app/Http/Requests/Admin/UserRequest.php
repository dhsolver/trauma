<?php namespace App\Http\Requests\Admin;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest {

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if($this->segment(3)!="") {
            $user = User::find($this->segment(3));
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
                    'first_name' => 'required|max:255',
                    'last_name' => 'required|max:255',
                    'email' => 'required|email|max:255|unique:users,email,'.$user->id,
                    'password' => 'confirmed|min:6',
                    'birthday' => 'required|max:25',
                    'phone' => 'required|max:50',
                    'hospital_name' => 'required|max:255',
                    'hospital_level' => 'required|max:50',
                    'hospital_address1' => 'required|max:255',
                    'hospital_city' => 'required|max:255',
                    'hospital_state' => 'required|max:50',
                    'hospital_zipcode' => 'required|max:50',
                ];
            }
            default:break;
        }
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
