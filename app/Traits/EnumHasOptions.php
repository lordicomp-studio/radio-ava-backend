<?php

namespace App\Traits;

trait EnumHasOptions
{

    /**
     * Get all options of the enum as key-value pairs with translations.
     *
     * @return array
     */
    public static function options(): array
    {
        $class = new \ReflectionClass(static::class);
        $constants = $class->getConstants();

        $keyValuePairs = [];
        foreach ($constants as $key => $value) {
            $keyValuePairs[$value->value] = __("enums." . static::class . ".$key");
        }

        return $keyValuePairs;
    }
}
