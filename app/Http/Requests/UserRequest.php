<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name'=> 'required',
            'email' => 'required|email|unique:'.User::class.',email'.$this->_getId(),
            'username' => 'required|unique:'.User::class.',username'.$this->_getId(),
            'password' => 'required'
        ];
    }

    private function _getId(){
        $id =  $this->route()->parameter('user');
       return $id?->id ? ",{$id?->id}" : '' ;
    }
}
