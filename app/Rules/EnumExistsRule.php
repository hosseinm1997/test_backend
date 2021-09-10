<?php

namespace App\Rules;

use App\Models\Enumeration;
use Illuminate\Contracts\Validation\Rule;

class EnumExistsRule implements Rule
{
    /**
     * @var int
     */
    private $parentId;

    /**
     * Create a new rule instance.
     *
     * @param int $parentId
     */
    public function __construct(int $parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return Enumeration::query()
            ->where('id', $value)
            ->where('parent_id', $this->parentId)
            ->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid Enum :value';
    }
}
