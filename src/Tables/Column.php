<?php

namespace Makemarketingmagic\ViewTools\Tables;

class Column
{

    private $field;
    private $caption;
    private $attributes;

    /**
     * @param string $field
     * @param string|null $caption
     * @param array $attributes
     */
    public function __construct(string $field, string $caption = null, array $attributes = [])
    {
        $this->field = $field;
        $this->caption = $caption;
        $this->attributes = $attributes;
    }

    public function getCaption()
    {
        return $this->caption ?? $this->field;
    }

    public function getField()
    {
        return $this->field;
    }

    public function getAttributes()
    {
        return $this->attributes ?? [];
    }
}
