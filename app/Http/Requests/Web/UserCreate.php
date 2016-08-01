<?php

namespace App\Http\Requests\Web;

use App\Http\Requests\Request;

class UserCreate extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required|max:100',
            'last_name' => 'required|max:100',
            'phone' => 'required|numeric|digits_between:7,22',
            'username' => 'required|max:32|alpha_dash|unique:slugs,name',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|password_complex',
            'role' => 'required|exists:roles,slug',
            'email_confirmed' => 'integer',
            'birthday' => 'required|date|birthday:' . config_min_age(),

            'image' => 'image|max:' . config_file_size(),
        ];
    }
}
