<?php

namespace App\Http\Requests\FeedBack;

use Illuminate\Contracts\Validation\ValidationRule;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class FeedBackRequest extends BaseRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'category' => ['required', 'string', 'max:255', Rule::in(['bug report', 'feature request', 'improvement', 'suggestion'])],
            'description' => 'required',
        ];
    }
}
