<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [];
        $method = $this->route()->getActionMethod();
        switch ($this->method()){
            case 'POST':
                switch ($method){
                    case 'add':
                        $rules = [
                            'name' => 'required',
                            'email' => 'required|unique:staff',
                            'tel' => 'required|unique:staff'
                        ];
                        break;
                    case 'edit':
                        $rules = [
                            'name' => 'required',
                            'email' => 'required',
                            'tel' => 'required'
                        ];
                        break;
                    default:
                        break;
                }
                break;
            default:
                break;
        }
        return $rules;
    }
    public function messages()
    {
        return [
            'name.required' => "khong duoc bo trong ten nhan vien",
            'email.required' => "khong duoc bo trong Email",
            'email.unique' => "Email da duoc dung",
            'tel.required' => "khong duoc bo trong so dien thoai",
            'tel.unique' => "so dien thoai da duoc dung",
        ];
    }
}
