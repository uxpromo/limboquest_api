<?php

namespace App\Http\Requests\V1\Admin\Comment;

use FinzorDev\Roles\Http\Requests\BaseFormRequest;

class CommentStoreRequest extends BaseFormRequest
{
    public function rules(): array
    {
        return [
            'commentable_type' => ['required', 'string'],
            'commentable_id' => ['required', 'integer'],
            'text' => ['required', 'string'],
        ];
    }
}
