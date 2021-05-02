<?php

namespace App\Http\Requests\Users;

use App\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public $recoveryPassword;
    public $user;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $token = $this->input('token', '');
        $email = $this->input('email', '');

        $this->user = User::where('email', $email)
            ->firstOrFail();

        $this->recoveryPassword = $this->user
            ->recoveryPasswords()
            ->where('token', $token)
            ->notExpired()
            ->firstOrFail();

        return $this->recoveryPassword;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ];
    }
}
