<?php

namespace Makemarketingmagic\ViewTools\Services;

use function explode;
use function strpos;

class ArrayHelper
{
    public function get($key, $array, $default = null) {
        return $this->resolve($key, $array, $default);
    }

    private function resolve($key, $array, $default = null) {
        $dotPos = strpos($key, '.');
        if ($dotPos !== false) {
            $keyParts = explode('.', $key, 2);
            $array = $array[$keyParts[0]];
            $key = $keyParts[1];
            return $this->resolve($key, $array, $default);
        } else {
            return $array[$key] ?? $default;
        }
    }
}
