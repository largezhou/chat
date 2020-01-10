<?php

namespace App\Http\Requests;

use App\Rules\IsFriend;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RecentContactRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'target_id' => [
                'required',
                new IsFriend(Auth::id()),
            ],
        ];
    }
}
