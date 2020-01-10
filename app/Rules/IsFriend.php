<?php

namespace App\Rules;

use App\Models\UserFriend;
use Illuminate\Contracts\Validation\Rule;

class IsFriend implements Rule
{
    protected $userId;

    /**
     * Create a new rule instance.
     *
     * @param int $userId
     *
     * @return void
     */
    public function __construct(int $userId)
    {
        $this->userId = $userId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return UserFriend::isFriend($this->userId, $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '你们还不是好友。';
    }
}
