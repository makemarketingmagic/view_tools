<?php

namespace Makemarketingmagic\ViewTools\Services;

use Closure;
use Makemarketingmagic\ViewTools\Table\TableCell;
use function explode;
use function strpos;

class ArrayHelper
{

    /**
     * @param string $key
     * @param array $array
     * @param mixed|null $default
     * @return mixed
     */
    public function get(string $key, array $array, mixed $default = null)
    {
        return $this->resolve($key, $array, $default);
    }

    public function has(string $key, array $array)
    {
        $result = true;
        $default = function () use (&$result) {
            $result = false;
        };
        $this->resolve($key, $array, $default);
        return $result;
    }

    /**
     * @param string $key
     * @param array $array
     * @param mixed|null $default
     * @return mixed
     */
    private function resolve(string $key, array $array, mixed $default = null): mixed
    {
        $dotPos = strpos($key, '.');
        if ($dotPos !== false) {
            $keyParts = explode('.', $key, 2);
            $array = $array[$keyParts[0]];
            $key = $keyParts[1];
            if ($array instanceof TableCell) {
                $array = $array->getContent();
            }
            return $this->resolve($key, $array, $default);
        } else {
            if (array_key_exists($key, $array)) {
                return $array[$key];
            }
            return ($default instanceof Closure) ? $default() : $default;
        }
    }
}
