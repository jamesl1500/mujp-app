<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if ( request()->user()->role_key == 'admin')
            return true;

        if ( !is_null(request()->input('email')) )
            return false;

        if ( strlen(request()->input('email')) > 0 )
            return false;

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ];

        if (request()->user()->role_key == 'admin') {
            $rules['email'] ='required|string|email|max:255|unique:users';
            $rules['role'] ='required|exists:roles,key';
        }

        $currentRoute = \request()->route()->getName();


        // TODO Password rule'unu route name'e göre kontrol et
        if (strlen(request()->input('password') > 0 )) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        return $rules;
    }
}
