<?php

namespace App\Traits;

use Exception;

trait ValidationTrait
{

    /**
     * @param array $array
     * @param bool $throw_error
     * @return bool
     * @throws Exception
     */
    public function validEmailArray(array $array, $throw_error = true)
    {
        if (empty($array)) {
            return false;
        }
        foreach ($array as $item) {
            if (!filter_var($item, FILTER_VALIDATE_EMAIL)) {
                if ($throw_error) {
                    throw new Exception($item . ' is not a valid email address');
                }
                return false;
            }
        }
        return true;
    }
}