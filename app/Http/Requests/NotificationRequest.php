<?php

namespace App\Http\Requests;

use App\Enums\RelationMorphEnums;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class NotificationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $n = $this->route('notification');
        return ($n->notifiable_type == RelationMorphEnums::USER)
            && ($n->notifiable_id == Auth::id());
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('只能操作自己的消息记录。');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'read_at' => 'nullable',
        ];
    }
}
