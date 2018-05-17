<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CollectionUnique implements Rule
{
    protected $collection;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($collection)
    {
        $this->collection = $collection;
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
        if($this->collection->contains($value))
            return false;
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Data is already exists.';
    }
}
