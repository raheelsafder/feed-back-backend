<?php

namespace App\Http\Requests\FeedBack;

use App\Http\Requests\BaseRequest;
use Illuminate\Contracts\Validation\ValidationRule;

class FeedBackCommentRequest extends BaseRequest
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
            'feed_back_id' => 'required|integer|exists:feed_backs,id',
            'content' => 'required|string',
        ];
    }
}
