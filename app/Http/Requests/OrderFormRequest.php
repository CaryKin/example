<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class OrderFormRequest extends FormRequest
{
    public int $user_id;
    public float $amount;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|integer',
            'amount' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => '用户ID不能为空',
            'amount.required' => '金额不能为空',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->user_id = $this->input("user_id");
        $this->amount = $this->input("amount");
    }

    protected function failedValidation(Validator $validator)
    {
        // 可以通过以下代码定制返回格式
        $errors = $validator->errors()->getMessages();
        $response = [
            'status' => 'error',
            'message' => '验证失败',
            'errors' => $errors,
            'code' => 422
        ];

        throw new ValidationException($validator, response()->json($response, 422));
    }
}
