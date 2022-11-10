<?php

namespace Makemarketingmagic\ViewTools\Table;

/**
 * Table column class
 *
 */
class TableColumn
{
    /**
     * Content of column
     *
     * @var string
     */
    protected string $content;

    /**
     * Attributes of th element
     *
     * @param array
     */
    protected array $attributes;

    /**
     * Class constructor
     *
     * @param string $content
     * @param array $attributes
     */
    public function __construct(string $content, array $attributes = [])
    {
        $this->content = $content;
        $this->attributes = $attributes;
    }

    /**
     * Returns html of row
     *
     * @return string
     */
    public function html()
    {
        return view(config('view_tools_tables.views.header'), [
            'content' => $this->content,
            'attributes' => Attribute::str($this->attributes)
        ])->render();
    }

    /**
     * Returns column as an array
     *
     * @return array
     */
    public function array()
    {
        return [
            'content' => $this->content,
            'attributes' => $this->attributes
        ];
    }
}
