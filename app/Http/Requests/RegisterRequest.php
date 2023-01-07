<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array<string, mixed>
	 */
	public function rules()
	{
		return [
			'email'                    => ['required', 'unique:users,email', 'email'],
			'username'                 => ['required', 'min:2', 'unique:users,username'],
			'fullname'                 => ['required'],
			'password'                 => ['required', 'min:6', 'max:15'],
			'birth_date'               => ['required'],
			'code'                     => ['required', 'integer'],
		];
	}
}
