<?php

namespace Makemarketingmagic\ViewTools\Table;

/**
 * Support class for html attributes
 *
 */
class Attribute
{
    /**
     * Returns combined attributes as string
     *
     * @param array $attributes
     *
     * @return string
     */
    public static function str(array $attributes): string
    {
        $result = "";
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $result .= ' '  . $key . '="' . $value . '"';
            }
        }
        return $result;
    }
}
